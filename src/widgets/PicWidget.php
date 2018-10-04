<?php

namespace kl83\filestorage\widgets;

use yii\helpers\Url;
use yii\helpers\Json;
use kl83\filestorage\Module;
use kl83\filestorage\models\File;

/**
 * Widget to upload one file.
 */
class PicWidget extends \yii\widgets\InputWidget
{
    /**
     * @var array Wrapper DOM element html-attributes.
     */
    public $wrapperOptions = [
        'class' => 'kl83-pic-widget',
    ];

    /**
     * @var \kl83\filestorage\Module Filestorage module instance.
     */
    private $filestorageModule;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->filestorageModule = Module::findInstance();
        $this->wrapperOptions['id'] = $this->id . '-wrapper';
        PicWidgetAsset::register($this->view);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        if ($value) {
            $file = File::findOne($value);
            if ($file) {
                $this->wrapperOptions['class'] .= " show-picture";
            }
        }
        $params = [
            'uploadUrl' => Url::to([$this->filestorageModule->id . '/default/upload']),
            'removeUrl' => Url::to([$this->filestorageModule->id . '/default/delete-file']),
        ];
        $this->view->registerJs(
            'kl83RegisterPicWidget("' . $this->wrapperOptions['id'] . '", ' . Json::encode($params) . ');'
        );
        return $this->render('pic', [
            'widget' => $this,
            'hasModel' => $this->hasModel(),
            'value' => $value,
            'file' => isset($file) ? $file : null,
        ]);
    }
}
