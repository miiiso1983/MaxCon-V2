<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار بسيط</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>اختبار بسيط للمنتجات</h1>
    
    <h2>1. معلومات قاعدة البيانات</h2>
    <p><strong>نوع قاعدة البيانات:</strong> {{ config('database.default') }}</p>
    <p><strong>مسار قاعدة البيانات:</strong> {{ config('database.connections.sqlite.database') }}</p>
    <p><strong>هل الملف موجود:</strong> {{ file_exists(config('database.connections.sqlite.database')) ? 'نعم' : 'لا' }}</p>
    
    <h2>2. اختبار الاتصال</h2>
    @php
        try {
            $connection = DB::connection();
            $pdo = $connection->getPdo();
            $connectionStatus = 'متصل بنجاح';
        } catch (Exception $e) {
            $connectionStatus = 'خطأ في الاتصال: ' . $e->getMessage();
        }
    @endphp
    <p><strong>حالة الاتصال:</strong> <span class="{{ strpos($connectionStatus, 'نجاح') !== false ? 'success' : 'error' }}">{{ $connectionStatus }}</span></p>
    
    <h2>3. اختبار الجداول</h2>
    @php
        try {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
            $tableNames = array_column($tables, 'name');
            $hasProducts = in_array('products', $tableNames);
        } catch (Exception $e) {
            $tableNames = [];
            $hasProducts = false;
            $tableError = $e->getMessage();
        }
    @endphp
    
    <p><strong>جدول المنتجات موجود:</strong> <span class="{{ $hasProducts ? 'success' : 'error' }}">{{ $hasProducts ? 'نعم' : 'لا' }}</span></p>
    
    @if(isset($tableError))
        <p class="error">خطأ في قراءة الجداول: {{ $tableError }}</p>
    @endif
    
    <h2>4. اختبار المنتجات</h2>
    @php
        try {
            $productCount = DB::table('products')->count();
            $products = DB::table('products')->limit(5)->get();
        } catch (Exception $e) {
            $productCount = 0;
            $products = collect();
            $productError = $e->getMessage();
        }
    @endphp
    
    <p><strong>عدد المنتجات:</strong> <span class="{{ $productCount > 0 ? 'success' : 'error' }}">{{ $productCount }}</span></p>
    
    @if(isset($productError))
        <p class="error">خطأ في قراءة المنتجات: {{ $productError }}</p>
    @endif
    
    @if($productCount > 0)
        <h3>أول 5 منتجات:</h3>
        <pre>{{ json_encode($products->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    @endif
    
    <h2>5. اختبار Eloquent</h2>
    @php
        try {
            $eloquentCount = App\Models\Product::count();
            $eloquentProducts = App\Models\Product::limit(3)->get(['id', 'name', 'tenant_id']);
        } catch (Exception $e) {
            $eloquentCount = 0;
            $eloquentProducts = collect();
            $eloquentError = $e->getMessage();
        }
    @endphp
    
    <p><strong>عدد المنتجات (Eloquent):</strong> <span class="{{ $eloquentCount > 0 ? 'success' : 'error' }}">{{ $eloquentCount }}</span></p>
    
    @if(isset($eloquentError))
        <p class="error">خطأ في Eloquent: {{ $eloquentError }}</p>
    @endif
    
    @if($eloquentCount > 0)
        <h3>أول 3 منتجات (Eloquent):</h3>
        <pre>{{ json_encode($eloquentProducts->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    @endif
    
    <hr>
    <p><a href="/test-products">الذهاب لصفحة الاختبار المفصلة</a></p>
    <p><a href="/tenant/sales/products">الذهاب لصفحة إدارة المنتجات</a></p>
</body>
</html>
