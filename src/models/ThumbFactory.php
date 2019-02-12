<?php

namespace kl83\filestorage\models;

use Imagine\Image\ImageInterface;
use kl83\filestorage\Module;
use yii\base\BaseObject;
use yii\imagine\Image;

class ThumbFactory extends BaseObject
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var string
     * @see ImageInterface::THUMBNAIL_OUTBOUND
     * @see ImageInterface::THUMBNAIL_INSET
     * @see Image::thumbnail()
     */
    public $mode;

    /**
     * @param File $file
     * @param bool $createThumb
     * Set to false if you no need to automatically create thumbnail of image
     * on object init
     * @return Thumb
     */
    public function createThumb(File $file, $createThumb = true): Thumb
    {
        $thumb = new Thumb([
            'factory' => $this,
            'file' => $file,
        ]);
        if ($createThumb) {
            $thumb->createThumbnail();
        }
        return $thumb;
    }

    public static function updateThumbnails(File $file)
    {
        $module = Module::getInstance();
        $factories = array_keys($module->thumbs);
        foreach ($factories as $factory) {
            $thumb = $module->getThumbFactory($factory)
                ->createThumb($file, false);
            $thumb->updateThumbnail();
        }
    }
}
