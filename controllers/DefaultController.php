<?php
namespace kl83\filestorage\controllers;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use kl83\filestorage\Module;
use kl83\filestorage\models\File;
use kl83\filestorage\models\FileSet;

/**
 *
 */
class DefaultController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => '\yii\filters\AccessControl',
                'except' => [ 'upload' ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => Module::getInstance()->managerRoles,
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'delete-file', 'move' ],
                        'matchCallback' => function(){
                            $model = $this->findFile(Yii::$app->request->get('id'));
                            return ! $model->createdBy || $model->createdBy == Yii::$app->user->id;
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => [ 'upload-to-file-set', 'upload-pic-set-item' ],
                        'matchCallback' => function(){
                            $id = Yii::$app->request->get('fileSetId');
                            if ( ! $id ) {
                                return true;
                            } else {
                                $model = FileSet::findOne($id);
                                return ! $model->createdBy || $model->createdBy == Yii::$app->user->id;
                            }
                        },
                    ],
                ],
            ]
        ];
    }

    /**
     * Return array of UloadedFile instances.
     * @param array|string $attributes
     * @return \yii\web\UploadedFile[]
     * @throws BadRequestHttpException
     */
    private function getUploadedFileInstances($attributes)
    {
        if ( ! $attributes ) {
            $attributes = array_keys($_FILES);
        } elseif ( is_string($attributes) ) {
            $attributes = [ $attributes ] ;
        } elseif ( ! is_array($attributes) ) {
            throw new BadRequestHttpException;
        }
        $result = [];
        $uploadedFileClassName = YII_ENV != 'test' ? '\yii\web\UploadedFile' : '\kl83\filestorage\UploadedFile';
        foreach ( $attributes as $attribute ) {
            $result[$attribute] = $uploadedFileClassName::getInstanceByName($attribute);
        }
        return $result;
    }

    /**
     * Saves the file and return his model.
     * @param \yii\web\UploadedFile $uploadedFile
     * @param integer $fileSetId
     * @return File
     */
    private function saveFile($uploadedFile, $fileSetId = null)
    {
        $file = new File([
            'uploadedFile' => $uploadedFile,
            'fileSetId' => $fileSetId ? $fileSetId : 0,
        ]);
        $file->save();
        return $file;
    }

    /**
     * Saves files and return she models.
     * @param \yii\web\UploadedFile[] $uploadedFiles
     * @param integer $fileSetId
     * @return File[]
     */
    private function saveFiles($uploadedFiles, $fileSetId = null)
    {
        $files = [];
        foreach ( $uploadedFiles as $attribute => $uploadedFile ) {
            $files[$attribute] = $this->saveFile($uploadedFile, $fileSetId);
        }
        return $files;
    }

    /**
     * Looks for a fileset by ID or creates a new one.
     * @param integer $id
     * @return \kl83\filestorage\models\FileSet
     * @throws NotFoundHttpException
     */
    private function findFileSet($id)
    {
        if ( ! $id ) {
            $model = new FileSet;
            $model->save();
            return $model;
        } else {
            $model = FileSet::findOne($id);
            if ( $model ) {
                return $model;
            } else {
                throw new NotFoundHttpException(Yii::t(Module::TRANSLATION_NAME, 'File set #{id} not found!', [
                    'id' => $id,
                ]));
            }
        }
    }

    /**
     * Looks for a file by ID.
     * @param integer $id
     * @return \kl83\filestorage\models\File
     * @throws NotFoundHttpException
     */
    private function findFile($id)
    {
        $model = File::findOne($id);
        if ( $model ) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t(Module::TRANSLATION_NAME, 'File #{id} not found!', [
                'id' => $id,
            ]));
        }
    }

    /**
     * Return array of File instances as array for json output.
     * @param File[] $files
     * @return type
     */
    private function filesToJsonData($files)
    {
        $result = [];
        foreach ( $files as $attribute => $file ) {
            if ( $file->id ) {
                $result[$attribute] = [
                    'id' => $file->id,
                    'url' => $file->url,
                ];
            } else {
                $result[$attribute] = [
                    'id' => false,
                    'errors' => $file->errors,
                ];
            }
        }
        return $result;
    }

    /**
     * Saves files and return their attributes as json data.
     * @param string[] $attributes
     * @return string
     */
    public function actionUpload($attributes = null)
    {
        $uploadedFiles = $this->getUploadedFileInstances($attributes);
        $files = $this->saveFiles($uploadedFiles);
        return $this->asJson($this->filesToJsonData($files));
    }

    /**
     * Saves files, adds them to a fileset and returns their attributes as json data.
     * @param integer $fileSetId
     * @param string[] $attributes
     * @return string
     */
    public function actionUploadToFileSet($fileSetId = null, $attributes = null)
    {
        $fileSet = $this->findFileSet($fileSetId);
        $uploadedFiles = $this->getUploadedFileInstances($attributes);
        $files = $this->saveFiles($uploadedFiles, $fileSet->id);
        return $this->asJson([
            'fileSetId' => $fileSet->id,
            'files' => $this->filesToJsonData($files),
        ]);
    }

    /**
     * Saves files, adds them to a fileset and returns rendered view for PicSetWidget.
     * @param integer $fileSetId
     * @param string[] $attributes
     * @return string
     */
    public function actionUploadPicSetItem($fileSetId = null, $attributes = null)
    {
        $fileSet = $this->findFileSet($fileSetId);
        $uploadedFiles = $this->getUploadedFileInstances($attributes);
        $files = $this->saveFiles($uploadedFiles, $fileSet->id);
        $html = [];
        foreach ( $files as $attribute => $file ) {
            $html[$attribute] = $this->renderPartial('@vendor/kl83/yii2-file-storage/views/picset/_item.php', [
                'file' => $file,
                'animate' => true,
            ]);
        }
        return $this->asJson([
            'fileSetId' => $fileSet->id,
            'html' => $html,
        ]);
    }

    /**
     * Change file position in fileset.
     * @param type $id
     * @param type $afterId
     */
    public function actionMove($id, $afterId)
    {
        $this->findFile($id)->moveAfter((int)$afterId);
    }

    /**
     * Deletes the file.
     * @param integer $id
     */
    public function actionDeleteFile($id)
    {
        $this->findFile($id)->delete();
    }
}
