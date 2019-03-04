<?php

namespace kl83\filestorage\models;

use Exception;
use Yii;
use yii\db\ActiveRecord;
use kl83\filestorage\Module;
use yii\imagine\Image;

/**
 * @property integer $id
 * @property integer $createdAt
 * @property integer $createdById
 * @property integer $idx
 * @property integer $fileSetId
 * @property string $relPath
 * @property string $url
 * @property string $path
 */
class File extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%kl83_file}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'kl83\behaviours\SortableBehaviour',
                'parentIdField' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['createdById', 'idx', 'fileSetId'], 'integer'],
            [['relPath'], 'string'],
            [
                'createdById',
                'exist',
                'targetClass' => Yii::$app->user->identityClass,
                'targetAttribute' => 'id',
            ],
            [
                'fileSetId',
                'exist',
                'targetClass' => FileSet::className(),
                'targetAttribute' => 'id',
            ],
            [
                'relPath',
                'match',
                'not' => true,
                'pattern' => Module::findInstance()->forbiddenFilesMask,
            ],
        ];
    }

    public function afterDelete()
    {
        @unlink($this->getPath());
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return Module::findInstance()->uploadDir . '/' . $this->relPath;
    }

    public function getUrlEncodedRelPath()
    {
        return preg_replace_callback('~[^/]+$~', function ($m) {
            return rawurlencode($m[0]);
        }, $this->relPath);
    }

    /**
     * @param bool $watermark
     * @return string
     */
    public function getUrl(bool $watermark = true): string
    {
        $module = Module::findInstance();
        if ($watermark && $module->watermark) {
            try {
                return (new MarkedFile($this))->getUrl();
            } catch (Exception $exception) {
                Yii::warning($exception->getMessage());
            }
        }
        return $module->uploadDirUrl . '/' . $this->getUrlEncodedRelPath();
    }

    public function getThumb(string $id = null): Thumb
    {
        $thumbFactory = Module::findInstance()->getThumbFactory($id);
        return $thumbFactory->createThumb($this);
    }

    public function getThumbUrl(string $id = null): string
    {
        return $this->getThumb($id)->getUrl();
    }

    private function rotate(int $deg)
    {
        $module = Module::getInstance();
        $path = $this->getPath();
        $image = Image::getImagine()->open($path);
        $image->rotate($deg)
            ->save($path, ['quality' => $module->jpegQuality]);
        ThumbFactory::updateThumbnails($this);
        if ($module->watermark) {
            (new MarkedFile($this))->delete();
        }
    }

    public function rotateLeft()
    {
        $this->rotate(-90);
    }

    public function rotateRight()
    {
        $this->rotate(90);
    }
}
