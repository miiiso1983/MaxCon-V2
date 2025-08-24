<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportExecution;
use App\Services\ReportService;
use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    protected $reportService;
    protected $exportService;

    public function __construct(ReportService $reportService, ReportExportService $exportService)
    {
        $this->reportService = $reportService;
        $this->exportService = $exportService;
    }

    /**
     * Display the reports dashboard
     */
    public function index(): View
    {
        $reports = Report::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $recentExecutions = ReportExecution::with(['report', 'user'])
            ->where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $predefinedReports = $this->reportService->getPredefinedReports();

        return view('tenant.reports.index', compact('reports', 'recentExecutions', 'predefinedReports'));

    }

    /**
     * History page (Blade view)
     */
    public function historyPage(): View
    {
        return view('tenant.reports.history');
    }

    /**
     * Execute a report
     */
    public function execute(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'parameters' => 'array',
            'format' => 'string|in:html,pdf,excel,csv'
        ]);

        $parameters = $request->get('parameters', []);
        $format = $request->get('format', 'html');

        $result = $this->reportService->executeReport($report, $parameters, $format);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'metadata' => $result['metadata'],
                'execution_id' => $result['execution']->id,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }
    }

    /**
     * Generate a predefined report
     */
    public function generate(Request $request, string $reportType)
    {
        // Decode URL-encoded Arabic text
        $reportType = urldecode($reportType);

        // If GET request, show the report parameters form
        if ($request->isMethod('GET')) {
            return $this->showReportForm($reportType);
        }

        // If POST request, execute the report
        $request->validate([
            'parameters' => 'array',
            'format' => 'string|in:html,pdf,excel,csv'
        ]);

        // Create temporary report from predefined configuration
        $predefinedReports = $this->reportService->getPredefinedReports();
        $reportConfig = null;

        foreach ($predefinedReports as $category => $reports) {
            foreach ($reports as $config) {
                // Check multiple variations of the report name
                $reportName = $config['name'];

                // Create different variations to match
                $variations = [
                    $reportName, // Original name: "تقرير العملاء الأكثر شراءً"
                    str_replace('تقرير ', '', $reportName), // Without "تقرير ": "العملاء الأكثر شراءً"
                    str_replace(' ', '_', $reportName), // With underscores: "تقرير_العملاء_الأكثر_شراءً"
                    str_replace(' ', '_', str_replace('تقرير ', '', $reportName)), // Without "تقرير " and with underscores: "العملاء_الأكثر_شراءً"
                    str_replace(' ', '_', strtolower($reportName)), // Lowercase with underscores
                    strtolower($reportName), // Just lowercase
                ];

                if (in_array($reportType, $variations)) {
                    $reportConfig = $config;
                    break 2;
                }
            }
        }

        if (!$reportConfig) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'تقرير غير موجود: ' . $reportType
                ], 404);
            }
            abort(404, 'تقرير غير موجود: ' . $reportType);
        }

        // Create temporary report instance
        $report = new Report($reportConfig);
        $report->id = 0; // Temporary ID

        $parameters = $request->get('parameters', []);
        $format = $request->get('format', 'html');

        $result = $this->reportService->executeReport($report, $parameters, $format);

        if ($result['success']) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $result['data'],
                    'metadata' => $result['metadata'],
                    'report_name' => $reportConfig['name'],
                ]);
            } else {
                // Return view with results
                return view('tenant.reports.results', [
                    'report' => $report,
                    'data' => $result['data'],
                    'metadata' => $result['metadata'],
                    'execution' => $result['execution']
                ]);
            }
        } else {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'],
                ], 400);
            } else {
                return back()->withErrors(['error' => $result['error']]);
            }
        }
    }

    /**
     * Show report parameters form
     */
    protected function showReportForm(string $reportType)
    {
        $predefinedReports = $this->reportService->getPredefinedReports();
        $reportConfig = null;

        foreach ($predefinedReports as $category => $reports) {
            foreach ($reports as $config) {
                // Check multiple variations of the report name
                $reportName = $config['name'];

                // Create different variations to match
                $variations = [
                    $reportName, // Original name: "تقرير العملاء الأكثر شراءً"
                    str_replace('تقرير ', '', $reportName), // Without "تقرير ": "العملاء الأكثر شراءً"
                    str_replace(' ', '_', $reportName), // With underscores: "تقرير_العملاء_الأكثر_شراءً"
                    str_replace(' ', '_', str_replace('تقرير ', '', $reportName)), // Without "تقرير " and with underscores: "العملاء_الأكثر_شراءً"
                    str_replace(' ', '_', strtolower($reportName)), // Lowercase with underscores
                    strtolower($reportName), // Just lowercase
                ];

                if (in_array($reportType, $variations)) {
                    $reportConfig = $config;
                    break 2;
                }
            }
        }

        if (!$reportConfig) {
            abort(404, 'تقرير غير موجود: ' . $reportType);
        }

        return view('tenant.reports.form', [
            'reportConfig' => $reportConfig,
            'reportType' => $reportType
        ]);
    }

    /**
     * Export a report execution
     */
    public function export(Request $request, ReportExecution $execution): JsonResponse
    {
        $request->validate([
            'format' => 'required|string|in:pdf,excel,csv,html'
        ]);

        $format = $request->get('format');

        try {
            $result = $this->exportService->export($execution, $format);

            return response()->json([
                'success' => true,
                'message' => "تم تصدير التقرير بصيغة: {$format}",
                'download_url' => $result['download_url'],
                'filename' => $result['filename'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Download exported report
     */
    public function download(ReportExecution $execution)
    {
        if (!$execution->file_path || !Storage::exists($execution->file_path)) {
            abort(404, 'ملف التقرير غير موجود');
        }

        return Storage::download($execution->file_path);
    }

    /**
     * Send report by email
     */
    public function sendEmail(Request $request, ReportExecution $execution): JsonResponse
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'format' => 'string|in:pdf,excel,csv'
        ]);

        $recipients = $request->get('recipients');
        $format = $request->get('format', 'pdf');

        $result = $this->exportService->sendByEmail($execution, $recipients, $format);

        return response()->json($result);
    }

    /**
     * Generate print version
     */
    public function print(ReportExecution $execution)
    {
        $printHtml = $this->exportService->generatePrintVersion($execution);

        return response($printHtml)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Get report builder interface
     */
    public function builder(): View
    {
        $categories = Report::getCategories();
        $types = Report::getTypes();

        return view('tenant.reports.builder', compact('categories', 'types'));
    }

    /**
     * Create custom report
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:' . implode(',', array_keys(Report::getTypes())),
            'category' => 'required|string|in:' . implode(',', array_keys(Report::getCategories())),
            'query_builder' => 'required|array',
            'columns' => 'required|array',
            'filters' => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $report = Report::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'category' => $request->category,
            'query_builder' => $request->query_builder,
            'columns' => $request->columns,
            'filters' => $request->filters ?? [],
            'settings' => $request->settings ?? [],
            'created_by' => auth()->id(),
            'tenant_id' => session('tenant_id'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء التقرير بنجاح',
            'report' => $report,
        ]);
    }

    /**
     * Get execution history
     */
    public function history(): JsonResponse
    {
        $tenantId = auth()->user()->tenant_id
            ?? (app()->has('tenant') ? (app('tenant')->id ?? null) : null)
            ?? session('tenant_id');

        $query = ReportExecution::with(['report', 'user'])->latest();

        if ($tenantId) {
            $query->whereHas('report', function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            });
        }

        if (request()->filled('status') && in_array(request('status'), [
            ReportExecution::STATUS_PENDING,
            ReportExecution::STATUS_RUNNING,
            ReportExecution::STATUS_COMPLETED,
            ReportExecution::STATUS_FAILED,
            ReportExecution::STATUS_CANCELLED,
        ], true)) {
            $query->where('status', request('status'));
        }

        $executions = $query->paginate(20);

        return response()->json($executions);
    }
}
