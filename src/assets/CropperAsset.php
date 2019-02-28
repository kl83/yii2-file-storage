<?php

namespace kl83\filestorage\assets;

use yii\web\AssetBundle;

class CropperAsset extends AssetBundle
{
    public $sourcePath = '@npm/cropperjs/dist';
    public $css = ['cropper.min.css'];
    public $js = ['cropper.min.js'];
}
