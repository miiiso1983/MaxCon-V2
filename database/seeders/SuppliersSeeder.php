<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenantId = 1; // Default tenant ID

        $suppliers = [
            [
                'tenant_id' => $tenantId,
                'name' => 'شركة الأدوية المتحدة',
                'code' => 'SUP-001',
                'type' => 'distributor',
                'status' => 'active',
                'contact_person' => 'أحمد محمد',
                'phone' => '07901234567',
                'email' => 'supplier1@test.com',
                'address' => 'بغداد - الكرادة',
                'tax_number' => '123456789',
                'payment_terms' => 'credit_30',
                'credit_limit' => 50000,
                'currency' => 'IQD',
                'category' => 'pharmaceutical',
                'notes' => 'مورد موثوق للأدوية',
                'rating' => 4.5,
                'total_orders' => 25,
                'total_amount' => 125000,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'شركة المعدات الطبية',
                'code' => 'SUP-002',
                'type' => 'manufacturer',
                'status' => 'active',
                'contact_person' => 'سارة أحمد',
                'phone' => '07801234567',
                'email' => 'supplier2@test.com',
                'address' => 'بغداد - الجادرية',
                'tax_number' => '987654321',
                'payment_terms' => 'credit_15',
                'credit_limit' => 75000,
                'currency' => 'USD',
                'category' => 'medical_equipment',
                'notes' => 'مورد معدات طبية متخصص',
                'rating' => 4.8,
                'total_orders' => 18,
                'total_amount' => 95000,
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'شركة مستحضرات التجميل',
                'code' => 'SUP-003',
                'type' => 'wholesaler',
                'status' => 'active',
                'contact_person' => 'محمد علي',
                'phone' => '07701234567',
                'email' => 'supplier3@test.com',
                'address' => 'بغداد - المنصور',
                'tax_number' => '456789123',
                'payment_terms' => 'cash',
                'credit_limit' => 30000,
                'currency' => 'IQD',
                'category' => 'cosmetics',
                'notes' => 'مورد مستحضرات تجميل عالية الجودة',
                'rating' => 4.2,
                'total_orders' => 32,
                'total_amount' => 85000,
            ]
        ];

        foreach ($suppliers as $supplierData) {
            // Check if supplier already exists
            $existing = Supplier::where('tenant_id', $tenantId)
                ->where('code', $supplierData['code'])
                ->first();

            if (!$existing) {
                Supplier::create($supplierData);
                $this->command->info('Created supplier: ' . $supplierData['name']);
            } else {
                $this->command->info('Supplier already exists: ' . $supplierData['name']);
            }
        }
    }
}
