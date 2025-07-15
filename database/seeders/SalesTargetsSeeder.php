<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesTarget;
use App\Models\SalesTargetProgress;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesTargetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample data
        $users = User::limit(10)->get();
        $products = Product::limit(5)->get();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }
        
        $tenantId = $users->first()->tenant_id ?? 'tenant_1';
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        // Sample sales targets
        $targets = [
            // Monthly Product Targets
            [
                'tenant_id' => $tenantId,
                'title' => 'هدف مبيعات أدوية القلب - ' . Carbon::now()->format('F Y'),
                'description' => 'هدف شهري لزيادة مبيعات أدوية القلب والأوعية الدموية',
                'target_type' => 'product',
                'target_entity_id' => $products->first()?->id ?? 1,
                'target_entity_name' => $products->first()?->name ?? 'أدوية القلب',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'year' => $currentYear,
                'month' => $currentMonth,
                'measurement_type' => 'both',
                'target_quantity' => 1000,
                'target_value' => 50000000,
                'currency' => 'IQD',
                'unit' => 'علبة',
                'achieved_quantity' => 750,
                'achieved_value' => 37500000,
                'progress_percentage' => 75,
                'status' => 'active',
                'created_by' => $users->first()->id,
                'last_updated_at' => Carbon::now()->subDays(2)
            ],
            
            // Quarterly Product Target
            [
                'tenant_id' => $tenantId,
                'title' => 'هدف مبيعات المنتجات الطبية - Q' . Carbon::now()->quarter . ' ' . $currentYear,
                'description' => 'هدف فصلي لزيادة مبيعات المنتجات الطبية',
                'target_type' => 'product',
                'target_entity_id' => $products->skip(1)->first()?->id ?? 2,
                'target_entity_name' => $products->skip(1)->first()?->name ?? 'المنتجات الطبية',
                'period_type' => 'quarterly',
                'start_date' => Carbon::now()->startOfQuarter(),
                'end_date' => Carbon::now()->endOfQuarter(),
                'year' => $currentYear,
                'quarter' => Carbon::now()->quarter,
                'measurement_type' => 'value',
                'target_value' => 150000000,
                'currency' => 'IQD',
                'achieved_value' => 120000000,
                'progress_percentage' => 80,
                'status' => 'active',
                'created_by' => $users->first()->id,
                'last_updated_at' => Carbon::now()->subDays(1)
            ],
            
            // Sales Rep Target
            [
                'tenant_id' => $tenantId,
                'title' => 'هدف مندوب المبيعات أحمد محمد - ' . Carbon::now()->format('F Y'),
                'description' => 'هدف شهري لمندوب المبيعات أحمد محمد',
                'target_type' => 'sales_rep',
                'target_entity_id' => $users->skip(1)->first()?->id ?? 2,
                'target_entity_name' => $users->skip(1)->first()?->name ?? 'أحمد محمد',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'year' => $currentYear,
                'month' => $currentMonth,
                'measurement_type' => 'value',
                'target_value' => 25000000,
                'currency' => 'IQD',
                'achieved_value' => 22000000,
                'progress_percentage' => 88,
                'status' => 'active',
                'created_by' => $users->first()->id,
                'last_updated_at' => Carbon::now()->subHours(6),
                'notification_80_sent' => true
            ],
            
            // Sales Team Target
            [
                'tenant_id' => $tenantId,
                'title' => 'هدف فريق المبيعات الأول - ' . Carbon::now()->format('F Y'),
                'description' => 'هدف شهري لفريق المبيعات الأول',
                'target_type' => 'sales_team',
                'target_entity_id' => 1,
                'target_entity_name' => 'فريق المبيعات الأول',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->startOfMonth(),
                'end_date' => Carbon::now()->endOfMonth(),
                'year' => $currentYear,
                'month' => $currentMonth,
                'measurement_type' => 'both',
                'target_quantity' => 2500,
                'target_value' => 75000000,
                'currency' => 'IQD',
                'unit' => 'منتج',
                'achieved_quantity' => 2750,
                'achieved_value' => 82500000,
                'progress_percentage' => 110,
                'status' => 'completed',
                'created_by' => $users->first()->id,
                'last_updated_at' => Carbon::now()->subDays(3),
                'notification_80_sent' => true,
                'notification_100_sent' => true
            ],
            
            // Department Target
            [
                'tenant_id' => $tenantId,
                'title' => 'هدف قسم المبيعات - ' . $currentYear,
                'description' => 'هدف سنوي لقسم المبيعات',
                'target_type' => 'department',
                'target_entity_id' => 1,
                'target_entity_name' => 'قسم المبيعات',
                'period_type' => 'yearly',
                'start_date' => Carbon::create($currentYear, 1, 1),
                'end_date' => Carbon::create($currentYear, 12, 31),
                'year' => $currentYear,
                'measurement_type' => 'value',
                'target_value' => 500000000,
                'currency' => 'IQD',
                'achieved_value' => 320000000,
                'progress_percentage' => 64,
                'status' => 'active',
                'created_by' => $users->first()->id,
                'last_updated_at' => Carbon::now()->subWeek()
            ],
            
            // Completed Target from Previous Month
            [
                'tenant_id' => $tenantId,
                'title' => 'هدف مبيعات المضادات الحيوية - ' . Carbon::now()->subMonth()->format('F Y'),
                'description' => 'هدف شهري مكتمل للمضادات الحيوية',
                'target_type' => 'product',
                'target_entity_id' => $products->skip(1)->first()?->id ?? 2,
                'target_entity_name' => $products->skip(1)->first()?->name ?? 'المضادات الحيوية',
                'period_type' => 'monthly',
                'start_date' => Carbon::now()->subMonth()->startOfMonth(),
                'end_date' => Carbon::now()->subMonth()->endOfMonth(),
                'year' => Carbon::now()->subMonth()->year,
                'month' => Carbon::now()->subMonth()->month,
                'measurement_type' => 'quantity',
                'target_quantity' => 800,
                'unit' => 'علبة',
                'achieved_quantity' => 850,
                'progress_percentage' => 106.25,
                'status' => 'completed',
                'created_by' => $users->first()->id,
                'last_updated_at' => Carbon::now()->subMonth()->endOfMonth(),
                'notification_80_sent' => true,
                'notification_100_sent' => true
            ]
        ];
        
        // Insert targets
        foreach ($targets as $targetData) {
            $target = SalesTarget::create($targetData);
            
            // Create progress records for active targets
            if ($target->status === 'active' || $target->status === 'completed') {
                $this->createProgressRecords($target);
            }
        }
        
        $this->command->info('Sales targets seeded successfully!');
    }
    
    /**
     * Create sample progress records for a target
     */
    private function createProgressRecords(SalesTarget $target): void
    {
        $startDate = $target->start_date;
        $endDate = min($target->end_date, Carbon::now());
        $currentDate = $startDate->copy();
        
        $totalDays = $startDate->diffInDays($target->end_date) + 1;
        $daysPassed = $startDate->diffInDays($endDate) + 1;
        
        $cumulativeQuantity = 0;
        $cumulativeValue = 0;
        
        while ($currentDate <= $endDate) {
            // Skip weekends for more realistic data
            if ($currentDate->isWeekend()) {
                $currentDate->addDay();
                continue;
            }
            
            // Calculate daily progress (with some randomness)
            $progressFactor = $currentDate->diffInDays($startDate) / $totalDays;
            $randomFactor = rand(50, 150) / 100; // 0.5 to 1.5
            
            $dailyQuantity = 0;
            $dailyValue = 0;
            
            if ($target->measurement_type === 'quantity' || $target->measurement_type === 'both') {
                $expectedDailyQuantity = ($target->target_quantity / $totalDays) * $randomFactor;
                $dailyQuantity = round($expectedDailyQuantity, 2);
                $cumulativeQuantity += $dailyQuantity;
            }
            
            if ($target->measurement_type === 'value' || $target->measurement_type === 'both') {
                $expectedDailyValue = ($target->target_value / $totalDays) * $randomFactor;
                $dailyValue = round($expectedDailyValue, 2);
                $cumulativeValue += $dailyValue;
            }
            
            // Calculate progress percentage
            $progressPercentage = 0;
            if ($target->measurement_type === 'quantity' && $target->target_quantity > 0) {
                $progressPercentage = ($cumulativeQuantity / $target->target_quantity) * 100;
            } elseif ($target->measurement_type === 'value' && $target->target_value > 0) {
                $progressPercentage = ($cumulativeValue / $target->target_value) * 100;
            } elseif ($target->measurement_type === 'both') {
                $quantityProgress = $target->target_quantity > 0 ? 
                    ($cumulativeQuantity / $target->target_quantity) * 100 : 0;
                $valueProgress = $target->target_value > 0 ? 
                    ($cumulativeValue / $target->target_value) * 100 : 0;
                $progressPercentage = ($quantityProgress + $valueProgress) / 2;
            }
            
            SalesTargetProgress::create([
                'tenant_id' => $target->tenant_id,
                'sales_target_id' => $target->id,
                'progress_date' => $currentDate->copy(),
                'daily_quantity' => $dailyQuantity,
                'daily_value' => $dailyValue,
                'cumulative_quantity' => $cumulativeQuantity,
                'cumulative_value' => $cumulativeValue,
                'progress_percentage' => round($progressPercentage, 2),
                'source_type' => 'seeder',
                'source_details' => [
                    'generated_by' => 'seeder',
                    'random_factor' => $randomFactor
                ],
                'updated_by' => $target->created_by
            ]);
            
            $currentDate->addDay();
        }
        
        // Update target with final values
        $target->update([
            'achieved_quantity' => $cumulativeQuantity,
            'achieved_value' => $cumulativeValue,
            'progress_percentage' => min(100, round($progressPercentage ?? 0, 2)),
            'last_updated_at' => $endDate
        ]);
    }
}
