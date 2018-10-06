<?php

namespace kl83\filestorage\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use kl83\filestorage\models\File;
use kl83\filestorage\models\FileSet;
use kl83\filestorage\models\UploadsHandler;
use kl83\filestorage\models\UploadsIterator;
use kl83\filestorage\models\UploadsResponse;

/**
 * File operations.
 */
class DefaultController extends \yii\web\Controller
{
    /**
     * Saves files and return their attributes as json data
     * @param string[]|string|null $attributes
     * $_FILES keys to save files from
     * If is null then all files will be saved
     * @param int|null $filesetId
     * If is null then File will be created without FileSet
     * If is 0 then new FileSet will be created
     * If is numeric and greater than 0 then files will be added to specified FileSet
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpload($attributes = null, $filesetId = null)
    {
        Yii::info($filesetId);
        $uploadedFiles = new UploadsIterator($attributes);
        $handler = new UploadsHandler([
            'uploadedFiles' => $uploadedFiles,
            'fileset' => $filesetId ? $this->findFileSet($filesetId) : $filesetId,
        ]);
        $handler->saveFiles();
        return $this->asJson(new UploadsResponse($handler));
    }

    /**
     * Change file order in fileset.
     * @param integer $id
     * @param integer $afterId
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionMove($id, $afterId)
    {
        $this->findFile($id)->moveAfter((int)$afterId);
    }

    /**
     * Deletes the file.
     * @param integer $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findFile($id)->delete();
    }

    /**
     * @param integer $id
     * @return File
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    private function findFile($id)
    {
        $model = File::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        if ($model->createdById && $model->createdById != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        return $model;
    }

    /**
     * @param integer $id
     * @return FileSet
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    private function findFileSet($id)
    {
        $model = FileSet::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        if ($model->createdById && $model->createdById != Yii::$app->user->id) {
            throw new ForbiddenHttpException();
        }
        return $model;
    }
}
