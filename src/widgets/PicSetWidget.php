<?php

namespace kl83\filestorage\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use kl83\filestorage\models\FileSet;

/**
 * Widget to upload several files grouped in a fileset
 */
class PicSetWidget extends \yii\widgets\InputWidget
{
    /**
     * @var array HTML-attributes
     */
    public $widgetOptions = [];

    /**
     * @var integer|boolean Maximum possible count of images
     * If false then count is unlimited
     */
    public $maxImages = false;

    /**
     * @return int
     */
    private function getValue()
    {
        return $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
    }

    /**
     * @return FileSet|null
     */
    private function getFileSet()
    {
        $value = $this->getValue();
        if ($value) {
            return FileSet::findOne($value);
        }
    }

    public function run()
    {
        PicSetWidgetAsset::register($this->view);
        if (empty($this->widgetOptions['id'])) {
            $this->widgetOptions['id'] = $this->id . '-widget';
        }
        Html::addCssClass($this->widgetOptions, 'kl83-picset-widget');
        $options = [
            'maxImages' => $this->maxImages,
        ];
        $this->view->registerJs('
            $("#' . $this->widgetOptions['id'] . '")
                .picsetWidget(' . Json::encode($options) . ');
        ');
        return $this->render('picset/widget', [
            'widget' => $this,
            'input' => self::renderInputHtml('hidden'),
            'fileSet' => $this->getFileSet(),
        ]);
    }
}
