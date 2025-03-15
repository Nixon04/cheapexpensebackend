<?php

declare(strict_types=1);

return [


    'projects' => [
        'app' => [

            'credentials' => [
                'file' => env('FIREBASE_CREDENTIALS', storage_path('app/services.json')),
            ],



            'database' => [
                'url' => env('FIREBASE_DATABASE_URL'),
            ],

   
        ],
    ],
];
