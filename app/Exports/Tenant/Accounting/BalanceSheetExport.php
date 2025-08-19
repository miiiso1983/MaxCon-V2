<?php

namespace App\Exports\Tenant\Accounting;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BalanceSheetExport implements FromView
{
    public function __construct(
        protected $asOfDate,
        protected $currentAssets,
        protected $nonCurrentAssets,
        protected $currentLiabilities,
        protected $nonCurrentLiabilities,
        protected $equityAccounts
    ) {}

    public function view(): View
    {
        return view('tenant.accounting.reports.exports.balance-sheet-excel', [
            'asOfDate' => $this->asOfDate,
            'currentAssets' => $this->currentAssets,
            'nonCurrentAssets' => $this->nonCurrentAssets,
            'currentLiabilities' => $this->currentLiabilities,
            'nonCurrentLiabilities' => $this->nonCurrentLiabilities,
            'equityAccounts' => $this->equityAccounts,
        ]);
    }
}

