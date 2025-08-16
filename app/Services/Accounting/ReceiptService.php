<?php

namespace App\Services\Accounting;

use App\Models\InvoicePayment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;

class ReceiptService
{
    /**
     * Generate receipt PDF for a payment and store it. Returns relative path.
     */
    public function generatePdf(InvoicePayment $payment): string
    {
        $invoice = $payment->invoice()->with(['customer', 'salesRep', 'tenant'])->first();
        // Prepare QR data
        // Create signed public URL for receipt verification (like invoice verification)
        $qrText = \URL::signedRoute('public.receipt.verify', ['payment' => $payment->id]);

        $qrPng = null;

        // Try multiple methods to generate QR code with larger size for mobile scanning
        try {
            // Method 1: SimpleSoftwareIO QrCode - Larger size for better mobile scanning
            if (class_exists('SimpleSoftwareIO\\QrCode\\Facades\\QrCode')) {
                $qrPng = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->margin(2)->generate($qrText));
            }
        } catch (\Throwable $e) {
            \Log::warning('QR Code generation failed with SimpleSoftwareIO: ' . $e->getMessage());
        }

        // Method 2: Fallback to external API if first method failed
        if (!$qrPng) {
            try {
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=png&data=' . urlencode($qrText);
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 10,
                        'user_agent' => 'MaxCon Receipt System'
                    ]
                ]);
                $qrImageData = @file_get_contents($qrUrl, false, $context);
                if ($qrImageData !== false) {
                    $qrPng = base64_encode($qrImageData);
                }
            } catch (\Throwable $e) {
                \Log::warning('QR Code generation failed with external API: ' . $e->getMessage());
            }
        }

        // Method 3: Generate fallback URL-based QR if signed URL fails
        if (!$qrPng) {
            try {
                $fallbackUrl = url("/receipt/{$payment->id}/verify");
                $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&format=png&data=' . urlencode($fallbackUrl);
                $qrImageData = @file_get_contents($qrUrl);
                if ($qrImageData !== false) {
                    $qrPng = base64_encode($qrImageData);
                }
            } catch (\Throwable $e) {
                \Log::error('All QR Code generation methods failed: ' . $e->getMessage());
                $qrPng = null;
            }
        }

        // Prepare logo base64 if available
        $logoB64 = null;
        try {
            $logoPath = $invoice->tenant->logo ?? null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $logoB64 = base64_encode(Storage::disk('public')->get($logoPath));
                $logoMime = \Illuminate\Support\Str::endsWith(strtolower($logoPath), '.png') ? 'image/png' : 'image/jpeg';
                $logoB64 = 'data:' . $logoMime . ';base64,' . $logoB64;
            }
        } catch (\Throwable $e) { $logoB64 = null; }

        $html = View::make('tenant.accounting.receivables.receipt-pdf', compact('invoice', 'payment', 'qrPng', 'qrData', 'logoB64'))
            ->render();

        // Configure mPDF for Arabic RTL
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $tmpDir = storage_path('app/mpdf_tmp');
        if (!is_dir($tmpDir)) { @mkdir($tmpDir, 0775, true); }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A5',
            'orientation' => 'P',
            'fontDir' => $fontDirs,
            'fontdata' => $fontData, // rely on bundled fonts; can add Noto Naskh later
            'default_font' => 'dejavusanscondensed',
            'tempDir' => $tmpDir,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->SetTitle('سند استلام');
        $mpdf->WriteHTML($html);
        $content = $mpdf->Output('', 'S');

        $filename = 'receipts/' . now()->format('Ymd_His') . '_' . ($payment->id ?: 'payment') . '.pdf';
        Storage::disk('public')->put($filename, $content);
        return $filename;
    }

    /**
     * Get Arabic label for payment method
     */
    private function getPaymentMethodLabel($method): string
    {
        $methods = [
            'cash' => 'نقداً',
            'credit_card' => 'بطاقة ائتمان',
            'debit_card' => 'بطاقة خصم',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
            'installment' => 'تقسيط',
            'other' => 'أخرى'
        ];

        return $methods[$method] ?? $method ?? 'نقداً';
    }
}

