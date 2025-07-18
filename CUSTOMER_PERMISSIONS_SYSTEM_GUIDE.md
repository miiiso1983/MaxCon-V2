# 🔐 دليل نظام صلاحيات العملاء - MaxCon ERP

## 📋 نظرة عامة

تم تطوير نظام صلاحيات شامل للعملاء يتيح لهم:
1. **إنشاء وإدارة الطلبيات** 
2. **الوصول للمعلومات المالية** (الدفعات السابقة والمديونية)

## 🏗️ البنية التقنية

### 1. النماذج (Models)

#### Customer Model
```php
// تم تحديث نموذج العميل ليدعم المصادقة
class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    
    // الصلاحيات الأساسية
    public function canPlaceOrders(): bool
    public function canViewFinancialInfo(): bool
    
    // المعلومات المالية
    public function getTotalDebtAttribute(): float
    public function getAvailableCreditAttribute(): float
    public function isOverCreditLimit(): bool
}
```

#### CustomerOrder Model
```php
// نموذج طلبيات العملاء
class CustomerOrder extends Model
{
    // حالات الطلب
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_COMPLETED = 'completed';
    
    // وظائف الإدارة
    public function canBeCancelled(): bool
    public function canBeModified(): bool
    public function approve(int $userId): bool
}
```

#### CustomerPayment Model
```php
// نموذج دفعات العملاء
class CustomerPayment extends Model
{
    // طرق الدفع
    const METHOD_CASH = 'cash';
    const METHOD_CHECK = 'check';
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    
    // إدارة الدفعات
    public function confirm(int $userId): bool
    public function cancel(string $reason = null): bool
}
```

### 2. نظام الصلاحيات

#### الصلاحيات المتاحة:
```php
// صلاحيات الطلبيات
'place_orders' => 'إنشاء طلبيات'
'view_own_orders' => 'عرض الطلبيات الخاصة'
'cancel_orders' => 'إلغاء الطلبيات'
'modify_orders' => 'تعديل الطلبيات'

// صلاحيات المعلومات المالية
'view_financial_info' => 'عرض المعلومات المالية'
'view_payment_history' => 'عرض تاريخ الدفعات'
'view_debt_details' => 'عرض تفاصيل المديونية'
'view_credit_limit' => 'عرض الحد الائتماني'

// صلاحيات الفواتير
'view_own_invoices' => 'عرض الفواتير الخاصة'
'download_invoices' => 'تحميل الفواتير'
```

#### الأدوار المحددة مسبقاً:
```php
// عميل أساسي
'basic_customer' => [
    'view_own_orders',
    'view_profile',
    'edit_profile',
    'view_own_invoices'
]

// عميل مميز
'premium_customer' => [
    'place_orders',
    'view_own_orders',
    'cancel_orders',
    'modify_orders',
    'view_financial_info',
    'view_payment_history',
    'view_debt_details',
    'view_credit_limit',
    'view_own_invoices',
    'download_invoices'
]

// عميل VIP
'vip_customer' => [
    // جميع الصلاحيات المتاحة
]
```

### 3. Controllers

#### Customer\OrderController
```php
// إدارة طلبيات العملاء
public function index()     // عرض الطلبيات
public function create()    // إنشاء طلب جديد
public function store()     // حفظ الطلب
public function show()      // عرض تفاصيل الطلب
public function edit()      // تعديل الطلب
public function update()    // تحديث الطلب
public function cancel()    // إلغاء الطلب
```

#### Customer\FinancialController
```php
// إدارة المعلومات المالية
public function index()         // لوحة المعلومات المالية
public function payments()      // تاريخ الدفعات
public function debt()          // تفاصيل المديونية
public function creditLimit()   // الحد الائتماني
public function invoices()      // الفواتير
public function downloadInvoice() // تحميل الفاتورة
```

#### Customer\AuthController
```php
// مصادقة العملاء
public function showLoginForm()
public function login()
public function logout()
public function showRegistrationForm()
public function register()
```

### 4. الروتات (Routes)

#### روتات المصادقة:
```php
Route::prefix('customer')->name('customer.')->group(function () {
    // Guest routes
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm']);
    Route::post('/register', [AuthController::class, 'register']);
});
```

#### روتات محمية بالصلاحيات:
```php
// الطلبيات
Route::middleware('permission:place_orders,customer')->group(function () {
    Route::resource('orders', OrderController::class);
});

// المعلومات المالية
Route::middleware('permission:view_financial_info,customer')->group(function () {
    Route::get('/financial', [FinancialController::class, 'index']);
});
```

## 🚀 كيفية الاستخدام

### 1. إعداد النظام

#### تشغيل Migrations:
```bash
php artisan migrate
```

#### تشغيل Seeders:
```bash
php artisan db:seed --class=CustomerPermissionsSeeder
```

### 2. إنشاء عميل جديد

```php
// إنشاء عميل
$customer = Customer::create([
    'name' => 'اسم العميل',
    'email' => 'customer@example.com',
    'password' => Hash::make('password'),
    'tenant_id' => 1,
    'is_active' => true,
]);

// تعيين دور
$customer->assignRole('premium_customer');

// أو تعيين صلاحيات محددة
$customer->givePermissionTo(['place_orders', 'view_financial_info']);
```

