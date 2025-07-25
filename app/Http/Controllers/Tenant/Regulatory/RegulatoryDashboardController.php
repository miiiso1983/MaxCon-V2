<?php

namespace App\Http\Controllers\Tenant\Regulatory;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Regulatory\CompanyRegistration;
use App\Models\Tenant\Regulatory\ProductRegistration;
use App\Models\Tenant\Regulatory\LaboratoryTest;
use App\Models\Tenant\Regulatory\RegulatoryInspection;
use App\Models\Tenant\Regulatory\QualityCertificate;
use App\Models\Tenant\Regulatory\ProductRecall;
use App\Models\Tenant\Regulatory\RegulatoryReport;
use App\Models\Tenant\Regulatory\RegulatoryDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class RegulatoryDashboardController extends Controller
{
    /**
     * Display the regulatory affairs dashboard
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        
        // Get overview statistics
        $stats = $this->getOverviewStats($tenantId);
        
        // Get alerts and notifications
        $alerts = $this->getAlerts($tenantId);
        
        // Get recent activities
        $recentActivities = $this->getRecentActivities($tenantId);
        
        // Get upcoming deadlines
        $upcomingDeadlines = $this->getUpcomingDeadlines($tenantId);
        
        // Get compliance metrics
        $complianceMetrics = $this->getComplianceMetrics($tenantId);
        
        // Get charts data
        $chartsData = $this->getChartsData($tenantId);
        
        return view('tenant.regulatory.dashboard', compact(
            'stats',
            'alerts',
            'recentActivities',
            'upcomingDeadlines',
            'complianceMetrics',
            'chartsData'
        ));
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats($tenantId)
    {
        return [
            'companies' => [
                'total' => CompanyRegistration::where('tenant_id', $tenantId)->count(),
                'active' => CompanyRegistration::where('tenant_id', $tenantId)->where('status', 'active')->count(),
                'expiring_soon' => CompanyRegistration::where('tenant_id', $tenantId)->expiringSoon()->count(),
                'expired' => CompanyRegistration::where('tenant_id', $tenantId)->expired()->count(),
            ],
            'products' => [
                'total' => ProductRegistration::where('tenant_id', $tenantId)->count(),
                'registered' => ProductRegistration::where('tenant_id', $tenantId)->where('status', 'registered')->count(),
                'pending' => ProductRegistration::where('tenant_id', $tenantId)->where('status', 'pending')->count(),
                'expiring_soon' => ProductRegistration::where('tenant_id', $tenantId)->expiringSoon()->count(),
            ],
            'laboratory_tests' => [
                'total' => LaboratoryTest::where('tenant_id', $tenantId)->count(),
                'pending' => LaboratoryTest::where('tenant_id', $tenantId)->pending()->count(),
                'completed' => LaboratoryTest::where('tenant_id', $tenantId)->completed()->count(),
                'overdue' => LaboratoryTest::where('tenant_id', $tenantId)->overdue()->count(),
            ],
            'inspections' => [
                'total' => RegulatoryInspection::where('tenant_id', $tenantId)->count(),
                'scheduled' => RegulatoryInspection::where('tenant_id', $tenantId)->where('status', 'scheduled')->count(),
                'completed' => RegulatoryInspection::where('tenant_id', $tenantId)->completed()->count(),
                'overdue' => RegulatoryInspection::where('tenant_id', $tenantId)->overdue()->count(),
            ],
            'certificates' => [
                'total' => QualityCertificate::where('tenant_id', $tenantId)->count(),
                'valid' => QualityCertificate::where('tenant_id', $tenantId)->valid()->count(),
                'expiring_soon' => QualityCertificate::where('tenant_id', $tenantId)->expiringSoon()->count(),
                'expired' => QualityCertificate::where('tenant_id', $tenantId)->expired()->count(),
            ],
            'recalls' => [
                'total' => ProductRecall::where('tenant_id', $tenantId)->count(),
                'active' => ProductRecall::where('tenant_id', $tenantId)->active()->count(),
                'high_priority' => ProductRecall::where('tenant_id', $tenantId)->highPriority()->count(),
                'completed' => ProductRecall::where('tenant_id', $tenantId)->completed()->count(),
            ],
            'reports' => [
                'total' => RegulatoryReport::where('tenant_id', $tenantId)->count(),
                'pending' => RegulatoryReport::where('tenant_id', $tenantId)->pending()->count(),
                'overdue' => RegulatoryReport::where('tenant_id', $tenantId)->overdue()->count(),
                'high_priority' => RegulatoryReport::where('tenant_id', $tenantId)->highPriority()->count(),
            ],
            'documents' => [
                'total' => RegulatoryDocument::where('tenant_id', $tenantId)->count(),
                'active' => RegulatoryDocument::where('tenant_id', $tenantId)->active()->count(),
                'expiring_soon' => RegulatoryDocument::where('tenant_id', $tenantId)->expiringSoon()->count(),
                'review_due' => RegulatoryDocument::where('tenant_id', $tenantId)->reviewDue()->count(),
            ]
        ];
    }

    /**
     * Get alerts and notifications
     */
    private function getAlerts($tenantId)
    {
        $alerts = [];

        // License expiry alerts
        $expiringLicenses = CompanyRegistration::where('tenant_id', $tenantId)
                                             ->expiringSoon()
                                             ->count();
        if ($expiringLicenses > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'تراخيص تنتهي قريباً',
                'message' => "يوجد {$expiringLicenses} ترخيص ينتهي خلال 30 يوماً",
                'url' => route('tenant.inventory.regulatory.companies.expiring-soon')
            ];
        }

        // Overdue inspections
        $overdueInspections = RegulatoryInspection::where('tenant_id', $tenantId)
                                                ->overdue()
                                                ->count();
        if ($overdueInspections > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-search',
                'title' => 'تفتيشات متأخرة',
                'message' => "يوجد {$overdueInspections} تفتيش متأخر",
                'url' => route('tenant.inventory.regulatory.inspections.overdue')
            ];
        }

        // Overdue laboratory tests
        $overdueTests = LaboratoryTest::where('tenant_id', $tenantId)
                                    ->overdue()
                                    ->count();
        if ($overdueTests > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-flask',
                'title' => 'فحوصات متأخرة',
                'message' => "يوجد {$overdueTests} فحص مخبري متأخر",
                'url' => route('tenant.inventory.regulatory.laboratory-tests.overdue')
            ];
        }

        // Active high priority recalls
        $highPriorityRecalls = ProductRecall::where('tenant_id', $tenantId)
                                          ->active()
                                          ->highPriority()
                                          ->count();
        if ($highPriorityRecalls > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'سحب منتجات عالي الأولوية',
                'message' => "يوجد {$highPriorityRecalls} عملية سحب منتجات عالية الأولوية",
                'url' => route('tenant.inventory.regulatory.recalls.high-priority')
            ];
        }

        // Overdue reports
        $overdueReports = RegulatoryReport::where('tenant_id', $tenantId)
                                        ->overdue()
                                        ->count();
        if ($overdueReports > 0) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-file-alt',
                'title' => 'تقارير متأخرة',
                'message' => "يوجد {$overdueReports} تقرير متأخر",
                'url' => route('tenant.inventory.regulatory.reports.overdue')
            ];
        }

        return $alerts;
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities($tenantId)
    {
        $activities = [];

        // Recent company registrations
        $recentCompanies = CompanyRegistration::where('tenant_id', $tenantId)
                                            ->latest()
                                            ->take(5)
                                            ->get();
        foreach ($recentCompanies as $company) {
            $activities[] = [
                'type' => 'company',
                'icon' => 'fas fa-building',
                'title' => 'تسجيل شركة جديدة',
                'description' => $company->company_name,
                'date' => $company->created_at,
                'url' => route('tenant.inventory.regulatory.companies.show', $company)
            ];
        }

        // Recent inspections
        $recentInspections = RegulatoryInspection::where('tenant_id', $tenantId)
                                               ->latest()
                                               ->take(5)
                                               ->get();
        foreach ($recentInspections as $inspection) {
            $activities[] = [
                'type' => 'inspection',
                'icon' => 'fas fa-search',
                'title' => 'تفتيش جديد',
                'description' => $inspection->inspection_type_name,
                'date' => $inspection->created_at,
                'url' => route('tenant.inventory.regulatory.inspections.show', $inspection)
            ];
        }

        // Sort by date and take latest 10
        usort($activities, function($a, $b) {
            return $b['date']->timestamp - $a['date']->timestamp;
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Get upcoming deadlines
     */
    private function getUpcomingDeadlines($tenantId)
    {
        $deadlines = [];

        // Upcoming inspections
        $upcomingInspections = RegulatoryInspection::where('tenant_id', $tenantId)
                                                 ->upcoming(30)
                                                 ->orderBy('scheduled_date')
                                                 ->take(5)
                                                 ->get();
        foreach ($upcomingInspections as $inspection) {
            $deadlines[] = [
                'type' => 'inspection',
                'icon' => 'fas fa-search',
                'title' => 'تفتيش مجدول',
                'description' => $inspection->company->company_name ?? 'غير محدد',
                'date' => $inspection->scheduled_date,
                'days_remaining' => Carbon::parse($inspection->scheduled_date)->diffInDays(now()),
                'url' => route('tenant.inventory.regulatory.inspections.show', $inspection)
            ];
        }

        // Expiring certificates
        $expiringCertificates = QualityCertificate::where('tenant_id', $tenantId)
                                                ->expiringSoon(30)
                                                ->orderBy('expiry_date')
                                                ->take(5)
                                                ->get();
        foreach ($expiringCertificates as $certificate) {
            $deadlines[] = [
                'type' => 'certificate',
                'icon' => 'fas fa-certificate',
                'title' => 'شهادة تنتهي',
                'description' => $certificate->certificate_type_name,
                'date' => $certificate->expiry_date,
                'days_remaining' => $certificate->expiry_date ? Carbon::parse($certificate->expiry_date)->diffInDays(now()) : 0,
                'url' => route('tenant.inventory.regulatory.certificates.show', $certificate)
            ];
        }

        // Sort by date
        usort($deadlines, function($a, $b) {
            return $a['date']->timestamp - $b['date']->timestamp;
        });

        return array_slice($deadlines, 0, 10);
    }

    /**
     * Get compliance metrics
     */
    private function getComplianceMetrics($tenantId)
    {
        $totalCompanies = CompanyRegistration::where('tenant_id', $tenantId)->count();
        $compliantCompanies = CompanyRegistration::where('tenant_id', $tenantId)
                                               ->where('compliance_status', 'compliant')
                                               ->count();

        $totalInspections = RegulatoryInspection::where('tenant_id', $tenantId)->count();

        // Check if overall_rating column exists before using it
        $passedInspections = 0;
        try {
            if (Schema::hasColumn('regulatory_inspections', 'overall_rating')) {
                $passedInspections = RegulatoryInspection::where('tenant_id', $tenantId)
                                                       ->whereIn('overall_rating', ['excellent', 'good', 'satisfactory'])
                                                       ->count();
            }
        } catch (\Exception $e) {
            // If column doesn't exist or query fails, default to 0
            $passedInspections = 0;
        }

        return [
            'company_compliance_rate' => $totalCompanies > 0 ? round(($compliantCompanies / $totalCompanies) * 100, 1) : 0,
            'inspection_pass_rate' => $totalInspections > 0 ? round(($passedInspections / $totalInspections) * 100, 1) : 0,
        ];
    }

    /**
     * Get charts data
     */
    private function getChartsData($tenantId)
    {
        // Monthly inspections data
        $monthlyInspections = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = RegulatoryInspection::where('tenant_id', $tenantId)
                                       ->whereYear('scheduled_date', $date->year)
                                       ->whereMonth('scheduled_date', $date->month)
                                       ->count();
            $monthlyInspections[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Product registration status distribution
        $productStatusDistribution = ProductRegistration::where('tenant_id', $tenantId)
                                                       ->selectRaw('status, COUNT(*) as count')
                                                       ->groupBy('status')
                                                       ->get()
                                                       ->mapWithKeys(function ($item) {
                                                           return [$item->status => $item->count];
                                                       });

        return [
            'monthly_inspections' => $monthlyInspections,
            'product_status_distribution' => $productStatusDistribution
        ];
    }
}
