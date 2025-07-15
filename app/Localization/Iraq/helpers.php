<?php

if (!function_exists('iraq_currency')) {
    /**
     * Get Iraq currency service instance
     */
    function iraq_currency()
    {
        return app('iraq.currency');
    }
}

if (!function_exists('iraq_tax')) {
    /**
     * Get Iraq tax service instance
     */
    function iraq_tax()
    {
        return app('iraq.tax');
    }
}

if (!function_exists('iraq_phone')) {
    /**
     * Get Iraq phone service instance
     */
    function iraq_phone()
    {
        return app('iraq.phone');
    }
}

if (!function_exists('iraq_banking')) {
    /**
     * Get Iraq banking service instance
     */
    function iraq_banking()
    {
        return app('iraq.banking');
    }
}

if (!function_exists('iraq_reports')) {
    /**
     * Get Iraq government reports service instance
     */
    function iraq_reports()
    {
        return app('iraq.reports');
    }
}

if (!function_exists('format_iraqi_currency')) {
    /**
     * Format amount in Iraqi Dinar
     */
    function format_iraqi_currency($amount, $showSymbol = true, $showDecimals = false)
    {
        return iraq_currency()->format($amount, $showSymbol, $showDecimals);
    }
}

if (!function_exists('iraqi_currency_to_words')) {
    /**
     * Convert amount to Arabic words
     */
    function iraqi_currency_to_words($amount)
    {
        return iraq_currency()->toWords($amount);
    }
}

if (!function_exists('validate_iraqi_phone')) {
    /**
     * Validate Iraqi phone number
     */
    function validate_iraqi_phone($phoneNumber)
    {
        return iraq_phone()->validate($phoneNumber);
    }
}

if (!function_exists('format_iraqi_phone')) {
    /**
     * Format Iraqi phone number
     */
    function format_iraqi_phone($phoneNumber, $format = 'international')
    {
        return iraq_phone()->format($phoneNumber, $format);
    }
}

if (!function_exists('detect_iraqi_operator')) {
    /**
     * Detect Iraqi mobile operator
     */
    function detect_iraqi_operator($phoneNumber)
    {
        return iraq_phone()->detectOperator($phoneNumber);
    }
}

if (!function_exists('calculate_iraqi_income_tax')) {
    /**
     * Calculate Iraqi income tax
     */
    function calculate_iraqi_income_tax($annualIncome)
    {
        return iraq_tax()->calculateIncomeTax($annualIncome);
    }
}

if (!function_exists('calculate_iraqi_corporate_tax')) {
    /**
     * Calculate Iraqi corporate tax
     */
    function calculate_iraqi_corporate_tax($profit, $deductions = [])
    {
        return iraq_tax()->calculateCorporateTax($profit, $deductions);
    }
}

if (!function_exists('validate_iraqi_tin')) {
    /**
     * Validate Iraqi Tax Identification Number
     */
    function validate_iraqi_tin($tin)
    {
        return iraq_tax()->validateTIN($tin);
    }
}

if (!function_exists('format_iraqi_tin')) {
    /**
     * Format Iraqi Tax Identification Number
     */
    function format_iraqi_tin($tin)
    {
        return iraq_tax()->formatTIN($tin);
    }
}

if (!function_exists('validate_iraqi_iban')) {
    /**
     * Validate Iraqi IBAN
     */
    function validate_iraqi_iban($iban)
    {
        return iraq_banking()->validateIBAN($iban);
    }
}

if (!function_exists('get_iraqi_banks')) {
    /**
     * Get all Iraqi banks
     */
    function get_iraqi_banks()
    {
        return iraq_banking()->getAllBanks();
    }
}

if (!function_exists('get_iraqi_bank_by_code')) {
    /**
     * Get Iraqi bank by code
     */
    function get_iraqi_bank_by_code($code)
    {
        return iraq_banking()->getBankByCode($code);
    }
}

if (!function_exists('generate_tax_report')) {
    /**
     * Generate Iraqi tax report
     */
    function generate_tax_report($data, $reportType = 'monthly_tax')
    {
        return iraq_reports()->generateTaxReport($data, $reportType);
    }
}

if (!function_exists('generate_pharmaceutical_report')) {
    /**
     * Generate Iraqi pharmaceutical report
     */
    function generate_pharmaceutical_report($data)
    {
        return iraq_reports()->generatePharmaceuticalReport($data);
    }
}

if (!function_exists('generate_employee_report')) {
    /**
     * Generate Iraqi employee report
     */
    function generate_employee_report($data)
    {
        return iraq_reports()->generateEmployeeReport($data);
    }
}

if (!function_exists('is_iraq_localization_enabled')) {
    /**
     * Check if Iraq localization is enabled
     */
    function is_iraq_localization_enabled()
    {
        return config('iraq.enabled', false);
    }
}

if (!function_exists('get_iraq_config')) {
    /**
     * Get Iraq configuration value
     */
    function get_iraq_config($key, $default = null)
    {
        return config("iraq.{$key}", $default);
    }
}

if (!function_exists('translate_ar')) {
    /**
     * Translate text to Arabic (Iraq specific)
     */
    function translate_ar($key, $replace = [], $locale = 'ar')
    {
        return trans("iraq::{$key}", $replace, $locale);
    }
}

if (!function_exists('format_iraqi_date')) {
    /**
     * Format date in Iraqi format
     */
    function format_iraqi_date($date, $format = null)
    {
        $format = $format ?: get_iraq_config('localization.date_format', 'Y/m/d');
        
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format($format);
    }
}

if (!function_exists('format_iraqi_datetime')) {
    /**
     * Format datetime in Iraqi format
     */
    function format_iraqi_datetime($datetime, $format = null)
    {
        $format = $format ?: get_iraq_config('localization.datetime_format', 'Y/m/d H:i');
        
        if (is_string($datetime)) {
            $datetime = \Carbon\Carbon::parse($datetime);
        }
        
        return $datetime->format($format);
    }
}

if (!function_exists('get_iraqi_government_entities')) {
    /**
     * Get Iraqi government entities
     */
    function get_iraqi_government_entities()
    {
        return get_iraq_config('government_entities', []);
    }
}

if (!function_exists('get_iraqi_mobile_operators')) {
    /**
     * Get Iraqi mobile operators
     */
    function get_iraqi_mobile_operators()
    {
        return get_iraq_config('phone.mobile_operators', []);
    }
}

if (!function_exists('get_iraqi_payment_methods')) {
    /**
     * Get available Iraqi payment methods
     */
    function get_iraqi_payment_methods()
    {
        return iraq_banking()->getPaymentMethods();
    }
}

if (!function_exists('convert_to_iraqi_dinar')) {
    /**
     * Convert amount from other currency to Iraqi Dinar
     */
    function convert_to_iraqi_dinar($amount, $fromCurrency)
    {
        return iraq_currency()->convertToIQD($amount, $fromCurrency);
    }
}

if (!function_exists('convert_from_iraqi_dinar')) {
    /**
     * Convert amount from Iraqi Dinar to other currency
     */
    function convert_from_iraqi_dinar($amount, $toCurrency)
    {
        return iraq_currency()->convertFromIQD($amount, $toCurrency);
    }
}
