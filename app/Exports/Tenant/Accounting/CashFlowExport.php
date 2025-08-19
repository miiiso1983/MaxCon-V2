<?php

namespace App\Exports\Tenant\Accounting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CashFlowExport implements FromView
{
    public function __construct(
        protected $dateFrom,
        protected $dateTo,
        protected $method,
        protected $operatingCashFlows,
        protected $investingCashFlows,
        protected $financingCashFlows,
        protected $netOperatingCashFlow,
        protected $netInvestingCashFlow,
        protected $netFinancingCashFlow,
        protected $beginningCash,
        protected $endingCash
    ) {}

    public function view(): View
    {
        return view('tenant.accounting.reports.exports.cash-flow-excel', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'method' => $this->method,
            'operatingCashFlows' => $this->operatingCashFlows,
            'investingCashFlows' => $this->investingCashFlows,
            'financingCashFlows' => $this->financingCashFlows,
            'netOperatingCashFlow' => $this->netOperatingCashFlow,
            'netInvestingCashFlow' => $this->netInvestingCashFlow,
            'netFinancingCashFlow' => $this->netFinancingCashFlow,
            'beginningCash' => $this->beginningCash,
            'endingCash' => $this->endingCash,
        ]);
    }
}

