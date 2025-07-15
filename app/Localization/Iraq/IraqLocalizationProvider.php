<?php

namespace App\Localization\Iraq;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

/**
 * Iraq Localization Service Provider
 * 
 * Provides comprehensive localization support for the Iraqi market including:
 * - Arabic language support
 * - Iraqi Dinar currency
 * - Iraqi tax system
 * - Government reporting formats
 * - Iraqi phone number formats
 * - Iraqi banking system
 */
class IraqLocalizationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Iraq-specific services
        $this->app->singleton('iraq.currency', function () {
            return new IraqCurrencyService();
        });

        $this->app->singleton('iraq.tax', function () {
            return new IraqTaxService();
        });

        $this->app->singleton('iraq.phone', function () {
            return new IraqPhoneService();
        });

        $this->app->singleton('iraq.banking', function () {
            return new IraqBankingService();
        });

        $this->app->singleton('iraq.reports', function () {
            return new IraqGovernmentReportsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load helper functions
        require_once __DIR__ . '/helpers.php';

        // Set default locale to Arabic
        App::setLocale('ar');
        
        // Configure currency settings for Iraqi Dinar
        Config::set('app.currency', 'IQD');
        Config::set('app.currency_symbol', 'د.ع');
        Config::set('app.currency_position', 'after'); // Symbol after amount
        
        // Configure date and time formats for Iraq
        Config::set('app.date_format', 'Y/m/d');
        Config::set('app.time_format', 'H:i');
        Config::set('app.datetime_format', 'Y/m/d H:i');
        
        // Configure timezone for Iraq
        Config::set('app.timezone', 'Asia/Baghdad');
        
        // Configure number formatting for Arabic
        Config::set('app.number_format', [
            'decimal_separator' => '.',
            'thousands_separator' => ',',
            'decimals' => 0, // Iraqi Dinar typically doesn't use decimals
        ]);

        // Load Iraq-specific translations
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'iraq');
        
        // Load Iraq-specific views
        $this->loadViewsFrom(__DIR__ . '/views', 'iraq');
        
        // Publish configuration files
        $this->publishes([
            __DIR__ . '/config/iraq.php' => config_path('iraq.php'),
        ], 'iraq-config');
        
        // Publish language files
        $this->publishes([
            __DIR__ . '/lang' => resource_path('lang'),
        ], 'iraq-lang');
        
        // Publish view files
        $this->publishes([
            __DIR__ . '/views' => resource_path('views/iraq'),
        ], 'iraq-views');
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [
            'iraq.currency',
            'iraq.tax',
            'iraq.phone',
            'iraq.banking',
            'iraq.reports',
        ];
    }
}
