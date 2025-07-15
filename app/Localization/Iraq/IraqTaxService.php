<?php

namespace App\Localization\Iraq;

/**
 * Iraq Tax Service
 * 
 * Handles Iraqi tax system calculations and compliance
 * Based on Iraqi tax laws and regulations
 */
class IraqTaxService
{
    /**
     * Value Added Tax (VAT) rate in Iraq
     */
    public const VAT_RATE = 0.00; // Iraq currently doesn't have VAT
    
    /**
     * Sales Tax rate
     */
    public const SALES_TAX_RATE = 0.00; // Currently no general sales tax
    
    /**
     * Income Tax rates for individuals
     */
    public const INCOME_TAX_BRACKETS = [
        ['min' => 0, 'max' => 250000, 'rate' => 0.00], // First 250,000 IQD tax-free
        ['min' => 250001, 'max' => 500000, 'rate' => 0.03], // 3%
        ['min' => 500001, 'max' => 1000000, 'rate' => 0.05], // 5%
        ['min' => 1000001, 'max' => 1500000, 'rate' => 0.10], // 10%
        ['min' => 1500001, 'max' => null, 'rate' => 0.15], // 15% for amounts above 1.5M
    ];
    
    /**
     * Corporate Income Tax rate
     */
    public const CORPORATE_TAX_RATE = 0.15; // 15%
    
    /**
     * Withholding Tax rates
     */
    public const WITHHOLDING_TAX_RATES = [
        'salary' => 0.00, // No withholding on salaries below threshold
        'professional_services' => 0.05, // 5%
        'contractors' => 0.07, // 7%
        'rent' => 0.05, // 5%
        'interest' => 0.10, // 10%
        'dividends' => 0.15, // 15%
    ];

    /**
     * Calculate VAT amount
     */
    public function calculateVAT(float $amount, bool $inclusive = false): array
    {
        $vatRate = self::VAT_RATE;
        
        if ($inclusive) {
            $vatAmount = $amount * ($vatRate / (1 + $vatRate));
            $netAmount = $amount - $vatAmount;
        } else {
            $vatAmount = $amount * $vatRate;
            $netAmount = $amount;
        }
        
        return [
            'net_amount' => round($netAmount, 2),
            'vat_amount' => round($vatAmount, 2),
            'total_amount' => round($netAmount + $vatAmount, 2),
            'vat_rate' => $vatRate * 100,
        ];
    }

    /**
     * Calculate income tax for individuals
     */
    public function calculateIncomeTax(float $annualIncome): array
    {
        $totalTax = 0;
        $taxBreakdown = [];
        
        foreach (self::INCOME_TAX_BRACKETS as $bracket) {
            $bracketMin = $bracket['min'];
            $bracketMax = $bracket['max'] ?? PHP_FLOAT_MAX;
            $rate = $bracket['rate'];
            
            if ($annualIncome <= $bracketMin) {
                break;
            }
            
            $taxableInBracket = min($annualIncome, $bracketMax) - $bracketMin + 1;
            if ($taxableInBracket > 0) {
                $taxInBracket = $taxableInBracket * $rate;
                $totalTax += $taxInBracket;
                
                $taxBreakdown[] = [
                    'bracket' => $bracketMin . ' - ' . ($bracketMax == PHP_FLOAT_MAX ? '∞' : $bracketMax),
                    'rate' => $rate * 100 . '%',
                    'taxable_amount' => $taxableInBracket,
                    'tax_amount' => $taxInBracket,
                ];
            }
        }
        
        return [
            'annual_income' => $annualIncome,
            'total_tax' => round($totalTax, 2),
            'net_income' => round($annualIncome - $totalTax, 2),
            'effective_rate' => $annualIncome > 0 ? round(($totalTax / $annualIncome) * 100, 2) : 0,
            'breakdown' => $taxBreakdown,
        ];
    }

