<?php
namespace kl83\filestorage;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;

class PicWidget extends \yii\widgets\InputWidget
{
    /**
     * Wrapper DOM element html-attributes
     * @var array
     */
    public $wrapperOptions = [
        'class' => 'kl83-pic-widget',
    ];
    /**
     * Filestorage module instance
     * @var \kl83\filestorage\Module
     */
    private $filestorageModule;

    public function init()
    {
        parent::init();
        $this->filestorageModule = Module::findInstance();
        $this->wrapperOptions['id'] = "$this->id-wrapper";
        PicAsset::register($this->view);
    }

    public function run()
    {
        $value = $this->hasModel() ? $this->model->{$this->attribute} : $this->value;
        if ( $value ) {
            $this->wrapperOptions['class'] .= " show-picture";
        }
        $params = [
            'uploadUrl' => Url::to(["{$this->filestorageModule->id}/default/upload"]),
            'removeUrl' => Url::to(["{$this->filestorageModule->id}/default/delete-file"]),
        ];
        $this->view->registerJs("kl83RegisterPicWidget('{$this->wrapperOptions['id']}', ".Json::encode($params).");");
        return $this->render('pic', [
            'widget' => $this,
            'hasModel' => $this->hasModel(),
            'value' => $value,
        ]);
    }
}
