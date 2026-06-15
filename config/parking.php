<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Smart Parking Configuration
    |--------------------------------------------------------------------------
    */

    // Pricing
    'first_hour_cost' => env('PARKING_FIRST_HOUR_COST', 2000),
    'next_hour_cost' => env('PARKING_NEXT_HOUR_COST', 1000),

    // Default balance for new RFID users
    'default_balance' => env('PARKING_DEFAULT_BALANCE', 100000),

    // Printer name for ESC/POS receipt printing
    'printer_name' => env('PARKING_PRINTER_NAME', 'POS-58'),

    // Print mode: 'windows' | 'file' | 'disabled'
    // - windows  : Print via Windows printer (production)
    // - file     : Save receipt to storage/logs/receipt_latest.txt (dev/testing)
    // - disabled : Skip printing entirely
    'print_mode' => env('PARKING_PRINT_MODE', 'windows'),
];
