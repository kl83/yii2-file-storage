<?php

namespace kl83\filestorage\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use kl83\filestorage\Module;
use kl83\filestorage\models\File;
use kl83\filestorage\models\FileSet;
use kl83\filestorage\models\UploadsHandler;
use kl83\filestorage\models\UploadsIterator;
use kl83\filestorage\models\UploadsResponse;

class DefaultController extends Controller
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
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpload($attributes = null, $filesetId = null)
    {
        $uploadedFiles = new UploadsIterator($attributes);
        $handler = new UploadsHandler([
            'uploadedFiles' => $uploadedFiles,
            'fileset' => $filesetId > 0 ? $this->findFileSet($filesetId) : $filesetId,
        ]);
        $handler->saveFiles();
        return $this->asJson(new UploadsResponse($handler));
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

    /**
     * @return bool
     */
    private function isUserCan()
    {
        $roles = Module::getInstance()->managerRoles;
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (Yii::$app->user->can($role)) {
                    return true;
                }
            }
        } else {
            return Yii::$app->user->can($roles);
        }
        return false;
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
        if (
            $model->createdById &&
            $model->createdById != Yii::$app->user->id &&
            !$this->isUserCan()
        ) {
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
        if (
            $model->createdById &&
            $model->createdById != Yii::$app->user->id &&
            !$this->isUserCan()
        ) {
            throw new ForbiddenHttpException();
        }
        return $model;
    }
}
