<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\ProductCategory;

class InvoiceQRHelper
{
    /**
     * Generate QR code data for invoice
     */
    public static function generateInvoiceQRData(int $tenantId, array $options = []): array
    {
        $limit = $options['limit'] ?? 20;
        $type = $options['type'] ?? 'featured'; // featured, category, all
        $categoryId = $options['category_id'] ?? null;

        $query = Product::where('tenant_id', $tenantId)
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->with(['category']);

        // Apply filters based on type
        switch ($type) {
            case 'featured':
                $query->where('is_featured', true);
                break;
            case 'category':
                if ($categoryId) {
                    $query->where('category_id', $categoryId);
                }
                break;
            case 'all':
                // No additional filter
                break;
        }

        $products = $query->select([
                'id', 'name', 'code', 'product_code', 'selling_price', 
                'currency', 'brand', 'category_id', 'base_unit', 'unit_of_measure'
            ])
            ->orderBy('name')
            ->limit($limit)
            ->get();

        // Create simplified data for invoice QR
        return [
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
    }

    /**
     * Generate QR code JSON string for invoice
     */
    public static function generateInvoiceQRJson(int $tenantId, array $options = []): string
    {
        $data = self::generateInvoiceQRData($tenantId, $options);
        return json_encode($data);
    }

    /**
     * Generate readable text version for invoice
     */
    public static function generateInvoiceQRText(int $tenantId, array $options = []): string
    {
        $data = self::generateInvoiceQRData($tenantId, $options);
        
        $text = "كتالوج المنتجات المتوفرة\n";
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

    /**
     * Generate compact QR data for small invoices
     */
    public static function generateCompactQRData(int $tenantId, array $options = []): array
    {
        $limit = min($options['limit'] ?? 10, 10); // Max 10 for compact
        
        $products = Product::where('tenant_id', $tenantId)
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('is_active', true);
            })
            ->where('is_featured', true) // Only featured for compact
            ->select(['id', 'name', 'code', 'product_code', 'selling_price', 'currency'])
            ->orderBy('name')
            ->limit($limit)
            ->get();

        return [
            'type' => 'compact_catalog',
            'tenant_id' => $tenantId,
            'date' => now()->format('Y-m-d'),
            'count' => $products->count(),
            'items' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => substr($product->name, 0, 30), // Truncate for size
                    'code' => $product->code ?? $product->product_code,
                    'price' => (float) $product->selling_price,
                    'currency' => $product->currency ?? 'IQD',
                ];
            })->toArray()
        ];
    }

    /**
     * Get QR data size in bytes
     */
    public static function getQRDataSize(array $data): int
    {
        return strlen(json_encode($data));
    }

    /**
     * Check if QR data is suitable for printing (not too large)
     */
    public static function isQRDataSuitableForPrinting(array $data): bool
    {
        $size = self::getQRDataSize($data);
        return $size <= 2000; // 2KB limit for reliable QR scanning
    }

    /**
     * Generate QR data optimized for invoice printing
     */
    public static function generateOptimizedInvoiceQR(int $tenantId, array $options = []): array
    {
        // Start with compact version
        $data = self::generateCompactQRData($tenantId, $options);
        
        // If still too large, reduce further
        if (!self::isQRDataSuitableForPrinting($data)) {
            $data['items'] = array_slice($data['items'], 0, 5); // Reduce to 5 items
            $data['count'] = count($data['items']);
        }
        
        return $data;
    }

    /**
     * Generate URL for online product catalog
     */
    public static function generateCatalogUrl(int $tenantId): string
    {
        // This would be a public URL where customers can view the catalog
        return url("/catalog/{$tenantId}");
    }

    /**
     * Generate QR with catalog URL instead of data
     */
    public static function generateUrlQRData(int $tenantId): array
    {
        return [
            'type' => 'catalog_url',
            'tenant_id' => $tenantId,
            'url' => self::generateCatalogUrl($tenantId),
            'generated_at' => now()->format('Y-m-d H:i'),
            'message' => 'امسح الكود لعرض كتالوج المنتجات'
        ];
    }
}
