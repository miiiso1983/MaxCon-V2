# دليل القوائم المنسدلة القابلة للبحث - MaxCon ERP

## نظرة عامة

تم تحويل جميع القوائم المنسدلة في نظام MaxCon ERP إلى قوائم منسدلة قابلة للبحث (Searchable Dropdowns) لتحسين تجربة المستخدم وسهولة الاستخدام.

## الميزات الرئيسية

### 🔍 البحث الذكي
- بحث فوري أثناء الكتابة
- دعم البحث باللغة العربية والإنجليزية
- إظهار النتائج المطابقة فقط

### 🎨 التصميم المحسن
- تصميم موحد عبر جميع الصفحات
- دعم الوضع المظلم
- تأثيرات بصرية سلسة
- تصميم متجاوب للهواتف المحمولة

### ⚡ الأداء المحسن
- تحميل سريع للخيارات
- ذاكرة تخزين مؤقت للبيانات
- تحسين للقوائم الكبيرة

## كيفية الاستخدام

### للمطورين

#### 1. إضافة قائمة منسدلة جديدة

```html
<!-- قائمة منسدلة أساسية -->
<select data-custom-select data-placeholder="اختر خياراً...">
    <option value="">اختر خياراً</option>
    <option value="1">خيار 1</option>
    <option value="2">خيار 2</option>
</select>

<!-- قائمة منسدلة قابلة للبحث -->
<select data-custom-select 
        data-placeholder="اختر المنتج..." 
        data-searchable="true"
        data-search-placeholder="ابحث عن المنتج...">
    <option value="">اختر المنتج</option>
    <option value="1">منتج 1</option>
    <option value="2">منتج 2</option>
</select>

<!-- قائمة منسدلة متعددة الاختيار -->
<select data-custom-select 
        data-placeholder="اختر المنتجات..." 
        data-searchable="true"
        multiple>
    <option value="1">منتج 1</option>
    <option value="2">منتج 2</option>
</select>
```

#### 2. الخصائص المتاحة

| الخاصية | الوصف | القيم المتاحة |
|---------|--------|---------------|
| `data-custom-select` | تفعيل النظام | مطلوب |
| `data-placeholder` | النص التوضيحي | أي نص |
| `data-searchable` | تفعيل البحث | `true`, `false` |
| `data-search-placeholder` | نص البحث | أي نص |
| `data-clearable` | إمكانية المسح | `true`, `false` |
| `data-no-results-text` | نص عدم وجود نتائج | أي نص |

#### 3. التهيئة البرمجية

```javascript
// تهيئة قائمة واحدة
const select = document.querySelector('#mySelect');
new CustomSelect(select, {
    searchable: true,
    placeholder: 'اختر خياراً...',
    clearable: true
});

// تهيئة جميع القوائم في حاوية
window.initCustomSelects(document.querySelector('.my-container'));

// إعادة تهيئة بعد تحديث المحتوى
window.reinitializeSelects();
```

### للمستخدمين

#### 1. البحث في القوائم
- اكتب في مربع البحث للعثور على الخيار المطلوب
- استخدم الأسهم للتنقل بين النتائج
- اضغط Enter لاختيار الخيار المحدد

#### 2. الاختيار المتعدد
- اختر عدة خيارات من نفس القائمة
- استخدم زر "اختيار الكل" لاختيار جميع الخيارات
- استخدم زر "مسح الكل" لإلغاء جميع الاختيارات

#### 3. اختصارات لوحة المفاتيح
- `↑` `↓` : التنقل بين الخيارات
- `Enter` : اختيار الخيار المحدد
- `Escape` : إغلاق القائمة
- `Backspace` : حذف آخر اختيار (في الاختيار المتعدد)

## التكوين المتقدم

### 1. تكوين خاص بالصفحة

```javascript
// تكوين مخصص لصفحات المخزون
const inventoryConfig = {
    searchThreshold: 3,
    defaultSearchable: true,
    clearable: true
};

// تطبيق التكوين
window.DropdownInitializer.init(inventoryConfig);
```

### 2. تخصيص التصميم

```css
/* تخصيص ألوان القائمة */
.custom-select-wrapper {
    --primary-color: #3b82f6;
    --border-color: #e2e8f0;
    --hover-color: #f8fafc;
}

/* تخصيص حجم القائمة */
.custom-select.large {
    min-height: 55px;
    font-size: 16px;
}
```

### 3. معالجة الأحداث

```javascript
// الاستماع لتغيير القيمة
document.querySelector('#mySelect').addEventListener('change', function(e) {
    console.log('تم اختيار:', e.target.value);
});

// الاستماع لفتح القائمة
document.querySelector('#mySelect').addEventListener('dropdown:open', function() {
    console.log('تم فتح القائمة');
});
```

## استكشاف الأخطاء

### المشاكل الشائعة

1. **القائمة لا تظهر بالتصميم الجديد**
   - تأكد من وجود `data-custom-select`
   - تحقق من تحميل ملفات CSS و JavaScript

2. **البحث لا يعمل**
   - تأكد من `data-searchable="true"`
   - تحقق من وجود خيارات كافية (أكثر من 5)

3. **مشاكل في الصفحات الديناميكية**
   - استخدم `window.reinitializeSelects()` بعد تحديث المحتوى
   - تأكد من تحميل `dropdown-initializer.js`

### رسائل وحدة التحكم

```javascript
// تفعيل وضع التطوير للمزيد من المعلومات
window.CustomSelectDebug = true;
```

## الدعم والمساعدة

للحصول على المساعدة أو الإبلاغ عن مشاكل:
- راجع وحدة التحكم في المتصفح للأخطاء
- تأكد من تحديث المتصفح
- تحقق من تحميل جميع الملفات المطلوبة

## التحديثات المستقبلية

- دعم البحث المتقدم بالفلاتر
- إضافة المزيد من التأثيرات البصرية
- تحسين الأداء للقوائم الكبيرة جداً
- دعم التجميع والتصنيف
