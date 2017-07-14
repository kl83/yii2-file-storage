<?php

namespace app\controllers;

use Yii;
use app\_extensions\UploadedFile;

class SiteController extends \yii\web\Controller
{

    public function actionIndex()
    {
        if ( Yii::$app->request->isPost ) {
            $file = UploadedFile::getInstanceByName('attachment');
            $result = Yii::$app->getModule('filestorage')->store->save($file);
            if ( $result ) {
                $result['success'] = true;
                return $this->asJson($result);
            } else {
                return $this->asJson([ 'succes' => false ]);
            }
        } else {
            $m = Yii::$app->getModule('filestorage');
            print_r($m->store);
        }
    }
}