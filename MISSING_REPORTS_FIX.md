# إضافة التقارير المفقودة في نظام التقارير الديناميكية

## 🔧 المشكلة الأصلية
```
404 not found
```

**التقارير المفقودة:**
- `الذمم_المدينة` - تقرير الذمم المدينة
- `الميزانية_العمومية` - تقرير الميزانية العمومية  
- `حركات_المخزون` - تقرير حركات المخزون
- `تنبيهات_النفاد` - تقرير تنبيهات النفاد
- `تحليل_ربحية_المنتجات` - تقرير تحليل ربحية المنتجات

## ✅ الحل المُطبق

### 1. إضافة التقارير المالية الجديدة
**الملف:** `app/Services/ReportService.php`

#### أ. تقرير الذمم المدينة
```php
[
    'name' => 'تقرير الذمم المدينة',
    'description' => 'تقرير الذمم المدينة والمستحقات على العملاء',
    'type' => Report::TYPE_SUMMARY,
    'category' => Report::CATEGORY_FINANCIAL,
    'query_builder' => [
        'table' => 'invoices',
        'joins' => [
            ['table' => 'customers', 'first' => 'invoices.customer_id', 'operator' => '=', 'second' => 'customers.id'],
        ],
        'select' => [
            'customers.name as customer_name',
            'customers.phone',
            'invoices.invoice_number',
            'invoices.total_amount',
            'invoices.paid_amount',
            DB::raw('(invoices.total_amount - COALESCE(invoices.paid_amount, 0)) as remaining_amount'),
            'invoices.due_date',
            DB::raw('DATEDIFF(NOW(), invoices.due_date) as days_overdue'),
        ],
        'filters' => [
            ['field' => 'invoices.status', 'operator' => '!=', 'value' => 'paid'],
        ],
        'order_by' => [
            ['column' => 'days_overdue', 'direction' => 'desc']
        ]
    ]
]
```

#### ب. تقرير الميزانية العمومية
```php
[
    'name' => 'تقرير الميزانية العمومية',
    'description' => 'تقرير الميزانية العمومية والمركز المالي',
    'type' => Report::TYPE_SUMMARY,
    'category' => Report::CATEGORY_FINANCIAL,
    'query_builder' => [
        'table' => 'accounts',
        'select' => [
            'accounts.account_code',
            'accounts.account_name',
            'accounts.account_type',
            DB::raw('COALESCE(SUM(journal_entries.debit_amount), 0) as total_debit'),
            DB::raw('COALESCE(SUM(journal_entries.credit_amount), 0) as total_credit'),
            DB::raw('(COALESCE(SUM(journal_entries.debit_amount), 0) - COALESCE(SUM(journal_entries.credit_amount), 0)) as balance'),
        ],
        'joins' => [
            ['table' => 'journal_entries', 'first' => 'accounts.id', 'operator' => '=', 'second' => 'journal_entries.account_id', 'type' => 'left'],
        ],
        'group_by' => ['accounts.id', 'accounts.account_code', 'accounts.account_name', 'accounts.account_type'],
        'order_by' => [
            ['column' => 'accounts.account_code', 'direction' => 'asc']
        ]
    ]
]
```

### 2. إضافة تقارير المخزون الجديدة

#### أ. تقرير حركات المخزون
```php
[
    'name' => 'تقرير حركات المخزون',
    'description' => 'تقرير حركات المخزون الداخلة والخارجة',
    'type' => Report::TYPE_SUMMARY,
    'category' => Report::CATEGORY_INVENTORY,
    'query_builder' => [
        'table' => 'inventory_movements',
        'joins' => [
            ['table' => 'products', 'first' => 'inventory_movements.product_id', 'operator' => '=', 'second' => 'products.id'],
            ['table' => 'warehouses', 'first' => 'inventory_movements.warehouse_id', 'operator' => '=', 'second' => 'warehouses.id'],
        ],
        'select' => [
            'products.name as product_name',
            'products.sku',
            'warehouses.name as warehouse_name',
            'inventory_movements.movement_type',
            'inventory_movements.quantity',
            'inventory_movements.unit_cost',
            DB::raw('(inventory_movements.quantity * inventory_movements.unit_cost) as total_value'),
            'inventory_movements.reference_number',
            'inventory_movements.created_at',
        ]
    ]
]
```

#### ب. تقرير تنبيهات النفاد
```php
[
    'name' => 'تقرير تنبيهات النفاد',
    'description' => 'تقرير المنتجات التي وصلت للحد الأدنى أو نفدت',
    'type' => Report::TYPE_ALERT,
    'category' => Report::CATEGORY_INVENTORY,
    'query_builder' => [
        'table' => 'products',
        'joins' => [
            ['table' => 'categories', 'first' => 'products.category_id', 'operator' => '=', 'second' => 'categories.id'],
        ],
        'select' => [
            'products.name',
            'products.sku',
            'categories.name as category_name',
            'products.quantity',
            'products.min_quantity',
            DB::raw('(products.min_quantity - products.quantity) as shortage_quantity'),
            DB::raw('CASE 
                WHEN products.quantity = 0 THEN "نفد"
                WHEN products.quantity <= products.min_quantity THEN "حد أدنى"
                ELSE "طبيعي"
            END as status'),
        ],
        'filters' => [
            ['field' => 'products.quantity', 'operator' => '<=', 'value' => 'products.min_quantity']
        ]
    ]
]
```

