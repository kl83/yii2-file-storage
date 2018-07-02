<?php

$config = [
    'id' => 'Test app',
    'language' => 'ru-RU',
    'basePath' => __DIR__,
    'vendorPath' => __DIR__ . '/../vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
    ],
    'components' => [
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=filestorage',
            'username' => 'filestorage',
            'password' => 'fugvrbfuIIQhtwis',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'assetManager' => [
            'linkAssets' => true,
        ],
    ],
    'modules' => [
        'filestorage' => [
            'class' => 'kl83\filestorage\Module',
            'maxImageWidth' => 800,
            'maxImageHeight' => 600,
        ]
    ],
];

if ( YII_ENV == 'test' ) {
    $config['components']['request']['scriptFile'] = __DIR__.'/index-test.php';
    $config['components']['request']['scriptUrl'] = '';
}

return $config;
