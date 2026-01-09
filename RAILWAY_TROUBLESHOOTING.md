# ReTech Market - Déploiement Railway - Guide Complet

## ⚠️ PROBLÈME ACTUEL : Assets Vite et Pusher

### Cause
Les variables `VITE_PUSHER_APP_KEY` et `VITE_PUSHER_APP_CLUSTER` doivent être présentes **pendant le build Docker**, pas seulement au runtime.

### Solution 1 : Configuration Railway (Recommandée)

Dans Railway Dashboard :

1. **Variables** tab → Ajoutez TOUTES ces variables :
```bash
# Runtime variables
PUSHER_APP_ID=2091867
PUSHER_APP_KEY=2b9fc757a0fee41d9b8f
PUSHER_APP_SECRET=ff820b74307903bbf7d8
PUSHER_APP_CLUSTER=eu

# Build-time variables (important !)
VITE_PUSHER_APP_KEY=2b9fc757a0fee41d9b8f
VITE_PUSHER_APP_CLUSTER=eu
```

2. **Settings** → **Deploy** → Ajoutez dans "Build Args" :
```
VITE_PUSHER_APP_KEY=$PUSHER_APP_KEY
VITE_PUSHER_APP_CLUSTER=$PUSHER_APP_CLUSTER
```

3. Redéployez manuellement

---

### Solution 2 : Sans Pusher (développement rapide)

Si vous voulez tester SANS Pusher d'abord :

**Dans Railway Variables** :
```bash
BROADCAST_DRIVER=log
```

**Mais attention** : Sans Pusher, certaines fonctionnalités ne marcheront pas (notifications en temps réel).

---

## Vérification Post-Déploiement

### 1. Vérifier les assets
```bash
# Dans Railway logs, cherchez :
"📦 Publishing Livewire assets..."
```

### 2. Tester dans le navigateur
Ouvrez la console (F12) et vérifiez :
- ✅ Pas d'erreur 404 pour `livewire.js`
- ✅ Pas d'erreur "You must pass your app key"
- ✅ Le mode dark/light fonctionne

---

## Troubleshooting

### Livewire 404 persiste ?
```bash
# SSH dans Railway container (si possible) :
ls -la public/vendor/livewire/

# OU redéployez complètement :
railway up --detach
```

### Mode Dark ne fonctionne toujours pas ?
C'est Alpine.js. Vérifiez dans la console navigateur :
```javascript
// Devrait afficher la version Alpine
window.Alpine
```

Si `undefined`, c'est que Livewire n'a pas chargé Alpine. Retour au problème Pusher.

---

## Variables Railway Finales (copier-coller)

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://retech-market-production.up.railway.app

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2091867
PUSHER_APP_KEY=2b9fc757a0fee41d9b8f
PUSHER_APP_SECRET=ff820b74307903bbf7d8
PUSHER_APP_CLUSTER=eu

VITE_PUSHER_APP_KEY=2b9fc757a0fee41d9b8f
VITE_PUSHER_APP_CLUSTER=eu
```

**Important** : `VITE_*` doivent être des variables d'environnement Railway normales, PAS des secrets.
