<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ReturnOrder;
use App\Models\ReturnItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class ReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first invoice for testing
        $invoice = Invoice::with('items')->first();

        if (!$invoice) {
            $this->command->info('No invoices found. Please create invoices first.');
            return;
        }

        // Create a return order
        $return = ReturnOrder::create([
            'tenant_id' => $invoice->tenant_id,
            'return_number' => 'RET202501010001',
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'return_date' => now()->subDays(2),
            'type' => 'return',
            'status' => 'pending',
            'reason' => 'المنتج لا يناسب احتياجات العميل',
            'notes' => 'العميل يطلب استرداد كامل للمبلغ',
            'refund_method' => 'cash',
            'total_amount' => 0,
            'refund_amount' => 0,
        ]);

        // Create return items for first item in invoice
        $invoiceItem = $invoice->items->first();
        if ($invoiceItem) {
            $returnItem = ReturnItem::create([
                'return_id' => $return->id,
                'invoice_item_id' => $invoiceItem->id,
                'product_id' => $invoiceItem->product_id,
                'product_name' => $invoiceItem->product_name,
                'product_code' => $invoiceItem->product_code,
                'batch_number' => $invoiceItem->batch_number,
                'expiry_date' => $invoiceItem->expiry_date,
                'quantity_returned' => min(2, $invoiceItem->quantity), // Return 2 or less
                'quantity_original' => $invoiceItem->quantity,
                'unit_price' => $invoiceItem->unit_price,
                'condition' => 'good',
                'reason' => 'لا يناسب احتياجات العميل',
                'total_amount' => min(2, $invoiceItem->quantity) * $invoiceItem->unit_price,
            ]);

            // Update return totals
            $return->calculateTotals();
        }

        // Create an exchange order
        if ($invoice->items->count() > 1) {
            $exchangeReturn = ReturnOrder::create([
                'tenant_id' => $invoice->tenant_id,
                'return_number' => 'RET202501010002',
                'invoice_id' => $invoice->id,
                'customer_id' => $invoice->customer_id,
                'return_date' => now()->subDays(1),
                'type' => 'exchange',
                'status' => 'approved',
                'reason' => 'العميل يريد استبدال المنتج بمنتج آخر',
                'notes' => 'تم الموافقة على الاستبدال',
                'total_amount' => 0,
                'refund_amount' => 0,
                'processed_by' => 1,
                'processed_at' => now()->subHours(2),
            ]);

            $secondItem = $invoice->items->skip(1)->first();
            if ($secondItem) {
                $exchangeItem = ReturnItem::create([
                    'return_id' => $exchangeReturn->id,
                    'invoice_item_id' => $secondItem->id,
                    'product_id' => $secondItem->product_id,
                    'product_name' => $secondItem->product_name,
                    'product_code' => $secondItem->product_code,
                    'batch_number' => $secondItem->batch_number,
                    'expiry_date' => $secondItem->expiry_date,
                    'quantity_returned' => 1,
                    'quantity_original' => $secondItem->quantity,
                    'unit_price' => $secondItem->unit_price,
                    'condition' => 'good',
                    'reason' => 'يريد منتج مختلف',
                    'total_amount' => $secondItem->unit_price,
                    'exchange_product_id' => $secondItem->product_id, // Same product for simplicity
                    'exchange_quantity' => 1,
                    'exchange_unit_price' => $secondItem->unit_price,
                    'exchange_total_amount' => $secondItem->unit_price,
                ]);

                // Update exchange totals
                $exchangeReturn->calculateTotals();
            }
        }

        $this->command->info('Return orders created successfully!');
    }
}
