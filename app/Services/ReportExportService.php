<?php

namespace App\Services;

use App\Models\Report;
use App\Models\ReportExecution;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReportExportService
{
    /**
     * Export report to specified format
     */
    public function export(ReportExecution $execution, string $format = 'pdf')
    {
        $report = $execution->report;
        $data = collect($execution->result_data);

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($report, $data, $execution);
            case 'excel':
                return $this->exportToExcel($report, $data, $execution);
            case 'csv':
                return $this->exportToCsv($report, $data, $execution);
            case 'html':
                return $this->exportToHtml($report, $data, $execution);
            default:
                throw new \InvalidArgumentException("Unsupported export format: {$format}");
        }
    }

    /**
     * Export to PDF
     */
    protected function exportToPdf(Report $report, $data, ReportExecution $execution)
    {
        $html = view('reports.exports.pdf', [
            'report' => $report,
            'data' => $data,
            'execution' => $execution,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        $filename = $this->generateFilename($report, 'pdf');
        $path = "reports/exports/{$filename}";
        
        Storage::put($path, $pdf->output());

        $execution->update(['file_path' => $path]);

        return [
            'success' => true,
            'file_path' => $path,
            'download_url' => Storage::url($path),
            'filename' => $filename,
        ];
    }

    /**
     * Export to Excel
     */
    protected function exportToExcel(Report $report, $data, ReportExecution $execution)
    {
        $filename = $this->generateFilename($report, 'xlsx');
        $path = "reports/exports/{$filename}";

        Excel::store(new ReportExport($report, $data, $execution), $path);

        $execution->update(['file_path' => $path]);

        return [
            'success' => true,
            'file_path' => $path,
            'download_url' => Storage::url($path),
            'filename' => $filename,
        ];
    }

    /**
     * Export to CSV
     */
    protected function exportToCsv(Report $report, $data, ReportExecution $execution)
    {
        $filename = $this->generateFilename($report, 'csv');
        $path = "reports/exports/{$filename}";

        $csvContent = $this->generateCsvContent($report, $data);
        Storage::put($path, $csvContent);

        $execution->update(['file_path' => $path]);

        return [
            'success' => true,
            'file_path' => $path,
            'download_url' => Storage::url($path),
            'filename' => $filename,
        ];
    }

    /**
     * Export to HTML
     */
    protected function exportToHtml(Report $report, $data, ReportExecution $execution)
    {
        $html = view('reports.exports.html', [
            'report' => $report,
            'data' => $data,
            'execution' => $execution,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();

        $filename = $this->generateFilename($report, 'html');
        $path = "reports/exports/{$filename}";
        
        Storage::put($path, $html);

        $execution->update(['file_path' => $path]);

        return [
            'success' => true,
            'file_path' => $path,
            'download_url' => Storage::url($path),
            'filename' => $filename,
        ];
    }

    /**
     * Generate CSV content
     */
    protected function generateCsvContent(Report $report, $data)
    {
        $columns = $report->formatted_columns;
        $output = fopen('php://temp', 'r+');

        // Write headers
        $headers = $columns->pluck('label')->toArray();
        fputcsv($output, $headers);

        // Write data rows
        foreach ($data as $row) {
            $csvRow = [];
            foreach ($columns as $column) {
                $value = $row[$column['field']] ?? '';
                
                // Format value based on column type
                $csvRow[] = $this->formatValue($value, $column);
            }
            fputcsv($output, $csvRow);
        }

        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);

        return $csvContent;
    }

    /**
     * Format value based on column type
     */
    protected function formatValue($value, array $column)
    {
        switch ($column['type']) {
            case 'currency':
                return number_format($value, 2) . ' د.ع';
            case 'number':
                return number_format($value);
            case 'datetime':
                return Carbon::parse($value)->format('Y-m-d H:i:s');
            case 'date':
                return Carbon::parse($value)->format('Y-m-d');
            case 'percentage':
                return number_format($value, 2) . '%';
            default:
                return $value;
        }
    }

    /**
     * Generate filename for export
     */
    protected function generateFilename(Report $report, string $extension)
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $reportName = str_replace(' ', '_', $report->name);
        
        return "{$reportName}_{$timestamp}.{$extension}";
    }

    /**
     * Send report via email
     */
    public function sendByEmail(ReportExecution $execution, array $recipients, string $format = 'pdf')
    {
        $exportResult = $this->export($execution, $format);
        
        if (!$exportResult['success']) {
            return ['success' => false, 'error' => 'Failed to export report'];
        }

        $report = $execution->report;
        $filePath = $exportResult['file_path'];
        $filename = $exportResult['filename'];

        try {
            Mail::send('emails.report', [
                'report' => $report,
                'execution' => $execution,
            ], function ($message) use ($recipients, $report, $filePath, $filename) {
                $message->to($recipients)
                    ->subject("تقرير: {$report->name}")
                    ->attach(Storage::path($filePath), [
                        'as' => $filename,
                    ]);
            });

            return ['success' => true, 'message' => 'تم إرسال التقرير بالبريد الإلكتروني بنجاح'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Print report (generate print-friendly HTML)
     */
    public function generatePrintVersion(ReportExecution $execution)
    {
        $report = $execution->report;
        $data = collect($execution->result_data);

        return view('reports.exports.print', [
            'report' => $report,
            'data' => $data,
            'execution' => $execution,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();
    }

    /**
     * Clean up old export files
     */
    public function cleanupOldFiles(int $daysOld = 7)
    {
        $cutoffDate = now()->subDays($daysOld);
        
        $oldExecutions = ReportExecution::where('created_at', '<', $cutoffDate)
            ->whereNotNull('file_path')
            ->get();

        $deletedCount = 0;
        foreach ($oldExecutions as $execution) {
            if (Storage::exists($execution->file_path)) {
                Storage::delete($execution->file_path);
                $execution->update(['file_path' => null]);
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}

/**
 * Excel Export Class
 */
class ReportExport implements \Maatwebsite\Excel\Concerns\FromCollection,
                              \Maatwebsite\Excel\Concerns\WithHeadings,
                              \Maatwebsite\Excel\Concerns\WithStyles,
                              \Maatwebsite\Excel\Concerns\WithTitle
{
    protected $report;
    protected $data;
    protected $execution;

    public function __construct(Report $report, $data, ReportExecution $execution)
    {
        $this->report = $report;
        $this->data = $data;
        $this->execution = $execution;
    }

    public function collection()
    {
        $columns = $this->report->formatted_columns;
        
        return $this->data->map(function ($row) use ($columns) {
            $formattedRow = [];
            foreach ($columns as $column) {
                $value = $row[$column['field']] ?? '';
                $formattedRow[] = $this->formatValue($value, $column);
            }
            return $formattedRow;
        });
    }

    public function headings(): array
    {
        return $this->report->formatted_columns->pluck('label')->toArray();
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return $this->report->name;
    }

    protected function formatValue($value, array $column)
    {
        switch ($column['type']) {
            case 'currency':
                return number_format($value, 2);
            case 'number':
                return number_format($value);
            case 'datetime':
                return Carbon::parse($value)->format('Y-m-d H:i:s');
            case 'date':
                return Carbon::parse($value)->format('Y-m-d');
            case 'percentage':
                return number_format($value, 2);
            default:
                return $value;
        }
    }
}
