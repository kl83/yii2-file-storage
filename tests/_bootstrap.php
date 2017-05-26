<?php
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require(__DIR__.'/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__.'/../vendor/autoload.php');

\Yii::$classMap['yii\web\UploadedFile'] = __DIR__.'/_extensions/UploadedFile.php';
