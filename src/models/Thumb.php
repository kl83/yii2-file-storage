<?php

namespace kl83\filestorage\models;

use Exception;
use kl83\filestorage\Module;
use Yii;
use yii\base\BaseObject;
use yii\imagine\Image;

class Thumb extends BaseObject
{
    /**
     * @var ThumbFactory
     */
    public $factory;

    /**
     * @var File
     */
    public $file;

    public function createThumbnail()
    {
        $path = $this->getPath();
        if (!file_exists($path)) {
            try {
                $dirname = dirname($path);
                if (!file_exists($dirname)) {
                    mkdir($dirname, 0777, true);
                }
                $img = Image::thumbnail(
                    $this->file->getPath(),
                    $this->factory->width,
                    $this->factory->height,
                    $this->factory->mode
                );
                $img->save($path, ['quality' => Module::getInstance()->jpegQuality]);
            } catch (Exception $exception) {
                Yii::warning($exception->getMessage());
            }
        }
    }

    public function deleteThumbnail()
    {
        $path = $this->getPath();
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function updateThumbnail()
    {
        $this->deleteThumbnail();
        $this->createThumbnail();
    }

    public function isExists(): bool
    {
        return file_exists($this->getPath());
    }

    private function getRelPath(bool $encode = false): string
    {
        return 'thumbs/' . $this->factory->id . '/' .
            $encode
                ? $this->file->getUrlEncodedRelPath()
                : $this->file->relPath;
    }

    public function getPath(): string
    {
        return Module::findInstance()->uploadDir . '/' . $this->getRelPath();
    }

    public function getUrl(): string
    {
        return Module::findInstance()->uploadDirUrl . '/' . $this->getRelPath(true);
    }
}
