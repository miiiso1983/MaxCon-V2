<?php

namespace App\Localization\Iraq;

/**
 * Iraq Banking Service
 * 
 * Handles Iraqi banking system, bank codes, and payment methods
 */
class IraqBankingService
{
    /**
     * Iraqi banks with their codes and information
     */
    public const IRAQI_BANKS = [
        'cbi' => [
            'name_ar' => 'البنك المركزي العراقي',
            'name_en' => 'Central Bank of Iraq',
            'code' => 'CBI',
            'swift_code' => 'CBIRIQBA',
            'type' => 'central',
            'established' => 1947,
        ],
        'rafidain' => [
            'name_ar' => 'مصرف الرافدين',
            'name_en' => 'Rafidain Bank',
            'code' => 'RB',
            'swift_code' => 'RAFI IQBA',
            'type' => 'government',
            'established' => 1941,
        ],
        'rasheed' => [
            'name_ar' => 'مصرف الرشيد',
            'name_en' => 'Rasheed Bank',
            'code' => 'RSB',
            'swift_code' => 'RASH IQBA',
            'type' => 'government',
            'established' => 1988,
        ],
        'trade_bank' => [
            'name_ar' => 'المصرف التجاري العراقي',
            'name_en' => 'Trade Bank of Iraq',
            'code' => 'TBI',
            'swift_code' => 'TRAD IQBA',
            'type' => 'government',
            'established' => 2003,
        ],
        'baghdad_bank' => [
            'name_ar' => 'بنك بغداد',
            'name_en' => 'Baghdad Bank',
            'code' => 'BB',
            'swift_code' => 'BAGH IQBA',
            'type' => 'private',
            'established' => 1992,
        ],
        'babylon_bank' => [
            'name_ar' => 'بنك بابل',
            'name_en' => 'Babylon Bank',
            'code' => 'BAB',
            'swift_code' => 'BABY IQBA',
            'type' => 'private',
            'established' => 1999,
        ],
        'gulf_commercial' => [
            'name_ar' => 'بنك الخليج التجاري',
            'name_en' => 'Gulf Commercial Bank',
            'code' => 'GCB',
            'swift_code' => 'GULF IQBA',
            'type' => 'private',
            'established' => 1999,
        ],
        'investment_bank' => [
            'name_ar' => 'بنك الاستثمار العراقي',
            'name_en' => 'Iraqi Investment Bank',
            'code' => 'IIB',
            'swift_code' => 'INVE IQBA',
            'type' => 'private',
            'established' => 1993,
        ],
        'middle_east' => [
            'name_ar' => 'بنك الشرق الأوسط العراقي للاستثمار',
            'name_en' => 'Middle East Iraqi Bank for Investment',
            'code' => 'MEIB',
            'swift_code' => 'MIDE IQBA',
            'type' => 'private',
            'established' => 1993,
        ],
        'kurdistan' => [
            'name_ar' => 'بنك كردستان',
            'name_en' => 'Kurdistan Bank',
            'code' => 'KB',
            'swift_code' => 'KURD IQBA',
            'type' => 'private',
            'established' => 2005,
        ],
        'ashur' => [
            'name_ar' => 'بنك آشور الدولي',
            'name_en' => 'Ashur International Bank',
            'code' => 'AIB',
            'swift_code' => 'ASHU IQBA',
            'type' => 'private',
            'established' => 2005,
        ],
        'zain_iraq' => [
            'name_ar' => 'بنك زين العراق الإسلامي',
            'name_en' => 'Zain Iraq Islamic Bank',
            'code' => 'ZIIB',
            'swift_code' => 'ZAIN IQBA',
            'type' => 'islamic',
            'established' => 2015,
        ],
        'elaf_islamic' => [
            'name_ar' => 'مصرف إيلاف الإسلامي',
            'name_en' => 'Elaf Islamic Bank',
            'code' => 'EIB',
            'swift_code' => 'ELAF IQBA',
            'type' => 'islamic',
            'established' => 2005,
        ],
        'cihan' => [
            'name_ar' => 'بنك جيهان للاستثمار والتمويل',
            'name_en' => 'Cihan Bank for Investment and Finance',
            'code' => 'CBIF',
            'swift_code' => 'CIHA IQBA',
            'type' => 'private',
            'established' => 2008,
        ],
    ];

    /**
     * Payment methods available in Iraq
     */
    public const PAYMENT_METHODS = [
        'cash' => [
            'name_ar' => 'نقداً',
            'name_en' => 'Cash',
            'type' => 'cash',
            'available' => true,
        ],
        'bank_transfer' => [
            'name_ar' => 'حوالة مصرفية',
            'name_en' => 'Bank Transfer',
            'type' => 'electronic',
            'available' => true,
        ],
        'check' => [
            'name_ar' => 'شيك',
            'name_en' => 'Check',
            'type' => 'paper',
            'available' => true,
        ],
        'qi_card' => [
            'name_ar' => 'بطاقة كي كارد',
            'name_en' => 'Qi Card',
            'type' => 'card',
            'available' => true,
            'issuer' => 'qi_card_company',
        ],
        'mastercard' => [
            'name_ar' => 'ماستر كارد',
            'name_en' => 'MasterCard',
            'type' => 'card',
            'available' => true,
            'issuer' => 'international',
        ],
        'visa' => [
            'name_ar' => 'فيزا',
            'name_en' => 'Visa',
            'type' => 'card',
            'available' => true,
            'issuer' => 'international',
        ],
        'zain_cash' => [
            'name_ar' => 'زين كاش',
            'name_en' => 'Zain Cash',
            'type' => 'mobile_wallet',
            'available' => true,
            'provider' => 'zain_iraq',
        ],
        'fastpay' => [
            'name_ar' => 'فاست باي',
            'name_en' => 'FastPay',
            'type' => 'mobile_wallet',
            'available' => true,
            'provider' => 'fastpay_iraq',
        ],
    ];

