<?php

namespace App\Exports\Tenant\Accounting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class IncomeStatementExport implements FromView
{
    public function __construct(
        protected $revenueAccounts,
        protected $expenseAccounts,
        protected $dateFrom,
        protected $dateTo
    ) {}

    public function view(): View
    {
        return view('tenant.accounting.reports.exports.income-statement-excel', [
            'revenueAccounts' => $this->revenueAccounts,
            'expenseAccounts' => $this->expenseAccounts,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);
    }
}

