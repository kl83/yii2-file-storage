<?php

namespace kl83\filestorage\controllers;

use kl83\filestorage\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RotateController extends Controller
{
    /**
     * Rotate the image to the left
     * @param $id int
     * @throws NotFoundHttpException
     */
    public function actionLeft($id)
    {
        $file = $this->findFile($id);
        $file->rotateLeft();
    }

    /**
     * Rotate the image to the right
     * @param $id int
     * @throws NotFoundHttpException
     */
    public function actionRight($id)
    {
        $file = $this->findFile($id);
        $file->rotateRight();
    }

    /**
     * @param $id int
     * @return File|null
     * @throws NotFoundHttpException
     */
    private function findFile($id): ?File
    {
        $file = File::findOne($id);
        if (!$file) {
            throw new NotFoundHttpException();
        }
        return $file;
    }
}