    /**
     * Get all Iraqi banks
     */
    public function getAllBanks(): array
    {
        return self::IRAQI_BANKS;
    }

    /**
     * Get banks by type
     */
    public function getBanksByType(string $type): array
    {
        return array_filter(self::IRAQI_BANKS, function ($bank) use ($type) {
            return $bank['type'] === $type;
        });
    }

    /**
     * Get bank by code
     */
    public function getBankByCode(string $code): ?array
    {
        foreach (self::IRAQI_BANKS as $key => $bank) {
            if ($bank['code'] === strtoupper($code)) {
                return array_merge($bank, ['key' => $key]);
            }
        }
        return null;
    }

    /**
     * Get bank by SWIFT code
     */
    public function getBankBySwiftCode(string $swiftCode): ?array
    {
        foreach (self::IRAQI_BANKS as $key => $bank) {
            if ($bank['swift_code'] === strtoupper($swiftCode)) {
                return array_merge($bank, ['key' => $key]);
            }
        }
        return null;
    }

    /**
     * Validate Iraqi bank account number
     */
    public function validateAccountNumber(string $accountNumber, string $bankCode = null): bool
    {
        // Remove spaces and special characters
        $cleaned = preg_replace('/[^0-9]/', '', $accountNumber);
        
        // Iraqi bank account numbers are typically 10-16 digits
        if (strlen($cleaned) < 10 || strlen($cleaned) > 16) {
            return false;
        }
        
        // Additional validation based on bank if provided
        if ($bankCode) {
            return $this->validateAccountNumberForBank($cleaned, $bankCode);
        }
        
        return true;
    }

    /**
     * Validate account number for specific bank
     */
    private function validateAccountNumberForBank(string $accountNumber, string $bankCode): bool
    {
        $bank = $this->getBankByCode($bankCode);
        if (!$bank) {
            return false;
        }
        
        // Bank-specific validation rules can be added here
        switch ($bankCode) {
            case 'RB': // Rafidain Bank
                return strlen($accountNumber) >= 12;
            case 'RSB': // Rasheed Bank
                return strlen($accountNumber) >= 12;
            case 'TBI': // Trade Bank of Iraq
                return strlen($accountNumber) >= 10;
            default:
                return strlen($accountNumber) >= 10;
        }
    }

    /**
     * Format account number
     */
    public function formatAccountNumber(string $accountNumber, string $bankCode = null): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $accountNumber);
        
        // Format based on bank or use default formatting
        if ($bankCode) {
            return $this->formatAccountNumberForBank($cleaned, $bankCode);
        }
        
        // Default formatting: XXXX-XXXX-XXXX
        return chunk_split($cleaned, 4, '-');
    }

    /**
     * Format account number for specific bank
     */
    private function formatAccountNumberForBank(string $accountNumber, string $bankCode): string
    {
        switch ($bankCode) {
            case 'RB': // Rafidain Bank: XXXX-XXXX-XXXX
                return chunk_split($accountNumber, 4, '-');
            case 'RSB': // Rasheed Bank: XXXX-XXXX-XXXX
                return chunk_split($accountNumber, 4, '-');
            default:
                return chunk_split($accountNumber, 4, '-');
        }
    }

    /**
     * Get available payment methods
     */
    public function getPaymentMethods(): array
    {
        return array_filter(self::PAYMENT_METHODS, function ($method) {
            return $method['available'];
        });
    }

    /**
     * Get payment methods by type
     */
    public function getPaymentMethodsByType(string $type): array
    {
        return array_filter(self::PAYMENT_METHODS, function ($method) use ($type) {
            return $method['type'] === $type && $method['available'];
        });
    }

    /**
     * Validate IBAN for Iraqi banks
     */
    public function validateIBAN(string $iban): bool
    {
        // Remove spaces and convert to uppercase
        $iban = strtoupper(str_replace(' ', '', $iban));
        
        // Iraqi IBAN format: IQ98 BANK 1234 5678 9012 3456
        if (!preg_match('/^IQ\d{2}[A-Z]{4}\d{16}$/', $iban)) {
            return false;
        }
        
        // Validate check digits using mod-97 algorithm
        return $this->validateIBANChecksum($iban);
    }

    /**
     * Validate IBAN checksum
     */
    private function validateIBANChecksum(string $iban): bool
    {
        // Move first 4 characters to end
        $rearranged = substr($iban, 4) . substr($iban, 0, 4);
        
        // Replace letters with numbers (A=10, B=11, ..., Z=35)
        $numeric = '';
        for ($i = 0; $i < strlen($rearranged); $i++) {
            $char = $rearranged[$i];
            if (is_numeric($char)) {
                $numeric .= $char;
            } else {
                $numeric .= (ord($char) - ord('A') + 10);
            }
        }
        
        // Calculate mod 97
        return bcmod($numeric, '97') === '1';
    }

    /**
     * Generate bank transfer reference
     */
    public function generateTransferReference(string $bankCode, string $type = 'payment'): string
    {
        $prefix = strtoupper($type);
        $timestamp = date('YmdHis');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$bankCode}-{$timestamp}-{$random}";
    }

    /**
     * Get bank branch information (placeholder - would need actual data)
     */
    public function getBankBranches(string $bankCode): array
    {
        // This would typically come from a database
        return [
            'main_branch' => [
                'name_ar' => 'الفرع الرئيسي',
                'name_en' => 'Main Branch',
                'address_ar' => 'بغداد - المنصور',
                'address_en' => 'Baghdad - Mansour',
                'phone' => '+964 1 555 0001',
            ],
            // More branches would be listed here
        ];
    }
}
