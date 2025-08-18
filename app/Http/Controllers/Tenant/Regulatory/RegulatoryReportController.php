<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\RegulatoryReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RegulatoryReportController extends Controller
{
    private function mapToExistingReportColumns(array $canonical): array
    {
        $columns = Schema::getColumnListing('regulatory_reports');
        $candidates = [
            'tenant_id' => ['tenant_id'],
            'report_title' => ['report_title','title'],
            'report_type' => ['report_type','type'],
            'report_period' => ['report_period','period'],
            'submission_authority' => ['submission_authority','authority','regulatory_authority'],
            'due_date' => ['due_date','due_on'],
            'submission_date' => ['submission_date','submitted_on'],
            'report_status' => ['report_status','status'],
            'prepared_by' => ['prepared_by'],
            'reviewed_by' => ['reviewed_by'],
            'approved_by' => ['approved_by'],
            'report_summary' => ['report_summary','summary'],
            'key_findings' => ['key_findings','findings'],
            'recommendations' => ['recommendations'],
            'follow_up_actions' => ['follow_up_actions','action_items'],
            'regulatory_reference' => ['regulatory_reference','reference'],
            'priority_level' => ['priority_level','priority'],
            'notes' => ['notes','remarks'],
        ];

        $data = [];
        $skipped = [];
        foreach ($canonical as $key => $value) {
            $found = null;
            foreach ($candidates[$key] ?? [$key] as $col) {
                if (in_array($col, $columns, true)) { $found = $col; break; }
            }
            if ($found) {
                if (in_array($key, ['due_date','submission_date'], true) && !empty($value)) {
                    $value = date('Y-m-d', strtotime((string)$value));
                }
                $data[$found] = $value;
            } else {
                $skipped[] = $key;
            }
        }

        return [$data, $skipped];
    }

    private function generateReportNumber($tenantId): string
    {
        return 'RPT-' . $tenantId . '-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 5));
    }
    private function mapToExistingReportColumns(array $canonical): array
    {
        $columns = Schema::getColumnListing('regulatory_reports');
        $candidates = [
            'tenant_id' => ['tenant_id'],
            'report_title' => ['report_title','title'],
            'report_type' => ['report_type','type'],
            'report_period' => ['report_period','period'],
            'submission_authority' => ['submission_authority','authority','regulatory_authority'],
            'due_date' => ['due_date','deadline','due_on'],
            'submission_date' => ['submission_date','submitted_at'],
            'report_status' => ['report_status','status'],
            'prepared_by' => ['prepared_by'],
            'reviewed_by' => ['reviewed_by'],
            'approved_by' => ['approved_by'],
            'report_summary' => ['report_summary','summary','description'],
            'key_findings' => ['key_findings','findings'],
            'recommendations' => ['recommendations'],
            'follow_up_actions' => ['follow_up_actions','action_items'],
            'regulatory_reference' => ['regulatory_reference','reference'],
            'priority_level' => ['priority_level','priority'],
            'notes' => ['notes','remarks'],
        ];

        $data = [];
        $skipped = [];
        foreach ($canonical as $key => $value) {
            $found = null;
            foreach ($candidates[$key] ?? [$key] as $col) {
                if (in_array($col, $columns, true)) { $found = $col; break; }
            }
            if ($found) {
                if (in_array($key, ['due_date','submission_date'], true) && !empty($value)) {
                    $value = date('Y-m-d', strtotime((string)$value));
                }
                $data[$found] = $value;
            } else {
                $skipped[] = $key;
            }
        }

        return [$data, $skipped];
    }

    private function generateReportNumber($tenantId): string
    {
        return 'RPT-' . $tenantId . '-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 5));
    }
    /**
     * Display a listing of reports
     */
    public function index()
    {
        $reports = RegulatoryReport::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tenant.regulatory.reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report
     */
    public function create()
    {
        return view('tenant.regulatory.reports.create');
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_title' => 'required|string|max:255',
            'report_type' => 'required|in:periodic,incident,compliance,audit,inspection,adverse_event',
            'report_period' => 'nullable|in:monthly,quarterly,semi_annual,annual',
            'submission_authority' => 'required|string|max:255',
            'due_date' => 'required|date',
            'submission_date' => 'nullable|date',
            'report_status' => 'required|in:draft,pending_review,submitted,approved,rejected',
            'prepared_by' => 'required|string|max:255',
            'reviewed_by' => 'nullable|string|max:255',
            'approved_by' => 'nullable|string|max:255',
            'report_summary' => 'nullable|string',
            'key_findings' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'follow_up_actions' => 'nullable|string',
            'regulatory_reference' => 'nullable|string|max:255',
            'priority_level' => 'nullable|in:low,medium,high,critical',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', 'فشل التحقق من صحة البيانات. يرجى مراجعة الحقول المطلوبة.');
        }

        try {
            $canonical = [
                'tenant_id' => Auth::user()->tenant_id,
                'report_title' => $request->report_title,
                'report_type' => $request->report_type,
                'report_period' => $request->report_period,
                'submission_authority' => $request->submission_authority,
                'due_date' => $request->due_date,
                'submission_date' => $request->submission_date,
                'report_status' => $request->report_status,
                'prepared_by' => $request->prepared_by,
                'reviewed_by' => $request->reviewed_by,
                'approved_by' => $request->approved_by,
                'report_summary' => $request->report_summary,
                'key_findings' => $request->key_findings,
                'recommendations' => $request->recommendations,
                'follow_up_actions' => $request->follow_up_actions,
                'regulatory_reference' => $request->regulatory_reference,
                'priority_level' => $request->priority_level,
                'notes' => $request->notes,
            ];

            // Map to existing DB columns
            [$data, $skipped] = $this->mapToExistingReportColumns($canonical);

            // Ensure DB-required fields
            if (Schema::hasColumn('regulatory_reports', 'report_number') && empty($data['report_number'])) {
                $data['report_number'] = $this->generateReportNumber(Auth::user()->tenant_id);
            }
            if (Schema::hasColumn('regulatory_reports', 'status') && empty($data['status']) && isset($canonical['report_status'])) {
                $data['status'] = $canonical['report_status'];
            }
            if (Schema::hasColumn('regulatory_reports', 'priority') && empty($data['priority']) && isset($canonical['priority_level'])) {
                $data['priority'] = $canonical['priority_level'];
            }

            // Create
            RegulatoryReport::create($data);

            $response = redirect()->route('tenant.inventory.regulatory.reports.index')
                ->with('success', 'تم إضافة التقرير بنجاح');
            if (!empty($skipped)) {
                $response->with('warning', 'تم الحفظ، لكن تم تخطي الحقول التالية لعدم وجود أعمدة مطابقة في قاعدة البيانات: ' . implode(', ', $skipped));
            }
            return $response;

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حفظ التقرير: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display overdue reports
     */
    public function overdue()
    {
        $reports = RegulatoryReport::where('tenant_id', Auth::user()->tenant_id)
            ->overdue()
            ->orderBy('due_date', 'asc')
            ->get();

        return view('tenant.regulatory.reports.overdue', compact('reports'));
    }

    /**
     * Show import form
     */
    public function showImportForm()
    {
        return view('tenant.regulatory.reports.import');
    }

    /**
     * Import reports from Excel/CSV
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
                        $errors[] = "الصف {$rowNumber}: عنوان التقرير والجهة المقدمة وتاريخ الاستحقاق مطلوبة";
                        $skipped++;
                        continue;
                    }

                    try {
                        RegulatoryReport::create([
                            'id' => Str::uuid(),
                            'tenant_id' => Auth::user()->tenant_id,
                            'report_title' => $data[0] ?? '',
                            'report_type' => $this->mapReportType($data[1] ?? ''),
                            'report_period' => $this->mapReportPeriod($data[2] ?? ''),
                            'submission_authority' => $data[3] ?? '',
                            'due_date' => $this->parseDate($data[4] ?? ''),
                            'submission_date' => $this->parseDate($data[5] ?? ''),
                            'report_status' => $this->mapReportStatus($data[6] ?? ''),
                            'prepared_by' => $data[7] ?? '',
                            'reviewed_by' => $data[8] ?? '',
                            'approved_by' => $data[9] ?? '',
                            'report_summary' => $data[10] ?? '',
                            'key_findings' => $data[11] ?? '',
                            'recommendations' => $data[12] ?? '',
                            'follow_up_actions' => $data[13] ?? '',
                            'regulatory_reference' => $data[14] ?? '',
                            'priority_level' => $this->mapPriorityLevel($data[15] ?? ''),
                            'notes' => $data[16] ?? ''
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

        $message = "تم استيراد {$imported} تقرير بنجاح.";
        if ($skipped > 0) {
            $message .= " تم تخطي {$skipped} صف.";
        }

        if (!empty($errors)) {
            return back()->with('warning', $message)->with('import_errors', $errors);
        }

        return redirect()->route('tenant.inventory.regulatory.reports.index')
            ->with('success', $message);
    }

    /**
     * Export reports to Excel
     */
    public function exportToExcel()
    {
        $reports = RegulatoryReport::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'regulatory_reports_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function() use ($reports) {
            $handle = fopen('php://output', 'w');
            
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, [
                'عنوان التقرير',
                'نوع التقرير',
                'فترة التقرير',
                'الجهة المقدمة',
                'تاريخ الاستحقاق',
                'تاريخ التقديم',
                'حالة التقرير',
                'معد بواسطة',
                'مراجع بواسطة',
                'معتمد بواسطة',
                'ملخص التقرير',
                'النتائج الرئيسية',
                'التوصيات',
                'إجراءات المتابعة',
                'المرجع التنظيمي',
                'مستوى الأولوية',
                'ملاحظات',
                'تاريخ الإنشاء'
            ]);

            foreach ($reports as $report) {
                fputcsv($handle, [
                    $report->report_title,
                    $this->getReportTypeLabel($report->report_type),
                    $this->getReportPeriodLabel($report->report_period),
                    $report->submission_authority,
                    $report->due_date,
                    $report->submission_date,
                    $this->getReportStatusLabel($report->report_status),
                    $report->prepared_by,
                    $report->reviewed_by,
                    $report->approved_by,
                    $report->report_summary,
                    $report->key_findings,
                    $report->recommendations,
                    $report->follow_up_actions,
                    $report->regulatory_reference,
                    $this->getPriorityLevelLabel($report->priority_level),
                    $report->notes,
                    $report->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    /**
     * Show report templates
     */
    public function showTemplates()
    {
        // Define available report templates
        $templates = [
            [
                'id' => 'compliance_quarterly',
                'title' => 'تقرير الامتثال الربعي',
                'description' => 'قالب شامل لتقرير الامتثال التنظيمي الربعي',
                'category' => 'compliance',
                'frequency' => 'quarterly',
                'sections' => [
                    'ملخص تنفيذي',
                    'حالة الامتثال',
                    'المخالفات والإجراءات التصحيحية',
                    'التوصيات',
                    'الخطة المستقبلية'
                ],
                'icon' => 'fas fa-clipboard-check',
                'color' => '#4ecdc4'
            ],
            [
                'id' => 'incident_report',
                'title' => 'تقرير الحوادث',
                'description' => 'قالب لتوثيق الحوادث والأحداث الضارة',
                'category' => 'incident',
                'frequency' => 'as_needed',
                'sections' => [
                    'تفاصيل الحادث',
                    'تحليل الأسباب الجذرية',
                    'الإجراءات المتخذة',
                    'الإجراءات الوقائية',
                    'المتابعة المطلوبة'
                ],
                'icon' => 'fas fa-exclamation-triangle',
                'color' => '#f56565'
            ],
            [
                'id' => 'audit_report',
                'title' => 'تقرير التدقيق الداخلي',
                'description' => 'قالب لتقارير التدقيق الداخلي لنظام الجودة',
                'category' => 'audit',
                'frequency' => 'annual',
                'sections' => [
                    'نطاق التدقيق',
                    'منهجية التدقيق',
                    'النتائج والملاحظات',
                    'عدم المطابقات',
                    'خطة الإجراءات التصحيحية'
                ],
                'icon' => 'fas fa-search',
                'color' => '#9f7aea'
            ],
            [
                'id' => 'inspection_response',
                'title' => 'رد على تفتيش تنظيمي',
                'description' => 'قالب للرد على ملاحظات التفتيش التنظيمي',
                'category' => 'inspection',
                'frequency' => 'as_needed',
                'sections' => [
                    'ملخص التفتيش',
                    'الرد على الملاحظات',
                    'الإجراءات التصحيحية',
                    'الجدول الزمني للتنفيذ',
                    'الأدلة المرفقة'
                ],
                'icon' => 'fas fa-clipboard-list',
                'color' => '#ed8936'
            ],
            [
                'id' => 'adverse_event',
                'title' => 'تقرير الأحداث الضارة',
                'description' => 'قالب لتوثيق الأحداث الضارة المرتبطة بالمنتجات',
                'category' => 'safety',
                'frequency' => 'as_needed',
                'sections' => [
                    'معلومات المريض',
                    'تفاصيل المنتج',
                    'وصف الحدث',
                    'تقييم السببية',
                    'الإجراءات المتخذة'
                ],
                'icon' => 'fas fa-heartbeat',
                'color' => '#f093fb'
            ],
            [
                'id' => 'periodic_safety',
                'title' => 'تقرير السلامة الدوري',
                'description' => 'قالب لتقرير السلامة الدوري للمنتجات',
                'category' => 'safety',
                'frequency' => 'semi_annual',
                'sections' => [
                    'ملخص بيانات السلامة',
                    'تحليل المخاطر والفوائد',
                    'التحديثات التنظيمية',
                    'التوصيات',
                    'خطة إدارة المخاطر'
                ],
                'icon' => 'fas fa-shield-alt',
                'color' => '#48bb78'
            ]
        ];

        return view('tenant.regulatory.reports.templates', compact('templates'));
    }

    /**
     * Create report from template
     */
    public function createFromTemplate($templateId)
    {
        // Get template data
        $templates = [
            'compliance_quarterly' => [
                'report_title' => 'تقرير الامتثال الربعي - ' . now()->format('Y-m'),
                'report_type' => 'compliance',
                'report_period' => 'quarterly',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => now()->addDays(30)->format('Y-m-d'),
                'priority_level' => 'high',
                'report_summary' => 'تقرير شامل عن حالة الامتثال التنظيمي للربع الحالي',
                'key_findings' => 'سيتم تحديد النتائج الرئيسية بعد إجراء التقييم',
                'recommendations' => 'سيتم تحديد التوصيات بناءً على نتائج التقييم'
            ],
            'incident_report' => [
                'report_title' => 'تقرير حادث - ' . now()->format('Y-m-d'),
                'report_type' => 'incident',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'priority_level' => 'critical',
                'report_summary' => 'تقرير مفصل عن الحادث المسجل',
                'key_findings' => 'سيتم تحديد الأسباب الجذرية للحادث',
                'recommendations' => 'سيتم وضع إجراءات وقائية لمنع تكرار الحادث'
            ],
            'audit_report' => [
                'report_title' => 'تقرير التدقيق الداخلي - ' . now()->format('Y'),
                'report_type' => 'audit',
                'report_period' => 'annual',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => now()->addDays(45)->format('Y-m-d'),
                'priority_level' => 'high',
                'report_summary' => 'تقرير شامل عن نتائج التدقيق الداخلي لنظام الجودة',
                'key_findings' => 'سيتم تحديد نقاط القوة والضعف في النظام',
                'recommendations' => 'سيتم وضع خطة تحسين شاملة'
            ],
            'inspection_response' => [
                'report_title' => 'رد على تفتيش تنظيمي - ' . now()->format('Y-m-d'),
                'report_type' => 'inspection',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => now()->addDays(14)->format('Y-m-d'),
                'priority_level' => 'high',
                'report_summary' => 'رد مفصل على ملاحظات التفتيش التنظيمي',
                'key_findings' => 'سيتم الرد على جميع الملاحظات المطروحة',
                'recommendations' => 'سيتم تنفيذ الإجراءات التصحيحية المطلوبة'
            ],
            'adverse_event' => [
                'report_title' => 'تقرير حدث ضار - ' . now()->format('Y-m-d'),
                'report_type' => 'adverse_event',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'priority_level' => 'critical',
                'report_summary' => 'تقرير عن حدث ضار مرتبط بالمنتج',
                'key_findings' => 'سيتم تحليل السببية وتقييم المخاطر',
                'recommendations' => 'سيتم اتخاذ إجراءات فورية لضمان السلامة'
            ],
            'periodic_safety' => [
                'report_title' => 'تقرير السلامة الدوري - ' . now()->format('Y-m'),
                'report_type' => 'periodic',
                'report_period' => 'semi_annual',
                'submission_authority' => 'وزارة الصحة العراقية',
                'due_date' => now()->addDays(60)->format('Y-m-d'),
                'priority_level' => 'medium',
                'report_summary' => 'تقرير دوري عن سلامة المنتجات',
                'key_findings' => 'سيتم تحليل بيانات السلامة المجمعة',
                'recommendations' => 'سيتم تحديث خطة إدارة المخاطر حسب الحاجة'
            ]
        ];

        $templateData = $templates[$templateId] ?? [];

        // If template not found, redirect back with error
        if (empty($templateData)) {
            return redirect()->route('tenant.inventory.regulatory.reports.templates')
                           ->with('error', 'القالب المطلوب غير موجود');
        }

        return view('tenant.regulatory.reports.create', compact('templateData'));
    }

    /**
     * Download template
     */
    public function downloadTemplate()
    {
        $filename = 'regulatory_reports_template.csv';

        $response = new StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, [
                'عنوان التقرير',
                'نوع التقرير',
                'فترة التقرير',
                'الجهة المقدمة',
                'تاريخ الاستحقاق',
                'تاريخ التقديم',
                'حالة التقرير',
                'معد بواسطة',
                'مراجع بواسطة',
                'معتمد بواسطة',
                'ملخص التقرير',
                'النتائج الرئيسية',
                'التوصيات',
                'إجراءات المتابعة',
                'المرجع التنظيمي',
                'مستوى الأولوية',
                'ملاحظات'
            ]);

            fputcsv($handle, [
                'التقرير الربعي للامتثال',
                'دوري',
                'ربعي',
                'وزارة الصحة العراقية',
                '2024-04-30',
                '2024-04-25',
                'مقدم',
                'د. أحمد محمد',
                'د. سارة علي',
                'د. محمد حسن',
                'تقرير شامل عن حالة الامتثال للربع الأول',
                'جميع المعايير مطابقة للمواصفات',
                'الاستمرار في تطبيق إجراءات الجودة',
                'مراجعة دورية للإجراءات',
                'REG-2024-Q1',
                'متوسط',
                'تقرير مقدم في الموعد المحدد'
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
    private function getReportTypeLabel($type)
    {
        $types = [
            'periodic' => 'دوري',
            'incident' => 'حادث',
            'compliance' => 'امتثال',
            'audit' => 'تدقيق',
            'inspection' => 'تفتيش',
            'adverse_event' => 'حدث سلبي'
        ];
        
        return $types[$type] ?? $type;
    }

    private function getReportPeriodLabel($period)
    {
        $periods = [
            'monthly' => 'شهري',
            'quarterly' => 'ربعي',
            'semi_annual' => 'نصف سنوي',
            'annual' => 'سنوي'
        ];
        
        return $periods[$period] ?? $period;
    }

    private function getReportStatusLabel($status)
    {
        $statuses = [
            'draft' => 'مسودة',
            'pending_review' => 'في انتظار المراجعة',
            'submitted' => 'مقدم',
            'approved' => 'معتمد',
            'rejected' => 'مرفوض'
        ];
        
        return $statuses[$status] ?? $status;
    }

    private function getPriorityLevelLabel($level)
    {
        $levels = [
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'عالي',
            'critical' => 'حرج'
        ];
        
        return $levels[$level] ?? $level;
    }

    private function mapReportType($label)
    {
        $types = [
            'دوري' => 'periodic',
            'حادث' => 'incident',
            'امتثال' => 'compliance',
            'تدقيق' => 'audit',
            'تفتيش' => 'inspection',
            'حدث سلبي' => 'adverse_event'
        ];
        
        return $types[$label] ?? 'periodic';
    }

    private function mapReportPeriod($label)
    {
        $periods = [
            'شهري' => 'monthly',
            'ربعي' => 'quarterly',
            'نصف سنوي' => 'semi_annual',
            'سنوي' => 'annual'
        ];
        
        return $periods[$label] ?? null;
    }

    private function mapReportStatus($label)
    {
        $statuses = [
            'مسودة' => 'draft',
            'في انتظار المراجعة' => 'pending_review',
            'مقدم' => 'submitted',
            'معتمد' => 'approved',
            'مرفوض' => 'rejected'
        ];
        
        return $statuses[$label] ?? 'draft';
    }

    private function mapPriorityLevel($label)
    {
        $levels = [
            'منخفض' => 'low',
            'متوسط' => 'medium',
            'عالي' => 'high',
            'حرج' => 'critical'
        ];
        
        return $levels[$label] ?? null;
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
