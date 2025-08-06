<?php

namespace App\Helpers;

use App\Enums\Currency;

class CurrencyHelper
{
    /**
     * Format amount with currency
     */
    public static function format(float $amount, ?string $currencyCode = null): string
    {
        $currency = $currencyCode ? Currency::from($currencyCode) : Currency::default();
        return $currency->format($amount);
    }

    /**
     * Convert amount between currencies
     */
    public static function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $from = Currency::from($fromCurrency);
        $to = Currency::from($toCurrency);
        
        return $from->convertTo($amount, $to);
    }

    /**
     * Get currency symbol
     */
    public static function symbol(string $currencyCode): string
    {
        return Currency::from($currencyCode)->symbol();
    }

    /**
     * Get currency name in Arabic
     */
    public static function nameAr(string $currencyCode): string
    {
        return Currency::from($currencyCode)->nameAr();
    }

    /**
     * Get currency name in English
     */
    public static function nameEn(string $currencyCode): string
    {
        return Currency::from($currencyCode)->nameEn();
    }

    /**
     * Get all supported currencies
     */
    public static function all(): array
    {
        return Currency::selectOptions();
    }

    /**
     * Get currency options for select dropdown
     */
    public static function options(): array
    {
        return Currency::options();
    }

    /**
     * Get default currency code
     */
    public static function default(): string
    {
        return Currency::default()->value;
    }

    /**
     * Check if currency is supported
     */
    public static function isSupported(string $currencyCode): bool
    {
        try {
            Currency::from($currencyCode);
            return true;
        } catch (\ValueError) {
            return false;
        }
    }

    /**
     * Get exchange rate between two currencies
     */
    public static function exchangeRate(string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $from = Currency::from($fromCurrency);
        $to = Currency::from($toCurrency);

        // Convert through USD
        $fromToUsd = 1 / $from->exchangeRateToUSD();
        $usdToTarget = $to->exchangeRateToUSD();

        return $fromToUsd * $usdToTarget;
    }

    /**
     * Format amount for display in forms
     */
    public static function formatForInput(float $amount, ?string $currencyCode = null): string
    {
        $currency = $currencyCode ? Currency::from($currencyCode) : Currency::default();
        return number_format($amount, $currency->decimalPlaces(), '.', '');
    }

    /**
     * Parse amount from input
     */
    public static function parseFromInput(string $input): float
    {
        // Remove any non-numeric characters except decimal point
        $cleaned = preg_replace('/[^\d.]/', '', $input);
        return floatval($cleaned);
    }
}
