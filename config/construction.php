<?php

return [
    'currency' => env('APP_CURRENCY', 'AED'),

    'formats' => [
        'date' => 'M j, Y',
        'date_time' => 'M j, Y g:i A',
        'percentage_decimals' => 1,
        'currency_decimals' => 2,
    ],

    'documents' => [
        'disk' => env('CONSTRUCTION_DOCUMENT_DISK', 'local'),
        'root' => 'documents',
        'max_upload_kb' => (int) env('CONSTRUCTION_MAX_UPLOAD_KB', 20480),
        'allowed_extensions' => [
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'csv',
            'txt',
            'jpg',
            'jpeg',
            'png',
            'webp',
            'dwg',
            'dxf',
            'zip',
        ],
    ],

    'tables' => [
        'default_pagination' => 25,
        'pagination_options' => [10, 25, 50, 100],
    ],
];
