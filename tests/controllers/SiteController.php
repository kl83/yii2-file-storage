<?php

namespace app\controllers;

use Yii;
use app\_extensions\UploadedFile;

class SiteController extends \yii\web\Controller
{

    public function actionIndex()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $file = UploadedFile::getInstanceByName('attachment');
        $result = Yii::$app->store->save($file);
        if ( $result ) {
            $result['success'] = true;
            return $result;
        } else {
            return [ 'succes' => false ];
        }
    }
}