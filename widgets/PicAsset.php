<?php
namespace kl83\widgets;

class PicAsset extends \yii\web\AssetBundle
{
    public $css = [ 'base.css' ];
    public $js = [ 'base.js' ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'kl83\assets\JQueryFormAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.'pic-widget';
        parent::init();
    }
}
