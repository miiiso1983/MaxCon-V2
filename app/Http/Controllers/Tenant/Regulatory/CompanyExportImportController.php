<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\CompanyRegistration;
use App\Imports\CompaniesCollectionImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Facades\Excel;

class CompanyExportImportController extends Controller
{
    /**
     * Export companies to Excel
     */
    public function exportToExcel()
    {
        $companies = CompanyRegistration::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'companies_export_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function() use ($companies) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($handle, [
                'اسم الشركة',
                'اسم الشركة (إنجليزي)',
                'رقم التسجيل',
                'رقم الترخيص',
                'نوع الترخيص',
                'الجهة التنظيمية',
                'تاريخ التسجيل',
                'تاريخ إصدار الترخيص',
                'تاريخ انتهاء الترخيص',
                'حالة الامتثال',
                'عنوان الشركة',
                'الشخص المسؤول',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'تاريخ التفتيش القادم',
                'ملاحظات',
                'تاريخ الإنشاء'
            ]);

            // Data rows
            foreach ($companies as $company) {
                fputcsv($handle, [
                    $company->company_name,
                    $company->company_name_en,
                    $company->registration_number,
                    $company->license_number,
                    $this->getLicenseTypeLabel($company->license_type),
                    $company->regulatory_authority,
                    $company->registration_date,
                    $company->license_issue_date,
                    $company->license_expiry_date,
                    $this->getComplianceStatusLabel($company->compliance_status),
                    $company->company_address,
                    $company->contact_person,
                    $company->contact_email,
                    $company->contact_phone,
                    $company->next_inspection_date,
                    $company->notes,
                    $company->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('tenant.regulatory.companies.import');
    }

    /**
     * Import companies from Excel/CSV
     */
    public function importFromExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'import_file' => 'required|file|mimes:csv,txt,xlsx|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $file = $request->file('import_file');
            $tenantId = Auth::user()->tenant_id;

            // Use the Collection Import for better control
            $import = new CompaniesCollectionImport($tenantId);
            Excel::import($import, $file);

            $imported = $import->getImportedCount();
            $errors = $import->getErrors();

            $message = "تم استيراد {$imported} شركة بنجاح";

            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " و " . (count($errors) - 3) . " أخطاء أخرى";
                }
            }

            // Log the import result
            \Illuminate\Support\Facades\Log::info('Companies import completed', [
                'tenant_id' => $tenantId,
                'imported_count' => $imported,
                'errors_count' => count($errors),
                'file_name' => $file->getClientOriginalName()
            ]);

            return redirect()->route('tenant.regulatory.companies.index')
                ->with($imported > 0 ? 'success' : 'warning', $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Companies import failed', [
                'tenant_id' => Auth::user()->tenant_id ?? null,
                'error' => $e->getMessage(),
                'file_name' => $request->file('import_file')?->getClientOriginalName()
            ]);

            return back()->with('error', 'خطأ في استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        $filename = 'companies_import_template.csv';

        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($handle, [
                'اسم الشركة',
                'اسم الشركة (إنجليزي)',
                'رقم التسجيل',
                'رقم الترخيص',
                'نوع الترخيص',
                'الجهة التنظيمية',
                'تاريخ التسجيل',
                'تاريخ إصدار الترخيص',
                'تاريخ انتهاء الترخيص',
                'حالة الامتثال',
                'عنوان الشركة',
                'الشخص المسؤول',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'تاريخ التفتيش القادم',
                'ملاحظات'
            ]);

            // Sample data
            fputcsv($handle, [
                'شركة الأدوية المتقدمة',
                'Advanced Pharmaceuticals Co.',
                'REG001',
                'LIC001',
                'تصنيع',
                'وزارة الصحة العراقية',
                '2023-01-15',
                '2023-01-20',
                '2025-01-20',
                'ملتزم',
                'بغداد - الكرادة - شارع الرئيسي',
                'أحمد محمد علي',
                'info@advanced-pharma.com',
                '+964 770 123 4567',
                '2024-06-15',
                'شركة رائدة في مجال الأدوية'
            ]);

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Helper methods
     */
    private function getLicenseTypeLabel($type)
    {
        $types = [
            'manufacturing' => 'تصنيع',
            'import' => 'استيراد',
            'export' => 'تصدير',
            'distribution' => 'توزيع',
            'wholesale' => 'جملة',
            'retail' => 'تجزئة',
            'research' => 'بحث وتطوير'
        ];
        
        return $types[$type] ?? $type;
    }

    private function getComplianceStatusLabel($status)
    {
        $statuses = [
            'compliant' => 'ملتزم',
            'non_compliant' => 'غير ملتزم',
            'under_investigation' => 'قيد التحقيق',
            'corrective_action' => 'إجراء تصحيحي'
        ];
        
        return $statuses[$status] ?? $status;
    }

    private function mapLicenseType($label)
    {
        $types = [
            'تصنيع' => 'manufacturing',
            'استيراد' => 'import',
            'تصدير' => 'export',
            'توزيع' => 'distribution',
            'جملة' => 'wholesale',
            'تجزئة' => 'retail',
            'بحث وتطوير' => 'research'
        ];
        
        return $types[$label] ?? 'manufacturing';
    }

    private function mapComplianceStatus($label)
    {
        $statuses = [
            'ملتزم' => 'compliant',
            'غير ملتزم' => 'non_compliant',
            'قيد التحقيق' => 'under_investigation',
            'إجراء تصحيحي' => 'corrective_action'
        ];
        
        return $statuses[$label] ?? 'compliant';
    }

    private function parseDate($dateString)
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            return date('Y-m-d', strtotime($dateString));
        } catch (\Exception $e) {
            return null;
        }
    }
}
