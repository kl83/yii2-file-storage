<?php
namespace kl83\filestorage;

/**
 * Asset for PicSetWidget.
 */
class PicSetAsset extends \yii\web\AssetBundle
{
    /**
     * @var array CSS.
     */
    public $css = ['base.css'];

    /**
     * @var array JS.
     */
    public $js = ['base.js'];

    /**
     * @var array Asset dependencies.
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
        'kl83\assets\JQueryFormAsset',
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (YII_DEBUG) {
            $this->publishOptions['forceCopy'] = true;
        }
        $this->sourcePath = __DIR__ . '/dist/picset-widget';
        parent::init();
    }
}
