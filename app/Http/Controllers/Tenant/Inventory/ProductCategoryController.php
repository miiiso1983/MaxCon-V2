<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $query = ProductCategory::where('tenant_id', $tenantId)
            ->with(['parent', 'children', 'products']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by parent
        if ($request->filled('parent_id')) {
            if ($request->parent_id === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent_id);
            }
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(20);

        // Get root categories for filter
        $rootCategories = ProductCategory::where('tenant_id', $tenantId)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        // Statistics
        $stats = [
            'total' => ProductCategory::where('tenant_id', $tenantId)->count(),
            'active' => ProductCategory::where('tenant_id', $tenantId)->where('status', 'active')->count(),
            'inactive' => ProductCategory::where('tenant_id', $tenantId)->where('status', 'inactive')->count(),
            'root' => ProductCategory::where('tenant_id', $tenantId)->whereNull('parent_id')->count(),
        ];

        return view('tenant.inventory.categories.index', compact('categories', 'rootCategories', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Get parent categories
        $parentCategories = ProductCategory::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('tenant.inventory.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:product_categories,code,NULL,id,tenant_id,' . $tenantId,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['tenant_id'] = $tenantId;

        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = ProductCategory::generateCode($tenantId);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        ProductCategory::create($data);

        return redirect()->route('tenant.inventory.categories.index')
            ->with('success', 'تم إنشاء الفئة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $category): View
    {
        $user = Auth::user();

        if ($category->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $category->load(['parent', 'children.products', 'products']);

        return view('tenant.inventory.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $category): View
    {
        $user = Auth::user();

        if ($category->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        // Get parent categories (excluding self and descendants)
        $parentCategories = ProductCategory::where('tenant_id', $user->tenant_id)
            ->where('status', 'active')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('tenant.inventory.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCategory $category): RedirectResponse
    {
        $user = Auth::user();

        if ($category->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:product_categories,code,' . $category->id . ',id,tenant_id,' . $user->tenant_id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:product_categories,id',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('tenant.inventory.categories.index')
            ->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $category): RedirectResponse
    {
        $user = Auth::user();

        if ($category->tenant_id !== $user->tenant_id) {
            abort(403, 'Unauthorized access');
        }

        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->route('tenant.inventory.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على منتجات');
        }

        // Check if category has children
        if ($category->children()->exists()) {
            return redirect()->route('tenant.inventory.categories.index')
                ->with('error', 'لا يمكن حذف الفئة لأنها تحتوي على فئات فرعية');
        }

        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('tenant.inventory.categories.index')
            ->with('success', 'تم حذف الفئة بنجاح');
    }
}