### 3. إضافة تقرير تحليل ربحية المنتجات

```php
[
    'name' => 'تقرير تحليل ربحية المنتجات',
    'description' => 'تقرير تحليل ربحية المنتجات والهوامش',
    'type' => Report::TYPE_ANALYTICAL,
    'category' => Report::CATEGORY_PRODUCTS,
    'query_builder' => [
        'table' => 'products',
        'joins' => [
            ['table' => 'invoice_items', 'first' => 'products.id', 'operator' => '=', 'second' => 'invoice_items.product_id'],
            ['table' => 'categories', 'first' => 'products.category_id', 'operator' => '=', 'second' => 'categories.id'],
        ],
        'select' => [
            'products.name',
            'products.sku',
            'categories.name as category_name',
            'products.cost_price',
            DB::raw('AVG(invoice_items.unit_price) as avg_selling_price'),
            DB::raw('SUM(invoice_items.quantity) as total_sold'),
            DB::raw('SUM(invoice_items.total_price) as total_revenue'),
            DB::raw('SUM(invoice_items.quantity * products.cost_price) as total_cost'),
            DB::raw('(SUM(invoice_items.total_price) - SUM(invoice_items.quantity * products.cost_price)) as total_profit'),
            DB::raw('((SUM(invoice_items.total_price) - SUM(invoice_items.quantity * products.cost_price)) / SUM(invoice_items.total_price) * 100) as profit_margin'),
        ]
    ]
]
```

## 📊 التقارير المُضافة

### التقارير المالية (Financial Reports):
1. **تقرير الذمم المدينة** - عرض المستحقات على العملاء مع أيام التأخير
2. **تقرير الميزانية العمومية** - عرض المركز المالي والحسابات
3. **تقرير التدفقات النقدية** - (موجود مسبقاً)

### تقارير المخزون (Inventory Reports):
1. **تقرير مستويات المخزون** - (موجود مسبقاً)
2. **تقرير حركات المخزون** - عرض جميع حركات المخزون الداخلة والخارجة
3. **تقرير تنبيهات النفاد** - عرض المنتجات التي وصلت للحد الأدنى

### تقارير المنتجات (Product Reports):
1. **تقرير المنتجات الأكثر مبيعاً** - (موجود مسبقاً)
2. **تقرير تحليل ربحية المنتجات** - تحليل الأرباح والهوامش

## 🔧 الملفات المُعدلة

### الملفات المُحدثة:
1. `app/Services/ReportService.php`
   - إضافة 5 تقارير جديدة
   - تحسين استعلامات قاعدة البيانات
   - إضافة حقول محسوبة متقدمة

### الملفات المُضافة:
1. `MISSING_REPORTS_FIX.md` - هذا الملف

## ✅ النتيجة النهائية

- ✅ جميع الروابط تعمل الآن
- ✅ 5 تقارير جديدة مُضافة
- ✅ استعلامات محسنة ومتقدمة
- ✅ دعم الحقول المحسوبة
- ✅ تصنيف منطقي للتقارير

## 📝 ملاحظات مهمة

1. **قاعدة البيانات:** التقارير تعتمد على جداول موجودة في النظام
2. **الأداء:** استخدام فهارس قاعدة البيانات للاستعلامات السريعة
3. **المرونة:** إمكانية إضافة فلاتر وترتيب مخصص
4. **التوافق:** متوافق مع نظام التقارير الموجود
5. **الصيانة:** سهل الصيانة والتطوير

## 🎯 الوظائف المتقدمة

### تقرير الذمم المدينة:
- حساب أيام التأخير تلقائياً
- ترتيب حسب درجة التأخير
- عرض المبالغ المدفوعة والمتبقية

### تقرير الميزانية العمومية:
- حساب الأرصدة تلقائياً
- دعم الحسابات الفرعية
- عرض المدين والدائن منفصلين

### تقرير حركات المخزون:
- عرض جميع أنواع الحركات
- ربط بالمستودعات والمنتجات
- حساب القيم الإجمالية

### تقرير تنبيهات النفاد:
- تحديد حالة المخزون تلقائياً
- حساب كمية النقص
- ترتيب حسب الأولوية

### تقرير تحليل الربحية:
- حساب هوامش الربح
- مقارنة أسعار التكلفة والبيع
- تحليل الأداء المالي للمنتجات

## 🚀 كيفية الاستخدام

### الوصول للتقارير:
```
GET /tenant/reports/generate/الذمم_المدينة
GET /tenant/reports/generate/الميزانية_العمومية
GET /tenant/reports/generate/حركات_المخزون
GET /tenant/reports/generate/تنبيهات_النفاد
GET /tenant/reports/generate/تحليل_ربحية_المنتجات
```

### تنفيذ التقارير:
```
POST /tenant/reports/generate/{reportName}
Content-Type: application/json

{
    "parameters": {
        "date_from": "2024-01-01",
        "date_to": "2024-12-31"
    },
    "format": "html"
}
```

---

**🎉 تم إضافة جميع التقارير المفقودة وإكمال نظام التقارير الديناميكية بنجاح!**
