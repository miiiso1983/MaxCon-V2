<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار المنتجات</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: right; }
        th { background-color: #f2f2f2; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>اختبار عرض المنتجات</h1>
    
    @php
        try {
            // عرض جميع المنتجات للاختبار
            $products = App\Models\Product::all();
            $count = $products->count();

            // عرض أيضاً المنتجات حسب tenant_id
            $products_tenant_1 = App\Models\Product::where('tenant_id', 1)->get();
            $count_tenant_1 = $products_tenant_1->count();

            $products_tenant_null = App\Models\Product::whereNull('tenant_id')->get();
            $count_tenant_null = $products_tenant_null->count();
        } catch (Exception $e) {
            $products = collect();
            $count = 0;
            $error = $e->getMessage();
        }
    @endphp
    
    <div>
        <p><strong>إجمالي المنتجات:</strong>
            <span class="{{ $count > 0 ? 'success' : 'error' }}">{{ $count }}</span>
        </p>

        <p><strong>المنتجات مع tenant_id = 1:</strong>
            <span class="{{ $count_tenant_1 > 0 ? 'success' : 'error' }}">{{ $count_tenant_1 }}</span>
        </p>

        <p><strong>المنتجات مع tenant_id = null:</strong>
            <span class="{{ $count_tenant_null > 0 ? 'success' : 'error' }}">{{ $count_tenant_null }}</span>
        </p>

        @if(isset($error))
            <p class="error">خطأ: {{ $error }}</p>
        @endif
    </div>
    
    @if($count > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tenant ID</th>
                    <th>كود المنتج</th>
                    <th>الاسم</th>
                    <th>الفئة</th>
                    <th>الشركة المصنعة</th>
                    <th>الوحدة</th>
                    <th>سعر التكلفة</th>
                    <th>سعر البيع</th>
                    <th>المخزون</th>
                    <th>الحد الأدنى</th>
                    <th>نشط</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->tenant_id ?? 'NULL' }}</td>
                    <td>{{ $product->product_code ?? 'N/A' }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category ?? 'N/A' }}</td>
                    <td>{{ $product->manufacturer ?? 'N/A' }}</td>
                    <td>{{ $product->unit_of_measure ?? 'N/A' }}</td>
                    <td>{{ $product->cost_price ?? 'N/A' }}</td>
                    <td>{{ $product->selling_price ?? 'N/A' }}</td>
                    <td>{{ $product->stock_quantity ?? 'N/A' }}</td>
                    <td>{{ $product->min_stock_level ?? 'N/A' }}</td>
                    <td>{{ $product->is_active ? 'نعم' : 'لا' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="error">لا توجد منتجات للعرض</p>
    @endif
    
    <hr>
    <h2>اختبار استيراد المنتجات</h2>
    <p>لاختبار الاستيراد، استخدم الملف التالي:</p>
    <pre>
name,category,manufacturer,barcode,unit,purchase_price,selling_price,min_stock_level,current_stock,description,notes
إيبوبروفين 400 مجم,مسكنات,شركة الأدوية اللبنانية,123456789015,قرص,0.75,1.25,80,400,مسكن ومضاد للالتهاب,لا يستخدم مع أمراض المعدة
    </pre>
</body>
</html>
