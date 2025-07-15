<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\SalesTargetIntegrationService;
use Illuminate\Support\Facades\Log;

class InvoiceObserver
{
    protected $salesTargetService;

    public function __construct(SalesTargetIntegrationService $salesTargetService)
    {
        $this->salesTargetService = $salesTargetService;
    }

    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        // Only update targets for confirmed/paid invoices
        if (in_array($invoice->status, ['confirmed', 'paid'])) {
            $this->salesTargetService->updateTargetsFromInvoice($invoice);
            
            Log::info('Sales targets updated from new invoice', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status
            ]);
        }
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        // Check if status changed to confirmed/paid
        if ($invoice->wasChanged('status')) {
            $oldStatus = $invoice->getOriginal('status');
            $newStatus = $invoice->status;
            
            // If changed from draft to confirmed/paid, update targets
            if ($oldStatus === 'draft' && in_array($newStatus, ['confirmed', 'paid'])) {
                $this->salesTargetService->updateTargetsFromInvoice($invoice);
                
                Log::info('Sales targets updated from invoice status change', [
                    'invoice_id' => $invoice->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            }
            
            // If changed from confirmed/paid to cancelled, reverse targets
            if (in_array($oldStatus, ['confirmed', 'paid']) && $newStatus === 'cancelled') {
                $this->salesTargetService->reverseTargetsFromInvoice($invoice);
                
                Log::info('Sales targets reversed from cancelled invoice', [
                    'invoice_id' => $invoice->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            }
        }
        
        // If invoice items were updated and invoice is confirmed/paid
        if ($invoice->wasChanged(['total_amount']) && in_array($invoice->status, ['confirmed', 'paid'])) {
            // For simplicity, we'll reverse the old values and add the new ones
            // In a more sophisticated system, you might want to track individual item changes
            $this->salesTargetService->reverseTargetsFromInvoice($invoice);
            $this->salesTargetService->updateTargetsFromInvoice($invoice);
            
            Log::info('Sales targets updated from invoice amount change', [
                'invoice_id' => $invoice->id,
                'old_amount' => $invoice->getOriginal('total_amount'),
                'new_amount' => $invoice->total_amount
            ]);
        }
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        // Reverse targets if the invoice was confirmed/paid
        if (in_array($invoice->status, ['confirmed', 'paid'])) {
            $this->salesTargetService->reverseTargetsFromInvoice($invoice);
            
            Log::info('Sales targets reversed from deleted invoice', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status
            ]);
        }
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        // Re-add targets if the invoice is confirmed/paid
        if (in_array($invoice->status, ['confirmed', 'paid'])) {
            $this->salesTargetService->updateTargetsFromInvoice($invoice);
            
            Log::info('Sales targets restored from restored invoice', [
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status
            ]);
        }
    }
}
