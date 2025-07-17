#!/bin/bash

# MaxCon ERP - Cloudways Deployment Script
# هذا السكريبت يساعد في نشر مشروع MaxCon ERP على خادم Cloudways

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check prerequisites
check_prerequisites() {
    print_status "Checking prerequisites..."
    
    if ! command_exists php; then
        print_error "PHP is not installed or not in PATH"
        exit 1
    fi
    
    if ! command_exists composer; then
        print_error "Composer is not installed or not in PATH"
        exit 1
    fi
    
    if ! command_exists npm; then
        print_error "NPM is not installed or not in PATH"
        exit 1
    fi
    
    print_success "All prerequisites are met"
}

# Function to prepare project for deployment
prepare_project() {
    print_status "Preparing project for deployment..."
    
    # Check if .env exists
    if [ ! -f .env ]; then
        print_warning ".env file not found. Creating from .env.example..."
        cp .env.example .env
        print_warning "Please update .env file with your production settings before continuing"
        read -p "Press Enter after updating .env file..."
    fi
    
    # Install dependencies
    print_status "Installing Composer dependencies..."
    composer install --optimize-autoloader --no-dev
    
    # Install NPM dependencies
    print_status "Installing NPM dependencies..."
    npm install
    
    # Build assets
    print_status "Building assets for production..."
    npm run build
    
    print_success "Project prepared successfully"
}

# Function to optimize for production
optimize_for_production() {
    print_status "Optimizing application for production..."
    
    # Generate application key if not set
    if ! grep -q "APP_KEY=base64:" .env; then
        print_status "Generating application key..."
        php artisan key:generate
    fi
    
    # Cache configuration
    print_status "Caching configuration..."
    php artisan config:cache
    
    # Cache routes
    print_status "Caching routes..."
    php artisan route:cache
    
    # Cache views
    print_status "Caching views..."
    php artisan view:cache
    
    # Cache events
    print_status "Caching events..."
    php artisan event:cache
    
    print_success "Application optimized for production"
}

# Function to create deployment archive
create_deployment_archive() {
    print_status "Creating deployment archive..."
    
    ARCHIVE_NAME="maxcon-erp-$(date +%Y%m%d-%H%M%S).tar.gz"
    
    # Create archive excluding unnecessary files
    tar -czf "$ARCHIVE_NAME" \
        --exclude=node_modules \
        --exclude=.git \
        --exclude=.gitignore \
        --exclude=.env.example \
        --exclude=storage/logs/* \
        --exclude=tests \
        --exclude=phpunit.xml \
        --exclude=README.md \
        --exclude=CONTRIBUTING.md \
        --exclude="*.md" \
        --exclude=deploy-to-cloudways.sh \
        .
    
    print_success "Deployment archive created: $ARCHIVE_NAME"
    echo "Upload this file to your Cloudways server via SFTP"
}

# Function to generate production .env template
generate_production_env() {
    print_status "Generating production .env template..."
    
    cat > .env.production << 'EOF'
APP_NAME="MaxCon ERP"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_LOCALE=ar
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ar_SA

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4
BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=maxcon_erp
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Central Database for Multi-Tenancy
CENTRAL_DB_CONNECTION=mysql
CENTRAL_DB_HOST=localhost
CENTRAL_DB_PORT=3306
CENTRAL_DB_DATABASE=maxcon_central
CENTRAL_DB_USERNAME=your_db_username
CENTRAL_DB_PASSWORD=your_db_password

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
MEMCACHED_HOST=127.0.0.1

# Redis (if available)
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.cloudways.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# AWS (if using S3)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Multi-Tenancy
TENANCY_ENABLED=true
CENTRAL_DOMAIN=yourdomain.com

VITE_APP_NAME="${APP_NAME}"
EOF

    print_success "Production .env template created: .env.production"
    print_warning "Remember to update the values in .env.production before uploading to server"
}

# Function to show post-deployment commands
show_post_deployment_commands() {
    print_status "Post-deployment commands to run on server:"
    
    cat << 'EOF'

=== Commands to run on Cloudways server via SSH ===

# 1. Navigate to application directory
cd applications/maxcon-erp/public_html

# 2. Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 3. Create storage link
php artisan storage:link

# 4. Run migrations
php artisan migrate --force

# 5. Seed initial data
php artisan db:seed --class=RolePermissionSeeder

# 6. Create super admin user
php artisan tinker
# Then run in tinker:
use App\Models\User;
use Spatie\Permission\Models\Role;
$user = User::create([
    'name' => 'Super Admin',
    'email' => 'admin@maxcon.com',
    'password' => bcrypt('your-secure-password'),
    'email_verified_at' => now(),
]);
$superAdminRole = Role::where('name', 'super-admin')->first();
$user->assignRole($superAdminRole);
echo "Super Admin created successfully!";
exit;

# 7. Clear and cache everything
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Test the application
curl -I https://yourdomain.com

=== End of server commands ===

EOF
}

# Main deployment function
main() {
    echo "=================================================="
    echo "    MaxCon ERP - Cloudways Deployment Script"
    echo "=================================================="
    echo ""
    
    # Check prerequisites
    check_prerequisites
    
    echo ""
    echo "What would you like to do?"
    echo "1) Prepare project for deployment"
    echo "2) Create deployment archive"
    echo "3) Generate production .env template"
    echo "4) Show post-deployment commands"
    echo "5) Full deployment preparation (1+2+3)"
    echo ""
    read -p "Enter your choice (1-5): " choice
    
    case $choice in
        1)
            prepare_project
            optimize_for_production
            ;;
        2)
            create_deployment_archive
            ;;
        3)
            generate_production_env
            ;;
        4)
            show_post_deployment_commands
            ;;
        5)
            prepare_project
            optimize_for_production
            create_deployment_archive
            generate_production_env
            show_post_deployment_commands
            ;;
        *)
            print_error "Invalid choice. Please run the script again."
            exit 1
            ;;
    esac
    
    echo ""
    print_success "Deployment preparation completed!"
    echo ""
    echo "Next steps:"
    echo "1. Upload the archive to your Cloudways server"
    echo "2. Extract it in the public_html directory"
    echo "3. Update .env file with production settings"
    echo "4. Run the post-deployment commands"
    echo ""
}

# Run main function
main "$@"
