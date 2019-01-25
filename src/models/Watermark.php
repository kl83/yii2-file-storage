<?php

namespace kl83\filestorage\models;

use Exception;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Yii;
use yii\base\BaseObject;
use yii\imagine\Image;

class Watermark extends BaseObject
{
    /**
     * The watermark position
     */
    const POSITION_RIGHT_BOTTOM = 'rb';
    const POSITION_RIGHT_TOP = 'rt';
    const POSITION_LEFT_BOTTOM = 'lb';
    const POSITION_LEFT_TOP = 'lt';

    /**
     * @var string Watermark file path
     * You can use Yii2 aliases
     */
    public $file;

    /**
     * @var string
     * @see Watermark::POSITION_RIGHT_BOTTOM
     * @see Watermark::POSITION_RIGHT_TOP
     * @see Watermark::POSITION_LEFT_BOTTOM
     * @see Watermark::POSITION_LEFT_TOP
     */
    public $position = self::POSITION_RIGHT_BOTTOM;

    /**
     * @var int Offset from horizontal edge of source image
     */
    public $hOffset = 25;

    /**
     * @var int Offset from vertical edge of source image
     */
    public $vOffset = 25;

    /**
     * @var ImageInterface
     */
    private $_watermark;

    /**
     * @var BoxInterface
     */
    private $_watermarkSize;

    private function getWatermark(): ImageInterface
    {
        if (!$this->_watermark) {
            $this->_watermark = Image::getImagine()->open(Yii::getAlias($this->file));
        }
        return $this->_watermark;
    }

    private function getWatermarkSize(): BoxInterface
    {
        if (!$this->_watermarkSize) {
            $this->_watermarkSize = $this->getWatermark()->getSize();
        }
        return $this->_watermarkSize;
    }

    private function calcPosition(BoxInterface $sourceSize): array
    {
        if (
            $this->position == self::POSITION_LEFT_BOTTOM ||
            $this->position == self::POSITION_LEFT_TOP
        ) {
            $x = $this->hOffset;
        } else {
            $x = $sourceSize->getWidth() - $this->getWatermarkSize()->getWidth() - $this->hOffset;
        }
        if (
            $this->position == self::POSITION_LEFT_TOP ||
            $this->position == self::POSITION_RIGHT_TOP
        ) {
            $y = $this->vOffset;
        } else {
            $y = $sourceSize->getHeight() - $this->getWatermarkSize()->getHeight() - $this->vOffset;
        }
        return [$x, $y];
    }

    public function addWatermark(ImageInterface $source): ImageInterface
    {
        try {
            return Image::watermark(
                $source,
                $this->getWatermark(),
                $this->calcPosition($source->getSize())
            );
        } catch (Exception $exception) {
            Yii::warning($exception->getMessage());
            return $source;
        }
    }
}
