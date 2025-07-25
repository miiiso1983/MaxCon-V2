<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\Inspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            return back()->withErrors($validator)->withInput();
        }

        try {
            Inspection::create([
                'id' => Str::uuid(),
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
                'follow_up_required' => $request->has('follow_up_required'),
                'follow_up_date' => $request->input('follow_up_date'),
                'notes' => $request->input('notes')
            ]);

            return redirect()->route('tenant.inventory.regulatory.inspections.index')
                ->with('success', 'تم إضافة التفتيش بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حفظ التفتيش: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display overdue inspections
     */
    public function overdue()
    {
        $inspections = Inspection::where('tenant_id', Auth::user()->tenant_id)
            ->overdue()
            ->orderBy('scheduled_date', 'asc')
            ->get();

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
                    $inspection->scheduled_date,
                    $inspection->completion_date,
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
     * Show calendar view
     */
    public function showCalendar()
    {
        // Get inspections for calendar view
        $inspections = Inspection::where('tenant_id', tenant()->id)
            ->whereNotNull('scheduled_date')
            ->orderBy('scheduled_date', 'asc')
            ->get();

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
                return [
                    'id' => $inspection->id,
                    'title' => $inspection->inspection_title,
                    'start' => $inspection->scheduled_date->format('Y-m-d'),
                    'end' => $inspection->completion_date ? $inspection->completion_date->format('Y-m-d') : null,
                    'backgroundColor' => $inspection->getInspectionStatusColor(),
                    'borderColor' => $inspection->getInspectionStatusColor(),
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'inspector' => $inspection->inspector_name,
                        'authority' => $inspection->inspection_authority,
                        'facility' => $inspection->facility_name,
                        'type' => $inspection->getInspectionTypeLabel(),
                        'status' => $inspection->getInspectionStatusLabel(),
                        'compliance' => $inspection->compliance_rating ? $inspection->getComplianceRatingLabel() : null,
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
