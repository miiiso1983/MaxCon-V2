#!/bin/bash

# MaxCon ERP - Post-Deployment Health Check Script
# يتحقق هذا السكريبت من صحة التطبيق بعد النشر على Cloudways

echo "🏥 فحص صحة MaxCon ERP بعد النشر..."
echo "=================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Counters
PASSED=0
FAILED=0
WARNINGS=0

# Function to print results
print_check() {
    if [ $2 -eq 0 ]; then
        echo -e "${GREEN}✅ $1${NC}"
        ((PASSED++))
    else
        echo -e "${RED}❌ $1${NC}"
        ((FAILED++))
    fi
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
    ((WARNINGS++))
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

echo -e "\n${BLUE}1. فحص ملفات Laravel الأساسية${NC}"
echo "-----------------------------"

# Check if Laravel is properly installed
if php artisan --version > /dev/null 2>&1; then
    VERSION=$(php artisan --version)
    print_check "Laravel يعمل بشكل صحيح - $VERSION" 0
else
    print_check "Laravel لا يعمل بشكل صحيح" 1
fi

# Check if .env file exists
[ -f ".env" ] && print_check "ملف .env موجود" 0 || print_check "ملف .env مفقود" 1

# Check if APP_KEY is set
if [ -f ".env" ]; then
    if grep -q "APP_KEY=base64:" .env; then
        print_check "APP_KEY مضبوط بشكل صحيح" 0
    else
        print_check "APP_KEY غير مضبوط" 1
    fi
fi

echo -e "\n${BLUE}2. فحص قاعدة البيانات${NC}"
echo "--------------------"

# Test database connection
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" > /dev/null 2>&1; then
    print_check "الاتصال بقاعدة البيانات يعمل" 0
else
    print_check "فشل الاتصال بقاعدة البيانات" 1
fi

# Check if migrations are up to date
if php artisan migrate:status > /dev/null 2>&1; then
    print_check "جداول قاعدة البيانات موجودة" 0
else
    print_check "مشكلة في جداول قاعدة البيانات" 1
fi

echo -e "\n${BLUE}3. فحص الصلاحيات${NC}"
echo "-----------------"

# Check storage permissions
if [ -w "storage" ]; then
    print_check "صلاحيات مجلد storage صحيحة" 0
else
    print_check "مشكلة في صلاحيات مجلد storage" 1
fi

# Check bootstrap/cache permissions
if [ -w "bootstrap/cache" ]; then
    print_check "صلاحيات مجلد bootstrap/cache صحيحة" 0
else
    print_check "مشكلة في صلاحيات مجلد bootstrap/cache" 1
fi

echo -e "\n${BLUE}4. فحص التخزين المؤقت${NC}"
echo "---------------------"

# Test cache
if php artisan cache:clear > /dev/null 2>&1; then
    print_check "نظام التخزين المؤقت يعمل" 0
else
    print_check "مشكلة في نظام التخزين المؤقت" 1
fi

# Test config cache
if php artisan config:cache > /dev/null 2>&1; then
    print_check "تخزين الإعدادات مؤقتاً يعمل" 0
else
    print_check "مشكلة في تخزين الإعدادات مؤقتاً" 1
fi

echo -e "\n${BLUE}5. فحص الطرق والعروض${NC}"
echo "---------------------"

# Test routes
if php artisan route:list > /dev/null 2>&1; then
    print_check "الطرق (Routes) تعمل بشكل صحيح" 0
else
    print_check "مشكلة في الطرق (Routes)" 1
fi

# Test views
if php artisan view:clear > /dev/null 2>&1; then
    print_check "العروض (Views) تعمل بشكل صحيح" 0
else
    print_check "مشكلة في العروض (Views)" 1
fi

echo -e "\n${BLUE}6. فحص الخدمات الخارجية${NC}"
echo "------------------------"

# Test Redis connection (if configured)
if grep -q "CACHE_STORE=redis" .env 2>/dev/null; then
    if redis-cli ping > /dev/null 2>&1; then
        print_check "اتصال Redis يعمل" 0
    else
        print_check "مشكلة في اتصال Redis" 1
    fi
else
    print_info "Redis غير مكون"
fi

# Test mail configuration
if grep -q "MAIL_MAILER=smtp" .env 2>/dev/null; then
    print_info "إعدادات البريد الإلكتروني مكونة"
else
    print_warning "إعدادات البريد الإلكتروني غير مكونة"
fi

echo -e "\n${BLUE}7. فحص الأمان${NC}"
echo "-------------"

# Check if APP_DEBUG is false
if grep -q "APP_DEBUG=false" .env 2>/dev/null; then
    print_check "APP_DEBUG مضبوط على false (آمن)" 0
else
    print_check "APP_DEBUG يجب أن يكون false في الإنتاج" 1
fi

# Check if APP_ENV is production
if grep -q "APP_ENV=production" .env 2>/dev/null; then
    print_check "APP_ENV مضبوط على production" 0
else
    print_check "APP_ENV يجب أن يكون production" 1
fi

echo -e "\n${BLUE}8. فحص الأداء${NC}"
echo "-------------"

# Check if config is cached
if [ -f "bootstrap/cache/config.php" ]; then
    print_check "إعدادات Laravel محفوظة مؤقتاً" 0
else
    print_warning "إعدادات Laravel غير محفوظة مؤقتاً - قم بتشغيل php artisan config:cache"
fi

# Check if routes are cached
if [ -f "bootstrap/cache/routes-v7.php" ]; then
    print_check "الطرق محفوظة مؤقتاً" 0
else
    print_warning "الطرق غير محفوظة مؤقتاً - قم بتشغيل php artisan route:cache"
fi

echo -e "\n${BLUE}9. فحص اللوجز${NC}"
echo "-------------"

# Check if log directory is writable
if [ -w "storage/logs" ]; then
    print_check "مجلد اللوجز قابل للكتابة" 0
else
    print_check "مشكلة في صلاحيات مجلد اللوجز" 1
fi

# Check for recent errors in logs
if [ -f "storage/logs/laravel.log" ]; then
    ERROR_COUNT=$(tail -100 storage/logs/laravel.log | grep -c "ERROR" || echo "0")
    if [ "$ERROR_COUNT" -eq 0 ]; then
        print_check "لا توجد أخطاء حديثة في اللوجز" 0
    else
        print_warning "توجد $ERROR_COUNT أخطاء في آخر 100 سطر من اللوجز"
    fi
else
    print_info "ملف اللوجز غير موجود بعد"
fi

echo -e "\n${BLUE}📊 ملخص النتائج${NC}"
echo "==============="
echo -e "${GREEN}✅ نجح: $PASSED${NC}"
echo -e "${RED}❌ فشل: $FAILED${NC}"
echo -e "${YELLOW}⚠️  تحذيرات: $WARNINGS${NC}"

echo -e "\n${BLUE}📋 التوصيات${NC}"
echo "============"

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 التطبيق يعمل بشكل صحيح!${NC}"
    echo ""
    echo "خطوات إضافية موصى بها:"
    echo "1. اختبر جميع الوظائف الأساسية"
    echo "2. تحقق من عمل نظام تسجيل الدخول"
    echo "3. اختبر إنشاء المستأجرين"
    echo "4. تحقق من عمل النسخ الاحتياطي"
    echo "5. راقب الأداء والذاكرة"
else
    echo -e "${RED}⚠️  يجب إصلاح المشاكل المذكورة أعلاه${NC}"
    echo ""
    echo "خطوات الإصلاح:"
    echo "1. تحقق من إعدادات قاعدة البيانات"
    echo "2. تأكد من صلاحيات المجلدات"
    echo "3. راجع ملف .env"
    echo "4. تحقق من لوجز الأخطاء"
fi

if [ $WARNINGS -gt 0 ]; then
    echo -e "${YELLOW}💡 راجع التحذيرات وحسن الأداء${NC}"
fi

echo ""
echo "للمراقبة المستمرة:"
echo "- راقب storage/logs/laravel.log"
echo "- تحقق من استخدام الذاكرة والمعالج"
echo "- راقب أداء قاعدة البيانات"
echo "- تأكد من عمل النسخ الاحتياطي"
