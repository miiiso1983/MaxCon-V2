#!/bin/bash

# MaxCon ERP - Pre-Deployment Check Script
# يتحقق هذا السكريبت من جاهزية المشروع للنشر على Cloudways

echo "🔍 فحص جاهزية MaxCon ERP للنشر على Cloudways..."
echo "=================================================="

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

echo -e "\n${BLUE}1. فحص الملفات الأساسية${NC}"
echo "------------------------"

# Check essential files
[ -f "artisan" ] && print_check "ملف artisan موجود" 0 || print_check "ملف artisan مفقود" 1
[ -f "composer.json" ] && print_check "ملف composer.json موجود" 0 || print_check "ملف composer.json مفقود" 1
[ -f "package.json" ] && print_check "ملف package.json موجود" 0 || print_check "ملف package.json مفقود" 1
[ -f ".env.example" ] && print_check "ملف .env.example موجود" 0 || print_check "ملف .env.example مفقود" 1
[ -f ".env.production" ] && print_check "ملف .env.production موجود" 0 || print_check "ملف .env.production مفقود" 1
[ -f "deploy.sh" ] && print_check "سكريبت النشر deploy.sh موجود" 0 || print_check "سكريبت النشر deploy.sh مفقود" 1

echo -e "\n${BLUE}2. فحص أدلة النشر${NC}"
echo "-------------------"

[ -f "CLOUDWAYS_DEPLOYMENT_GUIDE.md" ] && print_check "دليل النشر الشامل موجود" 0 || print_check "دليل النشر الشامل مفقود" 1
[ -f "cloudways-setup.md" ] && print_check "دليل الإعداد السريع موجود" 0 || print_check "دليل الإعداد السريع مفقود" 1

echo -e "\n${BLUE}3. فحص إعدادات الأمان${NC}"
echo "----------------------"

[ -f "public/.htaccess" ] && print_check "ملف .htaccess محسن موجود" 0 || print_check "ملف .htaccess مفقود" 1

# Check if .htaccess has security headers
if [ -f "public/.htaccess" ]; then
    if grep -q "X-Content-Type-Options" public/.htaccess; then
        print_check "إعدادات الأمان في .htaccess موجودة" 0
    else
        print_check "إعدادات الأمان في .htaccess مفقودة" 1
    fi
fi

echo -e "\n${BLUE}4. فحص التبعيات${NC}"
echo "----------------"

# Check if composer.lock exists
[ -f "composer.lock" ] && print_check "ملف composer.lock موجود" 0 || print_warning "ملف composer.lock مفقود - قم بتشغيل composer install"

# Check if package-lock.json exists
[ -f "package-lock.json" ] && print_check "ملف package-lock.json موجود" 0 || print_warning "ملف package-lock.json مفقود - قم بتشغيل npm install"

echo -e "\n${BLUE}5. فحص إعدادات Laravel${NC}"
echo "------------------------"

# Check if config files exist
[ -f "config/app.php" ] && print_check "ملف config/app.php موجود" 0 || print_check "ملف config/app.php مفقود" 1
[ -f "config/database.php" ] && print_check "ملف config/database.php موجود" 0 || print_check "ملف config/database.php مفقود" 1

# Check for multi-tenancy package
if grep -q "spatie/laravel-multitenancy" composer.json; then
    print_check "حزمة Multi-tenancy مثبتة" 0
else
    print_check "حزمة Multi-tenancy غير مثبتة" 1
fi

echo -e "\n${BLUE}6. فحص المجلدات المطلوبة${NC}"
echo "-------------------------"

[ -d "storage" ] && print_check "مجلد storage موجود" 0 || print_check "مجلد storage مفقود" 1
[ -d "bootstrap/cache" ] && print_check "مجلد bootstrap/cache موجود" 0 || print_check "مجلد bootstrap/cache مفقود" 1
[ -d "database/migrations" ] && print_check "مجلد database/migrations موجود" 0 || print_check "مجلد database/migrations مفقود" 1

echo -e "\n${BLUE}7. فحص Git Repository${NC}"
echo "---------------------"

if [ -d ".git" ]; then
    print_check "مستودع Git مهيأ" 0
    
    # Check if there are uncommitted changes
    if [ -z "$(git status --porcelain)" ]; then
        print_check "لا توجد تغييرات غير محفوظة" 0
    else
        print_warning "توجد تغييرات غير محفوظة في Git"
    fi
    
    # Check remote origin
    if git remote get-url origin > /dev/null 2>&1; then
        REMOTE_URL=$(git remote get-url origin)
        print_info "Remote URL: $REMOTE_URL"
        print_check "Remote origin مكون" 0
    else
        print_check "Remote origin غير مكون" 1
    fi
else
    print_check "مستودع Git غير مهيأ" 1
fi

echo -e "\n${BLUE}8. فحص إعدادات الإنتاج${NC}"
echo "----------------------"

# Check .env.production settings
if [ -f ".env.production" ]; then
    if grep -q "APP_ENV=production" .env.production; then
        print_check "APP_ENV مضبوط على production" 0
    else
        print_check "APP_ENV غير مضبوط على production" 1
    fi
    
    if grep -q "APP_DEBUG=false" .env.production; then
        print_check "APP_DEBUG مضبوط على false" 0
    else
        print_check "APP_DEBUG غير مضبوط على false" 1
    fi
else
    print_check "ملف .env.production مفقود" 1
fi

echo -e "\n${BLUE}📊 ملخص النتائج${NC}"
echo "==============="
echo -e "${GREEN}✅ نجح: $PASSED${NC}"
echo -e "${RED}❌ فشل: $FAILED${NC}"
echo -e "${YELLOW}⚠️  تحذيرات: $WARNINGS${NC}"

echo -e "\n${BLUE}📋 الخطوات التالية${NC}"
echo "=================="

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 المشروع جاهز للنشر على Cloudways!${NC}"
    echo ""
    echo "الخطوات التالية:"
    echo "1. اذهب إلى Cloudways وأنشئ خادم جديد"
    echo "2. اربط GitHub Repository"
    echo "3. اتبع الدليل في CLOUDWAYS_DEPLOYMENT_GUIDE.md"
    echo "4. قم بتشغيل deploy.sh بعد النشر الأولي"
else
    echo -e "${RED}⚠️  يجب إصلاح المشاكل المذكورة أعلاه قبل النشر${NC}"
fi

if [ $WARNINGS -gt 0 ]; then
    echo -e "${YELLOW}💡 راجع التحذيرات وأصلحها إذا لزم الأمر${NC}"
fi

echo ""
echo "للمساعدة، راجع:"
echo "- CLOUDWAYS_DEPLOYMENT_GUIDE.md"
echo "- cloudways-setup.md"
echo "- https://support.cloudways.com"
