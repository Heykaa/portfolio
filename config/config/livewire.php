<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads
    |--------------------------------------------------------------------------
    */
    'temporary_file_upload' => [
        'disk' => 'local',
        'directory' => 'livewire-tmp',
        'rules' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg',
            'wav', 'mp4', 'mov', 'avi', 'wmv',
            'mp3', 'm4a',
            'jpg', 'jpeg', 'webp',
            'pdf',
        ],
        'max_upload_time' => 5,
    ],

];
