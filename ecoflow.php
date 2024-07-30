<?php
/*
 * Récupère les infos des périphériques Ecoflow
 * Fonctionne sans aucune dépendance. Il suffit de copier ce fichier sur un serveur PHP
 * Exemple https://monserveur.com/ecoflow.php => renvoit la liste des périphériques
 * Exemple https://monserveur.com/ecoflow.php?sn=HW513000SF767194 => renvoit les paramètres d'un périphérique
 * Prg par Jérôme SAYNES. MIT Licence.
 */

// Créer un compte ici https://developer-eu.ecoflow.com/us pour récupérer ces infos.
$accessKey = 'ACCESSACCESSACCESSACCESSACCESSACCESS';
$secretKey = 'SECRETSECRETSECRETSECRETSECRETSECRET';

$nonce = '42';
$timestamp = time() * 1000;

$method = 'GET';

if (isset($_GET['sn'])) {
	$sn = $_GET['sn'];

	if (isset($_GET['watt'])) {
		// On fixe la valeur de sortie du Power Stream
		// exemple ?sn=HW513000SF767194&watt=100
		// Doc ici pour toutes les commandes : https://developer-eu.ecoflow.com/us/document/powerStreamMicroInverter
		$url = 'https://api-e.ecoflow.com/iot-open/sign/device/quota';
		$method = 'PUT';
		$content = [
			'cmdCode' => 'WN511_SET_PERMANENT_WATTS_PACK',
			'params.permanentWatts' => $_GET['watt'] * 10,
			'sn' => $sn
		];
	} else {
		// On récupère toutes les infos du périphérique
		// Exemple sn=HW513000SF767194
		$url = $url = 'https://api-e.ecoflow.com/iot-open/sign/device/quota/all?sn=' . $sn;
		$content = ['sn' => $sn];
	}
} else {
	// Si on a pas spécifié un serial number, on liste les périphériques
	$url = 'https://api-e.ecoflow.com/iot-open/sign/device/list';
	$content = [];
}

$sign = $content;
$sign['accessKey'] = $accessKey;
$sign['nonce'] = $nonce;
$sign['timestamp'] = $timestamp;
$sign = bin2hex(hash_hmac('sha256', http_build_query($sign), $secretKey, true));
$http = [
	'method' => $method,
	'header' => "Content-Type: application/json\r\naccessKey: $accessKey\r\nnonce: $nonce\r\ntimestamp: $timestamp\r\nsign: $sign\r\n"
];
if ($content) {
	$http['content'] = json_encode($content);
}

header('Content-Type: application/json');
die(file_get_contents($url, false, stream_context_create(['http' => $http])));
