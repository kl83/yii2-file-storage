<?php

namespace kl83\filestorage;

use yii\helpers\Json;
use yii\helpers\Url;
use kl83\filestorage\Module;
use kl83\filestorage\models\FileSet;

/**
 * Widget to upload several files grouped in a set.
 */
class PicSetWidget extends \yii\widgets\InputWidget
{
    /**
     * @var array Wrapper DOM element html-attributes.
     */
    public $wrapperOptions = [
        'class' => 'kl83-picset-widget',
    ];

    /**
     * @var integer|boolean Maximum possible count of images. False is
     * unlimited count.
     */
    public $maxImages = false;

    /**
     * @var \kl83\filestorage\Module Filestorage module instance.
     */
    private $filestorageModule;

    /**
     * {inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->filestorageModule = Module::findInstance();
        $this->wrapperOptions['id'] = "$this->id-wrapper";
        PicSetAsset::register($this->view);
    }

    /**
     * Returns the FileSet by id. If it is not found, then a new one will be returned.
     * @param integer $id
     * @return FileSet
     */
    private function getFileSet($id)
    {
        if ($id && $fileSet = FileSet::findOne($id)) {
            return $fileSet;
        } else {
            return new FileSet;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        $fileSet = $this->getFileSet($value);
        $params = [
            'uploadUrl' => Url::to([$this->filestorageModule->id . '/default/upload-pic-set-item']),
            'removeUrl' => Url::to([$this->filestorageModule->id . '/default/delete-file']),
            'moveUrl' => Url::to([$this->filestorageModule->id . '/default/move']),
            'maxImages' => $this->maxImages,
        ];
        $this->view->registerJs(
            'kl83RegisterPicSetWidget("' . $this->wrapperOptions['id'] . '", ' . Json::encode($params) . ');'
        );
        return $this->render('picset/widget', [
            'widget' => $this,
            'hasModel' => $this->hasModel(),
            'fileSet' => $fileSet,
        ]);
    }
}
