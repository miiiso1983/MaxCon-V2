<?php

namespace App\Exports\Tenant\Accounting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrialBalanceExport implements FromView
{
    public function __construct(
        protected $trialBalanceData,
        protected $totalDebits,
        protected $totalCredits,
        protected $dateFrom,
        protected $dateTo,
        protected $costCenterName = null
    ) {}

    public function view(): View
    {
        return view('tenant.accounting.reports.exports.trial-balance-excel', [
            'trialBalanceData' => $this->trialBalanceData,
            'totalDebits' => $this->totalDebits,
            'totalCredits' => $this->totalCredits,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'costCenterName' => $this->costCenterName,
        ]);
    }
}

