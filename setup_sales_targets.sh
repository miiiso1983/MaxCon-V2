#!/bin/bash

# MaxCon ERP - Sales Targets Module Setup Script
# This script sets up the complete Sales Targets module

echo "🎯 MaxCon ERP - إعداد وحدة أهداف البيع"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    print_error "هذا الملف يجب تشغيله من مجلد Laravel الرئيسي"
    exit 1
fi

print_info "بدء إعداد وحدة أهداف البيع..."
echo ""

# Step 1: Run Migrations
echo "📊 الخطوة 1: تشغيل Migrations"
echo "--------------------------------"
if php artisan migrate --force; then
    print_status "تم إنشاء جداول قاعدة البيانات بنجاح"
else
    print_error "فشل في تشغيل migrations"
    exit 1
fi
echo ""

# Step 2: Seed Sample Data
echo "🌱 الخطوة 2: إضافة البيانات التجريبية"
echo "----------------------------------------"
if php artisan db:seed --class=SalesTargetsSeeder --force; then
    print_status "تم إضافة البيانات التجريبية بنجاح"
else
    print_warning "فشل في إضافة البيانات التجريبية (قد تكون موجودة مسبقاً)"
fi
echo ""

# Step 3: Clear Cache
echo "🧹 الخطوة 3: تنظيف الذاكرة المؤقتة"
echo "------------------------------------"
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
print_status "تم تنظيف الذاكرة المؤقتة"
echo ""

# Step 4: Generate Application Key (if needed)
echo "🔑 الخطوة 4: التحقق من مفتاح التطبيق"
echo "--------------------------------------"
if grep -q "APP_KEY=$" .env 2>/dev/null; then
    php artisan key:generate --force
    print_status "تم إنشاء مفتاح التطبيق"
else
    print_info "مفتاح التطبيق موجود مسبقاً"
fi
echo ""

# Step 5: Create Storage Link
echo "🔗 الخطوة 5: إنشاء رابط التخزين"
echo "--------------------------------"
if php artisan storage:link; then
    print_status "تم إنشاء رابط التخزين"
else
    print_info "رابط التخزين موجود مسبقاً"
fi
echo ""

# Step 6: Set Permissions
echo "🔒 الخطوة 6: تعيين الصلاحيات"
echo "-----------------------------"
if [ -d "storage" ]; then
    chmod -R 775 storage
    print_status "تم تعيين صلاحيات مجلد storage"
fi

if [ -d "bootstrap/cache" ]; then
    chmod -R 775 bootstrap/cache
    print_status "تم تعيين صلاحيات مجلد bootstrap/cache"
fi
echo ""

# Step 7: Install/Update Composer Dependencies
echo "📦 الخطوة 7: تحديث Composer Dependencies"
echo "----------------------------------------"
if command -v composer &> /dev/null; then
    composer install --optimize-autoloader --no-dev
    print_status "تم تحديث Composer dependencies"
else
    print_warning "Composer غير مثبت - تخطي هذه الخطوة"
fi
echo ""

# Step 8: Install/Update NPM Dependencies
echo "📦 الخطوة 8: تحديث NPM Dependencies"
echo "-----------------------------------"
if command -v npm &> /dev/null; then
    if [ -f "package.json" ]; then
        npm install
        npm run build
        print_status "تم تحديث NPM dependencies وبناء الأصول"
    else
        print_info "ملف package.json غير موجود - تخطي هذه الخطوة"
    fi
else
    print_warning "NPM غير مثبت - تخطي هذه الخطوة"
fi
echo ""

# Step 9: Test Database Connection
echo "🔌 الخطوة 9: اختبار الاتصال بقاعدة البيانات"
echo "--------------------------------------------"
if php artisan migrate:status &> /dev/null; then
    print_status "الاتصال بقاعدة البيانات يعمل بشكل صحيح"
else
    print_error "فشل في الاتصال بقاعدة البيانات"
    print_info "تأكد من إعدادات قاعدة البيانات في ملف .env"
fi
echo ""

# Step 10: Run Sales Targets Command Test
echo "🎯 الخطوة 10: اختبار أوامر أهداف البيع"
echo "--------------------------------------"
if php artisan sales-targets:update --help &> /dev/null; then
    print_status "أوامر أهداف البيع جاهزة للاستخدام"
else
    print_warning "تعذر العثور على أوامر أهداف البيع"
fi
echo ""

# Final Summary
echo "📋 ملخص الإعداد"
echo "==============="
echo ""
print_info "تم إكمال إعداد وحدة أهداف البيع!"
echo ""
echo "🔗 الروابط المتاحة:"
echo "   • قائمة الأهداف: /tenant/sales/targets"
echo "   • إنشاء هدف جديد: /tenant/sales/targets/create"
echo "   • لوحة التحكم: /tenant/sales/targets/dashboard/overview"
echo "   • التقارير: /tenant/sales/targets/reports/analytics"
echo ""
echo "📚 الأوامر المفيدة:"
echo "   • تحديث الأهداف: php artisan sales-targets:update"
echo "   • فحص الإشعارات: php artisan sales-targets:update --check-notifications"
echo "   • تشغيل Queue: php artisan queue:work"
echo ""
echo "⚙️  الإعدادات الإضافية المطلوبة:"
echo "   1. إعداد SMTP في ملف .env للإشعارات"
echo "   2. إعداد Queue driver (database أو redis)"
echo "   3. إضافة Cron job للتحديثات الدورية:"
echo "      0 9 * * * php $(pwd)/artisan sales-targets:update"
echo ""
echo "🧪 للاختبار:"
echo "   • افتح: http://localhost:8000/setup-sales-targets.html"
echo "   • أو: http://localhost:8000/test-sales-targets-module.html"
echo ""

# Check if server is running
if pgrep -f "php.*serve" > /dev/null; then
    print_status "خادم Laravel يعمل"
    echo "   🌐 يمكنك الوصول للتطبيق على: http://localhost:8000"
else
    print_info "لبدء الخادم، استخدم: php artisan serve"
fi

echo ""
print_status "🎉 تم إكمال الإعداد بنجاح!"
echo ""

# Optional: Open browser
read -p "هل تريد فتح صفحة الاختبار في المتصفح؟ (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    if command -v open &> /dev/null; then
        open "http://localhost:8000/setup-sales-targets.html"
    elif command -v xdg-open &> /dev/null; then
        xdg-open "http://localhost:8000/setup-sales-targets.html"
    else
        print_info "افتح المتصفح يدوياً على: http://localhost:8000/setup-sales-targets.html"
    fi
fi

exit 0
