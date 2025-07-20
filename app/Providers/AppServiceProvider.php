<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
    }
}
