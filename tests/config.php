<?php
return [
    'id' => 'Test app',
    'basePath' => __DIR__,
    'components' => [
        'store' => [
            'class' => 'kl83\filestorage\Store',
        ],
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
];
