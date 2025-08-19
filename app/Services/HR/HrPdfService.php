<?php

namespace App\Services\HR;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Mpdf\Config\FontVariables;
use Mpdf\Config\ConfigVariables;

class HrPdfService
{
    /**
     * Render a Blade view to PDF content (string) using mPDF with Arabic RTL support and unified header/footer
     */
    public function render(string $view, array $data = [], string $title = 'تقارير الموارد البشرية', string $orientation = 'P'): string
    {
        $html = View::make($view, $data)->render();

        // Resolve tenant and logo
        $tenant = app()->has('tenant') ? app('tenant') : (Auth::user()->tenant ?? null);
        $companyName = $tenant->name ?? config('app.name', 'MaxCon');
        $logoB64 = $this->getTenantLogoBase64($tenant);

        // mPDF config
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        $tmpDir = storage_path('app/mpdf_tmp');
        if (!is_dir($tmpDir)) { @mkdir($tmpDir, 0775, true); }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => strtoupper($orientation) === 'L' ? 'L' : 'P',
            'fontDir' => $fontDirs,
            'fontdata' => $fontData, // rely on bundled fonts; custom fonts can be added via config later
            'default_font' => 'dejavusanscondensed',
            'tempDir' => $tmpDir,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        // Header & Footer (MaxCon style)
        $header = $this->buildHeaderHtml($companyName, $logoB64, $title);
        $footer = $this->buildFooterHtml();
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        // Body content
        $mpdf->WriteHTML($this->baseStyles());
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }

    private function baseStyles(): string
    {
        return '<style>
            body { direction: rtl; font-family: "dejavusans", "DejaVu Sans", sans-serif; color: #111827; }
            table { width: 100%; border-collapse: collapse; font-size: 12px; }
            th, td { border: 1px solid #e5e7eb; padding: 6px 8px; }
            thead th { background: #f1f5f9; font-weight: 700; text-align:center; }
            tbody td { text-align:center; }
        </style>';
    }

    private function buildHeaderHtml(string $companyName, ?string $logoB64, string $title): string
    {
        $logoImg = $logoB64 ? '<img src="' . e($logoB64) . '" style="height:28px;">' : '';
        return '<div style="border-bottom:1px solid #e5e7eb; padding:6px 0;">
            <table style="width:100%; border:0;">
                <tr>
                    <td style="width:25%; text-align:right;">' . $logoImg . '</td>
                    <td style="width:50%; text-align:center; font-weight:800; color:#1f2937;">' . e($title) . '</td>
                    <td style="width:25%; text-align:left; color:#374151; font-weight:700;">' . e($companyName) . '</td>
                </tr>
            </table>
        </div>';
    }

    private function buildFooterHtml(): string
    {
        return '<div style="border-top:1px solid #e5e7eb; font-size:11px; color:#6b7280; padding-top:6px;">
            <table style="width:100%; border:0;">
                <tr>
                    <td style="text-align:right;">تم التوليد: ' . now()->format('Y-m-d H:i') . '</td>
                    <td style="text-align:left;">صفحة {PAGENO} من {nbpg}</td>
                </tr>
            </table>
        </div>';
    }

    private function getTenantLogoBase64($tenant): ?string
    {
        try {
            if (!$tenant) { return null; }
            $logoPath = $tenant->logo ?? null;
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                $content = Storage::disk('public')->get($logoPath);
                $mime = Str::endsWith(strtolower($logoPath), '.png') ? 'image/png' : 'image/jpeg';
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }
        } catch (\Throwable $e) {
            return null;
        }
        return null;
    }
}

