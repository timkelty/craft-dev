<?php

// config/app.web.php
return [
    // Equivalent functionality to\craft\config\GeneralConfig::$enableBasicHttpAuth
    'as optionalBasicLogin' => [
        'class' => \craft\filters\BasicHttpAuthLogin::class,

        // List of action ID patterns, see \yii\filters\auth\AuthMethod::$optional
        'optional' => ['*'],
    ],

    // Required user login for all site requests, when not in production
    'as requiredBasicLogin' => [
        'class' => \craft\filters\BasicHttpAuthLogin::class,
        'enabled' => \craft\helpers\App::env('CRAFT_ENVIRONMENT') !== 'production',
    ],

    // Block all frontend requests with default credentials from env vars
    'as basicStatic' => \craft\filters\BasicHttpAuthStatic::class,

    // Block all frontend requests to siteA with configured credentials
    'as basicStaticWithConfig' => [
        'class' => \craft\filters\BasicHttpAuthStatic::class,
        'username' => 'foo',
        'password' => 'secret',
        'site' => ['siteA'],
    ],
];
