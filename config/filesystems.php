<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'users' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/users/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'admins' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/admins/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'cars' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/cars/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'complains' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/complains/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'customers_service' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/customers_service/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'free_services' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/free_services/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'masafrs' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/masafrs/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'messages' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/messages/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'request_services' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/request_services/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'users_id' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/users_id/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'masafrs_id' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/masafrs_id/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'driving_licenses' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/driving_licenses/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'main_trip_categories'=> [
            'driver' => 'local',
            'root' => base_path() . '/public/images/main_trip_categories/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'main_request_categories' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/main_request_categories/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'copons' => [
            'driver' => 'local',
            'root' => base_path() . '/public/images/copons/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        'advertisings'=> [
            'driver' => 'local',
            'root' => base_path() . '/public/images/advertisings/',
            'url' => env('APP_URL') . '/public',
            'visibility' => 'public',
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
