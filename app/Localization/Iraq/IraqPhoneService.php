<?php

namespace App\Localization\Iraq;

/**
 * Iraq Phone Service
 * 
 * Handles Iraqi phone number validation, formatting, and carrier detection
 */
class IraqPhoneService
{
    /**
     * Country code for Iraq
     */
    public const COUNTRY_CODE = '+964';
    
    /**
     * Mobile network operators in Iraq
     */
    public const MOBILE_OPERATORS = [
        'zain' => [
            'name_ar' => 'زين العراق',
            'name_en' => 'Zain Iraq',
            'prefixes' => ['780', '781', '782', '783'],
            'code' => 'ZI',
        ],
        'asiacell' => [
            'name_ar' => 'آسياسيل',
            'name_en' => 'AsiaCell',
            'prefixes' => ['770', '771', '772', '773', '774', '775'],
            'code' => 'AC',
        ],
        'korek' => [
            'name_ar' => 'كورك تيليكوم',
            'name_en' => 'Korek Telecom',
            'prefixes' => ['750', '751', '752', '753', '754', '755'],
            'code' => 'KT',
        ],
        'earthlink' => [
            'name_ar' => 'إيرث لينك',
            'name_en' => 'Earthlink',
            'prefixes' => ['760', '761', '762', '763'],
            'code' => 'EL',
        ],
        'fanoos' => [
            'name_ar' => 'فانوس',
            'name_en' => 'Fanoos',
            'prefixes' => ['740', '741', '742'],
            'code' => 'FN',
        ],
    ];
    
    /**
     * Landline area codes for major Iraqi cities
     */
    public const LANDLINE_CODES = [
        '1' => ['city_ar' => 'بغداد', 'city_en' => 'Baghdad'],
        '21' => ['city_ar' => 'البصرة', 'city_en' => 'Basra'],
        '30' => ['city_ar' => 'الناصرية', 'city_en' => 'Nasiriyah'],
        '31' => ['city_ar' => 'السماوة', 'city_en' => 'Samawah'],
        '32' => ['city_ar' => 'الديوانية', 'city_en' => 'Diwaniyah'],
        '33' => ['city_ar' => 'الحلة', 'city_en' => 'Hillah'],
        '34' => ['city_ar' => 'كربلاء', 'city_en' => 'Karbala'],
        '36' => ['city_ar' => 'النجف', 'city_en' => 'Najaf'],
        '40' => ['city_ar' => 'الكوت', 'city_en' => 'Kut'],
        '42' => ['city_ar' => 'بعقوبة', 'city_en' => 'Baqubah'],
        '43' => ['city_ar' => 'الرمادي', 'city_en' => 'Ramadi'],
        '50' => ['city_ar' => 'تكريت', 'city_en' => 'Tikrit'],
        '53' => ['city_ar' => 'كركوك', 'city_en' => 'Kirkuk'],
        '60' => ['city_ar' => 'الموصل', 'city_en' => 'Mosul'],
        '62' => ['city_ar' => 'دهوك', 'city_en' => 'Duhok'],
        '66' => ['city_ar' => 'أربيل', 'city_en' => 'Erbil'],
        '70' => ['city_ar' => 'السليمانية', 'city_en' => 'Sulaymaniyah'],
    ];

    /**
     * Validate Iraqi phone number
     */
    public function validate(string $phoneNumber): bool
    {
        $cleaned = $this->cleanPhoneNumber($phoneNumber);
        
        // Check if it's a mobile number
        if ($this->isMobile($cleaned)) {
            return $this->validateMobile($cleaned);
        }
        
        // Check if it's a landline number
        if ($this->isLandline($cleaned)) {
            return $this->validateLandline($cleaned);
        }
        
        return false;
    }

    /**
     * Format Iraqi phone number
     */
    public function format(string $phoneNumber, string $format = 'international'): string
    {
        $cleaned = $this->cleanPhoneNumber($phoneNumber);
        
        if (!$this->validate($phoneNumber)) {
            throw new \InvalidArgumentException('Invalid Iraqi phone number');
        }
        
        switch ($format) {
            case 'international':
                return $this->formatInternational($cleaned);
            case 'national':
                return $this->formatNational($cleaned);
            case 'local':
                return $this->formatLocal($cleaned);
            default:
                return $this->formatInternational($cleaned);
        }
    }

    /**
     * Detect mobile operator
     */
    public function detectOperator(string $phoneNumber): ?array
    {
        $cleaned = $this->cleanPhoneNumber($phoneNumber);
        
        if (!$this->isMobile($cleaned)) {
            return null;
        }
        
        $prefix = substr($cleaned, -10, 3); // Get first 3 digits of mobile number
        
        foreach (self::MOBILE_OPERATORS as $operatorKey => $operator) {
            if (in_array($prefix, $operator['prefixes'])) {
                return array_merge($operator, ['key' => $operatorKey]);
            }
        }
        
        return null;
    }

