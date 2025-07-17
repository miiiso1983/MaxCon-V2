#!/bin/bash

# MaxCon ERP - Cloudways Server Setup Script
# Run this script on your Cloudways server after uploading the project files

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

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

# Check if we're in the right directory
check_directory() {
    if [ ! -f "artisan" ]; then
        print_error "artisan file not found. Please run this script from the Laravel project root directory."
        exit 1
    fi
    print_success "Found Laravel project"
}

# Set proper file permissions
set_permissions() {
    print_status "Setting proper file permissions..."
    
    # Set directory permissions
    find . -type d -exec chmod 755 {} \;
    
    # Set file permissions
    find . -type f -exec chmod 644 {} \;
    
    # Make artisan executable
    chmod +x artisan
    
    # Set storage and cache permissions
    chmod -R 775 storage
    chmod -R 775 bootstrap/cache
    
    # Change ownership to web server user
    if id "www-data" &>/dev/null; then
        chown -R www-data:www-data storage bootstrap/cache
        print_success "Changed ownership to www-data"
    elif id "apache" &>/dev/null; then
        chown -R apache:apache storage bootstrap/cache
        print_success "Changed ownership to apache"
    else
        print_warning "Could not determine web server user. You may need to set ownership manually."
    fi
    
    print_success "File permissions set successfully"
}

# Install Composer dependencies
install_dependencies() {
    print_status "Installing Composer dependencies..."
    
    if ! command -v composer &> /dev/null; then
        print_error "Composer not found. Please install Composer first."
        exit 1
    fi
    
    composer install --optimize-autoloader --no-dev
    print_success "Composer dependencies installed"
}

# Generate application key
generate_app_key() {
    print_status "Generating application key..."
    
    if ! grep -q "APP_KEY=base64:" .env; then
        php artisan key:generate --force
        print_success "Application key generated"
    else
        print_warning "Application key already exists"
    fi
}

# Create storage link
create_storage_link() {
    print_status "Creating storage link..."
    
    if [ ! -L "public/storage" ]; then
        php artisan storage:link
        print_success "Storage link created"
    else
        print_warning "Storage link already exists"
    fi
}

# Run database migrations
run_migrations() {
    print_status "Running database migrations..."
    
    # Check database connection first
    if php artisan migrate:status &>/dev/null; then
        php artisan migrate --force
        print_success "Database migrations completed"
    else
        print_error "Database connection failed. Please check your .env database settings."
        return 1
    fi
}

# Seed initial data
seed_database() {
    print_status "Seeding initial data..."
    
    # Check if RolePermissionSeeder exists
    if [ -f "database/seeders/RolePermissionSeeder.php" ]; then
        php artisan db:seed --class=RolePermissionSeeder --force
        print_success "Initial data seeded"
    else
        print_warning "RolePermissionSeeder not found. Skipping seeding."
    fi
}

# Create super admin user
create_super_admin() {
    print_status "Creating super admin user..."
    
    read -p "Enter super admin email [admin@maxcon.com]: " admin_email
    admin_email=${admin_email:-admin@maxcon.com}
    
    read -s -p "Enter super admin password: " admin_password
    echo
    
    if [ -z "$admin_password" ]; then
        print_error "Password cannot be empty"
        return 1
    fi
    
    # Create super admin using tinker
    php artisan tinker --execute="
        use App\Models\User;
        use Spatie\Permission\Models\Role;
        
        \$user = User::where('email', '$admin_email')->first();
        if (\$user) {
            echo 'User already exists with email: $admin_email\n';
        } else {
            \$user = User::create([
                'name' => 'Super Admin',
                'email' => '$admin_email',
                'password' => bcrypt('$admin_password'),
                'email_verified_at' => now(),
            ]);
            
            \$superAdminRole = Role::where('name', 'super-admin')->first();
            if (\$superAdminRole) {
                \$user->assignRole(\$superAdminRole);
                echo 'Super Admin created successfully with email: $admin_email\n';
            } else {
                echo 'Super Admin role not found. Please run seeders first.\n';
            }
        }
    "
    
    print_success "Super admin setup completed"
}

# Optimize application for production
optimize_application() {
    print_status "Optimizing application for production..."
    
    # Clear all caches first
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    php artisan event:clear
    
    # Cache everything for production
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    
    print_success "Application optimized for production"
}

# Test application
test_application() {
    print_status "Testing application..."
    
    # Test basic Laravel functionality
    if php artisan --version &>/dev/null; then
        print_success "Laravel is working correctly"
    else
        print_error "Laravel test failed"
        return 1
    fi
    
    # Test database connection
    if php artisan migrate:status &>/dev/null; then
        print_success "Database connection is working"
    else
        print_error "Database connection failed"
        return 1
    fi
    
    print_success "Application tests passed"
}

# Main setup function
main() {
    echo "=================================================="
    echo "    MaxCon ERP - Cloudways Server Setup"
    echo "=================================================="
    echo ""
    
    print_status "Starting server setup..."
    
    # Check if we're in the right directory
    check_directory
    
    # Check if .env file exists
    if [ ! -f ".env" ]; then
        print_error ".env file not found. Please create it from .env.example or .env.cloudways"
        echo "You can copy: cp .env.cloudways .env"
        exit 1
    fi
    
    # Run setup steps
    set_permissions
    install_dependencies
    generate_app_key
    create_storage_link
    
    # Database setup
    print_status "Setting up database..."
    if run_migrations; then
        seed_database
        
        # Ask if user wants to create super admin
        read -p "Do you want to create a super admin user? (y/n): " create_admin
        if [[ $create_admin =~ ^[Yy]$ ]]; then
            create_super_admin
        fi
    else
        print_warning "Skipping database seeding due to migration failure"
    fi
    
    # Optimize application
    optimize_application
    
    # Test application
    test_application
    
    echo ""
    print_success "Server setup completed successfully!"
    echo ""
    echo "Next steps:"
    echo "1. Configure your domain in Cloudways dashboard"
    echo "2. Set up SSL certificate"
    echo "3. Configure DNS records"
    echo "4. Test your application at your domain"
    echo ""
    echo "Your application should now be accessible!"
}

# Show usage if help is requested
if [[ "$1" == "--help" || "$1" == "-h" ]]; then
    echo "MaxCon ERP - Cloudways Server Setup Script"
    echo ""
    echo "Usage: $0 [options]"
    echo ""
    echo "This script sets up the MaxCon ERP application on a Cloudways server."
    echo "Make sure to run this script from the Laravel project root directory."
    echo ""
    echo "Prerequisites:"
    echo "- Upload project files to server"
    echo "- Create and configure .env file"
    echo "- Ensure database is created and accessible"
    echo ""
    exit 0
fi

# Run main function
main "$@"
