<?php

namespace kl83\filestorage\widgets;

use kl83\filestorage\models\File;
use yii\helpers\Html;

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
     * @var string Thumbnail configuration id
     * By default this is the first thumbnail configuration
     * If false, then a full-sized picture will be used
     */
    public $thumbnail;

    /**
     * @var bool Watermark thumbnail
     * Only works when $this->thumbnail is false
     */
    public $watermark = false;

    /**
     * @var bool
     */
    public $enableRotation = true;

    /**
     * @var bool
     */
    public $enableCropper;

    /**
     * @var array
     * @see https://github.com/fengyuanchen/cropperjs#options
     */
    public $cropperOptions = [];

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

    public function getThumbnailUrl(): ?string
    {
        if ($file = $this->getFile()) {
            if ($this->thumbnail === false) {
                return $file->getUrl($this->watermark);
            } else {
                return $file->getThumbUrl($this->thumbnail);
            }
        }
        return null;
    }

    public function run()
    {
        PicWidgetAsset::register($this->view);
        Html::addCssClass($this->widgetOptions, 'kl83-pic-widget');
        if ($this->getValue()) {
            Html::addCssClass($this->widgetOptions,'show-picture');
        }
        $this->widgetOptions['data']['thumbnail-fullsize'] = $this->thumbnail === false;
        $this->widgetOptions['data']['thumbnail'] = $this->thumbnail;
        if ($this->enableRotation) {
            Html::addCssClass($this->widgetOptions, 'enable-rotation');
        }
        if ($this->enableCropper) {
            CropperCmpAsset::register($this->view);
            $this->widgetOptions['data']['cropper'] = $this->cropperOptions;
        }
        return $this->render('pic', [
            'widget' => $this,
            'input' => $this->renderInputHtml('hidden'),
            'file' => $this->getFile(),
        ]);
    }
}
