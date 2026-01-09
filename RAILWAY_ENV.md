# ReTech Market - Railway Environment Variables

## Required Variables

```bash
# Application
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_KEY_HERE  # Will be auto-generated if missing
APP_URL=https://retech-market-production.up.railway.app

# Database (automatically provided by Railway MySQL plugin)
# DATABASE_URL will be set by Railway - no manual config needed

# Pusher (Required for Livewire & Notifications)
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=eu

# Vite (for assets)
VITE_PUSHER_APP_KEY=${PUSHER_APP_KEY}
VITE_PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER}

# Broadcasting
BROADCAST_CONNECTION=pusher

# Mail (Mailtrap or SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@retechmarket.com

# Payment Gateway (Kkiapay)
KKIAPAY_PUBLIC_KEY=your_public_key
KKIAPAY_PRIVATE_KEY=your_private_key
KKIAPAY_SECRET=your_secret
KKIAPAY_SANDBOX=false
```

## Optional Variables

```bash
# Redis (if using Railway Redis plugin)
REDIS_URL=redis://...

# Session/Cache (will use database if Redis not configured)
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

## How to configure in Railway

1. Go to your Railway project
2. Click on your service
3. Go to "Variables" tab
4. Add the variables listed above
5. Redeploy

## Important Notes

- `DATABASE_URL` is automatically set by Railway MySQL plugin
- `APP_KEY` will be auto-generated on first deployment if not set
- Pusher credentials are REQUIRED for Livewire to work
- Without Pusher, you'll see "404 livewire.js" errors
