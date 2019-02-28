<?php

namespace kl83\filestorage\models;

use Imagine\Image\Box;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use yii\base\Model;
use yii\imagine\Image;

class Cropper extends Model
{
    /**
     * @var int
     */
    public $x;

    /**
     * @var int
     */
    public $y;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var File
     */
    public $file;

    /**
     * @var \kl83\filestorage\Module
     */
    public $module;

    public function rules()
    {
        return [
            [['x', 'y', 'width', 'height'], 'required'],
            [['x', 'y', 'width', 'height'], 'integer'],
        ];
    }

    private function getJpegQuality(): int
    {
        return $this->module ? $this->module->jpegQuality : 60;
    }

    public function crop()
    {
        $path = $this->file->getPath();
        $imagine = Image::getImagine();

        $img = $imagine->open($path);
        $imgBox = $img->getSize();

        $isOutOfBox = $this->x < 0 ||
            $this->y < 0 ||
            $this->x + $this->width > $imgBox->getWidth() ||
            $this->y + $this->height > $imgBox->getHeight();

        if ($isOutOfBox) {
            $imgCopy = $imagine->create(
                new Box($this->width, $this->height),
                (new RGB())->color('fff')
            );

            if ($this->x > 0) {
                $imgBox = new Box($imgBox->getWidth() - $this->x, $imgBox->getHeight());
                $img->crop(new Point($this->x, 0), $imgBox);
                $this->x = 0;
            } elseif ($this->x < 0) {
                $this->x = $this->x * -1;
            }
            if ($this->y > 0) {
                $imgBox = new Box($imgBox->getWidth(), $imgBox->getHeight() - $this->y);
                $img->crop(new Point(0, $this->y), $imgBox);
                $this->y = 0;
            } elseif ($this->y < 0) {
                $this->y = $this->y * -1;
            }

            if ($imgBox->getWidth() > $this->width - $this->x) {
                $imgBox = new Box($this->width - $this->x, $imgBox->getHeight());
                $img->crop(new Point(0, 0), $imgBox);
            }
            if ($imgBox->getHeight() > $this->height - $this->y) {
                $imgBox = new Box($imgBox->getWidth(), $this->height - $this->y);
                $img->crop(new Point(0, 0), $imgBox);
            }

            $imgCopy->paste($img, new Point($this->x, $this->y));
            $img = $imgCopy;
        } else {
            $img->crop(
                new Point($this->x, $this->y),
                new Box($this->width, $this->height)
            );
        }

        $img->save($path, ['quality' => $this->getJpegQuality()]);

        ThumbFactory::updateThumbnails($this->file);
        if ($this->module && $this->module->watermark) {
            (new MarkedFile($this->file))->delete();
        }
    }
}
