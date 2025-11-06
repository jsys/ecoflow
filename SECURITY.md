# Rapport d'audit de sécurité

Date de l'audit : 2025-11-06

## 📋 Résumé exécutif

Un audit de sécurité complet a été réalisé sur le code `ecoflow.php`. La version initiale présentait plusieurs vulnérabilités critiques qui ont été corrigées dans la version sécurisée.

**Score de sécurité :**
- Version initiale : 2/10 ⚠️
- Version sécurisée : 8/10 ✅

## 🔴 Vulnérabilités corrigées

### 1. Exposition des credentials (CWE-798) - CRITIQUE ✅ CORRIGÉ

**Problème initial :**
```php
$accessKey = 'ACCESSACCESSACCESSACCESSACCESSACCESS';
$secretKey = 'SECRETSECRETSECRETSECRETSECRETSECRET';
```

**Correction appliquée :**
- Externalisation dans un fichier `config.php` séparé
- Protection via `.htaccess` (accès HTTP bloqué)
- Exclusion Git via `.gitignore`
- Fichier `config.example.php` pour le modèle

**Impact :** Risque de compromission totale du compte Ecoflow éliminé

---

### 2. Nonce statique (CWE-330) - CRITIQUE ✅ CORRIGÉ

**Problème initial :**
```php
$nonce = '42';  // Toujours la même valeur !
```

**Correction appliquée :**
```php
$nonce = bin2hex(random_bytes(16));  // Aléatoire et unique
```

**Impact :** Protection contre les attaques par rejeu (replay attacks)

---

### 3. Absence d'authentification (CWE-306) - CRITIQUE ✅ CORRIGÉ

**Problème initial :**
- Accès public à l'API sans authentification
- N'importe qui pouvait contrôler les appareils

**Correction appliquée :**
```php
if (!isset($_GET['auth']) || $_GET['auth'] !== $config['apiAuthKey']) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}
```

**Impact :** Contrôle d'accès strict implémenté

---

### 4. Injection d'URL (CWE-20) - HAUTE ✅ CORRIGÉ

**Problème initial :**
```php
$url = '...?sn=' . $sn;  // Pas d'échappement
```

**Correction appliquée :**
```php
// Validation stricte
if (!preg_match('/^[A-Z0-9]{14,20}$/', $sn)) {
    return false;
}
$url = '...?sn=' . urlencode($sn);  // Échappement
```

**Impact :** Injection de paramètres impossible

---

### 5. Validation d'entrée manquante (CWE-20) - HAUTE ✅ CORRIGÉ

**Problème initial :**
```php
$sn = $_GET['sn'];  // Aucune validation
$_GET['watt'] * 10  // Peut être négatif, string, etc.
```

**Correction appliquée :**
```php
function validateSerialNumber($sn) {
    $sn = trim($sn);
    if (!preg_match('/^[A-Z0-9]{14,20}$/', $sn)) {
        return false;
    }
    return $sn;
}

function validateWatts($watt) {
    $watt = filter_var($watt, FILTER_VALIDATE_INT);
    if ($watt === false || $watt < 0 || $watt > 800) {
        return false;
    }
    return $watt;
}
```

**Impact :** Protection contre les injections et valeurs aberrantes

---

### 6. SSRF potentiel (CWE-918) - HAUTE ✅ CORRIGÉ

**Problème initial :**
- Aucune validation de l'URL cible

**Correction appliquée :**
```php
$allowedHosts = ['api-e.ecoflow.com'];
$parsed = parse_url($url);
if (!in_array($parsed['host'], $allowedHosts)) {
    die(json_encode(['error' => 'Invalid API endpoint']));
}
```

**Impact :** Impossible d'utiliser le script comme proxy pour attaquer d'autres systèmes

---

### 7. Gestion d'erreurs absente (CWE-755) - MOYENNE ✅ CORRIGÉ

**Problème initial :**
```php
die(file_get_contents($url, ...));  // Pas de gestion d'erreur
```

**Correction appliquée :**
```php
$response = @file_get_contents($url, false, $context);
if ($response === false) {
    logError("API request failed: $url");
    http_response_code(502);
    die(json_encode(['error' => 'API unavailable']));
}

// Vérification du code HTTP
if ($statusCode >= 400) {
    logError("API returned error $statusCode: $response");
    http_response_code($statusCode);
    die($response);
}
```

**Impact :** Erreurs gérées proprement, pas de leak d'information

---

### 8. Absence de timeout (CWE-400) - MOYENNE ✅ CORRIGÉ

**Problème initial :**
- Requêtes peuvent bloquer indéfiniment

**Correction appliquée :**
```php
'timeout' => $config['timeout'] ?? 10
```

**Impact :** Protection contre les blocages et DoS

---

### 9. Information Disclosure - MOYENNE ✅ CORRIGÉ

**Problème initial :**
- Erreurs PHP affichées directement

**Correction appliquée :**
```php
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
```

