<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\CompanyRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

        $file = $request->file('import_file');
        $path = $file->getRealPath();
        
        $imported = 0;
        $errors = [];
        $skipped = 0;

        try {
            if (($handle = fopen($path, 'r')) !== FALSE) {
                // Skip header row
                $header = fgetcsv($handle);
                
                $rowNumber = 1;
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $rowNumber++;
                    
                    // Skip empty rows
                    if (empty(array_filter($data))) {
                        continue;
                    }

                    // Validate required fields
                    if (empty($data[0]) || empty($data[2]) || empty($data[3])) {
                        $errors[] = "الصف {$rowNumber}: اسم الشركة ورقم التسجيل ورقم الترخيص مطلوبة";
                        $skipped++;
                        continue;
                    }

                    // Check if company already exists
                    $existingCompany = CompanyRegistration::where('tenant_id', Auth::user()->tenant_id)
                        ->where(function($query) use ($data) {
                            $query->where('registration_number', $data[2])
                                  ->orWhere('license_number', $data[3]);
                        })
                        ->first();

                    if ($existingCompany) {
                        $errors[] = "الصف {$rowNumber}: الشركة موجودة مسبقاً (رقم التسجيل: {$data[2]})";
                        $skipped++;
                        continue;
                    }

                    try {
                        CompanyRegistration::create([
                            'id' => Str::uuid(),
                            'tenant_id' => Auth::user()->tenant_id,
                            'company_name' => $data[0] ?? '',
                            'company_name_en' => $data[1] ?? '',
                            'registration_number' => $data[2] ?? '',
                            'license_number' => $data[3] ?? '',
                            'license_type' => $this->mapLicenseType($data[4] ?? ''),
                            'regulatory_authority' => $data[5] ?? '',
                            'registration_date' => $this->parseDate($data[6] ?? ''),
                            'license_issue_date' => $this->parseDate($data[7] ?? ''),
                            'license_expiry_date' => $this->parseDate($data[8] ?? ''),
                            'compliance_status' => $this->mapComplianceStatus($data[9] ?? ''),
                            'company_address' => $data[10] ?? '',
                            'contact_person' => $data[11] ?? '',
                            'contact_email' => $data[12] ?? '',
                            'contact_phone' => $data[13] ?? '',
                            'next_inspection_date' => $this->parseDate($data[14] ?? ''),
                            'notes' => $data[15] ?? ''
                        ]);
                        
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "الصف {$rowNumber}: خطأ في حفظ البيانات - " . $e->getMessage();
                        $skipped++;
                    }
                }
                
                fclose($handle);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'خطأ في قراءة الملف: ' . $e->getMessage());
        }

        $message = "تم استيراد {$imported} شركة بنجاح.";
        if ($skipped > 0) {
            $message .= " تم تخطي {$skipped} صف.";
        }

        if (!empty($errors)) {
            return back()->with('warning', $message)->with('import_errors', $errors);
        }

        return redirect()->route('tenant.inventory.regulatory.companies.index')
            ->with('success', $message);
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
