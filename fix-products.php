<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "Fixing products...\n";
echo "Database: " . config('database.default') . "\n";

// تحديث جميع المنتجات لتكون مع tenant_id = 1
$products = Product::whereNull('tenant_id')->get();
echo "Found " . $products->count() . " products with null tenant_id\n";

foreach ($products as $product) {
    $product->tenant_id = 1;
    $product->save();
    echo "Updated product ID: " . $product->id . " - " . $product->name . "\n";
}

// إنشاء tenant إذا لم يكن موجود
try {
    $tenant = DB::table('tenants')->where('id', 1)->first();
    if (!$tenant) {
        DB::table('tenants')->insert([
            'id' => 1,
            'name' => 'شركة تجريبية',
            'slug' => 'test-company',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Created tenant with ID 1\n";
    } else {
        echo "Tenant with ID 1 already exists\n";
    }
} catch (Exception $e) {
    echo "Error creating tenant: " . $e->getMessage() . "\n";
}

// إنشاء منتجات تجريبية إضافية
$testProducts = [
    [
        'tenant_id' => 1,
        'product_code' => 'PRD000002',
        'name' => 'أموكسيسيلين 250 مجم',
        'description' => 'مضاد حيوي واسع المجال',
        'category' => 'مضادات حيوية',
        'manufacturer' => 'شركة الأدوية الأردنية',
        'barcode' => '123456789013',
        'unit_of_measure' => 'كبسولة',
        'cost_price' => 1.20,
        'selling_price' => 2.50,
        'min_stock_level' => 50,
        'stock_quantity' => 200,
        'is_active' => true
    ],
    [
        'tenant_id' => 1,
        'product_code' => 'PRD000003',
        'name' => 'فيتامين سي 1000 مجم',
        'description' => 'مكمل غذائي لتقوية المناعة',
        'category' => 'فيتامينات',
        'manufacturer' => 'شركة الأدوية السعودية',
        'barcode' => '123456789014',
        'unit_of_measure' => 'قرص',
        'cost_price' => 0.80,
        'selling_price' => 1.50,
        'min_stock_level' => 75,
        'stock_quantity' => 300,
        'is_active' => true
    ]
];

foreach ($testProducts as $productData) {
    // تحقق من عدم وجود المنتج مسبقاً
    $existing = Product::where('product_code', $productData['product_code'])->first();
    if (!$existing) {
        Product::create($productData);
        echo "Created product: " . $productData['name'] . "\n";
    } else {
        echo "Product already exists: " . $productData['name'] . "\n";
    }
}

echo "\nFinal count: " . Product::where('tenant_id', 1)->count() . " products\n";
echo "Done!\n";
