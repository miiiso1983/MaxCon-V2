<?php

namespace App\Exports\Tenant\Accounting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccountLedgerExport implements FromView
{
    public function __construct(
        protected $account,
        protected $ledgerEntries,
        protected $openingBalance,
        protected $totalDebits,
        protected $totalCredits,
        protected $closingBalance,
        protected $dateFrom,
        protected $dateTo
    ) {}

    public function view(): View
    {
        return view('tenant.accounting.reports.exports.account-ledger-excel', [
            'account' => $this->account,
            'ledgerEntries' => $this->ledgerEntries,
            'openingBalance' => $this->openingBalance,
            'totalDebits' => $this->totalDebits,
            'totalCredits' => $this->totalCredits,
            'closingBalance' => $this->closingBalance,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);
    }
}

