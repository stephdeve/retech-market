# 🚀 ReTech Market - Deployment Guide

Complete guide for deploying ReTech Market to production using Docker and CI/CD.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Local Development Setup](#local-development-setup)
- [Production Deployment](#production-deployment)
- [Environment Variables](#environment-variables)
- [GitHub Actions CI/CD](#github-actions-cicd)
- [Troubleshooting](#troubleshooting)
- [Scaling & Performance](#scaling--performance)

---

## ✅ Prerequisites

- **Docker** and **Docker Compose** installed
- **Git** installed
- Server with minimum 2GB RAM, 20GB storage
- Domain name (optional but recommended)
- GitHub account for CI/CD

---

## 🏗️ Local Development Setup

### 1. Clone Repository

```bash
git clone https://github.com/your-username/retech-market.git
cd retech-market
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
docker-compose run --rm app php artisan key:generate
```

### 3. Build and Start Services

```bash
# Build Docker images
docker-compose build

# Start all services
docker-compose up -d

# Check running containers
docker-compose ps
```

### 4. Database Setup

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Seed database (optional)
docker-compose exec app php artisan db:seed

# Create storage symlink
docker-compose exec app php artisan storage:link
```

### 5. Access Application

Visit: **http://localhost**

Default admin credentials (if seeded):
- Email: `admin@retechmarket.com`
- Password: `password`

---

## 🌍 Production Deployment

### Option 1: Manual Deployment (Recommended First Time)

#### 1. Server Preparation

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add user to docker group
sudo usermod -aG docker $USER
```

#### 2. Clone and Configure

```bash
# Clone repository
cd /var/www
git clone https://github.com/your-username/retech-market.git
cd retech-market

# Copy production environment
cp .env.production.example .env

# Edit .env with your production values
nano .env
```

**Important:** Update these values in `.env`:
- `APP_KEY` (generate with `php artisan key:generate`)
- `DB_PASSWORD`
- `PUSHER_*` credentials
- `MAIL_*` configuration
- `KKIAPAY_*` credentials

#### 3. Deploy

```bash
# Make deployment script executable
chmod +x deploy.sh

# Run deployment
./deploy.sh
```

#### 4. Domain Configuration (Optional)

Install Nginx reverse proxy or use Traefik for SSL/TLS:

```nginx
# /etc/nginx/sites-available/retechmarket.com
server {
    listen 80;
    server_name retechmarket.com www.retechmarket.com;
    
    location / {
        proxy_pass http://localhost:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

Enable SSL with Let's Encrypt:

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d retechmarket.com -d www.retechmarket.com
```

---

### Option 2: Automated CI/CD Deployment

#### GitHub Secrets Setup

Add these secrets in your GitHub repository:

1. `DEPLOY_HOST` - Your server IP/domain
2. `DEPLOY_USER` - SSH username
3. `DEPLOY_KEY` - SSH private key
4. `SLACK_WEBHOOK` - Slack webhook URL (optional)

#### Workflow

1. **Push to `main` branch** → Triggers deployment
2. **GitHub Actions**:
   - Runs tests
   - Builds Docker image
   - Pushes to GitHub Container Registry
   - Deploys to production server
   - Runs migrations and cache optimization
3. **Notification** sent to Slack (if configured)

---

## 🔐 Environment Variables

### Essential Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `APP_ENV` | Environment | `production` |
| `APP_DEBUG` | Debug mode | `false` |
| `APP_URL` | Application URL | `https://retechmarket.com` |
| `DB_DATABASE` | Database name | `retech_market` |
| `DB_USERNAME` | Database user | `retech_user` |
| `DB_PASSWORD` | Database password | `secure_password` |

### Docker Service Hostnames

When running in Docker, use these hostnames:

- Database: `mysql` (not `127.0.0.1`)
- Redis: `redis`
- Cache: `redis`
- Queue: `redis`

---

## 🤖 GitHub Actions CI/CD

### Workflows

#### 1. Tests (`tests.yml`)

**Triggers**: Pull requests, push to `develop`

**Steps**:
- Checkout code
- Setup PHP 8.3
- Install dependencies
- Run migrations
- Execute tests

#### 2. Deploy (`deploy.yml`)

**Triggers**: Push to `main`, manual trigger

**Steps**:
- Build Docker image
- Push to GitHub Container Registry
- SSH to production server
- Pull latest images
- Run migrations
- Optimize caches

### Container Registry

Images are pushed to: `ghcr.io/your-username/retech-market`

---

## 🔧 Troubleshooting

### Common Issues

#### 1. Permission Errors

```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

#### 2. Database Connection Failed

```bash
# Check MySQL service
docker-compose ps mysql

# View MySQL logs
docker-compose logs mysql

# Restart MySQL
docker-compose restart mysql
```

#### 3. Queue Not Processing

```bash
# Check queue worker logs
docker-compose logs queue

# Restart queue worker
docker-compose restart queue
```

#### 4. Assets Not Loading

```bash
# Rebuild assets
docker-compose exec app npm run build

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear
```

### View Logs

```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f mysql
docker-compose logs -f nginx

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log
```

---

## 📈 Scaling & Performance

### Horizontal Scaling

Scale queue workers for better performance:

```bash
docker-compose up -d --scale queue=3
```

### Database Optimization

Edit `docker/mysql/my.cnf`:

```ini
innodb_buffer_pool_size = 512M  # Increase for more RAM
max_connections = 500            # Handle more concurrent users
```

### Redis Optimization

For high-traffic scenarios, consider Redis Cluster or use a managed Redis service.

### CDN Integration

Serve static assets via CDN:

1. Update `FILESYSTEM_DISK=s3` in `.env`
2. Configure AWS S3 credentials
3. Update `APP_URL` to CDN domain

### Monitoring

Install monitoring tools:

```bash
# Prometheus + Grafana
docker-compose -f docker-compose.monitoring.yml up -d
```

### Backup Strategy

**Automated daily backups**:

```bash
# Add to crontab
0 2 * * * cd /var/www/retech-market && docker-compose exec -T mysql mysqldump -u root -p$DB_ROOT_PASSWORD retech_market > backups/db-$(date +\%Y\%m\%d).sql
```

---

## 🆘 Support

- **Documentation**: Check Laravel docs at https://laravel.com/docs
- **GitHub Issues**: Report bugs at your repository
- **Community**: Laravel community forums

---

## 📝 Quick Commands Reference

```bash
# View running containers
docker-compose ps

# Restart all services
docker-compose restart

# Run migrations
docker-compose exec app php artisan migrate

# Clear all caches
docker-compose exec app php artisan optimize:clear

# Access container shell
docker-compose exec app sh

# View real-time logs
docker-compose logs -f

# Stop all services
docker-compose down

# Remove all containers and volumes (⚠️ destructive)
docker-compose down -v
```

---

**🎉 Happy Deploying!**
