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
                    $this->factory->height
                );
                $img->save($path, ['quality' => 60]);
            } catch (Exception $exception) {
                Yii::warning($exception->getMessage());
            }
        }
    }

    private function getRelPath(): string
    {
        return 'thumbs/' . $this->factory->id . '/' . $this->file->relPath;
    }

    public function getPath(): string
    {
        return Module::findInstance()->uploadDir . '/' . $this->getRelPath();
    }

    public function getUrl(): string
    {
        return Module::findInstance()->uploadDirUrl . '/' . $this->getRelPath();
    }
}
