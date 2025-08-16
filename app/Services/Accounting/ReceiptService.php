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
        $html = View::make('tenant.accounting.receivables.receipt-pdf', compact('invoice', 'payment'))
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

        $mpdf->SetRTL(true);
        $mpdf->SetTitle('سند استلام');
        $mpdf->WriteHTML($html);
        $content = $mpdf->Output('', 'S');

        $filename = 'receipts/' . now()->format('Ymd_His') . '_' . ($payment->id ?: 'payment') . '.pdf';
        Storage::disk('public')->put($filename, $content);
        return $filename;
    }
}

