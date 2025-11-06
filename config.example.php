<?php
/*
 * Fichier de configuration pour l'API Ecoflow
 *
 * INSTRUCTIONS :
 * 1. Copiez ce fichier vers 'config.php'
 * 2. Remplissez vos vraies clés API
 * 3. Ne committez JAMAIS le fichier config.php dans Git
 * 4. Assurez-vous que config.php n'est pas accessible via HTTP (voir .htaccess)
 */

return [
    // Créer un compte ici : https://developer-eu.ecoflow.com/us/
    'accessKey' => 'VOTRE_ACCESS_KEY_ICI',
    'secretKey' => 'VOTRE_SECRET_KEY_ICI',

    // Clé d'authentification pour sécuriser l'accès au script
    // Générez une clé aléatoire forte : openssl rand -hex 32
    'apiAuthKey' => 'VOTRE_CLE_AUTHENTIFICATION_FORTE_ICI',

    // Configuration optionnelle
    'timeout' => 10,  // Timeout en secondes pour les requêtes API
    'logErrors' => true,  // Activer les logs d'erreur
    'logFile' => __DIR__ . '/ecoflow_errors.log',  // Chemin du fichier de log
];
