<?php
namespace kl83\filestorage;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use kl83\filestorage\models\FileSet;

class PicSetWidget extends \yii\widgets\InputWidget
{
    /**
     * Filestorage module id or module instance
     * @var string|\kl83\filestorage\Module
     */
    public $filestorageModule = 'filestorage';
    /**
     * Wrapper DOM element html-attributes
     * @var array
     */
    public $wrapperOptions = [
        'class' => 'kl83-picset-widget',
    ];
    /**
     * Maximum possible count of images. False is unlimited count.
     * @var integer|boolean
     */
    public $maxImages = false;

    public function init()
    {
        parent::init();
        if ( is_string($this->filestorageModule) ) {
            $this->filestorageModule = Yii::$app->getModule($this->filestorageModule);
        }
        $this->wrapperOptions['id'] = "$this->id-wrapper";
        $this->registerTranslations();
        PicSetAsset::register($this->view);
    }

    /**
     * Register translations
     */
    private function registerTranslations()
    {
        Yii::$app->i18n->translations['kl83/widgets/picset'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' =>  __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
            'fileMap' => [
                'kl83/widgets/picset' => 'picset.php',
            ],
        ];
    }

    /**
     * Returns the FileSet by id. If it is not found, then a new one will be returned.
     * @param integer $id
     * @return FileSet
     */
    private function getFileSet($id)
    {
        if ( $id && $fileSet = FileSet::findOne($id) ) {
            return $fileSet;
        } else {
            return new FileSet;
        }
    }

    public function run()
    {
        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        $fileSet = $this->getFileSet($value);
        $params = [
            'uploadUrl' => Url::to(["{$this->filestorageModule->id}/default/upload-pic-set-item"]),
            'removeUrl' => Url::to(["{$this->filestorageModule->id}/default/delete-file"]),
            'moveUrl' => Url::to(["{$this->filestorageModule->id}/default/move"]),
            'maxImages' => $this->maxImages,
        ];
        $this->view->registerJs("kl83RegisterPicSetWidget('{$this->wrapperOptions['id']}', ".Json::encode($params).");");
        return $this->render('picset/widget', [
            'widget' => $this,
            'hasModel' => $this->hasModel(),
            'fileSet' => $fileSet,
        ]);
    }
}
