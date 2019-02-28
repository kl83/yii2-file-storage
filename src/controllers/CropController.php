<?php

namespace kl83\filestorage\controllers;

use kl83\filestorage\models\Cropper;
use Yii;

class CropController extends BaseController
{
    /**
     * @param $id
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($id)
    {
        $cropper = new Cropper([
            'module' => $this->module,
            'file' => $this->findFile($id),
        ]);
        $cropper->setAttributes(Yii::$app->request->get('options'));
        if ($cropper->validate()) {
            $cropper->crop();
        }
    }
}
