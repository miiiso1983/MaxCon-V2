<?php

namespace App\Localization\Iraq;

/**
 * Iraq Currency Service
 * 
 * Handles Iraqi Dinar currency formatting, conversion, and display
 */
class IraqCurrencyService
{
    /**
     * Currency code for Iraqi Dinar
     */
    public const CURRENCY_CODE = 'IQD';
    
    /**
     * Currency symbol for Iraqi Dinar
     */
    public const CURRENCY_SYMBOL = 'د.ع';
    
    /**
     * Currency name in Arabic
     */
    public const CURRENCY_NAME_AR = 'دينار عراقي';
    
    /**
     * Currency name in English
     */
    public const CURRENCY_NAME_EN = 'Iraqi Dinar';
    
    /**
     * Smallest currency unit (fils)
     */
    public const SMALLEST_UNIT = 'فلس';
    
    /**
     * Exchange rate to USD (approximate - should be updated from external API)
     */
    public const USD_EXCHANGE_RATE = 1320; // 1 USD = 1320 IQD (approximate)

    /**
     * Format amount in Iraqi Dinar
     */
    public function format(float $amount, bool $showSymbol = true, bool $showDecimals = false): string
    {
        $decimals = $showDecimals ? 3 : 0; // Iraqi Dinar can have up to 3 decimal places (fils)
        $formattedAmount = number_format($amount, $decimals, '.', ',');
        
        if ($showSymbol) {
            return $formattedAmount . ' ' . self::CURRENCY_SYMBOL;
        }
        
        return $formattedAmount;
    }

    /**
     * Format amount with currency name
     */
    public function formatWithName(float $amount, string $locale = 'ar'): string
    {
        $formattedAmount = $this->format($amount, false);
        $currencyName = $locale === 'ar' ? self::CURRENCY_NAME_AR : self::CURRENCY_NAME_EN;
        
        return $formattedAmount . ' ' . $currencyName;
    }

    /**
     * Convert amount to words in Arabic
     */
    public function toWords(float $amount): string
    {
        $integerPart = (int) $amount;
        $decimalPart = round(($amount - $integerPart) * 1000); // Convert to fils
        
        $words = $this->numberToArabicWords($integerPart);
        
        if ($decimalPart > 0) {
            $words .= ' و ' . $this->numberToArabicWords($decimalPart) . ' ' . self::SMALLEST_UNIT;
        }
        
        return $words . ' ' . self::CURRENCY_NAME_AR;
    }

    /**
     * Convert number to Arabic words
     */
    private function numberToArabicWords(int $number): string
    {
        if ($number == 0) return 'صفر';
        
        $ones = [
            '', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة',
            'عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر',
            'ستة عشر', 'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'
        ];
        
        $tens = [
            '', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'
        ];
        
        $hundreds = [
            '', 'مائة', 'مائتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'
        ];
        
        if ($number < 20) {
            return $ones[$number];
        } elseif ($number < 100) {
            $ten = intval($number / 10);
            $one = $number % 10;
            return $tens[$ten] . ($one > 0 ? ' و' . $ones[$one] : '');
        } elseif ($number < 1000) {
            $hundred = intval($number / 100);
            $remainder = $number % 100;
            return $hundreds[$hundred] . ($remainder > 0 ? ' و' . $this->numberToArabicWords($remainder) : '');
        } elseif ($number < 1000000) {
            $thousand = intval($number / 1000);
            $remainder = $number % 1000;
            $thousandWords = $this->numberToArabicWords($thousand) . ' ألف';
            return $thousandWords . ($remainder > 0 ? ' و' . $this->numberToArabicWords($remainder) : '');
        } elseif ($number < 1000000000) {
            $million = intval($number / 1000000);
            $remainder = $number % 1000000;
            $millionWords = $this->numberToArabicWords($million) . ' مليون';
            return $millionWords . ($remainder > 0 ? ' و' . $this->numberToArabicWords($remainder) : '');
        }
        
        return (string) $number; // Fallback for very large numbers
    }

    /**
     * Convert from other currencies to IQD
     */
    public function convertToIQD(float $amount, string $fromCurrency): float
    {
        $rates = [
            'USD' => self::USD_EXCHANGE_RATE,
        ];

        if (!isset($rates[$fromCurrency])) {
            throw new \InvalidArgumentException("Currency {$fromCurrency} not supported. Only USD is supported for conversion to IQD.");
        }

        return $amount * $rates[$fromCurrency];
    }

    /**
     * Convert from IQD to other currencies
     */
    public function convertFromIQD(float $amount, string $toCurrency): float
    {
        $rates = [
            'USD' => 1 / self::USD_EXCHANGE_RATE,
        ];

        if (!isset($rates[$toCurrency])) {
            throw new \InvalidArgumentException("Currency {$toCurrency} not supported. Only USD is supported for conversion from IQD.");
        }

        return $amount * $rates[$toCurrency];
    }

    /**
     * Get currency information
     */
    public function getCurrencyInfo(): array
    {
        return [
            'code' => self::CURRENCY_CODE,
            'symbol' => self::CURRENCY_SYMBOL,
            'name_ar' => self::CURRENCY_NAME_AR,
            'name_en' => self::CURRENCY_NAME_EN,
            'smallest_unit' => self::SMALLEST_UNIT,
            'decimal_places' => 3,
            'symbol_position' => 'after',
        ];
    }
}
