<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\LaboratoryTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaboratoryTestController extends Controller
{
    /**
     * Display a listing of the tests
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $tests = LaboratoryTest::where('tenant_id', $tenantId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $counts = [
            'total' => LaboratoryTest::where('tenant_id', $tenantId)->count(),
            'in_progress' => LaboratoryTest::where('tenant_id', $tenantId)->where('status', 'in_progress')->count(),
            'completed' => LaboratoryTest::where('tenant_id', $tenantId)->whereIn('status', ['completed','passed'])->count(),
            'overdue' => LaboratoryTest::where('tenant_id', $tenantId)->overdue()->count(),
        ];

        return view('tenant.regulatory.laboratory-tests.index', compact('tests', 'counts'));
    }

    /**
     * Show the form for creating a new test
     */
    public function create()
    {
        return view('tenant.regulatory.laboratory-tests.create');
    }

    /**
     * Store a newly created test
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'test_name' => 'required|string|max:255',
            'test_type' => 'required|in:quality_control,stability,microbiological,chemical,physical,bioequivalence',
            'product_name' => 'required|string|max:255',
            'batch_number' => 'required|string|max:100',
            'laboratory_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:test_date',
            'test_status' => 'required|in:pending,in_progress,completed,failed,cancelled',
            'test_method' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'results' => 'nullable|string',
            'conclusion' => 'nullable|string',
            'technician_name' => 'nullable|string|max:255',
            'supervisor_name' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            LaboratoryTest::create([
                'tenant_id' => Auth::user()->tenant_id,
                'test_name' => $request->get('test_name'),
                'test_type' => $request->get('test_type'),
                'product_name' => $request->get('product_name'),
                'drug_name' => $request->get('product_name'),
                'batch_number' => $request->get('batch_number'),
                'laboratory_name' => $request->get('laboratory_name'),
                'test_date' => $request->get('test_date'),
                'completion_date' => $request->get('completion_date') ?: null,
                'status' => $this->mapDbStatus($request->get('test_status')),
                'test_method' => $request->get('test_method'),
                'specifications' => $request->get('specifications'),
                'results' => $request->get('results'),
                'conclusion' => $request->get('conclusion'),
                'technician_name' => $request->get('technician_name'),
                'supervisor_name' => $request->get('supervisor_name'),
                'manufacturer' => $request->get('laboratory_name') ?: $request->get('product_name'),
                'cost' => $request->get('cost'),
                'notes' => $request->get('notes')
            ]);

            return redirect()->route('tenant.inventory.regulatory.laboratory-tests.index')
                ->with('success', 'تم إضافة الفحص المخبري بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حفظ الفحص: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display overdue tests
     */
    public function overdue()
    {
        $tests = LaboratoryTest::where('tenant_id', Auth::user()->tenant_id)
            ->overdue()
            ->orderBy('test_date', 'asc')
            ->get();

        return view('tenant.regulatory.laboratory-tests.overdue', compact('tests'));
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('tenant.regulatory.laboratory-tests.import');
    }

    /**
     * Import tests from Excel/CSV
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
                        $errors[] = "الصف {$rowNumber}: اسم الفحص واسم المنتج ورقم الدفعة مطلوبة";
                        $skipped++;
                        continue;
                    }

                    try {
                        LaboratoryTest::create([
                            'tenant_id' => Auth::user()->tenant_id,
                            'test_name' => $data[0] ?? '',
                            'test_type' => $this->mapTestType($data[1] ?? ''),
                            'product_name' => $data[2] ?? '',
                            'drug_name' => $data[2] ?? '',
                            'batch_number' => $data[3] ?? '',
                            'laboratory_name' => $data[4] ?? '',
                            'test_date' => $this->parseDate($data[5] ?? ''),
                            'completion_date' => $this->parseDate($data[6] ?? ''),
                            'status' => $this->mapDbStatus($this->mapTestStatus($data[7] ?? '')),
                            'test_method' => $data[8] ?? '',
                            'specifications' => $data[9] ?? '',
                            'results' => $data[10] ?? '',
                            'conclusion' => $data[11] ?? '',
                            'technician_name' => $data[12] ?? '',
                            'supervisor_name' => $data[13] ?? '',
                            'manufacturer' => $data[4] ?? ($data[2] ?? null),
                            'cost' => is_numeric($data[14] ?? '') ? $data[14] : null,
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

        $message = "تم استيراد {$imported} فحص بنجاح.";
        if ($skipped > 0) {
            $message .= " تم تخطي {$skipped} صف.";
        }

        if (!empty($errors)) {
            return back()->with('warning', $message)->with('import_errors', $errors);
        }

        return redirect()->route('tenant.inventory.regulatory.laboratory-tests.index')
            ->with('success', $message);
    }

    /**
     * Export tests to Excel
     */
    public function exportToExcel()
    {
        $tests = LaboratoryTest::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laboratory_tests_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function() use ($tests) {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($handle, [
                'اسم الفحص',
                'نوع الفحص',
                'اسم المنتج',
                'رقم الدفعة',
                'اسم المختبر',
                'تاريخ الفحص',
                'تاريخ الإنجاز',
                'الحالة',
                'طريقة الفحص',
                'المواصفات',
                'النتائج',
                'الخلاصة',
                'اسم الفني',
                'اسم المشرف',
                'التكلفة',
                'ملاحظات',
                'تاريخ الإنشاء'
            ]);

            // Data rows
            foreach ($tests as $test) {
                fputcsv($handle, [
                    $test->test_name,
                    $this->getTestTypeLabel($test->test_type),
                    $test->product_name,
                    $test->batch_number,
                    $test->laboratory_name,
                    $test->test_date,
                    $test->completion_date,
                    $this->getTestStatusLabel($test->test_status),
                    $test->test_method,
                    $test->specifications,
                    $test->results,
                    $test->conclusion,
                    $test->technician_name,
                    $test->supervisor_name,
                    $test->cost,
                    $test->notes,
                    $test->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Download sample Excel template
     */
    public function downloadTemplate()
    {
        $filename = 'laboratory_tests_template.csv';

        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($handle, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($handle, [
                'اسم الفحص',
                'نوع الفحص',
                'اسم المنتج',
                'رقم الدفعة',
                'اسم المختبر',
                'تاريخ الفحص',
                'تاريخ الإنجاز',
                'حالة الفحص',
                'طريقة الفحص',
                'المواصفات',
                'النتائج',
                'الخلاصة',
                'اسم الفني',
                'اسم المشرف',
                'التكلفة',
                'ملاحظات'
            ]);

            // Sample data
            fputcsv($handle, [
                'فحص جودة الأقراص',
                'مراقبة الجودة',
                'باراسيتامول 500 مجم',
                'BATCH001',
                'مختبر الجودة المركزي',
                '2024-01-15',
                '2024-01-20',
                'مكتمل',
                'USP Method',
                'وزن القرص: 500±25 مجم، التفكك: أقل من 15 دقيقة',
                'وزن القرص: 498 مجم، التفكك: 12 دقيقة',
                'مطابق للمواصفات',
                'أحمد محمد',
                'د. سارة علي',
                '150.00',
                'فحص روتيني للجودة'
            ]);

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Show schedule form
     */
    public function showSchedule()
    {
        return view('tenant.regulatory.laboratory-tests.schedule');
    }

    /**
     * Helper methods
     */
    private function getTestTypeLabel($type)
    {
        $types = [
            'quality_control' => 'مراقبة الجودة',
            'stability' => 'اختبار الثبات',
            'microbiological' => 'فحص ميكروبيولوجي',
            'chemical' => 'فحص كيميائي',
            'physical' => 'فحص فيزيائي',
            'bioequivalence' => 'التكافؤ الحيوي'
        ];
        
        return $types[$type] ?? $type;
    }

    private function getTestStatusLabel($status)
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'failed' => 'فاشل',
            'cancelled' => 'ملغي'
        ];
        
        return $statuses[$status] ?? $status;
    }

    private function mapTestType($label)
    {
        $types = [
            'مراقبة الجودة' => 'quality_control',
            'اختبار الثبات' => 'stability',
            'فحص ميكروبيولوجي' => 'microbiological',
            'فحص كيميائي' => 'chemical',
            'فحص فيزيائي' => 'physical',
            'التكافؤ الحيوي' => 'bioequivalence'
        ];
        
        return $types[$label] ?? 'quality_control';
    }

    private function mapTestStatus($label)
    {
        $statuses = [
            'في الانتظار' => 'pending',
            'قيد التنفيذ' => 'in_progress',
            'مكتمل' => 'completed',
            'فاشل' => 'failed',
            'ملغي' => 'cancelled'
        ];
        
        return $statuses[$label] ?? 'pending';
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
