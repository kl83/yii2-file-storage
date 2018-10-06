<?php

namespace kl83\filestorage;

use Yii;
use yii\base\InvalidConfigException;

/**
 * Module contain controller and models to store files in upload directory and collect they in file sets.
 */
class Module extends \yii\base\Module
{
    /**
     * I18n.
     */
    const TRANSLATION_NAME = 'kl83/filestorage';

    private static $instance;

    /**
     * @var string Upload directory path.
     */
    public $uploadDir = '@webroot/uploads';

    /**
     * @var string Upload directory url.
     */
    public $uploadDirUrl = '@web/uploads';

    /**
     * @var integer If uploaded file is an image, then the module checks its
     * width and reduces it to this value.
     */
    public $maxImageWidth = 1920;

    /**
     * @var integer If uploaded file is an image, then the module checks its
     * height and reduces it to this value.
     */
    public $maxImageHeight = 1080;

    /**
     * @var array User roles and permission names to manage files
     */
    public $managerRoles = ['admin', 'administrator'];

    public $forbiddenFilesMask = '~\.(php|cgi|htacess|htpasswd)$~';

    public function testUploadDir()
    {
        if (!file_exists($this->uploadDir) || !is_dir($this->uploadDir)) {
            $msg = Yii::t(
                self::TRANSLATION_NAME,
                'Directory "{d}" don\'t exists or is not a directory!',
                ['d' => $this->uploadDir]
            );
            throw new InvalidConfigException($msg);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        Yii::setAlias('@kl83', '@vendor/kl83');
        parent::init();
        self::registerTranslations();
        $this->uploadDir = Yii::getAlias($this->uploadDir);
        $this->uploadDirUrl = Yii::getAlias($this->uploadDirUrl);
    }

    /**
     * I18n.
     */
    public static function registerTranslations()
    {
        Yii::$app->i18n->translations[self::TRANSLATION_NAME] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__ . '/messages',
            'fileMap' => [
                self::TRANSLATION_NAME => 'base.php',
            ],
        ];
    }

    /**
     * Finds and returns module instance
     * @return self|null
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
