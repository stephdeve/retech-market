#!/bin/bash

# ReTech Market Deployment Script
# This script deploys the latest version of the application

set -e

echo "🚀 Starting deployment..."

# Pull latest changes
echo "📥 Pulling latest Docker images..."
docker-compose pull

# Stop and remove old containers
echo "🛑 Stopping old containers..."
docker-compose down

# Start new containers
echo "▶️  Starting new containers..."
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 10

# Run migrations
echo "🔄 Running database migrations..."
docker-compose exec -T app php artisan migrate --force

# Clear and cache configuration
echo "🧹 Optimizing application..."
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache
docker-compose exec -T app php artisan event:cache

# Create storage link if not exists
echo "🔗 Creating storage symlink..."
docker-compose exec -T app php artisan storage:link

# Restart queue workers
echo "♻️  Restarting queue workers..."
docker-compose restart queue

# Health check
echo "🏥 Running health check..."
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)

if [ "$RESPONSE" -eq 200 ]; then
    echo "✅ Deployment successful! Application is running."
    echo "🌐 Application available at: http://localhost"
else
    echo "❌ Deployment failed! Health check returned status: $RESPONSE"
    echo "📋 Check logs with: docker-compose logs"
    exit 1
fi

# Show running containers
echo ""
echo "📊 Running containers:"
docker-compose ps

echo ""
echo "✨ Deployment completed!"
echo "📝 View logs: docker-compose logs -f"
