<?php
namespace kl83\filestorage;

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
        if ( YII_DEBUG ) {
            $this->publishOptions['forceCopy'] = true;
        }
        $this->sourcePath = __DIR__.'/dist/pic-widget';
        parent::init();
    }
}
