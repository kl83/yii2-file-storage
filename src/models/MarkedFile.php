<?php

namespace kl83\filestorage\models;

use kl83\filestorage\Module;
use yii\imagine\Image;

class MarkedFile
{
    /**
     * @var File
     */
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    private function getRelPath(): string
    {
        return 'wm/' . $this->file->relPath;
    }

    private function getPath(): string
    {
        return Module::findInstance()->uploadDir . '/' . $this->getRelPath();
    }

    private function createMarkedFile()
    {
        $path = $this->getPath();
        if (!file_exists($path)) {
            $source = Image::getImagine()
                ->open($this->file->getPath());
            $dirname = dirname($path);
            if (!file_exists($dirname)) {
                mkdir($dirname, 0777, true);
            }
            Module::findInstance()
                ->getWatermark()
                ->addWatermark($source)
                ->save($path, ['quality' => 60]);
        }
    }

    public function delete()
    {
        $path = $this->getPath();
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function getUrl(bool $createFile = true): string
    {
        if ($createFile) {
            $this->createMarkedFile();
        }
        return Module::findInstance()->uploadDirUrl . '/' . $this->getRelPath();
    }
}
