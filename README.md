# MaxCon - Pharmaceutical ERP System

<div align="center">

![MaxCon Logo](https://img.shields.io/badge/MaxCon-Pharmaceutical%20ERP-blue?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-10+-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**نظام إدارة موارد المؤسسات الدوائية المتكامل**

[العربية](#العربية) | [English](#english)

</div>

---

## العربية

### 📋 نظرة عامة

MaxCon هو نظام إدارة موارد المؤسسات (ERP) متخصص للشركات الدوائية، مطور باستخدام Laravel 10+ مع دعم كامل للغة العربية والسوق العراقي.

### ✨ المميزات الرئيسية

#### 🏢 إدارة متعددة المستأجرين (Multi-Tenant)
- نظام مستأجرين منفصل لكل شركة
- قواعد بيانات منعزلة لضمان الأمان
- إدارة مستقلة للمستخدمين والصلاحيات

#### 💊 إدارة المبيعات والمشتريات
- إدارة شاملة لدورة المبيعات
- نظام فواتير متقدم مع QR codes
- إدارة المرتجعات والاستبدالات
- تتبع المدفوعات والذمم المدينة

#### 📦 إدارة المخزون المتقدمة
- تتبع المخزون في الوقت الفعلي
- تنبيهات انتهاء الصلاحية والنفاد
- إدارة الباركود والـ QR codes
- تقارير حركة المخزون التفصيلية

#### 👥 إدارة الموارد البشرية
- ملفات الموظفين الشاملة
- نظام الحضور والانصراف
- إدارة الرواتب والبدلات
- تقارير الأداء والتقييم

#### 💰 النظام المحاسبي
- دليل حسابات هرمي
- دعم العملات المتعددة
- القيود اليومية التلقائية
- التقارير المالية الشاملة

#### 📊 التقارير والتحليلات
- أكثر من 50 تقرير ديناميكي
- لوحات تحكم تفاعلية
- تصدير للـ Excel و PDF
- تحليلات الذكاء الاصطناعي

#### 🌍 التوطين للسوق العراقي
- دعم كامل للغة العربية
- الدينار العراقي كعملة أساسية
- النظام الضريبي العراقي
- تكامل مع البنوك العراقية

### 🛠️ التقنيات المستخدمة

- **Backend**: Laravel 10+, PHP 8.1+
- **Database**: MySQL/MariaDB, Redis
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Real-time**: Livewire
- **Architecture**: Repository Pattern, Service Layer
- **Security**: RBAC, Multi-tenant isolation

### 📋 متطلبات النظام

- PHP 8.1 أو أحدث
- Composer
- MySQL 8.0+ أو MariaDB 10.3+
- Redis (اختياري للـ caching)
- Node.js & NPM (للـ frontend assets)

### 🚀 التثبيت المحلي

1. **استنساخ المشروع**
```bash
git clone https://github.com/miiiso1983/MaxCon-V2.git
cd MaxCon-V2
```

2. **تثبيت التبعيات**
```bash
composer install
npm install
```

3. **إعداد البيئة**
```bash
cp .env.example .env
php artisan key:generate
```

4. **إعداد قاعدة البيانات**
```bash
# إنشاء قواعد البيانات
mysql -u root -p
CREATE DATABASE maxcon_central;
CREATE DATABASE maxcon_erp;

# تحديث إعدادات قاعدة البيانات في .env
php artisan migrate
php artisan db:seed
```

5. **بناء الـ Assets**
```bash
npm run build
```

6. **تشغيل الخادم**
```bash
php artisan serve
```

### 🌐 النشر على Cloudways

للنشر على Cloudways، راجع الأدلة التفصيلية:

- **[دليل النشر الشامل](CLOUDWAYS_DEPLOYMENT_GUIDE.md)** - دليل مفصل خطوة بخطوة
- **[الإعداد السريع](cloudways-setup.md)** - خطوات سريعة للنشر
- **[سكريبت النشر التلقائي](deploy.sh)** - أتمتة عملية النشر

#### خطوات النشر السريعة:

1. **إنشاء خادم على Cloudways**
   - اختر DigitalOcean أو AWS
   - PHP 8.2، MySQL 8.0
   - حجم الخادم: 2GB RAM أو أكثر

2. **ربط GitHub Repository**
   ```
   Repository: https://github.com/miiiso1983/MaxCon-V2.git
   Branch: main
   ```

3. **إعداد قواعد البيانات**
   ```sql
   CREATE DATABASE maxcon_central;
   CREATE DATABASE maxcon_erp;
   ```

4. **تشغيل سكريبت النشر**
   ```bash
   chmod +x deploy.sh
   ./deploy.sh
   ```

5. **إعداد SSL والدومين**
   - تفعيل Let's Encrypt SSL
   - ربط اسم النطاق

### 📁 هيكل المشروع

```
maxcon-erp/
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Repositories/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   └── js/
├── routes/
└── public/
```

### 🔧 الإعدادات

#### إعداد Multi-Tenancy
```bash
php artisan tenants:migrate
php artisan tenants:seed
```

#### إعداد الصلاحيات
```bash
php artisan permissions:sync
```

### 📖 الوثائق

- [دليل المستخدم](docs/user-guide.md)
- [دليل المطور](docs/developer-guide.md)
- [API Documentation](docs/api.md)
- [دليل النشر](DEPLOYMENT_GUIDE.md)

### 🤝 المساهمة

نرحب بالمساهمات! يرجى قراءة [دليل المساهمة](CONTRIBUTING.md) قبل البدء.

### 📄 الترخيص

هذا المشروع مرخص تحت [MIT License](LICENSE).

### 📞 الدعم

- البريد الإلكتروني: support@maxcon.com
- الوثائق: [docs.maxcon.com](https://docs.maxcon.com)
- المجتمع: [community.maxcon.com](https://community.maxcon.com)

---

## English

### 📋 Overview

MaxCon is a comprehensive Enterprise Resource Planning (ERP) system specifically designed for pharmaceutical companies, built with Laravel 10+ and full Arabic language support for the Iraqi market.

### ✨ Key Features

- **Multi-Tenant Architecture**: Isolated databases for each company
- **Sales & Procurement Management**: Complete sales cycle with QR codes
- **Advanced Inventory Management**: Real-time tracking with expiry alerts
- **Human Resources Management**: Employee files, payroll, attendance
- **Accounting System**: Hierarchical chart of accounts, multi-currency
- **Dynamic Reporting**: 50+ reports with AI analytics
- **Iraqi Market Localization**: Arabic support, IQD currency, tax system

### 🛠️ Tech Stack

- Laravel 10+, PHP 8.1+, MySQL/MariaDB, Redis
- Tailwind CSS, Alpine.js, Livewire
- Repository Pattern, Service Layer, RBAC

### 🚀 Quick Start

```bash
git clone https://github.com/your-username/maxcon-erp.git
cd maxcon-erp
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate && php artisan db:seed
npm run build && php artisan serve
```


---

<div align="center">

**Made with ❤️ for the Pharmaceutical Industry**

[⭐ Star this repo](https://github.com/your-username/maxcon-erp) | [🐛 Report Bug](https://github.com/your-username/maxcon-erp/issues) | [💡 Request Feature](https://github.com/your-username/maxcon-erp/issues)

</div>
