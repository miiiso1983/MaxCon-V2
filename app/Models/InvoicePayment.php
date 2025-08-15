<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoicePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'receipt_number',
        'pdf_path',
        'whatsapp_sent_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'whatsapp_sent_at' => 'datetime',
    ];

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper Methods
    public function getPaymentMethodLabel()
    {
        $methods = [
            'cash' => 'نقداً',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'credit_card' => 'بطاقة ائتمان',
            'other' => 'أخرى',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getFormattedAmount()
    {
        return number_format($this->amount, 2) . ' د.ع';
    }

    public function getFormattedDate()
    {
        return $this->payment_date->format('Y-m-d');
    }

    public static function generateReceiptNumber(int $tenantId): string
    {
        $prefix = 'RCPT-' . now()->format('Ym');
        $last = static::whereHas('invoice', function($q) use ($tenantId){
                $q->where('tenant_id', $tenantId);
            })
            ->where('receipt_number', 'like', $prefix . '%')
            ->orderBy('receipt_number', 'desc')
            ->first();
        $seq = 1;
        if ($last && $last->receipt_number) {
            $seq = intval(substr($last->receipt_number, -4)) + 1;
        }
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
