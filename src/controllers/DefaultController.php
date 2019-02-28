<?php

namespace kl83\filestorage\controllers;

use kl83\filestorage\models\UploadsHandler;
use kl83\filestorage\models\UploadsIterator;
use kl83\filestorage\models\UploadsResponse;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends BaseController
{
    /**
     * Saves files and return their attributes as json data
     * @param string[]|string|null $attributes
     * $_FILES keys to save files from
     * If is null then all files will be saved
     * @param int|null $filesetId
     * If is null then File will be created without FileSet
     * If is -1 then new FileSet will be created
     * If is numeric then files will be added to specified FileSet
     * @param null $thumbnail Thumbnail configuration id
     * If null, the default thumbnail configuration will be used
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpload($attributes = null, $filesetId = null, $thumbnail = null)
    {
        set_time_limit($this->module->uploadTimeLimit);
        $uploadedFiles = new UploadsIterator($attributes);
        $handler = new UploadsHandler([
            'uploadedFiles' => $uploadedFiles,
            'fileset' => $filesetId > 0 ? $this->findFileSet($filesetId) : $filesetId,
        ]);
        $handler->saveFiles();
        return $this->asJson(new UploadsResponse($handler, $thumbnail));
    }

    /**
     * Changes the order of files in a fileset
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
     * Deletes the file
     * @param integer $id
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findFile($id)->delete();
    }
}
