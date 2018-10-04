<?php

namespace kl83\filestorage\widgets;

class PicWidgetAsset extends BaseAsset
{
    public $css = ['css/pic-widget.css'];
    public $js = ['js/pic-widget.js'];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'kl83\filestorage\assets\JqueryFormAsset',
    ];
}
