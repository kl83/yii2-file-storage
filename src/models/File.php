<?php

namespace kl83\filestorage\models;

use Yii;
use kl83\filestorage\Module;

/**
 * Model for work with a file.
 * @property integer $id
 * @property integer $createdAt
 * @property integer $createdById
 * @property integer $idx
 * @property integer $fileSetId
 * @property string $relPath
 * @property string $url
 * @property string $path
 */
class File extends \yii\db\ActiveRecord
{
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

    public function afterDelete()
    {
        @unlink($this->getPath());
    }

    public function rules()
    {
        return [
            [['createdById', 'idx', 'fileSetId'], 'integer'],
            [['relPath'], 'string'],
            ['createdById', 'exist', 'targetClass' => Yii::$app->user->identityClass, 'targetAttribute' => 'id'],
            ['fileSetId', 'exist', 'targetClass' => FileSet::className(), 'targetAttribute' => 'id'],
            ['relPath', 'match', 'not' => true, 'pattern' => Module::findInstance()->forbiddenFilesMask],
        ];
    }

    /**
     * File path
     * @return string
     */
    public function getPath()
    {
        return Module::findInstance()->uploadDir . '/' . $this->relPath;
    }

    /**
     * File url
     * @return string
     */
    public function getUrl()
    {
        return Module::findInstance()->uploadDirUrl . '/' . $this->relPath;
    }
}
