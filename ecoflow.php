<?php
/*
 * Récupère les infos des périphériques Ecoflow
 * Version sécurisée avec authentification et validation
 *
 * Exemple https://monserveur.com/ecoflow.php?auth=VOTRE_CLE => liste des périphériques
 * Exemple https://monserveur.com/ecoflow.php?auth=VOTRE_CLE&sn=HW513000SF767194 => paramètres d'un périphérique
 * Exemple https://monserveur.com/ecoflow.php?auth=VOTRE_CLE&sn=HW513000SF767194&watt=100 => définir puissance
 *
 * Prg par Jérôme SAYNES. MIT Licence.
 */

// Configuration de sécurité PHP
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Chargement de la configuration
$configFile = __DIR__ . '/config.php';
if (!file_exists($configFile)) {
    http_response_code(500);
    die(json_encode(['error' => 'Configuration file missing. Copy config.example.php to config.php']));
}

$config = require $configFile;

// Validation de la configuration
if (empty($config['accessKey']) || empty($config['secretKey']) || empty($config['apiAuthKey'])) {
    http_response_code(500);
    die(json_encode(['error' => 'Invalid configuration. Check your config.php file']));
}

// Logger les erreurs
function logError($message) {
    global $config;
    if (!empty($config['logErrors']) && !empty($config['logFile'])) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        error_log("[$timestamp] [$ip] $message\n", 3, $config['logFile']);
    }
}

// Authentification
if (!isset($_GET['auth']) || $_GET['auth'] !== $config['apiAuthKey']) {
    logError('Authentication failed');
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized. Valid auth parameter required']));
}

// Fonction de validation du serial number
function validateSerialNumber($sn) {
    $sn = trim($sn);
    // Format typique Ecoflow : lettres majuscules et chiffres, 14-20 caractères
    if (!preg_match('/^[A-Z0-9]{14,20}$/', $sn)) {
        return false;
    }
    return $sn;
}

// Fonction de validation des watts
function validateWatts($watt) {
    $watt = filter_var($watt, FILTER_VALIDATE_INT);
    if ($watt === false || $watt < 0 || $watt > 800) {
        return false;
    }
    return $watt;
}

// Fonction pour faire une requête sécurisée à l'API
function makeApiRequest($url, $method, $content, $config) {
    // Validation de l'URL (whitelist)
    $allowedHosts = ['api-e.ecoflow.com'];
    $parsed = parse_url($url);
    if (!isset($parsed['host']) || !in_array($parsed['host'], $allowedHosts)) {
        logError('Invalid API host: ' . ($parsed['host'] ?? 'unknown'));
        http_response_code(400);
        die(json_encode(['error' => 'Invalid API endpoint']));
    }

    // Génération d'un nonce aléatoire unique
    $nonce = bin2hex(random_bytes(16));
    $timestamp = time() * 1000;

    // Signature HMAC
    $sign = $content;
    $sign['accessKey'] = $config['accessKey'];
    $sign['nonce'] = $nonce;
    $sign['timestamp'] = $timestamp;
    $sign = bin2hex(hash_hmac('sha256', http_build_query($sign), $config['secretKey'], true));

    // Configuration de la requête HTTP
    $http = [
        'method' => $method,
        'header' => "Content-Type: application/json\r\naccessKey: {$config['accessKey']}\r\nnonce: $nonce\r\ntimestamp: $timestamp\r\nsign: $sign\r\n",
        'timeout' => $config['timeout'] ?? 10,
        'ignore_errors' => true  // Pour capturer les codes d'erreur HTTP
    ];

    if ($content && $method !== 'GET') {
        $http['content'] = json_encode($content);
    }

    // Exécution de la requête
    $context = stream_context_create(['http' => $http]);
    $response = @file_get_contents($url, false, $context);

    // Gestion des erreurs
    if ($response === false) {
        logError("API request failed: $url");
        http_response_code(502);
        die(json_encode(['error' => 'API unavailable. Please try again later']));
    }

    // Vérification du code de réponse HTTP
    if (isset($http_response_header)) {
        $statusLine = $http_response_header[0] ?? '';
        preg_match('/\d{3}/', $statusLine, $matches);
        $statusCode = $matches[0] ?? 200;

        if ($statusCode >= 400) {
            logError("API returned error $statusCode: $response");
            http_response_code($statusCode);
            die($response);
        }
    }

    return $response;
}

// Traitement de la requête
$method = 'GET';
$content = [];
$url = '';

if (isset($_GET['sn'])) {
    // Validation du serial number
    $sn = validateSerialNumber($_GET['sn']);
    if ($sn === false) {
        logError('Invalid serial number format: ' . ($_GET['sn'] ?? ''));
        http_response_code(400);
        die(json_encode(['error' => 'Invalid serial number format. Expected 14-20 alphanumeric characters']));
    }

    if (isset($_GET['watt'])) {
        // Modification de la puissance du PowerStream
        $watt = validateWatts($_GET['watt']);
        if ($watt === false) {
            logError('Invalid watt value: ' . ($_GET['watt'] ?? ''));
            http_response_code(400);
            die(json_encode(['error' => 'Invalid watt value. Expected integer between 0 and 800']));
        }

        $url = 'https://api-e.ecoflow.com/iot-open/sign/device/quota';
        $method = 'PUT';
        $content = [
            'cmdCode' => 'WN511_SET_PERMANENT_WATTS_PACK',
            'params.permanentWatts' => $watt * 10,
            'sn' => $sn
        ];
    } else {
        // Récupération des infos d'un périphérique
        $url = 'https://api-e.ecoflow.com/iot-open/sign/device/quota/all?sn=' . urlencode($sn);
        $content = ['sn' => $sn];
    }
} else {
    // Liste de tous les périphériques
    $url = 'https://api-e.ecoflow.com/iot-open/sign/device/list';
    $content = [];
}

// Exécution et retour de la réponse
header('Content-Type: application/json');
echo makeApiRequest($url, $method, $content, $config);
