<?php

namespace kl83\filestorage\assets;

use yii\web\AssetBundle;

class TinymceFmAsset extends AssetBundle
{
    public $sourcePath = '@vendor/kl83/yii2-file-storage/src/web';
    public $js = ['js/tinymce-fm.js'];
    public $depends = [
        'yii\web\JqueryAsset',
        'kl83\filestorage\assets\JqueryFormAsset',
    ];
}
