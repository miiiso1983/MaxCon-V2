<?php

namespace App\Enums;

enum Currency: string
{
    case IQD = 'IQD';
    case USD = 'USD';

    /**
     * Get currency symbol
     */
    public function symbol(): string
    {
        return match($this) {
            self::IQD => 'د.ع',
            self::USD => '$',
        };
    }

    /**
     * Get currency name in Arabic
     */
    public function nameAr(): string
    {
        return match($this) {
            self::IQD => 'دينار عراقي',
            self::USD => 'دولار أمريكي',
        };
    }

    /**
     * Get currency name in English
     */
    public function nameEn(): string
    {
        return match($this) {
            self::IQD => 'Iraqi Dinar',
            self::USD => 'US Dollar',
        };
    }

    /**
     * Get decimal places for currency
     */
    public function decimalPlaces(): int
    {
        return match($this) {
            self::IQD => 3,
            self::USD => 2,
        };
    }

    /**
     * Get symbol position (before or after amount)
     */
    public function symbolPosition(): string
    {
        return match($this) {
            self::IQD => 'after',
            self::USD => 'before',
        };
    }

    /**
     * Get exchange rate to USD (1 USD = X currency)
     */
    public function exchangeRateToUSD(): float
    {
        return match($this) {
            self::IQD => 1320.0, // 1 USD = 1320 IQD
            self::USD => 1.0,    // 1 USD = 1 USD
        };
    }

    /**
     * Format amount with currency symbol
     */
    public function format(float $amount): string
    {
        $formatted = number_format($amount, $this->decimalPlaces(), '.', ',');
        
        return match($this->symbolPosition()) {
            'before' => $this->symbol() . ' ' . $formatted,
            'after' => $formatted . ' ' . $this->symbol(),
        };
    }

    /**
     * Convert amount to another currency
     */
    public function convertTo(float $amount, Currency $toCurrency): float
    {
        if ($this === $toCurrency) {
            return $amount;
        }

        // Convert to USD first, then to target currency
        $usdAmount = $amount / $this->exchangeRateToUSD();
        return $usdAmount * $toCurrency->exchangeRateToUSD();
    }

    /**
     * Get all supported currencies as array
     */
    public static function options(): array
    {
        return [
            self::IQD->value => self::IQD->nameAr() . ' (' . self::IQD->value . ')',
            self::USD->value => self::USD->nameAr() . ' (' . self::USD->value . ')',
        ];
    }

    /**
     * Get all supported currencies for select options
     */
    public static function selectOptions(): array
    {
        $options = [];
        foreach (self::cases() as $currency) {
            $options[] = [
                'value' => $currency->value,
                'label' => $currency->nameAr() . ' (' . $currency->value . ')',
                'symbol' => $currency->symbol(),
                'name_ar' => $currency->nameAr(),
                'name_en' => $currency->nameEn(),
            ];
        }
        return $options;
    }

    /**
     * Get default currency
     */
    public static function default(): self
    {
        return self::IQD;
    }
}
