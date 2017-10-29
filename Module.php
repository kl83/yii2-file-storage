<?php
namespace kl83\filestorage;

use Yii;

/**
 * Module contain controller and models to store files in upload directory and collect they in file sets.
 */
class Module extends \yii\base\Module
{
    const TRANSLATION_NAME = 'kl83/filestorage';

    /**
     * Upload directory path.
     * @var string
     */
    public $uploadDir = '@webroot/uploads';
    /**
     * Upload directory url.
     * @var string
     */
    public $uploadDirUrl = '@web/uploads';

    /**
     * If uploaded file is an image, then the module checks its width and reduces it to this value.
     * @var integer
     */
    public $maxImageWidth = 1920;
    /**
     * If uploaded file is an image, then the module checks its height and reduces it to this value.
     * @var integer
     */
    public $maxImageHeight = 1080;
    /**
     * User role or permission name to manage files.
     * @var string
     */
    public $managerRoles = [ 'admin', 'administrator' ];

    public function init()
    {
        Yii::setAlias('@kl83', '@vendor/kl83');
        parent::init();
        self::registerTranslations();
        $this->uploadDir = Yii::getAlias($this->uploadDir);
        $this->uploadDirUrl = Yii::getAlias($this->uploadDirUrl);
    }

    public static function registerTranslations()
    {
        Yii::$app->i18n->translations[self::TRANSLATION_NAME] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__.DIRECTORY_SEPARATOR.'messages',
            'fileMap' => [
                self::TRANSLATION_NAME => 'base.php',
            ],
        ];
    }

    /**
     * Return module configuration or module instance. To get module settings in models without module initialization.
     * @return self
     */
    public static function findInstance()
    {
        $className = self::className();
        foreach ( Yii::$app->modules as $moduleId => $module ) {
            if ( is_object($module) ) {
                if ( $module::className() == $className ) {
                    return $module;
                }
            } elseif ( is_array($module) ) {
                if ( $module['class'] == $className ) {
                    return Yii::$app->getModule($moduleId);
                }
            } elseif ( $module == $className ) {
                return Yii::$app->getModule($moduleId);
            }
        }
        return null;
    }
}