**Impact :** Pas de leak d'information système via les erreurs

---

### 10. Bug double assignation - MINEUR ✅ CORRIGÉ

**Problème initial :**
```php
$url = $url = 'https://...';
```

**Correction appliquée :**
```php
$url = 'https://...';
```

---

## ✅ Améliorations de sécurité ajoutées

### Logs de sécurité
```php
function logError($message) {
    global $config;
    if (!empty($config['logErrors'])) {
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        error_log("[$timestamp] [$ip] $message\n", 3, $config['logFile']);
    }
}
```

**Logs générés :**
- Tentatives d'authentification échouées
- Formats de paramètres invalides
- Erreurs API

### Protection .htaccess

```apache
<FilesMatch "^(config\.php|.*\.log|\.git.*)$">
    Require all denied
</FilesMatch>

Header always set X-Frame-Options "DENY"
Header always set X-XSS-Protection "1; mode=block"
Header always set X-Content-Type-Options "nosniff"
```

### Gestion Git sécurisée

Fichier `.gitignore` créé :
```
config.php
*.log
ecoflow_errors.log
```

---

## 📊 Comparaison avant/après

| Critère | Avant | Après |
|---------|-------|-------|
| Authentification | ❌ Aucune | ✅ Clé API obligatoire |
| Credentials | ❌ Hardcodées | ✅ Externalisées |
| Nonce | ❌ Statique ('42') | ✅ Aléatoire |
| Validation entrées | ❌ Aucune | ✅ Stricte (regex + type) |
| Sanitization | ❌ Aucune | ✅ urlencode() + trim() |
| Gestion erreurs | ❌ Aucune | ✅ Complète avec codes HTTP |
| Timeout | ❌ Infini | ✅ 10 secondes configurable |
| Logs | ❌ Aucun | ✅ Sécurité + erreurs |
| Protection fichiers | ❌ Aucune | ✅ .htaccess + .gitignore |
| Whitelist URL | ❌ Aucune | ✅ api-e.ecoflow.com uniquement |

---

## ⚠️ Risques résiduels

### 1. Absence de rate limiting (FAIBLE)

**Risque :**
- Brute force de la clé d'authentification
- Spam de l'API Ecoflow

**Recommandations :**
- Implémenter un rate limiter (ex: max 10 requêtes/minute par IP)
- Utiliser des solutions externes (Cloudflare, fail2ban)

### 2. Clé d'authentification dans l'URL (FAIBLE)

**Risque :**
- La clé peut être loggée dans les logs Apache/Nginx
- Visible dans l'historique du navigateur

**Recommandations :**
- Utiliser un header HTTP personnalisé : `X-Auth-Key`
- Ou HTTP Basic Auth
- Toujours utiliser HTTPS

### 3. Pas de rotation automatique des clés (FAIBLE)

**Recommandations :**
- Documenter la procédure de rotation des clés
- Implémenter une expiration des clés (optionnel)

### 4. Pas de monitoring des anomalies (FAIBLE)

**Recommandations :**
- Alertes sur tentatives d'authentification multiples échouées
- Monitoring des patterns d'utilisation anormaux

---

## 🔐 Recommandations supplémentaires

### Pour un déploiement en production

1. **HTTPS obligatoire**
   ```apache
   # Dans .htaccess
   RewriteEngine On
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   ```

2. **Whitelist IP** (si usage interne)
   ```apache
   <Files "ecoflow.php">
       Require ip 192.168.1.0/24
       Require ip 10.0.0.0/8
   </Files>
   ```

3. **Headers de sécurité supplémentaires**
   ```php
   header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
   header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
   ```

4. **Monitoring et alertes**
   - Intégrer avec un SIEM (Splunk, ELK, etc.)
   - Alertes email sur tentatives d'accès suspectes

5. **Sauvegarde et rotation des logs**
   ```bash
   # Cron job pour rotation
   0 0 * * * /usr/sbin/logrotate /etc/logrotate.d/ecoflow
   ```

---

## 📝 Checklist de déploiement sécurisé

- [ ] Copier `config.example.php` vers `config.php`
- [ ] Générer une clé d'authentification forte (32+ caractères)
- [ ] Renseigner les clés API Ecoflow
- [ ] Vérifier que `config.php` n'est pas accessible via HTTP
- [ ] Tester l'authentification (doit échouer sans `?auth=`)
- [ ] Activer HTTPS avec un certificat valide
- [ ] Configurer les permissions de fichiers (chmod 600 config.php)
- [ ] Vérifier que les logs fonctionnent
- [ ] Tester la validation des paramètres (sn et watt invalides)
- [ ] Configurer la rotation des logs
- [ ] Documenter la procédure pour l'équipe

---

## 📞 Contact sécurité

Pour signaler une vulnérabilité, contactez l'auteur du projet.

**Ne divulguez pas publiquement les vulnérabilités avant qu'elles ne soient corrigées.**

---

*Dernière mise à jour : 2025-11-06*
