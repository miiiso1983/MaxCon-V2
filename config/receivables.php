<?php
return [
    // Optional explicit mapping by account_code. Leave null to auto-detect.
    'ar_account_code' => env('AR_ACCOUNT_CODE', null),
    'cash_account_code' => env('CASH_ACCOUNT_CODE', null),

    // Auto-send WhatsApp message with receipt after payment
    'send_whatsapp_on_receipt' => env('AR_SEND_WHATSAPP', true),
];

