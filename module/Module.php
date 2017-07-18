<?php
namespace kl83\modules\filestorage;

use Yii;

/**
 * Module contain controller and models to store files in upload directory and collect they in file sets.
 */
class Module extends \yii\base\Module
{
    const TRANSLATION_NAME = 'kl83/modules/filestorage';

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
     * User role to manage files.
     * @var string
     */
    public $managerRoles = [ 'admin' ];

    public function init()
    {
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
}
