<?php

namespace kl83\filestorage\models;

use Exception;
use Yii;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\base\ModelEvent;
use yii\web\UploadedFile;
use yii\imagine\Image;
use kl83\filestorage\Module;

class FileBuilder
{
    /**
     * @var Module
     */
    private $module;

    /**
     * @var File
     */
    private $file;

    /**
     * @var UploadedFile
     */
    private $uploadedFile;

    /**
     * @var string
     */
    private $downloadSrc;

    public function __construct()
    {
        $this->module = Module::findInstance();
        $this->file = new File();
    }

    public function createdBy($userId)
    {
        $this->file->createdById = $userId;
        return $this;
    }

    public function createdByCurrentUser()
    {
        $this->file->createdById = Yii::$app->user->id;
        return $this;
    }

    public function fileset($fileset)
    {
        if ($fileset) {
            $this->file->fileSetId =
                is_numeric($fileset) ? $fileset : $fileset->id;
        }
        return $this;
    }

    public function uploadedFile($uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }

    public function download($src)
    {
        $this->downloadSrc = $src;
        return $this;
    }

    /**
     * If the file is a image then minimizes his size by decreasing resolution
     * and quality.
     */
    private function minimizeImage()
    {
        try {
            $img = Image::getImagine()->open($this->file->path);
        } catch (Exception $e) {
            return;
        }
        $box = $img->getSize();
        if ($box->getWidth() > $this->module->maxImageWidth) {
            $box = $box->widen($this->module->maxImageWidth);
        }
        if ($box->getHeight() > $this->module->maxImageHeight) {
            $box = $box->heighten($this->module->maxImageHeight);
        }
        $img->resize($box);
        $img->save(null, ['quality' => 60]);
    }

    private function getUserDir()
    {
        $userId = (int)Yii::$app->user->id;
        return ($userId % 1000) . '/' . $userId;
    }

    /**
     * Generates a relative path for a new file
     * Path relative to upload directory
     * @return string
     * @throws \yii\base\Exception
     */
    private function generateFilePath()
    {
        if ($this->uploadedFile) {
            $fileName = $this->uploadedFile->name;
        } else {
            $fileName = basename($this->downloadSrc);
        }
        do {
            $randomDir = Yii::$app->security->generateRandomString();
            if (Yii::$app->user->isGuest) {
                $randomDir = substr($randomDir, 0, 2) . '/' .
                    substr($randomDir, 2);
            }
            $path = $this->getUserDir() . '/' . $randomDir . '/' . $fileName;
        } while (file_exists($this->module->uploadDir . '/' . $path));
        return $path;
    }

    public function __beforeValidateFile()
    {
        $this->file->relPath = $this->generateFilePath();
    }

    public function __beforeInsertFile(ModelEvent $event)
    {
        $path = $this->file->path;
        if (!mkdir(dirname($path), 0777, true)) {
            throw new Exception();
        }
        if ($this->uploadedFile) {
            if (!$this->uploadedFile->saveAs($path)) {
                throw new Exception();
            }
        } elseif (preg_match('~^https?://~', $this->downloadSrc)) {
            try {
                file_put_contents($path, fopen($this->downloadSrc, 'r'));
            } catch (ErrorException $e) {
                $event->isValid = false;
            }
        }
        $this->minimizeImage();
    }

    public function build()
    {
        $this->file->on(
            ActiveRecord::EVENT_BEFORE_VALIDATE,
            [$this, '__beforeValidateFile']
        );
        $this->file->on(
            ActiveRecord::EVENT_BEFORE_INSERT,
            [$this, '__beforeInsertFile']
        );
        return $this->file;
    }
}
