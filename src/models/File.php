<?php

namespace kl83\filestorage\models;

use Yii;
use yii\base\Exception;
use yii\imagine\Image;
use kl83\filestorage\Module;

/**
 * Model for work with a file.
 * @property integer $id
 * @property integer $idx
 * @property integer $fileSetId
 * @property integer $createdAt
 * @property integer $createdBy
 * @property string $url
 * @property string $path
 * @property string $relPath
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @var \yii\web\UploadedFile Uploaded file
     */
    public $uploadedFile;

    /**
     * @var kl83\filestorage\Module Module instance
     */
    private $moduleInstance;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return "{{%kl83_file}}";
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => '\kl83\behaviours\SortableBehaviour',
                'parentIdField' => false,
            ],
        ];
    }

    /**
     * If the file is a image then minimizes his size by decreasing quality to 60.
     */
    private function minimizeImage()
    {
        try {
            $img = Image::getImagine()->open($this->path);
        } catch (\Exception $e) {
            return;
        }
        $box = $img->getSize();
        if (
            $box->getWidth() > $this->moduleInstance->maxImageWidth ||
            $box->getHeight() > $this->moduleInstance->maxImageHeight
        ) {
            $box = $box->widen($this->moduleInstance->maxImageWidth);
            if ($box->getHeight() > $this->moduleInstance->maxImageHeight) {
                $box = $box->heighten($this->moduleInstance->maxImageHeight);
            }
            $img->resize($box);
        }
        $img->save(null, ['quality' => 60]);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->moduleInstance = Module::findInstance();
        $this->on(self::EVENT_BEFORE_INSERT, function () {
            $this->createdBy = (int)Yii::$app->user->id;
        });
        $this->on(self::EVENT_BEFORE_DELETE, function () {
            @unlink($this->path);
        });
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->minimizeImage();
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uploadedFile'], function(){
                if ($this->isNewRecord) {
                    if (
                        !file_exists($this->moduleInstance->uploadDir) ||
                        !is_dir($this->moduleInstance->uploadDir)
                    ) {
                        $msg = Yii::t(
                            Module::TRANSLATION_NAME,
                            'Directory "{d}" don\'t exists or is not a directory!',
                            ['d' => $this->moduleInstance->uploadDir]
                        );
                        throw new Exception($msg);
                    }
                }
            }],
            [['uploadedFile'], function () {
                if ($this->isNewRecord) {
                    if (preg_match('~\.(php|cgi|htacess|htpasswd)$~', $this->uploadedFile->name, $m)) {
                        $msg = Yii::t(
                            Module::TRANSLATION_NAME,
                            'Uploading {t} files is forbidden!',
                            ['t' => $m[1]]
                        );
                        $this->addError('uploadedFile', $msg);
                    } else {
                        $this->saveFile();
                    }
                }
            }],
        ];
    }

    /**
     * Save file to random directory in upload directory.
     * Name of file is not changing.
     * @param \yii\web\UploadedFile $file
     * @return boolean|array
     * @throws \yii\base\Exception
     */
    private function saveFile()
    {
        $this->relPath = $this->generateFilePath($this->uploadedFile->name);
        if (!$this->uploadedFile->saveAs($this->path)) {
            $msg = Yii::t(
                Module::TRANSLATION_NAME,
                'Could not save file "{f}"!',
                ['f' => $this->path]
            );
            throw new Exception($msg);
        }
    }

    /**
     * Create a directory or throw an exception.
     * @param string $dir
     * @throws Exception
     */
    private function createDirectory($dir)
    {
        if (!mkdir($dir, 0777, true)) {
            $msg = Yii::t(
                Module::TRANSLATION_NAME,
                'Could not create directory "{d}"!',
                ['d' => $dir]
            );
            throw new Exception($msg);
        }
    }

    /**
     * Return user directory relative path.
     * It is at the upload dir.
     * @param integer $userId
     * @return string
     * @throws \yii\base\Exception
     */
    public function getUserDir($userId = false)
    {
        if ($userId === false) {
            $userId = (int)Yii::$app->user->id;
        } else {
            $userId = (int)$userId;
        }
        $relPath = ($userId % 1000) . '/' . $userId;
        $userDir = $this->moduleInstance->uploadDir . '/' . $relPath;
        if (!file_exists($userDir)) {
            $this->createDirectory($userDir);
        }
        return $relPath;
    }

    /**
     * Return generated relative path for new file.
     * Path is relative to upload directory.
     * Name of file non changing.
     * @param string $fileName
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateFilePath($fileName)
    {
        $userDir = $this->getUserDir();
        do {
            $randomDir = Yii::$app->security->generateRandomString();
            if (Yii::$app->user->getIsGuest()) {
                $randomDir = substr($randomDir, 0, 2) . '/' . substr($randomDir, 2);
            }
            $relDirPath = $userDir . '/' . $randomDir;
            $relFilePath = $relDirPath . '/' . $fileName;
            $filePath = $this->moduleInstance->uploadDir . '/' . $relFilePath;
        } while (file_exists($filePath));
        $dirPath = $this->moduleInstance->uploadDir . '/' . $relDirPath;
        if (!file_exists($dirPath)) {
            $this->createDirectory($dirPath);
        }
        return $relFilePath;
    }

    /**
     * File path.
     * @return string
     */
    public function getPath()
    {
        return $this->moduleInstance->uploadDir . '/' . $this->relPath;
    }

    /**
     * File url.
     * @return string
     */
    public function getUrl()
    {
        return $this->moduleInstance->uploadDirUrl . '/' . $this->relPath;
    }
}
