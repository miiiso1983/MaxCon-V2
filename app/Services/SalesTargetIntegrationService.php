<?php

namespace App\Services;

use App\Models\SalesTarget;
use App\Models\SalesTargetProgress;
use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SalesTargetIntegrationService
{
    /**
     * Update targets progress when an invoice is created/updated
     */
    public function updateTargetsFromInvoice(Invoice $invoice): void
    {
        try {
            DB::beginTransaction();
            
            // Get invoice items with products
            $invoiceItems = $invoice->items()->with('product')->get();
            
            foreach ($invoiceItems as $item) {
                if (!$item->product) continue;
                
                $quantity = $item->quantity;
                $value = $item->total_amount;
                $productId = $item->product_id;
                $salesRepId = $invoice->sales_rep_id;
                // Note: vendor_id would be used here if Vendor model exists
                // $vendorId = $item->product->vendor_id ?? null;

                // Update product targets
                $this->updateProductTargets($productId, $quantity, $value, $invoice);

                // Update vendor targets (disabled until Vendor model is created)
                // if ($vendorId) {
                //     $this->updateVendorTargets($vendorId, $quantity, $value, $invoice);
                // }
                
                // Update sales rep targets
                if ($salesRepId) {
                    $this->updateSalesRepTargets($salesRepId, $quantity, $value, $invoice);
                }
                
                // Update team and department targets
                $this->updateTeamAndDepartmentTargets($salesRepId, $quantity, $value, $invoice);
            }
            
            DB::commit();
            
            Log::info('Sales targets updated from invoice', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'total_amount' => $invoice->total_amount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update sales targets from invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update product-specific targets
     */
    private function updateProductTargets(int $productId, float $quantity, float $value, Invoice $invoice): void
    {
        $targets = SalesTarget::forTenant($invoice->tenant_id)
                             ->where('target_type', 'product')
                             ->where('target_entity_id', $productId)
                             ->active()
                             ->current()
                             ->get();
        
        foreach ($targets as $target) {
            $this->updateTargetProgress($target, $quantity, $value, [
                'type' => 'invoice',
                'id' => $invoice->id,
                'details' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $invoice->customer_name,
                    'product_id' => $productId,
                    'date' => $invoice->invoice_date
                ]
            ]);
        }
    }
    
    /**
     * Update vendor-specific targets
     */
    private function updateVendorTargets(int $vendorId, float $quantity, float $value, Invoice $invoice): void
    {
        $targets = SalesTarget::forTenant($invoice->tenant_id)
                             ->where('target_type', 'vendor')
                             ->where('target_entity_id', $vendorId)
                             ->active()
                             ->current()
                             ->get();
        
        foreach ($targets as $target) {
            $this->updateTargetProgress($target, $quantity, $value, [
                'type' => 'invoice',
                'id' => $invoice->id,
                'details' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $invoice->customer_name,
                    'vendor_id' => $vendorId,
                    'date' => $invoice->invoice_date
                ]
            ]);
        }
    }
    
    /**
     * Update sales rep targets
     */
    private function updateSalesRepTargets(int $salesRepId, float $quantity, float $value, Invoice $invoice): void
    {
        $targets = SalesTarget::forTenant($invoice->tenant_id)
                             ->where('target_type', 'sales_rep')
                             ->where('target_entity_id', $salesRepId)
                             ->active()
                             ->current()
                             ->get();
        
        foreach ($targets as $target) {
            $this->updateTargetProgress($target, $quantity, $value, [
                'type' => 'invoice',
                'id' => $invoice->id,
                'details' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $invoice->customer_name,
                    'sales_rep_id' => $salesRepId,
                    'date' => $invoice->invoice_date
                ]
            ]);
        }
    }
    
    /**
     * Update team and department targets
     */
    private function updateTeamAndDepartmentTargets(?int $salesRepId, float $quantity, float $value, Invoice $invoice): void
    {
        if (!$salesRepId) return;
        
        // Get sales rep's team and department (this would depend on your user/team structure)
        // For now, we'll update all team and department targets
        
        $teamTargets = SalesTarget::forTenant($invoice->tenant_id)
                                 ->where('target_type', 'sales_team')
                                 ->active()
                                 ->current()
                                 ->get();
        
        foreach ($teamTargets as $target) {
            $this->updateTargetProgress($target, $quantity, $value, [
                'type' => 'invoice',
                'id' => $invoice->id,
                'details' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $invoice->customer_name,
                    'sales_rep_id' => $salesRepId,
                    'date' => $invoice->invoice_date
                ]
            ]);
        }
        
        $departmentTargets = SalesTarget::forTenant($invoice->tenant_id)
                                       ->where('target_type', 'department')
                                       ->active()
                                       ->current()
                                       ->get();
        
        foreach ($departmentTargets as $target) {
            $this->updateTargetProgress($target, $quantity, $value, [
                'type' => 'invoice',
                'id' => $invoice->id,
                'details' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $invoice->customer_name,
                    'sales_rep_id' => $salesRepId,
                    'date' => $invoice->invoice_date
                ]
            ]);
        }
    }
    
    /**
     * Update individual target progress
     */
    private function updateTargetProgress(SalesTarget $target, float $quantity, float $value, array $source): void
    {
        // Determine which values to use based on measurement type
        $quantityToAdd = 0;
        $valueToAdd = 0;
        
        if ($target->measurement_type === 'quantity' || $target->measurement_type === 'both') {
            $quantityToAdd = $quantity;
        }
        
        if ($target->measurement_type === 'value' || $target->measurement_type === 'both') {
            $valueToAdd = $value;
        }
        
        // Update target progress
        $target->updateProgress($quantityToAdd, $valueToAdd, $source);
    }
    
    /**
     * Reverse target progress when an invoice is deleted/cancelled
     */
    public function reverseTargetsFromInvoice(Invoice $invoice): void
    {
        try {
            DB::beginTransaction();
            
            $invoiceItems = $invoice->items()->with('product')->get();
            
            foreach ($invoiceItems as $item) {
                if (!$item->product) continue;
                
                $quantity = -$item->quantity; // Negative to reverse
                $value = -$item->total_amount; // Negative to reverse
                $productId = $item->product_id;
                $salesRepId = $invoice->sales_rep_id;
                // Note: vendor_id would be used here if Vendor model exists
                // $vendorId = $item->product->vendor_id ?? null;

                // Reverse product targets
                $this->updateProductTargets($productId, $quantity, $value, $invoice);

                // Reverse vendor targets (disabled until Vendor model is created)
                // if ($vendorId) {
                //     $this->updateVendorTargets($vendorId, $quantity, $value, $invoice);
                // }
                
                // Reverse sales rep targets
                if ($salesRepId) {
                    $this->updateSalesRepTargets($salesRepId, $quantity, $value, $invoice);
                }
                
                // Reverse team and department targets
                $this->updateTeamAndDepartmentTargets($salesRepId, $quantity, $value, $invoice);
            }
            
            DB::commit();
            
            Log::info('Sales targets reversed from cancelled invoice', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to reverse sales targets from invoice', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get targets summary for a specific entity
     */
    public function getTargetsSummary(string $tenantId, string $entityType, int $entityId): array
    {
        $targets = SalesTarget::forTenant($tenantId)
                             ->where('target_type', $entityType)
                             ->where('target_entity_id', $entityId)
                             ->with(['progress'])
                             ->get();
        
        $summary = [
            'total_targets' => $targets->count(),
            'active_targets' => $targets->where('status', 'active')->count(),
            'completed_targets' => $targets->where('status', 'completed')->count(),
            'overdue_targets' => $targets->filter(function($target) {
                return $target->is_overdue;
            })->count(),
            'average_progress' => $targets->avg('progress_percentage') ?? 0,
            'current_month_targets' => $targets->filter(function($target) {
                return $target->period_type === 'monthly' && 
                       $target->month === Carbon::now()->month &&
                       $target->year === Carbon::now()->year;
            })->values(),
            'current_quarter_targets' => $targets->filter(function($target) {
                return $target->period_type === 'quarterly' && 
                       $target->quarter === Carbon::now()->quarter &&
                       $target->year === Carbon::now()->year;
            })->values(),
            'current_year_targets' => $targets->filter(function($target) {
                return $target->period_type === 'yearly' && 
                       $target->year === Carbon::now()->year;
            })->values()
        ];
        
        return $summary;
    }
    
    /**
     * Get performance metrics for dashboard
     */
    public function getPerformanceMetrics(string $tenantId): array
    {
        $currentMonth = Carbon::now();
        
        return [
            'monthly_performance' => $this->getMonthlyPerformance($tenantId, $currentMonth),
            'quarterly_performance' => $this->getQuarterlyPerformance($tenantId, $currentMonth),
            'yearly_performance' => $this->getYearlyPerformance($tenantId, $currentMonth),
            'top_performers' => $this->getTopPerformers($tenantId),
            'underperformers' => $this->getUnderperformers($tenantId)
        ];
    }
    
    /**
     * Get monthly performance data
     */
    private function getMonthlyPerformance(string $tenantId, Carbon $month): array
    {
        $targets = SalesTarget::forTenant($tenantId)
                             ->where('period_type', 'monthly')
                             ->where('year', $month->year)
                             ->where('month', $month->month)
                             ->get();
        
        return [
            'total_targets' => $targets->count(),
            'completed' => $targets->where('status', 'completed')->count(),
            'on_track' => $targets->filter(function($target) {
                return $target->progress_percentage >= $target->time_progress_percentage;
            })->count(),
            'behind' => $targets->filter(function($target) {
                return $target->progress_percentage < $target->time_progress_percentage;
            })->count(),
            'average_progress' => round($targets->avg('progress_percentage') ?? 0, 2)
        ];
    }
    
    /**
     * Get quarterly performance data
     */
    private function getQuarterlyPerformance(string $tenantId, Carbon $date): array
    {
        $targets = SalesTarget::forTenant($tenantId)
                             ->where('period_type', 'quarterly')
                             ->where('year', $date->year)
                             ->where('quarter', $date->quarter)
                             ->get();
        
        return [
            'total_targets' => $targets->count(),
            'completed' => $targets->where('status', 'completed')->count(),
            'average_progress' => round($targets->avg('progress_percentage') ?? 0, 2)
        ];
    }
    
    /**
     * Get yearly performance data
     */
    private function getYearlyPerformance(string $tenantId, Carbon $date): array
    {
        $targets = SalesTarget::forTenant($tenantId)
                             ->where('period_type', 'yearly')
                             ->where('year', $date->year)
                             ->get();
        
        return [
            'total_targets' => $targets->count(),
            'completed' => $targets->where('status', 'completed')->count(),
            'average_progress' => round($targets->avg('progress_percentage') ?? 0, 2)
        ];
    }
    
    /**
     * Get top performers
     */
    private function getTopPerformers(string $tenantId): array
    {
        return SalesTarget::forTenant($tenantId)
                         ->where('target_type', 'sales_rep')
                         ->active()
                         ->orderByDesc('progress_percentage')
                         ->limit(5)
                         ->get()
                         ->map(function($target) {
                             return [
                                 'name' => $target->target_entity_name,
                                 'progress' => $target->progress_percentage,
                                 'target_value' => $target->target_value,
                                 'achieved_value' => $target->achieved_value
                             ];
                         })
                         ->toArray();
    }
    
    /**
     * Get underperformers
     */
    private function getUnderperformers(string $tenantId): array
    {
        return SalesTarget::forTenant($tenantId)
                         ->where('target_type', 'sales_rep')
                         ->active()
                         ->where('progress_percentage', '<', 70) // Less than 70%
                         ->orderBy('progress_percentage')
                         ->limit(5)
                         ->get()
                         ->map(function($target) {
                             return [
                                 'name' => $target->target_entity_name,
                                 'progress' => $target->progress_percentage,
                                 'target_value' => $target->target_value,
                                 'achieved_value' => $target->achieved_value,
                                 'remaining_days' => $target->remaining_days
                             ];
                         })
                         ->toArray();
    }
}
