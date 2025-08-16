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
        $qrData = [
            'receipt_number' => $payment->receipt_number,
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'tenant' => $invoice->tenant->name ?? null,
            'customer' => optional($invoice->customer)->name,
            'sales_rep' => optional($invoice->salesRep)->name,
            'amount' => (float) $payment->amount,
            'payment_method' => $payment->payment_method,
            'payment_date' => optional($payment->payment_date)->format('Y-m-d'),
        ];
        $qrPng = null;
        try {
            if (class_exists('SimpleSoftwareIO\QrCode\Facade\QrCode')) {
                $qrPng = base64_encode(\SimpleSoftwareIO\QrCode\Facade\QrCode::format('png')->size(220)->margin(1)->generate(json_encode($qrData, JSON_UNESCAPED_UNICODE)));
            }
        } catch (\Throwable $e) {
            $qrPng = null;
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
}

