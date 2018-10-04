<?php

namespace kl83\filestorage\widgets;

class PicSetWidgetAsset extends BaseAsset
{
    public $css = ['css/picset-widget.css'];
    public $js = ['js/picset-widget.js'];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'kl83\filestorage\assets\JqueryFormAsset',
    ];
}
