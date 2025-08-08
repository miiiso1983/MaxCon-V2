<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BasicDataSeeder extends Seeder
{
    public function run()
    {
        // Create tenant
        $tenantId = DB::table('tenants')->insertGetId([
            'name' => 'شركة ماكس كون للأدوية',
            'domain' => 'maxcon.app',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update user with tenant_id
        DB::table('users')->where('id', 1)->update([
            'tenant_id' => $tenantId,
            'updated_at' => now(),
        ]);

        // Create customers
        $customers = [
            [
                'tenant_id' => $tenantId,
                'name' => 'صيدلية الشفاء',
                'email' => 'shifa@pharmacy.com',
                'phone' => '07901234567',
                'customer_code' => 'CUST001',
                'address' => 'بغداد - الكرادة',
                'credit_limit' => 50000.00,
                'current_balance' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'صيدلية النور',
                'email' => 'noor@pharmacy.com',
                'phone' => '07907654321',
                'customer_code' => 'CUST002',
                'address' => 'بغداد - المنصور',
                'credit_limit' => 75000.00,
                'current_balance' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'صيدلية الحياة',
                'email' => 'hayat@pharmacy.com',
                'phone' => '07901111111',
                'customer_code' => 'CUST003',
                'address' => 'بغداد - الجادرية',
                'credit_limit' => 100000.00,
                'current_balance' => 0.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('customers')->insert($customers);

        // Create products
        $products = [
            [
                'tenant_id' => $tenantId,
                'name' => 'باراسيتامول 500 مجم',
                'code' => 'PARA500',
                'description' => 'مسكن للألم وخافض للحرارة',
                'unit_price' => 250.00,
                'selling_price' => 350.00,
                'current_stock' => 1000,
                'unit' => 'قرص',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'أموكسيسيلين 250 مجم',
                'code' => 'AMOX250',
                'description' => 'مضاد حيوي واسع المجال',
                'unit_price' => 500.00,
                'selling_price' => 750.00,
                'current_stock' => 500,
                'unit' => 'كبسولة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'فيتامين د 1000 وحدة',
                'code' => 'VITD1000',
                'description' => 'مكمل غذائي لفيتامين د',
                'unit_price' => 1000.00,
                'selling_price' => 1500.00,
                'current_stock' => 200,
                'unit' => 'قرص',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'أسبرين 100 مجم',
                'code' => 'ASP100',
                'description' => 'مضاد للتجلط ومسكن',
                'unit_price' => 150.00,
                'selling_price' => 250.00,
                'current_stock' => 800,
                'unit' => 'قرص',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tenant_id' => $tenantId,
                'name' => 'أوميجا 3',
                'code' => 'OMEGA3',
                'description' => 'مكمل غذائي للأحماض الدهنية',
                'unit_price' => 2000.00,
                'selling_price' => 3000.00,
                'current_stock' => 150,
                'unit' => 'كبسولة',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}
