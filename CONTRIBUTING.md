# Contributing to MaxCon ERP

نرحب بمساهماتكم في تطوير MaxCon ERP! هذا الدليل سيساعدكم على فهم كيفية المساهمة في المشروع.

## 🤝 كيفية المساهمة

### 1. Fork المشروع
```bash
# انقر على زر Fork في GitHub
# ثم استنسخ المشروع المنسوخ
git clone https://github.com/your-username/maxcon-erp.git
cd maxcon-erp
```

### 2. إعداد البيئة التطويرية
```bash
# تثبيت التبعيات
composer install
npm install

# إعداد البيئة
cp .env.example .env
php artisan key:generate

# إعداد قاعدة البيانات
php artisan migrate
php artisan db:seed
```

### 3. إنشاء فرع جديد
```bash
git checkout -b feature/your-feature-name
# أو
git checkout -b fix/your-bug-fix
```

### 4. تطوير التحسينات
- اتبع معايير الكود المحددة
- أضف اختبارات للميزات الجديدة
- تأكد من أن جميع الاختبارات تمر بنجاح

### 5. إرسال Pull Request
```bash
git add .
git commit -m "Add: your feature description"
git push origin feature/your-feature-name
```

## 📋 معايير الكود

### PHP/Laravel
- اتبع PSR-12 coding standards
- استخدم Repository Pattern للـ models
- استخدم Service Layer للـ business logic
- أضف DocBlocks للـ methods والـ classes

### Frontend
- استخدم Tailwind CSS للـ styling
- اتبع BEM methodology للـ CSS classes
- استخدم Alpine.js للـ JavaScript interactions

### Database
- استخدم migrations للتغييرات في قاعدة البيانات
- أضف seeders للبيانات التجريبية
- استخدم foreign keys للعلاقات

## 🧪 الاختبارات

```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبارات محددة
php artisan test --filter=TestClassName

# تشغيل اختبارات مع coverage
php artisan test --coverage
```

## 📝 أنواع المساهمات

### 🐛 إصلاح الأخطاء
- ابحث في Issues الموجودة أولاً
- أنشئ issue جديد إذا لم يكن موجوداً
- اربط Pull Request بالـ issue

### ✨ ميزات جديدة
- ناقش الميزة في issue أولاً
- تأكد من توافقها مع رؤية المشروع
- أضف documentation للميزة الجديدة

### 📚 تحسين الوثائق
- تحسين README.md
- إضافة أو تحديث التعليقات
- كتابة أدلة المستخدم

### 🌍 الترجمة
- إضافة لغات جديدة
- تحسين الترجمات الموجودة
- تحديث ملفات اللغة

## 🔍 مراجعة الكود

### قبل إرسال Pull Request
- [ ] الكود يتبع معايير المشروع
- [ ] جميع الاختبارات تمر بنجاح
- [ ] لا توجد أخطاء في console
- [ ] الـ documentation محدث
- [ ] الـ commit messages واضحة

### معايير المراجعة
- الكود قابل للقراءة والفهم
- الأداء محسن
- الأمان مراعى
- التوافق مع المتصفحات

## 📧 التواصل

### قنوات التواصل
- **GitHub Issues**: للأخطاء والميزات الجديدة
- **GitHub Discussions**: للنقاشات العامة
- **Email**: support@maxcon.com للاستفسارات

### آداب التواصل
- كن محترماً ومهذباً
- استخدم لغة واضحة ومفهومة
- قدم تفاصيل كافية عند الإبلاغ عن مشاكل
- ساعد الآخرين في المجتمع

## 🏷️ تسمية Commits

استخدم التنسيق التالي:
```
Type: Short description

Longer description if needed

Fixes #issue-number
```

### أنواع Commits
- `Add:` إضافة ميزة جديدة
- `Fix:` إصلاح خطأ
- `Update:` تحديث ميزة موجودة
- `Remove:` حذف كود أو ميزة
- `Refactor:` إعادة هيكلة الكود
- `Docs:` تحديث الوثائق
- `Test:` إضافة أو تحديث اختبارات
- `Style:` تحسينات التصميم

## 🎯 أولويات التطوير

### عالية الأولوية
- إصلاح الأخطاء الأمنية
- تحسين الأداء
- إصلاح الأخطاء الحرجة

### متوسطة الأولوية
- ميزات جديدة مطلوبة
- تحسين تجربة المستخدم
- تحسين الوثائق

### منخفضة الأولوية
- تحسينات التصميم
- إعادة هيكلة الكود
- ميزات إضافية

## 📜 الترخيص

بمساهمتك في هذا المشروع، فإنك توافق على أن مساهماتك ستكون مرخصة تحت نفس ترخيص المشروع (MIT License).

## 🙏 شكر وتقدير

نشكر جميع المساهمين في تطوير MaxCon ERP:

- المطورين الذين يساهمون بالكود
- المختبرين الذين يبلغون عن الأخطاء
- المترجمين الذين يساعدون في الترجمة
- المجتمع الذي يقدم الدعم والمساعدة

---

**شكراً لمساهمتكم في جعل MaxCon ERP أفضل! 🚀**
