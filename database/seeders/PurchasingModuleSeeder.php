<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Supplier;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Product;

class PurchasingModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first active tenant
        $tenant = Tenant::where('is_active', true)->first();
        if (!$tenant) {
            $this->command->info('No active tenant found. Creating one...');
            $tenant = Tenant::create([
                'name' => 'شركة الأدوية المتقدمة',
                'slug' => 'advanced-pharma-' . time(),
                'plan' => 'premium',
                'max_users' => 50,
                'storage_limit' => 21474836480, // 20GB
                'is_active' => true,
                'status' => 'active',
                'trial_ends_at' => now()->addDays(30),
            ]);
        }

        // Get or create admin user
        $admin = User::where('tenant_id', $tenant->id)
                    ->whereHas('roles', function($q) {
                        $q->where('name', 'tenant-admin');
                    })->first();

        if (!$admin) {
            $admin = User::create([
                'name' => 'مدير المشتريات',
                'email' => 'purchasing@' . $tenant->slug . '.com',
                'password' => bcrypt('AdminPass123!'),
                'phone' => '+964 750 123 4567',
                'tenant_id' => $tenant->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('tenant-admin');
        }

        // Create suppliers if not exist
        $suppliers = [];
        $supplierData = [
            [
                'name' => 'شركة الأدوية العالمية',
                'code' => 'SUP-001',
                'email' => 'info@global-pharma.com',
                'phone' => '+964 1 234 5678',
                'address' => 'بغداد - الكرادة',
                'contact_person' => 'أحمد محمد',
                'type' => 'pharmaceutical',
                'status' => 'active',
            ],
            [
                'name' => 'مؤسسة الأجهزة الطبية',
                'code' => 'SUP-002',
                'email' => 'sales@medical-devices.com',
                'phone' => '+964 1 345 6789',
                'address' => 'بغداد - الجادرية',
                'contact_person' => 'فاطمة علي',
                'type' => 'medical_equipment',
                'status' => 'active',
            ],
            [
                'name' => 'شركة المستلزمات المكتبية',
                'code' => 'SUP-003',
                'email' => 'orders@office-supplies.com',
                'phone' => '+964 1 456 7890',
                'address' => 'بغداد - المنصور',
                'contact_person' => 'محمد حسن',
                'type' => 'office_supplies',
                'status' => 'active',
            ],
        ];

        foreach ($supplierData as $data) {
            $data['tenant_id'] = $tenant->id;
            $suppliers[] = Supplier::firstOrCreate(
                ['code' => $data['code'], 'tenant_id' => $tenant->id],
                $data
            );
        }

        // Create products if not exist
        $products = [];
        $productData = [
            [
                'name' => 'باراسيتامول 500 مجم',
                'code' => 'MED-001',
                'category' => 'أدوية',
                'unit' => 'علبة',
                'price' => 2500.00,
                'cost' => 2000.00,
                'status' => 'active',
            ],
            [
                'name' => 'جهاز قياس الضغط',
                'code' => 'DEV-001',
                'category' => 'أجهزة طبية',
                'unit' => 'قطعة',
                'price' => 150000.00,
                'cost' => 120000.00,
                'status' => 'active',
            ],
            [
                'name' => 'ورق A4',
                'code' => 'OFF-001',
                'category' => 'مستلزمات مكتبية',
                'unit' => 'رزمة',
                'price' => 15000.00,
                'cost' => 12000.00,
                'status' => 'active',
            ],
        ];

        foreach ($productData as $data) {
            $data['tenant_id'] = $tenant->id;
            $products[] = Product::firstOrCreate(
                ['code' => $data['code'], 'tenant_id' => $tenant->id],
                $data
            );
        }

        // Create purchase requests
        $purchaseRequests = [
            [
                'request_number' => 'PR-2025-001',
                'title' => 'طلب شراء أدوية أساسية',
                'description' => 'طلب شراء أدوية أساسية للصيدلية',
                'priority' => 'high',
                'status' => 'pending',
                'required_date' => now()->addDays(7),
                'justification' => 'نفاد المخزون من الأدوية الأساسية',
                'estimated_total' => 50000.00,
                'is_urgent' => true,
                'items' => [
                    [
                        'item_name' => 'باراسيتامول 500 مجم',
                        'quantity' => 20,
                        'estimated_price' => 2500.00,
                        'specifications' => 'علبة 20 قرص',
                        'unit' => 'علبة',
                    ],
                ],
            ],
            [
                'request_number' => 'PR-2025-002',
                'title' => 'طلب شراء أجهزة طبية',
                'description' => 'طلب شراء أجهزة طبية للعيادة',
                'priority' => 'medium',
                'status' => 'draft',
                'required_date' => now()->addDays(14),
                'justification' => 'تحديث الأجهزة الطبية',
                'estimated_total' => 300000.00,
                'is_urgent' => false,
                'items' => [
                    [
                        'item_name' => 'جهاز قياس الضغط',
                        'quantity' => 2,
                        'estimated_price' => 150000.00,
                        'specifications' => 'جهاز رقمي عالي الدقة',
                        'unit' => 'قطعة',
                    ],
                ],
            ],
            [
                'request_number' => 'PR-2025-003',
                'title' => 'طلب شراء مستلزمات مكتبية',
                'description' => 'طلب شراء مستلزمات مكتبية للإدارة',
                'priority' => 'low',
                'status' => 'approved',
                'required_date' => now()->addDays(10),
                'justification' => 'تجديد المستلزمات المكتبية',
                'estimated_total' => 75000.00,
                'approved_budget' => 80000.00,
                'approved_by' => $admin->id,
                'approved_at' => now()->subDays(1),
                'approval_notes' => 'معتمد مع زيادة الميزانية',
                'is_urgent' => false,
                'items' => [
                    [
                        'item_name' => 'ورق A4',
                        'quantity' => 5,
                        'estimated_price' => 15000.00,
                        'specifications' => 'ورق أبيض عالي الجودة',
                        'unit' => 'رزمة',
                    ],
                ],
            ],
        ];

        foreach ($purchaseRequests as $requestData) {
            $items = $requestData['items'];
            unset($requestData['items']);
            
            $requestData['tenant_id'] = $tenant->id;
            $requestData['requested_by'] = $admin->id;

            $purchaseRequest = PurchaseRequest::firstOrCreate(
                ['request_number' => $requestData['request_number'], 'tenant_id' => $tenant->id],
                $requestData
            );

            // Create items
            foreach ($items as $itemData) {
                $itemData['total_estimated'] = $itemData['quantity'] * $itemData['estimated_price'];
                
                PurchaseRequestItem::firstOrCreate(
                    [
                        'purchase_request_id' => $purchaseRequest->id,
                        'item_name' => $itemData['item_name']
                    ],
                    $itemData
                );
            }
        }

        $this->command->info('✅ تم إنشاء بيانات وحدة المشتريات بنجاح!');
        $this->command->info("🏢 المؤسسة: {$tenant->name}");
        $this->command->info("👤 المدير: {$admin->name} ({$admin->email})");
        $this->command->info("🏭 الموردين: " . count($suppliers));
        $this->command->info("📦 المنتجات: " . count($products));
        $this->command->info("📋 طلبات الشراء: " . count($purchaseRequests));
    }
}
