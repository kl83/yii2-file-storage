<?php

namespace app\controllers;

use Yii;
use kl83\modules\filestorage\models\FileSet;

class SiteController extends \yii\web\Controller
{

    public function actionNewPicSet()
    {
        return $this->render('new-pic-set');
    }

    public function actionPicSet($id)
    {
        return $this->render('pic-set', [
            'fileSet' => FileSet::findOne($id),
        ]);
    }

    public function actionPicSetMaxImages($id)
    {
        return $this->render('pic-set-max-images', [
            'fileSet' => FileSet::findOne($id),
        ]);
    }
}
