<?php

return [

    'default' => 'default',

    'documentations' => [

        'default' => [

            'api' => [
                'title' => env('APP_NAME', 'Laravel') . ' API Documentation',
            ],

            'routes' => [
                'api' => 'api/documentation',
            ],

            'paths' => [
                'docs' => storage_path('api-docs'),
                'docs_json' => 'openapi.json',
                'docs_yaml' => 'openapi.yaml',
                'annotations' => [
                    base_path('app/Http/Controllers/Api'),
                    base_path('app/Models'),
                ],
                'excludes' => [],
                'base' => null,
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'middleware' => ['api'],
        ],

        'api' => [
            'version' => '1.0.0',
        ],

        'ui' => [
            'display' => true,
        ],

        'generator' => [
            'basePath' => null,
            'unshift' => false,
        ],
    ],

    'constants' => [
        'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8081'),
    ],
];
