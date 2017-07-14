<?php
return [
    'id' => 'Test app',
    'basePath' => __DIR__,
    'components' => [
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'request' => [
            'scriptFile' => __DIR__.'/index-test.php',
            'scriptUrl' => '',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
    ],
    'modules' => [
        'filestorage' => 'kl83\modules\filestorage\Module',
    ],
];
