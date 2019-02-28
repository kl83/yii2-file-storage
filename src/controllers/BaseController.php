<?php

namespace kl83\filestorage\controllers;

use kl83\filestorage\models\File;
use kl83\filestorage\models\FileSet;
use kl83\filestorage\Module;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * @property Module $module
 */
abstract class BaseController extends Controller
{
    /**
     * @return bool
     */
    protected function isUserCan()
    {
        $roles = $this->module->managerRoles;
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
    protected function findFile($id)
    {
        $file = File::findOne($id);
        if (!$file) {
            throw new NotFoundHttpException();
        }
        if (
            $file->createdById &&
            $file->createdById != Yii::$app->user->id &&
            !$this->isUserCan()
        ) {
            throw new ForbiddenHttpException();
        }
        return $file;
    }

    /**
     * @param integer $id
     * @return FileSet
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    protected function findFileSet($id)
    {
        $fileSet = FileSet::findOne($id);
        if (!$fileSet) {
            throw new NotFoundHttpException();
        }
        if (
            $fileSet->createdById &&
            $fileSet->createdById != Yii::$app->user->id &&
            !$this->isUserCan()
        ) {
            throw new ForbiddenHttpException();
        }
        return $fileSet;
    }
}
