# API Ecoflow

Pilotage du matériel Ecoflow **sans aucune dépendance externe**. Interface PHP sécurisée pour communiquer avec l'API Ecoflow.

## 🔒 Fonctionnalités de sécurité

- ✅ Authentification par clé API
- ✅ Validation stricte des entrées
- ✅ Nonce aléatoire pour chaque requête
- ✅ Protection HMAC-SHA256
- ✅ Gestion d'erreurs robuste
- ✅ Logs de sécurité
- ✅ Protection des fichiers sensibles (.htaccess)
- ✅ Timeouts configurables

## 📦 Installation

### 1. Créer un compte développeur Ecoflow

Créez un compte ici : https://developer-eu.ecoflow.com/us/ pour obtenir :
- `accessKey`
- `secretKey`

### 2. Configuration

```bash
# Copiez le fichier de configuration exemple
cp config.example.php config.php
```

Éditez `config.php` et renseignez :

```php
return [
    'accessKey' => 'VOTRE_ACCESS_KEY',
    'secretKey' => 'VOTRE_SECRET_KEY',
    'apiAuthKey' => 'CLE_AUTHENTIFICATION_FORTE',  // Générez avec: openssl rand -hex 32
    'timeout' => 10,
    'logErrors' => true,
    'logFile' => __DIR__ . '/ecoflow_errors.log',
];
```

### 3. Sécurisation

**IMPORTANT** : Ne committez JAMAIS le fichier `config.php` dans Git !

Le fichier `.gitignore` est déjà configuré pour exclure :
- `config.php`
- `*.log`

Le fichier `.htaccess` protège automatiquement :
- Les fichiers de configuration
- Les logs
- Les fichiers cachés (.git, .env, etc.)

### 4. Déploiement

Hébergez les fichiers sur :
- Un serveur web avec PHP 7.4+
- Une box domotique (Jeedom, Home Assistant, etc.)
- Un serveur local (XAMPP, WAMP, etc.)

## 🚀 Utilisation

Toutes les requêtes nécessitent le paramètre `auth` avec votre clé d'authentification.

### Lister tous les périphériques

```bash
https://monserveur.com/ecoflow.php?auth=VOTRE_CLE_AUTH
```

Réponse :

```json
{
  "code": "0",
  "message": "Success",
  "data": [
    {
      "sn": "HW51ZOH400000000",
      "online": 0,
      "productName": "PowerStream"
    },
    {
      "sn": "P2EBZ7X00000000",
      "online": 0,
      "productName": "RIVER Pro"
    }
  ],
  "eagleEyeTraceId": "ea1a2a5c2c17223695115605617d0000",
  "tid": ""
}
```

### Obtenir les informations d'un périphérique

```bash
https://monserveur.com/ecoflow.php?auth=VOTRE_CLE_AUTH&sn=HW513000SF767194
```

### Définir la puissance du PowerStream

```bash
https://monserveur.com/ecoflow.php?auth=VOTRE_CLE_AUTH&sn=HW513000SF767194&watt=100
```

**Limites** : 0-800 watts

## 📊 Exemples de réponses

### Pour un PowerStream

Doc complète : https://developer-eu.ecoflow.com/us/document/powerStreamMicroInverter

<details>
<summary>Voir l'exemple de réponse JSON (cliquez pour déplier)</summary>

```json
{
  "code": "0",
  "message": "Success",
  "data": {
    "20_1.pv2Temp": 330,
    "20_1.invOutputWatts": 0,
    "20_1.pv2RelayStatus": 0,
    "20_1.batInputVolt": 4,
    "20_1.invDemandWatts": 6000,
    "20_1.invOnOff": 1,
    "20_1.invOpVolt": 2317,
    "20_1.permanentWatts": 6000,
    "20_1.batSoc": 0,
    "20_1.invOutputCur": 10,
    "20_1.gridConsWatts": 6000,
    "20_1.invTemp": 0,
    "20_1.updateTime": "2024-07-31 03:08:32"
  }
}
```

</details>

### Pour une batterie RIVER Pro

<details>
<summary>Voir l'exemple de réponse JSON (cliquez pour déplier)</summary>

