<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'ruta_documento_entrada' => 'stramites/documento_entrada',
    ],

    'sistema_rpp' =>[
        'token' => env('SISTEMA_RPP_SERVICE_TOKEN'),
        'insertar_movimiento_registral' => env('SISTEMA_RPP_SERVICE_INSERT'),
        'actualizar_movimiento_registral' => env('SISTEMA_RPP_SERVICE_UPDATE'),
        'cambiar_tipo_servicio' => env('SISTEMA_RPP_SERVICE_UPDATE_SERVICE'),
        'actualizar_paginas' => env('SISTEMA_RPP_SERVICE_UPDATE_PAGES'),
    ]

];
