<?php

namespace kl83\filestorage\controllers;

class RotateController extends BaseController
{
    /**
     * Rotate the image to the left
     * @param $id int
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionLeft($id)
    {
        $file = $this->findFile($id);
        $file->rotateLeft();
    }

    /**
     * Rotate the image to the right
     * @param $id int
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionRight($id)
    {
        $file = $this->findFile($id);
        $file->rotateRight();
    }
}
