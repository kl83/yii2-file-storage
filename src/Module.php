<?php

namespace kl83\filestorage;

use Yii;

/**
 * Module contain controller and models to store files in upload directory and collect they in file sets.
 */
class Module extends \yii\base\Module
{
    /**
     * I18n.
     */
    const TRANSLATION_NAME = 'kl83/filestorage';

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
     * @var string User role or permission name to manage files.
     */
    public $managerRoles = [ 'admin', 'administrator' ];

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
     * Return module configuration or module instance. To get module settings in
     * models without module initialization.
     * @return self
     */
    public static function findInstance()
    {
        $className = self::className();
        foreach (Yii::$app->modules as $moduleId => $module) {
            if (is_object($module)) {
                if ($module::className() == $className) {
                    return $module;
                }
            } elseif (is_array($module)) {
                if ($module['class'] == $className) {
                    return Yii::$app->getModule($moduleId);
                }
            } elseif ($module == $className) {
                return Yii::$app->getModule($moduleId);
            }
        }
        return null;
    }
}
