<?php
namespace kl83\modules\filestorage\controllers;

use yii\filters\VerbFilter;
use kl83\modules\filestorage\Module;
use yii\web\UploadedFile;

class DefaultController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'upload' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return 'ok 1';
    }

    public function actionUpload()
    {
        $result = [];
        foreach ( array_keys($_FILES) as $attr ) {
            if ( YII_ENV == 'test' ) {
                $file = \app\_extensions\UploadedFile::getInstanceByName($attr);
            } else {
                $file = UploadedFile::getInstanceByName($attr);
            }
            $result[$attr] = Module::getInstance()->store->save($file);
        }
        if ( $result ) {
            return $this->asJson([ 'files' => $result, 'success' => true ]);
        } else {
            return $this->asJson([ 'succes' => false ]);
        }
    }
}