<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = Product::where('tenant_id', $tenantId)
            ->with(['category']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low_stock':
                    $query->whereRaw('COALESCE(current_stock, stock_quantity, 0) <= COALESCE(minimum_stock, min_stock_level, 0)');
                    break;
                case 'out_of_stock':
                    $query->where(function($q) {
                        $q->where('current_stock', '<=', 0)->orWhere('stock_quantity', '<=', 0);
                    });
                    break;
                case 'in_stock':
                    $query->whereRaw('COALESCE(current_stock, stock_quantity, 0) > COALESCE(minimum_stock, min_stock_level, 0)');
                    break;
            }
        }

        $products = $query->orderBy('name')->paginate(20);

        // Get categories for filter
        $categories = ProductCategory::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = [
            'total' => Product::where('tenant_id', $tenantId)->count(),
            'active' => Product::where('tenant_id', $tenantId)->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })->count(),
            'low_stock' => Product::where('tenant_id', $tenantId)->where(function($q) {
                $q->whereRaw('COALESCE(current_stock, stock_quantity, 0) <= COALESCE(minimum_stock, min_stock_level, 0)');
            })->count(),
            'out_of_stock' => Product::where('tenant_id', $tenantId)->where(function($q) {
                $q->where('current_stock', '<=', 0)->orWhere('stock_quantity', '<=', 0);
            })->count(),
        ];

        return view('tenant.inventory.products.index', compact('products', 'categories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Get categories
        $categories = ProductCategory::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.inventory.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,discontinued,out_of_stock',
            'base_unit' => 'required|string|max:50',
        ]);

        $data = $request->all();
        $data['tenant_id'] = $tenantId;

        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = Product::generateCode($tenantId);
        }

        $product = Product::create($data);

        return redirect()->route('tenant.inventory.inventory-products.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $user = auth()->user();

        if ($product->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $product->load(['category']);

        return view('tenant.inventory.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $user = auth()->user();

        if ($product->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        // Get categories
        $categories = ProductCategory::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.inventory.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $user = auth()->user();

        if ($product->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:product_categories,id',
            'brand' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,discontinued,out_of_stock',
            'base_unit' => 'required|string|max:50',
        ]);

        $product->update($request->all());

        return redirect()->route('tenant.inventory.inventory-products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $user = auth()->user();

        if ($product->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $product->delete();

        return redirect()->route('tenant.inventory.inventory-products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }
}
