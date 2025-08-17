<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InspectionController extends Controller
{
    /**
     * Display a listing of inspections
     */
    public function index()
    {
        $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tenant.regulatory.inspections.index', compact('inspections'));
    }

    /**
     * Show the form for creating a new inspection
     */
    public function create()
    {
        return view('tenant.regulatory.inspections.create');
    }

    /**
     * Store a newly created inspection
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inspection_title' => 'required|string|max:255',
            'inspection_type' => 'required|in:routine,complaint,follow_up,pre_approval,post_market',
            'inspector_name' => 'required|string|max:255',
            'inspection_authority' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:scheduled_date',
            'inspection_status' => 'required|in:scheduled,in_progress,completed,cancelled,postponed',
            'facility_name' => 'required|string|max:255',
            'facility_address' => 'required|string',
            'scope_of_inspection' => 'nullable|string',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'compliance_rating' => 'nullable|in:excellent,good,satisfactory,needs_improvement,non_compliant',
            'follow_up_required' => 'boolean',
            'follow_up_date' => 'nullable|date|after:completion_date',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'فشل التحقق من صحة البيانات. يرجى مراجعة الحقول المطلوبة.');
        }

        try {
            // Canonical payload
            $canonical = [
                'tenant_id' => Auth::user()->tenant_id,
                'inspection_title' => $request->input('inspection_title'),
                'inspection_type' => $request->input('inspection_type'),
                'inspector_name' => $request->input('inspector_name'),
                'inspection_authority' => $request->input('inspection_authority'),
                'scheduled_date' => $request->input('scheduled_date'),
                'completion_date' => $request->input('completion_date'),
                'inspection_status' => $request->input('inspection_status'),
                'facility_name' => $request->input('facility_name'),
                'facility_address' => $request->input('facility_address'),
                'scope_of_inspection' => $request->input('scope_of_inspection'),
                'findings' => $request->input('findings'),
                'recommendations' => $request->input('recommendations'),
                'compliance_rating' => $request->input('compliance_rating'),
                'follow_up_required' => $request->has('follow_up_required') ? 1 : 0,
                'follow_up_date' => $request->input('follow_up_date'),
                'notes' => $request->input('notes'),
            ];

            // Map to existing DB columns and collect skipped fields
            [$data, $skipped] = $this->mapToExistingInspectionColumns($canonical);

            // Ensure required columns present in production schema
            if (Schema::hasColumn('inspections', 'inspection_number') && empty($data['inspection_number'])) {
                $data['inspection_number'] = $this->generateInspectionNumber(Auth::user()->tenant_id);
            }

            Inspection::create($data);

            $response = redirect()->route('tenant.inventory.regulatory.inspections.index')
                ->with('success', 'تم إضافة التفتيش بنجاح');

            if (!empty($skipped)) {
                $response->with('warning', 'تم الحفظ، لكن تم تخطي الحقول التالية لعدم وجود أعمدة مطابقة في قاعدة البيانات: ' . implode(', ', $skipped));
            }

            return $response;

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حفظ التفتيش: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display overdue inspections
     */
    public function overdue()
    {
        try {
            $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
                ->overdue()
                ->orderBy('scheduled_date', 'asc')
                ->get();
        } catch (\Exception $e) {
            // Fallback: if scheduled_date column missing, approximate overdue by created_at older than today and not completed/cancelled
            $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
                ->whereNotIn('inspection_status', ['completed','cancelled'])
                ->whereDate('created_at', '<', now()->toDateString())
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('tenant.regulatory.inspections.overdue', compact('inspections'));
    }

    /**
     * Display the specified inspection
     */
    public function show($id)
    {
        $inspection = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        return view('tenant.regulatory.inspections.show', compact('inspection'));
    }

    /**
     * Show the form for editing the specified inspection
     */
    public function edit($id)
    {
        $inspection = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        return view('tenant.regulatory.inspections.edit', compact('inspection'));
    }

    /**
     * Update the specified inspection in storage
     */
    public function update(Request $request, $id)
    {
        $inspection = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'inspection_title' => 'required|string|max:255',
            'inspection_type' => 'required|in:routine,complaint,follow_up,pre_approval,post_market',
            'inspector_name' => 'required|string|max:255',
            'inspection_authority' => 'required|string|max:255',
            'scheduled_date' => 'required|date',
            'completion_date' => 'nullable|date|after_or_equal:scheduled_date',
            'inspection_status' => 'required|in:scheduled,in_progress,completed,cancelled,postponed',
            'facility_name' => 'required|string|max:255',
            'facility_address' => 'required|string',
            'scope_of_inspection' => 'nullable|string',
            'findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'compliance_rating' => 'nullable|in:excellent,good,satisfactory,needs_improvement,non_compliant',
            'follow_up_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $inspection->update([
                'inspection_title' => $request->input('inspection_title'),
                'inspection_type' => $request->input('inspection_type'),
                'inspector_name' => $request->input('inspector_name'),
                'inspection_authority' => $request->input('inspection_authority'),
                'scheduled_date' => $request->input('scheduled_date'),
                'completion_date' => $request->input('completion_date'),
                'inspection_status' => $request->input('inspection_status'),
                'facility_name' => $request->input('facility_name'),
                'facility_address' => $request->input('facility_address'),
                'scope_of_inspection' => $request->input('scope_of_inspection'),
                'findings' => $request->input('findings'),
                'recommendations' => $request->input('recommendations'),
                'compliance_rating' => $request->input('compliance_rating'),
                'follow_up_required' => $request->has('follow_up_required'),
                'follow_up_date' => $request->input('follow_up_date'),
                'notes' => $request->input('notes')
            ]);

            return redirect()->route('tenant.inventory.regulatory.inspections.index')
                ->with('success', 'تم تحديث التفتيش بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث التفتيش: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified inspection from storage
     */
    public function destroy($id)
    {
        $inspection = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->where('id', $id)
            ->firstOrFail();

        try {
            $inspection->delete();

            return redirect()->route('tenant.inventory.regulatory.inspections.index')
                ->with('success', 'تم حذف التفتيش بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف التفتيش: ' . $e->getMessage());
        }
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('tenant.regulatory.inspections.import');
    }

    /**
     * Import inspections from Excel/CSV
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
                $header = fgetcsv($handle);
                
                $rowNumber = 1;
                while (($data = fgetcsv($handle)) !== FALSE) {
                    $rowNumber++;
                    
                    if (empty(array_filter($data))) {
                        continue;
                    }

                    if (empty($data[0]) || empty($data[2]) || empty($data[3])) {
                        $errors[] = "الصف {$rowNumber}: عنوان التفتيش واسم المفتش والجهة مطلوبة";
                        $skipped++;
                        continue;
                    }

                    try {
                        Inspection::create([
                            'id' => Str::uuid(),
                            'tenant_id' => Auth::user()->tenant_id,
                            'inspection_title' => $data[0] ?? '',
                            'inspection_type' => $this->mapInspectionType($data[1] ?? ''),
                            'inspector_name' => $data[2] ?? '',
                            'inspection_authority' => $data[3] ?? '',
                            'scheduled_date' => $this->parseDate($data[4] ?? ''),
                            'completion_date' => $this->parseDate($data[5] ?? ''),
                            'inspection_status' => $this->mapInspectionStatus($data[6] ?? ''),
                            'facility_name' => $data[7] ?? '',
                            'facility_address' => $data[8] ?? '',
                            'scope_of_inspection' => $data[9] ?? '',
                            'findings' => $data[10] ?? '',
                            'recommendations' => $data[11] ?? '',
                            'compliance_rating' => $this->mapComplianceRating($data[12] ?? ''),
                            'follow_up_required' => strtolower($data[13] ?? '') === 'نعم',
                            'follow_up_date' => $this->parseDate($data[14] ?? ''),
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

        $message = "تم استيراد {$imported} تفتيش بنجاح.";
        if ($skipped > 0) {
            $message .= " تم تخطي {$skipped} صف.";
        }

        if (!empty($errors)) {
            return back()->with('warning', $message)->with('import_errors', $errors);
        }

        return redirect()->route('tenant.inventory.regulatory.inspections.index')
            ->with('success', $message);
    }

    /**
     * Export inspections to Excel
     */
    public function exportToExcel()
    {
        $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'inspections_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function() use ($inspections) {
            $handle = fopen('php://output', 'w');
            
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, [
                'عنوان التفتيش',
                'نوع التفتيش',
                'اسم المفتش',
                'الجهة المفتشة',
                'التاريخ المجدول',
                'تاريخ الإنجاز',
                'حالة التفتيش',
                'اسم المنشأة',
                'عنوان المنشأة',
                'نطاق التفتيش',
                'النتائج',
                'التوصيات',
                'تقييم الامتثال',
                'متابعة مطلوبة',
                'تاريخ المتابعة',
                'ملاحظات',
                'تاريخ الإنشاء'
            ]);

            foreach ($inspections as $inspection) {
                fputcsv($handle, [
                    $inspection->inspection_title,
                    $this->getInspectionTypeLabel($inspection->inspection_type),
                    $inspection->inspector_name,
                    $inspection->inspection_authority,
                    ($inspection->scheduled_date ?? $inspection->created_at)?->format('Y-m-d'),
                    $inspection->completion_date?->format('Y-m-d'),
                    $this->getInspectionStatusLabel($inspection->inspection_status),
                    $inspection->facility_name,
                    $inspection->facility_address,
                    $inspection->scope_of_inspection,
                    $inspection->findings,
                    $inspection->recommendations,
                    $this->getComplianceRatingLabel($inspection->compliance_rating),
                    $inspection->follow_up_required ? 'نعم' : 'لا',
                    $inspection->follow_up_date,
                    $inspection->notes,
                    $inspection->created_at->format('Y-m-d H:i:s')
                ]);
            }

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
        return view('tenant.regulatory.inspections.schedule');
    }

    /**
     * Map canonical inspection fields to existing DB columns dynamically
     */
    private function mapToExistingInspectionColumns(array $canonical): array
    {
        $columns = Schema::getColumnListing('inspections');

        $candidates = [
            'tenant_id' => ['tenant_id'],
            'inspection_title' => ['inspection_title','title','name','subject'],
            'inspection_type' => ['inspection_type','type'],
            'inspector_name' => ['inspector_name','inspector'],
            'inspection_authority' => ['inspection_authority','authority'],
            'scheduled_date' => ['scheduled_date','scheduled_at','inspection_date','date'],
            'completion_date' => ['completion_date','completed_at','completion_at','finished_at'],
            'inspection_status' => ['inspection_status','status'],
            'facility_name' => ['facility_name','facility','company_name','organization'],
            'facility_address' => ['facility_address','address','location'],
            'scope_of_inspection' => ['scope_of_inspection','inspection_scope','scope'],
            'findings' => ['findings','results','observations'],
            'recommendations' => ['recommendations','actions','action_items'],
            'compliance_rating' => ['compliance_rating','compliance','rating'],
            'follow_up_required' => ['follow_up_required','follow_up','needs_follow_up','followup_required'],
            'follow_up_date' => ['follow_up_date','followup_date'],
            'notes' => ['notes','remarks'],
        ];

        $data = [];
        $skipped = [];

        foreach ($canonical as $key => $value) {
            $foundColumn = null;
            foreach ($candidates[$key] ?? [$key] as $col) {
                if (in_array($col, $columns, true)) { $foundColumn = $col; break; }
            }
            if ($foundColumn !== null) {
                // Format dates to Y-m-d if input is date string
                if (in_array($key, ['scheduled_date','completion_date','follow_up_date'], true) && !empty($value)) {
                    $value = date('Y-m-d', strtotime((string)$value));
                }
                $data[$foundColumn] = $value;
            } else {
                $skipped[] = $key;
            }
        }

        return [$data, $skipped];
    }

    /**
     * Generate inspection_number when DB requires it
     */
    private function generateInspectionNumber($tenantId): string
    {
        // INSP-<tenant>-<YYYYMMDD>-<RANDOM>
        return 'INSP-' . $tenantId . '-' . date('Ymd') . '-' . strtoupper(Str::random(5));
    }

    /**
     * Show calendar view
     */
    public function showCalendar()
    {
        // Get inspections for calendar view with fallback if scheduled_date column doesn't exist
        try {
            $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
                ->whereNotNull('scheduled_date')
                ->orderBy('scheduled_date', 'asc')
                ->get();
        } catch (\Exception $e) {
            // Fallback: use created_at if scheduled_date is not available in production schema
            $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // If no inspections exist, create some sample data for demonstration
        if ($inspections->isEmpty()) {
            $sampleEvents = [
                [
                    'id' => 'sample-1',
                    'title' => 'تفتيش دوري للجودة - مصنع الأدوية المتقدمة',
                    'start' => now()->addDays(3)->format('Y-m-d'),
                    'backgroundColor' => '#ed8936',
                    'borderColor' => '#ed8936',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => 'د. أحمد محمد',
                        'authority' => 'وزارة الصحة العراقية',
                        'facility' => 'مصنع الأدوية المتقدمة',
                        'type' => 'روتيني',
                        'status' => 'مجدول',
                        'compliance' => null,
                    ]
                ],
                [
                    'id' => 'sample-2',
                    'title' => 'تفتيش شكوى - مختبر التحاليل الطبية',
                    'start' => now()->addDays(7)->format('Y-m-d'),
                    'backgroundColor' => '#4299e1',
                    'borderColor' => '#4299e1',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => 'د. فاطمة علي',
                        'authority' => 'وزارة الصحة العراقية',
                        'facility' => 'مختبر التحاليل الطبية',
                        'type' => 'شكوى',
                        'status' => 'قيد التنفيذ',
                        'compliance' => null,
                    ]
                ],
                [
                    'id' => 'sample-3',
                    'title' => 'تفتيش متابعة - شركة الأدوية الوطنية',
                    'start' => now()->subDays(5)->format('Y-m-d'),
                    'end' => now()->subDays(3)->format('Y-m-d'),
                    'backgroundColor' => '#48bb78',
                    'borderColor' => '#48bb78',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => 'د. محمد حسن',
                        'authority' => 'وزارة الصحة العراقية',
                        'facility' => 'شركة الأدوية الوطنية',
                        'type' => 'متابعة',
                        'status' => 'مكتمل',
                        'compliance' => 'ممتاز',
                    ]
                ],
                [
                    'id' => 'sample-4',
                    'title' => 'تفتيش ما قبل الموافقة - مصنع اللقاحات',
                    'start' => now()->addDays(14)->format('Y-m-d'),
                    'backgroundColor' => '#ed8936',
                    'borderColor' => '#ed8936',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => 'د. سارة أحمد',
                        'authority' => 'وزارة الصحة العراقية',
                        'facility' => 'مصنع اللقاحات الحديث',
                        'type' => 'ما قبل الموافقة',
                        'status' => 'مجدول',
                        'compliance' => null,
                    ]
                ],
                [
                    'id' => 'sample-5',
                    'title' => 'تفتيش ما بعد التسويق - صيدلية المركز',
                    'start' => now()->addDays(21)->format('Y-m-d'),
                    'backgroundColor' => '#ed8936',
                    'borderColor' => '#ed8936',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => 'د. عمر خالد',
                        'authority' => 'وزارة الصحة العراقية',
                        'facility' => 'صيدلية المركز الطبي',
                        'type' => 'ما بعد التسويق',
                        'status' => 'مجدول',
                        'compliance' => null,
                    ]
                ]
            ];

            $events = collect($sampleEvents);
        } else {
            // Prepare calendar events from database
            $events = $inspections->map(function ($inspection) {
                // Use scheduled_date if available, else created_at
                $startDate = isset($inspection->scheduled_date) && $inspection->scheduled_date ? $inspection->scheduled_date : $inspection->created_at;
                $endDate = (isset($inspection->completion_date) && $inspection->completion_date) ? $inspection->completion_date : null;

                return [
                    'id' => $inspection->id,
                    'title' => $inspection->inspection_title ?? 'تفتيش',
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate ? $endDate->format('Y-m-d') : null,
                    'backgroundColor' => method_exists($inspection, 'getInspectionStatusColor') ? $inspection->getInspectionStatusColor() : '#4299e1',
                    'borderColor' => method_exists($inspection, 'getInspectionStatusColor') ? $inspection->getInspectionStatusColor() : '#4299e1',
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => $inspection->inspector_name ?? 'غير محدد',
                        'authority' => $inspection->inspection_authority ?? 'غير محدد',
                        'facility' => $inspection->facility_name ?? 'غير محدد',
                        'type' => method_exists($inspection, 'getInspectionTypeLabel') ? $inspection->getInspectionTypeLabel() : ($inspection->inspection_type ?? 'غير محدد'),
                        'status' => method_exists($inspection, 'getInspectionStatusLabel') ? $inspection->getInspectionStatusLabel() : ($inspection->inspection_status ?? 'غير محدد'),
                        'compliance' => (isset($inspection->compliance_rating) && $inspection->compliance_rating && method_exists($inspection, 'getComplianceRatingLabel')) ? $inspection->getComplianceRatingLabel() : null,
                    ]
                ];
            });
        }

        return view('tenant.regulatory.inspections.calendar', compact('events'));
    }

    /**
     * Download template
     */
    public function downloadTemplate()
    {
        $filename = 'inspections_template.csv';

        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, [
                'عنوان التفتيش',
                'نوع التفتيش',
                'اسم المفتش',
                'الجهة المفتشة',
                'التاريخ المجدول',
                'تاريخ الإنجاز',
                'حالة التفتيش',
                'اسم المنشأة',
                'عنوان المنشأة',
                'نطاق التفتيش',
                'النتائج',
                'التوصيات',
                'تقييم الامتثال',
                'متابعة مطلوبة',
                'تاريخ المتابعة',
                'ملاحظات'
            ]);

            fputcsv($handle, [
                'تفتيش دوري للجودة',
                'روتيني',
                'د. أحمد محمد',
                'وزارة الصحة العراقية',
                '2024-01-15',
                '2024-01-16',
                'مكتمل',
                'مصنع الأدوية المتقدمة',
                'بغداد - المنطقة الصناعية',
                'فحص خطوط الإنتاج ومراقبة الجودة',
                'جميع المعايير مطابقة للمواصفات',
                'الاستمرار في تطبيق إجراءات الجودة',
                'ممتاز',
                'لا',
                '',
                'تفتيش ناجح بدون ملاحظات'
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
    private function getInspectionTypeLabel($type)
    {
        $types = [
            'routine' => 'روتيني',
            'complaint' => 'شكوى',
            'follow_up' => 'متابعة',
            'pre_approval' => 'ما قبل الموافقة',
            'post_market' => 'ما بعد التسويق'
        ];
        
        return $types[$type] ?? $type;
    }

    private function getInspectionStatusLabel($status)
    {
        $statuses = [
            'scheduled' => 'مجدول',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            'postponed' => 'مؤجل'
        ];
        
        return $statuses[$status] ?? $status;
    }

    private function getComplianceRatingLabel($rating)
    {
        $ratings = [
            'excellent' => 'ممتاز',
            'good' => 'جيد',
            'satisfactory' => 'مرضي',
            'needs_improvement' => 'يحتاج تحسين',
            'non_compliant' => 'غير ملتزم'
        ];
        
        return $ratings[$rating] ?? $rating;
    }

    private function mapInspectionType($label)
    {
        $types = [
            'روتيني' => 'routine',
            'شكوى' => 'complaint',
            'متابعة' => 'follow_up',
            'ما قبل الموافقة' => 'pre_approval',
            'ما بعد التسويق' => 'post_market'
        ];
        
        return $types[$label] ?? 'routine';
    }

    private function mapInspectionStatus($label)
    {
        $statuses = [
            'مجدول' => 'scheduled',
            'قيد التنفيذ' => 'in_progress',
            'مكتمل' => 'completed',
            'ملغي' => 'cancelled',
            'مؤجل' => 'postponed'
        ];
        
        return $statuses[$label] ?? 'scheduled';
    }

    private function mapComplianceRating($label)
    {
        $ratings = [
            'ممتاز' => 'excellent',
            'جيد' => 'good',
            'مرضي' => 'satisfactory',
            'يحتاج تحسين' => 'needs_improvement',
            'غير ملتزم' => 'non_compliant'
        ];
        
        return $ratings[$label] ?? null;
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
