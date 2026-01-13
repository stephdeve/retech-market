#!/bin/sh

set -e

echo "🚀 Starting ReTech Market on Railway..."

# Create storage directories if they don't exist
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/products
mkdir -p storage/app/public/products/videos
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then
    echo "⚠️  Generating APP_KEY..."
    php artisan key:generate --force
fi

# Publish Livewire assets (JS/CSS)
echo "📦 Publishing Livewire assets..."
php artisan livewire:publish || echo "⚠️  Livewire publish skipped"

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

php artisan db:seed --class=AdminSeeder --force
php artisan db:seed --class=CategorySeeder --force

# Clear all caches first
echo "🧹 Clearing caches..."
php artisan optimize:clear

# Cache configuration for performance
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Create storage link
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

# Configure Nginx to listen on Railway's PORT
PORT=${PORT:-8080}
echo "🌐 Configuring Nginx to listen on port $PORT..."

cat > /etc/nginx/http.d/default.conf <<EOF
server {
    listen $PORT;
    listen [::]:$PORT;
    server_name _;
    root /var/www/html/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    client_max_body_size 20M;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
}
EOF

echo "✅ Starting services..."

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
exec nginx -g 'daemon off;'
