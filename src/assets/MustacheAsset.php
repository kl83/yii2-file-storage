<?php

namespace kl83\filestorage\assets;

use yii\web\AssetBundle;

class MustacheAsset extends AssetBundle
{
    public $sourcePath = '@bower/mustache';
    public $js = ['mustache.min.js'];
}
