<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('currency_format')) {
    /**
     * Format amount with currency symbol
     */
    function currency_format(float $amount, ?string $currencyCode = null): string
    {
        return CurrencyHelper::format($amount, $currencyCode);
    }
}

if (!function_exists('currency_convert')) {
    /**
     * Convert amount between currencies
     */
    function currency_convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        return CurrencyHelper::convert($amount, $fromCurrency, $toCurrency);
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get currency symbol
     */
    function currency_symbol(string $currencyCode): string
    {
        return CurrencyHelper::symbol($currencyCode);
    }
}

if (!function_exists('currency_name')) {
    /**
     * Get currency name in Arabic
     */
    function currency_name(string $currencyCode): string
    {
        return CurrencyHelper::nameAr($currencyCode);
    }
}

if (!function_exists('currency_options')) {
    /**
     * Get currency options for select dropdown
     */
    function currency_options(): array
    {
        return CurrencyHelper::options();
    }
}

if (!function_exists('default_currency')) {
    /**
     * Get default currency code
     */
    function default_currency(): string
    {
        return CurrencyHelper::default();
    }
}

if (!function_exists('currency_exchange_rate')) {
    /**
     * Get exchange rate between currencies
     */
    function currency_exchange_rate(string $fromCurrency, string $toCurrency): float
    {
        return CurrencyHelper::exchangeRate($fromCurrency, $toCurrency);
    }
}
