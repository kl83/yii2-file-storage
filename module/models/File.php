<?php
namespace kl83\modules\filestorage\models;

use Yii;
use yii\base\Exception;
use kl83\modules\filestorage\Module;
use yii\imagine\Image;

/**
 *
 * @property integer $id
 * @property integer $idx
 * @property integer $fileSetId
 * @property integer $createdAt
 * @property integer $createdBy
 * @property string $path
 * @property string $url
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * Directory to save the file
     * @var string
     */
    public $uploadDir;
    /**
     * Base url to access the file
     * @var string
     */
    public $uploadDirUrl;
    /**
     * Uploaded file
     * @var \yii\web\UploadedFile
     */
    public $uploadedFile;
    /**
     * If uploaded file is an image, then the module checks its width and reduces it to this value.
     * @var integer
     */
    public $maxImageWidth;
    /**
     * If uploaded file is an image, then the module checks its height and reduces it to this value.
     * @var integer
     */
    public $maxImageHeight;

    public static function tableName()
    {
        return "{{%kl83_file}}";
    }

    public function behaviors()
    {
        return [
            [
                'class' => '\kl83\behaviours\SortableBehaviour',
                'parentIdField' => false,
            ],
        ];
    }

    public function init()
    {
        Module::registerTranslations();
        $this->on(self::EVENT_BEFORE_INSERT, function(){
            $this->createdBy = (int)Yii::$app->user->id;
        });
        $this->on(self::EVENT_AFTER_INSERT, function(){
            try {
                $img = Image::getImagine()->open($this->path);
                $box = $img->getSize();
                if ( $box->getWidth() > $this->maxImageWidth || $box->getHeight() > $this->maxImageHeight ) {
                    $box = $box->widen($this->maxImageWidth);
                    if ( $box->getHeight() > $this->maxImageHeight ) {
                        $box = $box->heighten($this->maxImageHeight);
                    }
                    $img->resize($box)->save();
                }
            } catch ( \Exception $e ) {
            }
        });
        $this->on(self::EVENT_BEFORE_DELETE, function(){
            @unlink($this->path);
        });
        parent::init();
    }

    public function rules()
    {
        return [
            [['uploadDir'], function(){
                if ( $this->isNewRecord ) {
                    if ( ! file_exists($this->uploadDir) || ! is_dir($this->uploadDir) ) {
                        $msg = Yii::t( Module::TRANSLATION_NAME, 'Directory "{d}" don\'t exists or is not a directory!', [
                            'd' => $this->uploadDir,
                        ]);
                        throw new Exception($msg);
                    }
                }
            }],
            [['uploadedFile'], function(){
                if ( $this->isNewRecord ) {
                    if ( preg_match('~\.(php|cgi|htacess|htpasswd)$~', $this->uploadedFile->name, $m) ) {
                        $msg = Yii::t( 'kl83/modules/filestorage', 'Uploading {t} files is forbidden!', [
                            't' => $m[1],
                        ]);
                        $this->addError('uploadedFile', $msg);
                    } else {
                        $this->saveFile();
                    }
                }
            }],
        ];
    }

    /**
     * Save file to random directory in upload directory.
     * Name of file is not changing.
     * @param \yii\web\UploadedFile $file
     * @return boolean|array
     * @throws \yii\base\Exception
     */
    private function saveFile()
    {
        $relFilePath = $this->generateFilePath($this->uploadedFile->name);
        $this->path = $this->uploadDir."/$relFilePath";
        if ( ! $this->uploadedFile->saveAs($this->path) ) {
            $msg = Yii::t(Module::TRANSLATION_NAME, 'Could not save file "{f}"!', [
                'f' => $this->path,
            ]);
            throw new Exception($msg);
        }
        $this->url = $this->uploadDirUrl."/$relFilePath";
    }

    /**
     * Create a directory or throw an exception.
     * @param string $dir
     * @throws Exception
     */
    private function createDirectory($dir)
    {
        if ( ! mkdir($dir, 0777, true) ) {
            $msg = Yii::t(Module::TRANSLATION_NAME, 'Could not create directory "{d}"!', [
                'd' => $dir,
            ]);
            throw new Exception($msg);
        }
    }

    /**
     * Return user directory relative path.
     * It is at the upload dir.
     * @param integer $userId
     * @return string
     * @throws \yii\base\Exception
     */
    public function getUserDir($userId = false)
    {
        if ( $userId === false ) {
            $userId = (int)Yii::$app->user->id;
        } else {
            $userId = (int)$userId;
        }
        $relPath = ( $userId % 1000 ) . "/$userId";
        $userDir = $this->uploadDir."/$relPath";
        if ( ! file_exists($userDir) ) {
            $this->createDirectory($userDir);
        }
        return $relPath;
    }

    /**
     * Return generated relative path for new file.
     * Path is relative to upload directory.
     * Name of file non changing.
     * @param string $fileName
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateFilePath($fileName)
    {
        $userDir = $this->getUserDir();
        do {
            $randomDir = Yii::$app->security->generateRandomString();
            if ( Yii::$app->user->getIsGuest() ) {
                $randomDir = substr($randomDir, 0, 2) . "/" . substr($randomDir, 2);
            }
            $relDirPath = "$userDir/" . $randomDir;
            $relFilePath = "$relDirPath/$fileName";
            $filePath = $this->uploadDir."/$relFilePath";
        } while ( file_exists($filePath) );
        $dirPath = $this->uploadDir."/$relDirPath";
        if ( ! file_exists($dirPath) ) {
            $this->createDirectory($dirPath);
        }
        return $relFilePath;
    }
}
