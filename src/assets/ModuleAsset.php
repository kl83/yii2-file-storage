<?php

namespace kl83\filestorage\assets;

use Yii;
use yii\web\AssetBundle;
use kl83\filestorage\Module;

class ModuleAsset extends AssetBundle
{
    public function init()
    {
        parent::init();
        $module = Module::findInstance();
        Yii::$app->view->registerJsVar('kl83FileStorageOptions', [
            'moduleId' => $module->id,
        ]);
    }
}
