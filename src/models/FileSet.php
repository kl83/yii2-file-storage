<?php

namespace kl83\filestorage\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $createdAt
 * @property integer $createdById
 * @property File[] $files
 */
class FileSet extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%kl83_file_set}}';
    }

    public function rules()
    {
        return [
            ['createdById', 'integer'],
            [
                'createdById',
                'exist',
                'targetClass' => Yii::$app->user->identityClass,
                'targetAttribute' => 'id',
            ],
        ];
    }

    public function beforeDelete()
    {
        foreach ($this->files as $file) {
            $file->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['fileSetId' => 'id']);
    }
}
