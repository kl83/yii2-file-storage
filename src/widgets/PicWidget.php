<?php

namespace kl83\filestorage\widgets;

use yii\helpers\Html;
use kl83\filestorage\models\File;

/**
 * Widget to upload one file
 */
class PicWidget extends \yii\widgets\InputWidget
{
    /**
     * @var array HTML-attributes
     */
    public $widgetOptions = [];

    /**
     * @return integer
     */
    private function getValue()
    {
        return $this->hasModel()
            ? $this->model->{$this->attribute}
            : $this->value;
    }

    /**
     * @return File|null
     */
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
        Html::addCssClass($this->widgetOptions, 'kl83-pic-widget');
        if ($this->getValue()) {
            Html::addCssClass($this->widgetOptions,'show-picture');
        }
        return $this->render('pic', [
            'widget' => $this,
            'input' => $this->renderInputHtml('hidden'),
            'file' => $this->getFile(),
        ]);
    }
}