    /**
     * Detect city for landline numbers
     */
    public function detectCity(string $phoneNumber): ?array
    {
        $cleaned = $this->cleanPhoneNumber($phoneNumber);
        
        if (!$this->isLandline($cleaned)) {
            return null;
        }
        
        // Remove country code and get area code
        $withoutCountryCode = substr($cleaned, 3);
        
        foreach (self::LANDLINE_CODES as $code => $city) {
            if (strpos($withoutCountryCode, $code) === 0) {
                return array_merge($city, ['code' => $code]);
            }
        }
        
        return null;
    }

    /**
     * Clean phone number (remove spaces, dashes, etc.)
     */
    private function cleanPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-digit characters except +
        $cleaned = preg_replace('/[^\d+]/', '', $phoneNumber);
        
        // Add country code if missing
        if (!str_starts_with($cleaned, '+964') && !str_starts_with($cleaned, '964')) {
            if (str_starts_with($cleaned, '0')) {
                $cleaned = '964' . substr($cleaned, 1);
            } else {
                $cleaned = '964' . $cleaned;
            }
        }
        
        // Remove + if present
        $cleaned = ltrim($cleaned, '+');
        
        return $cleaned;
    }

    /**
     * Check if number is mobile
     */
    private function isMobile(string $phoneNumber): bool
    {
        // Iraqi mobile numbers: 964 + 7XX + XXXXXXX (10 digits after country code)
        return preg_match('/^9647\d{8}$/', $phoneNumber);
    }

    /**
     * Check if number is landline
     */
    private function isLandline(string $phoneNumber): bool
    {
        // Iraqi landline numbers: 964 + area code + number
        return preg_match('/^964[1-6]\d{6,8}$/', $phoneNumber);
    }

    /**
     * Validate mobile number
     */
    private function validateMobile(string $phoneNumber): bool
    {
        if (!$this->isMobile($phoneNumber)) {
            return false;
        }
        
        $prefix = substr($phoneNumber, 4, 3); // Get prefix after 964
        
        foreach (self::MOBILE_OPERATORS as $operator) {
            if (in_array($prefix, $operator['prefixes'])) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Validate landline number
     */
    private function validateLandline(string $phoneNumber): bool
    {
        if (!$this->isLandline($phoneNumber)) {
            return false;
        }
        
        $withoutCountryCode = substr($phoneNumber, 3);
        
        foreach (array_keys(self::LANDLINE_CODES) as $code) {
            if (strpos($withoutCountryCode, $code) === 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Format in international format
     */
    private function formatInternational(string $phoneNumber): string
    {
        if ($this->isMobile($phoneNumber)) {
            // +964 7XX XXX XXXX
            return '+964 ' . substr($phoneNumber, 3, 3) . ' ' . 
                   substr($phoneNumber, 6, 3) . ' ' . substr($phoneNumber, 9);
        } else {
            // +964 XX XXXX XXXX
            $withoutCountryCode = substr($phoneNumber, 3);
            $areaCode = '';
            $number = '';
            
            foreach (array_keys(self::LANDLINE_CODES) as $code) {
                if (strpos($withoutCountryCode, $code) === 0) {
                    $areaCode = $code;
                    $number = substr($withoutCountryCode, strlen($code));
                    break;
                }
            }
            
            return '+964 ' . $areaCode . ' ' . chunk_split($number, 4, ' ');
        }
    }

    /**
     * Format in national format
     */
    private function formatNational(string $phoneNumber): string
    {
        if ($this->isMobile($phoneNumber)) {
            // 07XX XXX XXXX
            return '0' . substr($phoneNumber, 3, 3) . ' ' . 
                   substr($phoneNumber, 6, 3) . ' ' . substr($phoneNumber, 9);
        } else {
            // 0XX XXXX XXXX
            $withoutCountryCode = substr($phoneNumber, 3);
            return '0' . chunk_split($withoutCountryCode, 4, ' ');
        }
    }

    /**
     * Format in local format
     */
    private function formatLocal(string $phoneNumber): string
    {
        if ($this->isMobile($phoneNumber)) {
            // 7XX XXX XXXX
            return substr($phoneNumber, 3, 3) . ' ' . 
                   substr($phoneNumber, 6, 3) . ' ' . substr($phoneNumber, 9);
        } else {
            // Local landline format varies by city
            $withoutCountryCode = substr($phoneNumber, 3);
            return chunk_split($withoutCountryCode, 4, ' ');
        }
    }

    /**
     * Get all mobile operators
     */
    public function getMobileOperators(): array
    {
        return self::MOBILE_OPERATORS;
    }

    /**
     * Get all landline area codes
     */
    public function getLandlineCodes(): array
    {
        return self::LANDLINE_CODES;
    }
}
