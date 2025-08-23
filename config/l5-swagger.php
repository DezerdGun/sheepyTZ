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
                    base_path('app'),
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
        // константы можно не использовать, все пути в аннотациях задаем строкой
    ],
];
