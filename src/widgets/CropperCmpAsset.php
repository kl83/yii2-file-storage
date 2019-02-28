<?php

namespace kl83\filestorage\widgets;

use kl83\filestorage\assets\CropperAsset;
use yii\web\JqueryAsset;

class CropperCmpAsset extends BaseAsset
{
    public $js = ['js/cropper-cmp.js'];
    public $css = ['css/cropper-cmp.css'];
    public $depends = [
        CropperAsset::class,
        JqueryAsset::class,
    ];
}