```json
{
  "code": "0",
  "message": "Success",
  "data": {
    "pd.wattsInSum": 0,
    "pd.model": 1,
    "bmsMaster.soc": 20,
    "bmsMaster.maxChargeSoc": 100,
    "bmsMaster.maxCellTemp": 32,
    "inv.outputWatts": 0,
    "inv.cfgAcOutVoltage": 230000,
    "bmsMaster.fullCap": 14013,
    "bmsMaster.remainCap": 2706,
    "bmsMaster.cycles": 28,
    "pd.soc": 20
  }
}
```

</details>

## 🔧 Validation des paramètres

### Serial Number (sn)
- Format : 14-20 caractères alphanumériques majuscules
- Exemple valide : `HW513000SF767194`
- Regex : `^[A-Z0-9]{14,20}$`

### Watts (watt)
- Type : entier
- Plage : 0-800 watts
- Valeur envoyée à l'API : `watt * 10`

## 🛡️ Sécurité

### Authentification

Toutes les requêtes doivent inclure le paramètre `auth` :

```bash
?auth=VOTRE_CLE_AUTHENTIFICATION
```

Sans ce paramètre, vous obtiendrez une erreur `401 Unauthorized`.

### Génération d'une clé d'authentification forte

```bash
# Linux/Mac
openssl rand -hex 32

# Ou en PHP
php -r "echo bin2hex(random_bytes(32));"
```

### Logs de sécurité

Les tentatives d'accès non autorisées sont loggées dans `ecoflow_errors.log` (si activé dans config.php) :

```
[2024-11-06 10:30:15] [192.168.1.100] Authentication failed
[2024-11-06 10:31:22] [192.168.1.105] Invalid serial number format: test123
```

### Recommandations

1. **HTTPS obligatoire** : Utilisez toujours HTTPS en production
2. **Whitelist IP** : Limitez l'accès par IP si possible (via .htaccess ou firewall)
3. **Rotation des clés** : Changez régulièrement vos clés API
4. **Monitoring** : Surveillez les logs d'accès
5. **Rate limiting** : Ajoutez un rate limiter si exposition publique

## 🔍 Gestion des erreurs

### Codes d'erreur HTTP

- `400 Bad Request` : Paramètre invalide (sn ou watt)
- `401 Unauthorized` : Authentification échouée
- `500 Internal Server Error` : Configuration manquante/invalide
- `502 Bad Gateway` : API Ecoflow injoignable

### Exemples de réponses d'erreur

```json
{"error": "Unauthorized. Valid auth parameter required"}
{"error": "Invalid serial number format. Expected 14-20 alphanumeric characters"}
{"error": "Invalid watt value. Expected integer between 0 and 800"}
{"error": "API unavailable. Please try again later"}
```

## 📚 Documentation

### API Ecoflow officielle
- Introduction : https://developer-eu.ecoflow.com/us/document/introduction
- PowerStream : https://developer-eu.ecoflow.com/us/document/powerStreamMicroInverter
- Portail développeur : https://developer-eu.ecoflow.com/us/

### Extensions possibles

Vous pouvez étendre ce script pour :
- Contrôler d'autres appareils Ecoflow (Delta, River, etc.)
- Implémenter d'autres commandes (voir documentation API)
- Ajouter un système de cache
- Créer une interface web
- Intégrer avec Home Assistant, Node-RED, etc.

## 📝 Licence

MIT Licence - Jérôme SAYNES

## 🐛 Dépannage

### Erreur "Configuration file missing"
```bash
cp config.example.php config.php
# Puis éditez config.php avec vos clés
```

### Erreur 401 Unauthorized
- Vérifiez que vous avez bien le paramètre `?auth=VOTRE_CLE`
- Vérifiez que la valeur correspond à `apiAuthKey` dans config.php

### Erreur 502 API unavailable
- Vérifiez votre connexion Internet
- Vérifiez que vos clés Ecoflow sont valides
- Testez sur https://developer-eu.ecoflow.com/

### Les logs ne fonctionnent pas
- Vérifiez les permissions d'écriture du répertoire
- Vérifiez que `logErrors` est à `true` dans config.php

## 🎯 Compatibilité

- **PHP** : 7.4 ou supérieur
- **Extensions requises** :
  - `openssl` (signature HMAC)
  - `json`
  - `curl` ou `allow_url_fopen` activé

## Vous pouvez :
  * Lister les périphériques
  * Afficher toutes les infos d'un périphérique
  * Définir la puissance de sortie d'un PowerStream
  * Étendre les fonctionnalités en vous référant à la documentation officielle
