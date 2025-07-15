# MaxCon SaaS - Multi-Tenant Laravel Application

🚀 **MaxCon SaaS** is a professional multi-tenant SaaS application built with Laravel 12+, designed for scalability, security, and modern development practices.

## 🎯 Features

### 🏢 Multi-Tenancy
- **Complete tenant isolation** with shared database architecture
- **Subdomain and custom domain support**
- **Tenant-specific settings and configurations**
- **Automatic tenant context switching**

### 🔐 Security & Authentication
- **Role-Based Access Control (RBAC)** with Spatie Permission
- **Two-Factor Authentication (2FA)** with Google Authenticator
- **Rate limiting** for login attempts and API calls
- **Activity logging** for audit trails
- **Secure password policies**

### 🎨 Modern Frontend
- **Tailwind CSS** for responsive design
- **Alpine.js** for lightweight interactions
- **Livewire** for real-time components
- **Font Awesome** icons
- **Mobile-first responsive design**

### 🏗️ Architecture
- **Repository Pattern** for data access
- **Service Layer** for business logic
- **Event-Driven Architecture**
- **API-First design** with RESTful endpoints
- **Clean Code principles** with SOLID design patterns

### 📊 Admin Features
- **Super Admin Dashboard** for system management
- **Tenant management** with suspension/activation
- **User management** with role assignment
- **System health monitoring**
- **Activity logs and reporting**

## 🛠️ Tech Stack

### Backend
- **Laravel 12+** (PHP 8.1+)
- **MySQL/MariaDB** for database
- **Redis** for caching and sessions
- **Laravel Sanctum** for API authentication
- **Spatie Packages** for permissions and activity logs

### Frontend
- **Blade Templates** with Livewire
- **Tailwind CSS** for styling
- **Alpine.js** for interactions
- **Vite** for asset compilation

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/MariaDB

### Installation

1. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure database in .env**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=maxcon_saas
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed --class=RolePermissionSeeder
   php artisan db:seed --class=SuperAdminSeeder
   ```

5. **Start the application**
   ```bash
   # Terminal 1: Start Laravel server
   php artisan serve
   
   # Terminal 2: Start Vite dev server
   npm run dev
   ```

## 👥 Default Users

### Super Admin
- **Email:** admin@maxcon.com
- **Password:** password123
- **Access:** Full system administration

### Demo Tenant Admin
- **Email:** admin@demo.com
- **Password:** password123
- **Tenant:** demo.localhost:8000

### Demo Users
- **Manager:** manager@demo.com / password123
- **Employee:** employee@demo.com / password123
- **Customer:** customer@demo.com / password123

## 🏗️ Project Structure

```
app/
├── Http/Controllers/
│   ├── Admin/              # Super admin controllers
│   ├── Api/                # API controllers
│   └── Auth/               # Authentication controllers
├── Livewire/               # Livewire components
├── Models/                 # Eloquent models
├── Repositories/           # Repository pattern
├── Services/               # Business logic
├── Contracts/              # Interfaces
└── Providers/              # Service providers

resources/
├── css/
│   ├── app.css            # Main styles
│   └── components.css     # Custom components
├── js/app.js              # JavaScript entry
└── views/
    ├── layouts/           # Layout templates
    ├── auth/              # Authentication views
    ├── admin/             # Admin panel views
    └── livewire/          # Livewire components
```

## 🔧 Key Features Implemented

### ✅ Multi-Tenant Architecture
- Tenant model with isolation
- Subdomain routing support
- Tenant middleware for context switching
- Tenant-specific user scoping

### ✅ Authentication & Security
- Login with rate limiting
- Role-based permissions (5 roles: super-admin, tenant-admin, manager, employee, customer)
- Activity logging for audit trails
- Secure password hashing

### ✅ Admin Panel
- Super admin dashboard with system stats
- Tenant management (CRUD operations)
- User management with Livewire
- System health monitoring

### ✅ API Endpoints
- RESTful API with Sanctum authentication
- Rate limiting and security middleware
- Comprehensive user and tenant endpoints

### ✅ Modern Frontend
- Responsive design with Tailwind CSS
- Interactive components with Alpine.js
- Real-time updates with Livewire
- Professional UI/UX design

## 📚 API Documentation

### Authentication
```http
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/user
```

### Users (with permissions)
```http
GET    /api/v1/users
POST   /api/v1/users
GET    /api/v1/users/{id}
PUT    /api/v1/users/{id}
DELETE /api/v1/users/{id}
```

### Admin (Super Admin only)
```http
GET    /api/v1/admin/tenants
POST   /api/v1/admin/tenants
GET    /api/v1/admin/system/stats
```

## 🧪 Testing the Application

1. **Access the application:**
   - Main site: http://localhost:8000
   - Demo tenant: http://demo.localhost:8000

2. **Login as Super Admin:**
   - Go to http://localhost:8000/login
   - Email: admin@maxcon.com
   - Password: password123

3. **Test tenant context:**
   - Go to http://demo.localhost:8000/login
   - Email: admin@demo.com
   - Password: password123

4. **Test user management:**
   - Navigate to Users section
   - Create, edit, and manage users
   - Test role assignments

## 🚀 Production Deployment

1. **Optimize for production:**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

2. **Set environment:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Configure web server for subdomains**
4. **Set up SSL certificates**
5. **Configure Redis for caching**

## 📄 License

This project is licensed under the MIT License.

---

**Built with ❤️ using Laravel 12, Tailwind CSS, Livewire, and modern web technologies.**
