<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'invoice_number',
        'sales_order_id',
        'customer_id',
        'warehouse_id',
        'sales_rep_id',
        'created_by',
        'invoice_date',
        'due_date',
        'status',
        'payment_status',
        'type',
        'subtotal',
        'subtotal_amount',
        'discount_amount',
        'discount_percentage',
        'discount_type',
        'tax_amount',
        'tax_percentage',
        'shipping_cost',
        'additional_charges',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'previous_debt',
        'current_debt',
        'previous_balance',
        'credit_limit',
        'sales_representative',
        'warehouse_name',
        'whatsapp_history',
        'currency',
        'exchange_rate',
        'billing_address',
        'shipping_address',
        'payment_terms',
        'terms_conditions',
        'notes',
        'qr_code',
        'pdf_path',
        'sent_at',
        'viewed_at',
        'paid_at',
        'email_history',
        'free_samples'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'additional_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'whatsapp_history' => 'array',
        'exchange_rate' => 'decimal:4',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'paid_at' => 'datetime',
        'email_history' => 'array',
    ];

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function salesReturns(): HasMany
    {
        return $this->hasMany(SalesReturn::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereIn('status', ['sent', 'viewed', 'partial_paid']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['sent', 'viewed', 'partial_paid', 'overdue']);
    }

    // Helper Methods
    public function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $year = now()->year;
        $month = str_pad(now()->month, 2, '0', STR_PAD_LEFT);

        $lastInvoice = static::where('tenant_id', $this->tenant_id)
            ->where('invoice_number', 'like', "{$prefix}-{$year}{$month}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "{$prefix}-{$year}{$month}" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotals()
    {
        $this->subtotal = $this->items->sum('line_total');
        $this->tax_amount = $this->items->sum('tax_amount');
        $this->total_amount = $this->subtotal - $this->discount_amount + $this->tax_amount + $this->shipping_cost;
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
        $this->save();
    }

    public function generateQrCode()
    {
        // Create signed public URL for verification (secure)
        $qrUrl = \URL::signedRoute('public.invoice.verify', ['invoice' => $this->id]);

        try {
            // Method 1: Use external QR code service (most reliable)
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrUrl);

            // Try to fetch the QR code image
            $context = stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'MaxCon Invoice System'
                ]
            ]);

            $qrCodeImage = @file_get_contents($qrCodeUrl, false, $context);

            if ($qrCodeImage !== false) {
                $qrCodeBase64 = base64_encode($qrCodeImage);
                $this->update(['qr_code' => $qrCodeBase64]);
                return $qrCodeBase64;
            }

        } catch (\Exception $e) {
            Log::error('External QR Code service failed: ' . $e->getMessage());
        }

        try {
            // Method 2: Try local QR code generation with simple URL
            $qrCodeImage = QrCode::format('png')
                ->size(200)
                ->margin(1)
                ->errorCorrection('M')
                ->generate($qrUrl);

            $qrCodeBase64 = base64_encode($qrCodeImage);
            $this->update(['qr_code' => $qrCodeBase64]);
            return $qrCodeBase64;

        } catch (\Exception $e) {
            Log::error('Local QR Code generation failed: ' . $e->getMessage());
        }

        try {
            // Method 3: Try SVG format
            $qrCodeSvg = QrCode::format('svg')
                ->size(200)
                ->margin(1)
                ->generate($qrUrl);

            $qrCodeBase64 = base64_encode($qrCodeSvg);
            $this->update(['qr_code' => $qrCodeBase64]);
            return $qrCodeBase64;

        } catch (\Exception $e) {
            Log::error('SVG QR Code generation failed: ' . $e->getMessage());
        }

        // Method 4: Create a visual placeholder
        $placeholder = $this->createQrCodePlaceholder();
        $qrCodeBase64 = base64_encode($placeholder);
        $this->update(['qr_code' => $qrCodeBase64]);
        return $qrCodeBase64;
    }

    private function createQrCodePlaceholder()
    {
        return '<svg width="200" height="200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="qrPattern" patternUnits="userSpaceOnUse" width="10" height="10">
                    <rect width="5" height="5" fill="#000"/>
                    <rect x="5" y="5" width="5" height="5" fill="#000"/>
                </pattern>
            </defs>
            <rect width="200" height="200" fill="white" stroke="#ddd" stroke-width="2"/>
            <rect x="20" y="20" width="160" height="160" fill="url(#qrPattern)" opacity="0.3"/>
            <text x="100" y="110" text-anchor="middle" font-family="Arial" font-size="14" fill="#333" font-weight="bold">
                QR Code
            </text>
            <text x="100" y="130" text-anchor="middle" font-family="Arial" font-size="12" fill="#666">
                ' . $this->invoice_number . '
            </text>
            <text x="100" y="150" text-anchor="middle" font-family="Arial" font-size="10" fill="#999">
                Scan for details
            </text>
        </svg>';
    }

    public function markAsSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }

    public function markAsViewed()
    {
        if ($this->status === 'sent') {
            $this->status = 'viewed';
            $this->viewed_at = now();
            $this->save();
        }
    }

    public function addPayment($amount, $paymentMethod = 'cash', $referenceNumber = null, $notes = null)
    {
        $payment = $this->payments()->create([
            'amount' => $amount,
            'payment_date' => now(),
            'payment_method' => $paymentMethod,
            'reference_number' => $referenceNumber,
            'notes' => $notes,
            'created_by' => Auth::id() ?? 1,
        ]);

        $this->paid_amount += $amount;
        $this->remaining_amount = $this->total_amount - $this->paid_amount;

        // Update payment status
        if ($this->remaining_amount <= 0) {
            $this->payment_status = 'paid';
            $this->status = 'paid';
            $this->paid_at = now();
        } elseif ($this->paid_amount > 0) {
            $this->payment_status = 'partial';
            $this->status = 'partial_paid';
        }

        $this->save();

        // Post accounting entry and update customer balance
        try {
            app(\App\Services\Accounting\ReceivablesService::class)->postPaymentEntry($this, $payment);
            if ($this->customer) {
                app(\App\Services\Accounting\ReceivablesService::class)->applyPaymentToCustomer($this->customer, (float) $amount);
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to post accounting entry for invoice payment', [
                'invoice_id' => $this->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $payment;
    }

    public function isOverdue(): bool
    {
        return $this->due_date < now() && in_array($this->status, ['sent', 'viewed', 'partial_paid']);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'sent' => 'blue',
            'viewed' => 'indigo',
            'partial_paid' => 'yellow',
            'paid' => 'green',
            'overdue' => 'red',
            'cancelled' => 'red',
            'refunded' => 'orange',
            default => 'gray'
        };
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }



    /**
     * Get QR Code (generate if not exists)
     */
    public function getQrCodeAttribute($value)
    {
        // If QR code doesn't exist, generate it
        if (empty($value)) {
            return $this->generateQrCode();
        }
        return $value;
    }

    /**
     * Force regenerate QR Code
     */
    public function regenerateQrCode()
    {
        return $this->generateQrCode();
    }
}
