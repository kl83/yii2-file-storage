<?php

namespace kl83\filestorage\widgets;

use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\Html;
use kl83\filestorage\Module;
use kl83\filestorage\models\File;

/**
 * Widget to upload one file.
 */
class PicWidget extends \yii\widgets\InputWidget
{
    /**
     * @var array Wrapper html-attributes
     */
    public $widgetOptions = [];

    private function getValue()
    {
        return $this->hasModel()
            ? $this->model->{$this->attribute}
            : $this->value;
    }

    private function getFile()
    {
        $value = $this->getValue();
        if ($value) {
            return File::findOne($value);
        }
    }

    public function run()
    {
        PicWidgetAsset::register($this->view);
        if (empty($this->widgetOptions['id'])) {
            $this->widgetOptions['id'] = self::getId();
        }
        Html::addCssClass($this->widgetOptions, 'kl83-pic-widget');
        $file = $this->getFile();
        return $this->render('pic', [
            'widget' => $this,
            'input' => $this->renderInputHtml('hidden'),
            'file' => $file,
        ]);
    }
}