    /**
     * Calculate corporate income tax
     */
    public function calculateCorporateTax(float $profit, array $deductions = []): array
    {
        $totalDeductions = array_sum($deductions);
        $taxableProfit = max(0, $profit - $totalDeductions);
        $taxAmount = $taxableProfit * self::CORPORATE_TAX_RATE;
        
        return [
            'gross_profit' => $profit,
            'total_deductions' => $totalDeductions,
            'taxable_profit' => $taxableProfit,
            'tax_rate' => self::CORPORATE_TAX_RATE * 100 . '%',
            'tax_amount' => round($taxAmount, 2),
            'net_profit' => round($taxableProfit - $taxAmount, 2),
            'deductions_breakdown' => $deductions,
        ];
    }

    /**
     * Calculate withholding tax
     */
    public function calculateWithholdingTax(float $amount, string $type): array
    {
        if (!isset(self::WITHHOLDING_TAX_RATES[$type])) {
            throw new \InvalidArgumentException("Invalid withholding tax type: {$type}");
        }
        
        $rate = self::WITHHOLDING_TAX_RATES[$type];
        $taxAmount = $amount * $rate;
        
        return [
            'gross_amount' => $amount,
            'withholding_type' => $type,
            'tax_rate' => $rate * 100 . '%',
            'tax_amount' => round($taxAmount, 2),
            'net_amount' => round($amount - $taxAmount, 2),
        ];
    }

    /**
     * Get tax certificate information
     */
    public function generateTaxCertificate(array $taxData): array
    {
        return [
            'certificate_number' => 'TAX-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
            'issue_date' => date('Y-m-d'),
            'tax_year' => date('Y'),
            'taxpayer_info' => $taxData['taxpayer'] ?? [],
            'tax_calculations' => $taxData['calculations'] ?? [],
            'total_tax_due' => $taxData['total_tax'] ?? 0,
            'payment_status' => $taxData['payment_status'] ?? 'pending',
            'due_date' => date('Y-m-d', strtotime('+30 days')),
        ];
    }

    /**
     * Validate tax identification number (TIN)
     */
    public function validateTIN(string $tin): bool
    {
        // Iraqi TIN format: 9 digits
        return preg_match('/^\d{9}$/', $tin);
    }

    /**
     * Format tax identification number
     */
    public function formatTIN(string $tin): string
    {
        if (!$this->validateTIN($tin)) {
            throw new \InvalidArgumentException('Invalid TIN format');
        }
        
        return substr($tin, 0, 3) . '-' . substr($tin, 3, 3) . '-' . substr($tin, 6, 3);
    }

    /**
     * Get tax types and their descriptions
     */
    public function getTaxTypes(): array
    {
        return [
            'income_tax' => [
                'name_ar' => 'ضريبة الدخل',
                'name_en' => 'Income Tax',
                'description_ar' => 'ضريبة على الدخل الشخصي والمهني',
                'rate' => 'متدرجة',
            ],
            'corporate_tax' => [
                'name_ar' => 'ضريبة الشركات',
                'name_en' => 'Corporate Tax',
                'description_ar' => 'ضريبة على أرباح الشركات',
                'rate' => self::CORPORATE_TAX_RATE * 100 . '%',
            ],
            'withholding_tax' => [
                'name_ar' => 'ضريبة الاستقطاع',
                'name_en' => 'Withholding Tax',
                'description_ar' => 'ضريبة مستقطعة من المصدر',
                'rate' => 'متغيرة حسب النوع',
            ],
        ];
    }

    /**
     * Generate tax report for government submission
     */
    public function generateGovernmentTaxReport(array $data): array
    {
        return [
            'report_id' => 'TAX-RPT-' . date('Ymd') . '-' . uniqid(),
            'reporting_period' => $data['period'] ?? date('Y-m'),
            'company_info' => $data['company'] ?? [],
            'tax_summary' => [
                'total_income' => $data['total_income'] ?? 0,
                'total_deductions' => $data['total_deductions'] ?? 0,
                'taxable_income' => $data['taxable_income'] ?? 0,
                'total_tax_calculated' => $data['total_tax'] ?? 0,
                'total_tax_paid' => $data['tax_paid'] ?? 0,
                'tax_balance' => ($data['total_tax'] ?? 0) - ($data['tax_paid'] ?? 0),
            ],
            'generated_at' => date('Y-m-d H:i:s'),
            'generated_by' => $data['generated_by'] ?? 'System',
        ];
    }
}
