<?php

namespace App\Http\Controllers\Tenant\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductQRController extends Controller
{
    /**
     * Generate QR code data for available products
     */
    public function generateAvailableProductsQR(Request $request): JsonResponse
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            return response()->json(['error' => 'No tenant access'], 403);
        }

        // Get available products (excluding stock information)
        $products = Product::where('tenant_id', $tenantId)
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->with(['category'])
            ->select([
                'id', 'name', 'code', 'product_code', 'barcode',
                'selling_price', 'currency', 'brand', 'manufacturer',
                'category_id', 'base_unit', 'unit_of_measure', 'description'
            ])
            ->orderBy('name')
            ->get();

        // Format products data for QR code
        $qrData = [
            'type' => 'product_catalog',
            'tenant_id' => $tenantId,
            'generated_at' => now()->toISOString(),
            'total_products' => $products->count(),
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code ?? $product->product_code,
                    'barcode' => $product->barcode,
                    'price' => (float) $product->selling_price,
                    'currency' => $product->currency ?? 'IQD',
                    'brand' => $product->brand,
                    'manufacturer' => $product->manufacturer,
                    'category' => $product->category ? $product->category->name : null,
                    'unit' => $product->base_unit ?? $product->unit_of_measure ?? 'piece',
                    'description' => $product->description ? substr($product->description, 0, 100) : null,
                ];
            })->toArray()
        ];

        return response()->json([
            'success' => true,
            'qr_data' => json_encode($qrData),
            'products_count' => $products->count(),
            'data_size' => strlen(json_encode($qrData)) . ' bytes'
        ]);
    }

    /**
     * Generate QR code data for specific category products
     */
    public function generateCategoryProductsQR(Request $request, ProductCategory $category): JsonResponse
    {
        $user = auth()->user();

        if ($category->tenant_id !== $user->tenant_id) {
            return response()->json(['error' => 'Unauthorized access'], 403);
        }

        // Get products in this category
        $products = Product::where('tenant_id', $user->tenant_id)
            ->where('category_id', $category->id)
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->select([
                'id', 'name', 'code', 'product_code', 'barcode',
                'selling_price', 'currency', 'brand', 'manufacturer',
                'base_unit', 'unit_of_measure', 'description'
            ])
            ->orderBy('name')
            ->get();

        // Format data for QR code
        $qrData = [
            'type' => 'category_products',
            'tenant_id' => $user->tenant_id,
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'code' => $category->code
            ],
            'generated_at' => now()->toISOString(),
            'total_products' => $products->count(),
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code ?? $product->product_code,
                    'barcode' => $product->barcode,
                    'price' => (float) $product->selling_price,
                    'currency' => $product->currency ?? 'IQD',
                    'brand' => $product->brand,
                    'manufacturer' => $product->manufacturer,
                    'unit' => $product->base_unit ?? $product->unit_of_measure ?? 'piece',
                    'description' => $product->description ? substr($product->description, 0, 100) : null,
                ];
            })->toArray()
        ];

        return response()->json([
            'success' => true,
            'qr_data' => json_encode($qrData),
            'products_count' => $products->count(),
            'category_name' => $category->name,
            'data_size' => strlen(json_encode($qrData)) . ' bytes'
        ]);
    }

    /**
     * Show QR code generator page
     */
    public function index(): View
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant access');
        }

        // Get categories for selection
        $categories = ProductCategory::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->withCount('products')
            ->orderBy('name')
            ->get();

        // Get products count
        $totalProducts = Product::where('tenant_id', $tenantId)
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->count();

        return view('tenant.inventory.qr-generator.index', compact('categories', 'totalProducts'));
    }

    /**
     * Generate simplified QR for invoice printing
     */
    public function generateInvoiceQR(Request $request): JsonResponse
    {
        $user = auth()->user();
        $tenantId = $user->tenant_id;

        if (!$tenantId) {
            return response()->json(['error' => 'No tenant access'], 403);
        }

        $request->validate([
            'type' => 'required|in:all,category,featured',
            'category_id' => 'nullable|exists:product_categories,id',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        $query = Product::where('tenant_id', $tenantId)
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->with(['category']);

        // Apply filters
        if ($request->type === 'category' && $request->category_id) {
            $query->where('category_id', $request->category_id);
        } elseif ($request->type === 'featured') {
            $query->where('is_featured', true);
        }

        // Apply limit
        if ($request->limit) {
            $query->limit($request->limit);
        }

        $products = $query->select([
                'id', 'name', 'code', 'product_code', 'selling_price',
                'currency', 'brand', 'category_id', 'base_unit', 'unit_of_measure'
            ])
            ->orderBy('name')
            ->get();

        // Create simplified data for invoice QR
        $qrData = [
            'type' => 'invoice_catalog',
            'tenant_id' => $tenantId,
            'generated_at' => now()->format('Y-m-d H:i'),
            'count' => $products->count(),
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code ?? $product->product_code,
                    'price' => number_format($product->selling_price, 2),
                    'currency' => $product->currency ?? 'IQD',
                    'brand' => $product->brand,
                    'category' => $product->category ? $product->category->name : null,
                    'unit' => $product->base_unit ?? $product->unit_of_measure ?? 'piece',
                ];
            })->toArray()
        ];

        return response()->json([
            'success' => true,
            'qr_data' => json_encode($qrData),
            'products_count' => $products->count(),
            'data_size' => strlen(json_encode($qrData)) . ' bytes',
            'qr_text' => $this->generateQRText($qrData)
        ]);
    }

    /**
     * Generate readable text version of QR data
     */
    private function generateQRText(array $data): string
    {
        $text = "جميع المنتجات المتوفرة\n";
        $text .= "التاريخ: " . $data['generated_at'] . "\n";
        $text .= "عدد المنتجات: " . $data['count'] . "\n\n";

        foreach ($data['products'] as $index => $product) {
            $text .= ($index + 1) . ". " . $product['name'] . "\n";
            $text .= "   الرمز: " . $product['code'] . "\n";
            $text .= "   السعر: " . $product['price'] . " " . $product['currency'] . "\n";
            if ($product['brand']) {
                $text .= "   العلامة: " . $product['brand'] . "\n";
            }
            if ($product['category']) {
                $text .= "   الفئة: " . $product['category'] . "\n";
            }
            $text .= "\n";
        }

        return $text;
    }
}
