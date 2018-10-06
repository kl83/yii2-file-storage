<?php

namespace kl83\filestorage\models;

use Yii;
use yii\base\BaseObject;
use yii\web\UploadedFile;

/**
 * Saves uploaded files
 */
class UploadsHandler extends BaseObject
{
    const CREATE_NEW_FILESET = -1;

    /**
     * @var UploadsIterator
     */
    public $uploadedFiles;

    /**
     * @var int|FileSet|null
     * If is self::CREATE_NEW_FILESET then new FileSet will be created
     */
    public $fileset;

    /**
     * @var File[]
     */
    public $savedFiles = [];

    public function init()
    {
        if (is_numeric($this->fileset)) {
            if ($this->fileset == self::CREATE_NEW_FILESET) {
                $this->fileset = new FileSet([
                    'createdById' => Yii::$app->user->id,
                ]);
            } else {
                $this->fileset = FileSet::findOne($this->fileset);
            }
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $attribute
     * @param array $arr
     */
    private function saveFile($uploadedFile, $attribute, &$arr)
    {
        if (is_array($uploadedFile)) {
            $arr[$attribute] = [];
            foreach ($uploadedFile as $key => $file) {
                $this->saveFile($file, $key, $arr[$attribute]);
            }
        } else {
            $file = (new FileBuilder())
                ->uploadedFile($uploadedFile)
                ->fileset($this->fileset)
                ->createdByCurrentUser()
                ->build();
            $file->save();
            $arr[$attribute] = $file;
        }
    }

    public function saveFiles()
    {
        if ($this->fileset && $this->fileset->isNewRecord) {
            $this->fileset->save();
        }
        foreach ($this->uploadedFiles as $attribute => $uploadedFile) {
            $this->saveFile($uploadedFile, $attribute, $this->savedFiles);
        }
    }
}
