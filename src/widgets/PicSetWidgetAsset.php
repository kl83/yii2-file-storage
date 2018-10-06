<?php

namespace kl83\filestorage\widgets;

use Yii;

class PicSetWidgetAsset extends BaseAsset
{
    public $css = ['css/picset-widget.css'];
    public $js = ['js/picset-widget.js'];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'kl83\filestorage\assets\JqueryFormAsset',
        'kl83\filestorage\assets\MustacheAsset',
        'kl83\filestorage\assets\ModuleAsset',
    ];

    public function init()
    {
        parent::init();
        Yii::$app->view->registerJsVar(
            'picsetItemTemplate',
            file_get_contents(__DIR__ . '/views/picset/_item.mustache')
        );
    }
}
