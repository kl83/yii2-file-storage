<?php

namespace app\controllers;

use kl83\modules\filestorage\models\FileSet;
use kl83\modules\filestorage\models\File;

class SiteController extends \yii\web\Controller
{
    public $defaultAction = 'new-pic-set';

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

    public function actionPic($id = null)
    {
        return $this->render('pic', [
            'file' => $id ? File::findOne($id) : new File,
        ]);
    }
}
