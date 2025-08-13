<?php

return [
    'mode' => 'utf-8',
    'format' => 'A4-L', // Landscape A4
    'default_font_size' => '12',
    'default_font' => 'Cairo',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 10,
    'margin_bottom' => 10,
    'orientation' => 'L',

    // mPDF specific
    'mpdf' => [
        'tempDir' => storage_path('app/mpdf-temp'),
        'autoScriptToLang' => true,
        'autoLangToFont' => true,
        'autoArabic' => true,
        'useSubstitutions' => true,
        'showImageErrors' => false,
        'rtl' => true,
        'directionality' => 'rtl',
    ],

    'font_path' => base_path('resources/fonts/'),
    'font_data' => [
        'Cairo' => [
            'R' => 'Cairo-Regular.ttf',
            'B' => 'Cairo-Bold.ttf',
        ],
        'Amiri' => [
            'R' => 'Amiri-Regular.ttf',
            'B' => 'Amiri-Bold.ttf',
        ],
        'NotoNaskh' => [
            'R' => 'NotoNaskhArabic-Regular.ttf',
            'B' => 'NotoNaskhArabic-Bold.ttf',
        ],
        'NotoKufi' => [
            'R' => 'NotoKufiArabic-Regular.ttf',
            'B' => 'NotoKufiArabic-Bold.ttf',
        ],
    ],
];

