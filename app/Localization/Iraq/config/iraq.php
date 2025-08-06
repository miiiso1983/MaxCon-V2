<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Iraq Localization Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration settings for Iraqi market localization
    | including currency, tax system, government entities, and compliance rules.
    |
    */

    'enabled' => env('IRAQ_LOCALIZATION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Currency Settings
    |--------------------------------------------------------------------------
    */
    'currency' => [
        'code' => 'IQD',
        'symbol' => 'د.ع',
        'name_ar' => 'دينار عراقي',
        'name_en' => 'Iraqi Dinar',
        'smallest_unit' => 'فلس',
        'decimal_places' => 3,
        'symbol_position' => 'after', // before or after
        'thousands_separator' => ',',
        'decimal_separator' => '.',
        'exchange_rates' => [
            'USD' => 1320, // 1 USD = 1320 IQD
        ],
        'supported_currencies' => [
            'IQD' => [
                'code' => 'IQD',
                'symbol' => 'د.ع',
                'name_ar' => 'دينار عراقي',
                'name_en' => 'Iraqi Dinar',
                'decimal_places' => 3,
                'symbol_position' => 'after'
            ],
            'USD' => [
                'code' => 'USD',
                'symbol' => '$',
                'name_ar' => 'دولار أمريكي',
                'name_en' => 'US Dollar',
                'decimal_places' => 2,
                'symbol_position' => 'before'
            ]
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax System Configuration
    |--------------------------------------------------------------------------
    */
    'tax' => [
        'vat_rate' => 0.00, // Iraq currently doesn't have VAT
        'sales_tax_rate' => 0.00,
        'corporate_tax_rate' => 0.15, // 15%
        
        'income_tax_brackets' => [
            ['min' => 0, 'max' => 250000, 'rate' => 0.00],
            ['min' => 250001, 'max' => 500000, 'rate' => 0.03],
            ['min' => 500001, 'max' => 1000000, 'rate' => 0.05],
            ['min' => 1000001, 'max' => 1500000, 'rate' => 0.10],
            ['min' => 1500001, 'max' => null, 'rate' => 0.15],
        ],
        
        'withholding_tax_rates' => [
            'salary' => 0.00,
            'professional_services' => 0.05,
            'contractors' => 0.07,
            'rent' => 0.05,
            'interest' => 0.10,
            'dividends' => 0.15,
        ],
        
        'tin_format' => '/^\d{9}$/', // 9 digits
    ],

    /*
    |--------------------------------------------------------------------------
    | Phone Number Configuration
    |--------------------------------------------------------------------------
    */
    'phone' => [
        'country_code' => '+964',
        'mobile_operators' => [
            'zain' => ['name_ar' => 'زين العراق', 'prefixes' => ['780', '781', '782', '783']],
            'asiacell' => ['name_ar' => 'آسياسيل', 'prefixes' => ['770', '771', '772', '773', '774', '775']],
            'korek' => ['name_ar' => 'كورك تيليكوم', 'prefixes' => ['750', '751', '752', '753', '754', '755']],
            'earthlink' => ['name_ar' => 'إيرث لينك', 'prefixes' => ['760', '761', '762', '763']],
            'fanoos' => ['name_ar' => 'فانوس', 'prefixes' => ['740', '741', '742']],
        ],
        'landline_codes' => [
            '1' => 'بغداد',
            '21' => 'البصرة',
            '30' => 'الناصرية',
            '31' => 'السماوة',
            '32' => 'الديوانية',
            '33' => 'الحلة',
            '34' => 'كربلاء',
            '36' => 'النجف',
            '40' => 'الكوت',
            '42' => 'بعقوبة',
            '43' => 'الرمادي',
            '50' => 'تكريت',
            '53' => 'كركوك',
            '60' => 'الموصل',
            '62' => 'دهوك',
            '66' => 'أربيل',
            '70' => 'السليمانية',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Banking System Configuration
    |--------------------------------------------------------------------------
    */
    'banking' => [
        'iban_country_code' => 'IQ',
        'iban_length' => 23,
        'account_number_min_length' => 10,
        'account_number_max_length' => 16,
        
        'payment_methods' => [
            'cash' => ['name_ar' => 'نقداً', 'available' => true],
            'bank_transfer' => ['name_ar' => 'حوالة مصرفية', 'available' => true],
            'check' => ['name_ar' => 'شيك', 'available' => true],
            'qi_card' => ['name_ar' => 'بطاقة كي كارد', 'available' => true],
            'zain_cash' => ['name_ar' => 'زين كاش', 'available' => true],
            'fastpay' => ['name_ar' => 'فاست باي', 'available' => true],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Government Entities
    |--------------------------------------------------------------------------
    */
    'government_entities' => [
        'tax_authority' => [
            'name_ar' => 'الهيئة العامة للضرائب',
            'name_en' => 'General Tax Authority',
            'code' => 'GTA',
            'website' => 'https://gctc.gov.iq',
            'contact_email' => 'info@gctc.gov.iq',
        ],
        'ministry_of_trade' => [
            'name_ar' => 'وزارة التجارة',
            'name_en' => 'Ministry of Trade',
            'code' => 'MOT',
            'website' => 'https://mot.gov.iq',
            'contact_email' => 'info@mot.gov.iq',
        ],
        'ministry_of_health' => [
            'name_ar' => 'وزارة الصحة',
            'name_en' => 'Ministry of Health',
            'code' => 'MOH',
            'website' => 'https://moh.gov.iq',
            'contact_email' => 'info@moh.gov.iq',
        ],
        'central_bank' => [
            'name_ar' => 'البنك المركزي العراقي',
            'name_en' => 'Central Bank of Iraq',
            'code' => 'CBI',
            'website' => 'https://cbi.iq',
            'contact_email' => 'info@cbi.iq',
        ],
        'ministry_of_labor' => [
            'name_ar' => 'وزارة العمل والشؤون الاجتماعية',
            'name_en' => 'Ministry of Labor and Social Affairs',
            'code' => 'MOLSA',
            'website' => 'https://molsa.gov.iq',
            'contact_email' => 'info@molsa.gov.iq',
        ],
        'customs_authority' => [
            'name_ar' => 'الهيئة العامة للجمارك',
            'name_en' => 'General Customs Authority',
            'code' => 'GCA',
            'website' => 'https://customs.gov.iq',
            'contact_email' => 'info@customs.gov.iq',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Templates
    |--------------------------------------------------------------------------
    */
    'reports' => [
        'monthly_tax' => [
            'title_ar' => 'التقرير الضريبي الشهري',
            'frequency' => 'monthly',
            'due_date' => '15th of following month',
            'format' => 'pdf',
            'required_fields' => [
                'company_info', 'tax_period', 'gross_income', 
                'deductions', 'taxable_income', 'tax_calculated'
            ],
        ],
        'pharmaceutical_sales' => [
            'title_ar' => 'تقرير مبيعات الأدوية',
            'frequency' => 'quarterly',
            'due_date' => '30 days after quarter end',
            'format' => 'excel',
            'required_fields' => [
                'license_number', 'reporting_period', 'drug_sales_summary',
                'controlled_substances', 'import_records'
            ],
        ],
        'employee_records' => [
            'title_ar' => 'تقرير سجلات الموظفين',
            'frequency' => 'monthly',
            'due_date' => '10th of following month',
            'format' => 'excel',
            'required_fields' => [
                'company_registration', 'employee_count', 'new_hires',
                'terminations', 'salary_details'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Localization Settings
    |--------------------------------------------------------------------------
    */
    'localization' => [
        'default_locale' => 'ar',
        'supported_locales' => ['ar', 'en'],
        'date_format' => 'Y/m/d',
        'time_format' => 'H:i',
        'datetime_format' => 'Y/m/d H:i',
        'timezone' => 'Asia/Baghdad',
        'rtl' => true,
        'number_format' => [
            'decimal_separator' => '.',
            'thousands_separator' => ',',
            'decimals' => 0,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Compliance Rules
    |--------------------------------------------------------------------------
    */
    'compliance' => [
        'invoice_requirements' => [
            'qr_code' => true,
            'tax_information' => true,
            'company_registration' => true,
            'sequential_numbering' => true,
            'arabic_language' => true,
        ],
        'record_retention' => [
            'invoices' => 7, // years
            'tax_records' => 7, // years
            'employee_records' => 5, // years
            'financial_statements' => 10, // years
        ],
        'mandatory_fields' => [
            'company_tax_id',
            'customer_name',
            'invoice_date',
            'invoice_number',
            'item_description',
            'amount_in_words',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        'government_apis' => [
            'tax_authority' => [
                'enabled' => false,
                'endpoint' => 'https://api.gctc.gov.iq',
                'api_key' => env('IRAQ_TAX_API_KEY'),
            ],
            'customs' => [
                'enabled' => false,
                'endpoint' => 'https://api.customs.gov.iq',
                'api_key' => env('IRAQ_CUSTOMS_API_KEY'),
            ],
        ],
        'banking_apis' => [
            'central_bank' => [
                'enabled' => false,
                'endpoint' => 'https://api.cbi.iq',
                'api_key' => env('IRAQ_CBI_API_KEY'),
            ],
        ],
    ],
];
