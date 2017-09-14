<?php
namespace kl83\filestorage;

class PicSetAsset extends \yii\web\AssetBundle
{
    public $css = [ 'base.css' ];
    public $js = [ 'base.js' ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'kl83\assets\JQueryFormAsset',
    ];

    public function init()
    {
        if ( YII_DEBUG ) {
            $this->publishOptions['forceCopy'] = true;
        }
        $this->sourcePath = __DIR__.'/dist/picset-widget';
        parent::init();
    }
}