### 3. فحص الصلاحيات

```php
// في Controller
if (!$customer->canPlaceOrders()) {
    abort(403, 'ليس لديك صلاحية لإنشاء طلبيات');
}

// في Blade
@can('place_orders', 'customer')
    <a href="{{ route('customer.orders.create') }}">إنشاء طلب جديد</a>
@endcan
```

### 4. إدارة الطلبيات

```php
// إنشاء طلب جديد
$order = CustomerOrder::create([
    'customer_id' => $customer->id,
    'tenant_id' => $customer->tenant_id,
    'status' => 'pending',
    'total_amount' => 1000,
]);

// إضافة عناصر للطلب
CustomerOrderItem::create([
    'customer_order_id' => $order->id,
    'product_id' => $product->id,
    'quantity' => 5,
    'unit_price' => 200,
]);
```

### 5. إدارة المعلومات المالية

```php
// عرض المعلومات المالية
$financialData = [
    'total_debt' => $customer->total_debt,
    'available_credit' => $customer->available_credit,
    'recent_payments' => $customer->getRecentPayments(10),
];

// تسجيل دفعة جديدة
$payment = CustomerPayment::create([
    'customer_id' => $customer->id,
    'amount' => 500,
    'payment_method' => 'cash',
    'status' => 'confirmed',
]);
```

## 🔧 الإعدادات المطلوبة

### 1. إعداد Guards في config/auth.php:
```php
'guards' => [
    'customer' => [
        'driver' => 'session',
        'provider' => 'customers',
    ],
],

'providers' => [
    'customers' => [
        'driver' => 'eloquent',
        'model' => App\Models\Customer::class,
    ],
],
```

### 2. إعداد Middleware:
```php
// في app/Http/Kernel.php
protected $routeMiddleware = [
    'auth.customer' => \App\Http\Middleware\CustomerAuth::class,
];
```

## 📊 قاعدة البيانات

### الجداول المطلوبة:
1. **customers** - معلومات العملاء
2. **customer_orders** - طلبيات العملاء
3. **customer_order_items** - عناصر الطلبيات
4. **customer_payments** - دفعات العملاء
5. **permissions** - الصلاحيات
6. **roles** - الأدوار
7. **model_has_permissions** - ربط النماذج بالصلاحيات
8. **model_has_roles** - ربط النماذج بالأدوار

### العلاقات:
```
Customer
├── CustomerOrders (hasMany)
├── CustomerPayments (hasMany)
├── Invoices (hasMany)
└── Roles/Permissions (morphMany)

CustomerOrder
├── Customer (belongsTo)
├── CustomerOrderItems (hasMany)
└── Tenant (belongsTo)

CustomerPayment
├── Customer (belongsTo)
├── Invoice (belongsTo)
└── Tenant (belongsTo)
```

## 🎯 الميزات الرئيسية

### 1. نظام الطلبيات:
- ✅ إنشاء طلبيات متعددة المنتجات
- ✅ تتبع حالة الطلب
- ✅ إمكانية التعديل والإلغاء
- ✅ حساب المجاميع تلقائياً
- ✅ نظام الأولويات

### 2. المعلومات المالية:
- ✅ عرض الرصيد الحالي
- ✅ تاريخ الدفعات السابقة
- ✅ تفاصيل المديونية
- ✅ الحد الائتماني المتاح
- ✅ تحليل الأعمار للديون

### 3. الأمان والصلاحيات:
- ✅ مصادقة منفصلة للعملاء
- ✅ نظام صلاحيات مرن
- ✅ أدوار محددة مسبقاً
- ✅ حماية الروتات
- ✅ تتبع نشاط العملاء

## 🔗 الروابط المهمة

### للعملاء:
- **تسجيل الدخول:** `/customer/login`
- **لوحة التحكم:** `/customer/dashboard`
- **الطلبيات:** `/customer/orders`
- **المعلومات المالية:** `/customer/financial`

### للإدارة:
- **إدارة العملاء:** `/admin/customers`
- **صلاحيات العملاء:** `/admin/customers/{id}/permissions`
- **المعلومات المالية:** `/admin/customers/{id}/financial`

## 📝 ملاحظات مهمة

1. **الأمان:** جميع الروتات محمية بالصلاحيات المناسبة
2. **المرونة:** يمكن تخصيص الصلاحيات لكل عميل
3. **التتبع:** جميع العمليات مسجلة ومؤرخة
4. **التوافق:** متوافق مع نظام Multi-tenancy
5. **الأداء:** استخدام Indexes لتحسين الأداء

## 🎉 النتيجة النهائية

تم إنشاء نظام شامل لصلاحيات العملاء يتيح:

### ✅ للعملاء:
- تسجيل دخول منفصل وآمن
- إنشاء وإدارة الطلبيات
- عرض المعلومات المالية الشخصية
- تتبع الدفعات والمديونية
- تحميل الفواتير

### ✅ للإدارة:
- إدارة صلاحيات العملاء
- تتبع نشاط العملاء
- إدارة الطلبيات والموافقات
- إدارة المعلومات المالية

النظام جاهز للاستخدام ويمكن تطويره وتخصيصه حسب الحاجة! 🚀
