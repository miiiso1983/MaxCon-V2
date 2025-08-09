<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\InvoicePermissionHelper;
use App\Models\Invoice;
use App\Models\Customer;
use App\Observers\InvoiceObserver;
use App\Observers\CustomerObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Sales Target Integration Observer
        Invoice::observe(InvoiceObserver::class);

        // Register Customer Observer for tenant limits
        Customer::observe(CustomerObserver::class);

        // Register custom Blade directives for invoice permissions
        Blade::if('canInvoice', function ($permission, $invoice = null) {
            return InvoicePermissionHelper::canPerformAction($permission, $invoice);
        });

        Blade::if('canAccessInvoice', function ($invoice) {
            return InvoicePermissionHelper::canAccess($invoice);
        });

        Blade::if('hasInvoicePermission', function ($permission) {
            return InvoicePermissionHelper::can($permission);
        });
    }
}
