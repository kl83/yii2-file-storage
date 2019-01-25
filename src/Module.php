<?php

namespace kl83\filestorage;

use kl83\filestorage\models\ThumbFactory;
use Yii;

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
     * @var array|string User roles and permission names to manage files
     */
    public $managerRoles = ['admin', 'administrator'];

    public $forbiddenFilesMask = '~\.(php|cgi|htacess|htpasswd)$~';


    public function init()
    {
        parent::init();
        $this->uploadDir = Yii::getAlias($this->uploadDir);
        $this->uploadDirUrl = Yii::getAlias($this->uploadDirUrl);
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
        ]);
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
