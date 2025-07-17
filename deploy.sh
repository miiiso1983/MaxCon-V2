#!/bin/bash

# MaxCon ERP Deployment Script for Cloudways
# This script automates the deployment process

echo "🚀 Starting MaxCon ERP Deployment..."

# Exit on any error
set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    print_error "artisan file not found. Make sure you're in the Laravel project root."
    exit 1
fi

print_status "Putting application in maintenance mode..."
php artisan down --retry=60

print_status "Pulling latest changes from Git..."
git pull origin main

print_status "Installing/updating Composer dependencies..."
composer install --no-dev --optimize-autoloader

print_status "Installing/updating NPM dependencies..."
npm ci

print_status "Building assets..."
npm run build

print_status "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

print_status "Clearing and caching routes..."
php artisan route:clear
php artisan route:cache

print_status "Clearing and caching views..."
php artisan view:clear
php artisan view:cache

print_status "Optimizing autoloader..."
composer dump-autoload --optimize

print_status "Running database migrations..."
php artisan migrate --force

print_status "Clearing application cache..."
php artisan cache:clear

print_status "Caching events..."
php artisan event:cache

print_status "Restarting queue workers..."
php artisan queue:restart

print_status "Setting proper permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs

print_status "Bringing application back online..."
php artisan up

print_status "✅ Deployment completed successfully!"
print_warning "Don't forget to:"
echo "  1. Update your .env file with production values"
echo "  2. Generate a new APP_KEY if needed: php artisan key:generate"
echo "  3. Set up your database credentials"
echo "  4. Configure your mail settings"
echo "  5. Set up SSL certificate"
echo "  6. Configure your domain DNS"
