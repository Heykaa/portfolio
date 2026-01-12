<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    'disks' => [

        /*
        |--------------------------------------------------------------------------
        | Local Disk (IMPORTANT for Livewire temp uploads)
        |--------------------------------------------------------------------------
        */
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'), // ✅ STANDARD (Livewire temp uses this)
            'throw' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Public Disk (for images uploaded via Filament)
        |--------------------------------------------------------------------------
        */
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // If you have others (s3, etc.) keep as-is.
    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
