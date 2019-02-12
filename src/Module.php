<?php

namespace kl83\filestorage;

use Imagine\Image\ImageInterface;
use kl83\filestorage\models\Watermark;
use kl83\filestorage\models\ThumbFactory;
use Yii;
use yii\console\Application as ConsoleApplication;
use yii\helpers\ArrayHelper;

/**
 * Module contain controller and models to store files in upload directory and
 * collect they in file sets.
 */
class Module extends \yii\base\Module
{
    /**
     * @var Module|null
     */
    private static $instance;

    /**
     * @var string
     */
    public $uploadDir = '@webroot/uploads';

    /**
     * @var string
     */
    public $uploadDirUrl = '@web/uploads';

    /**
     * @var integer
     */
    public $maxImageWidth = 1920;

    /**
     * @var integer
     */
    public $maxImageHeight = 1080;

    /**
     * @var int
     */
    public $jpegQuality = 60;

    /**
     * @var int
     */
    public $uploadTimeLimit = 600;

    /**
     * @var array Thumbnails configurations
     * You can override the thumbs config. But be careful,
     * the first (and default) factory config is used by the widgets.
     * @see ThumbFactory
     */
    public $thumbs = [
        'thumbnail' => [
            'width' => 130,
            'height' => 130,
        ]
    ];

    /**
     * @var array
     * The watermark configuration
     * @see Watermark
     */
    public $watermark;

    /**
     * @var array|string User roles and permission names to manage files
     */
    public $managerRoles = ['admin', 'administrator'];

    public $forbiddenFilesMask = '~\.(php|cgi|htacess|htpasswd)$~';

    /**
     * @var Watermark
     */
    private $_watermark;

    public function init()
    {
        parent::init();
        $this->uploadDir = Yii::getAlias($this->uploadDir);
        $this->uploadDirUrl = Yii::getAlias($this->uploadDirUrl);
        if (Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'kl83\filestorage\console';
        }
    }

    public function getControllerPath()
    {
        if (Yii::$app instanceof ConsoleApplication) {
            return __DIR__ . '/console';
        } else {
            return parent::getControllerPath();
        }
    }

    public function getThumbFactory(string $id = null): ThumbFactory
    {
        if (!$id) {
            $id = array_keys($this->thumbs)[0];
        }
        return new ThumbFactory([
            'id' => $id,
            'width' => $this->thumbs[$id]['width'],
            'height' => $this->thumbs[$id]['height'],
            'mode' => ArrayHelper::getValue(
                $this->thumbs[$id],
                'mode',
                ImageInterface::THUMBNAIL_OUTBOUND
            ),
        ]);
    }

    public function getAllThumbFactories()
    {
        $factories = [];
        foreach (array_keys($this->thumbs) as $id) {
            $factories[$id] = $this->getThumbFactory($id);
        }
        return $factories;
    }

    public function getWatermark(): Watermark
    {
        if (!$this->_watermark) {
            $this->_watermark = new Watermark($this->watermark);
        }
        return $this->_watermark;
    }

    /**
     * @return Module|null
     */
    public static function findInstance()
    {
        if (!self::$instance) {
            $className = self::className();
            foreach (Yii::$app->modules as $moduleId => $module) {
                if (is_object($module)) {
                    if ($module::className() == $className) {
                        self::$instance = $module;
                        break;
                    }
                } elseif (is_array($module)) {
                    if ($module['class'] == $className) {
                        self::$instance = Yii::$app->getModule($moduleId);
                        break;
                    }
                } elseif ($module == $className) {
                    self::$instance = Yii::$app->getModule($moduleId);
                    break;
                }
            }
        }
        return self::$instance;
    }
}
