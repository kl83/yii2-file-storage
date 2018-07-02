<?php
namespace kl83\filestorage\models;

use Yii;

/**
 * Model for work with a file sets.
 * @property integer $id
 * @property integer $createdAt
 * @property integer $createdBy
 * @property kl83\filestorage\models\File[] $files
 */
class FileSet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return "{{%kl83_file_set}}";
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->on(self::EVENT_BEFORE_INSERT, function () {
           $this->createdBy = (int)Yii::$app->user->id;
        });
        $this->on(self::EVENT_BEFORE_DELETE, function () {
            foreach ($this->files as $file) {
                $file->delete();
            }
        });
        parent::init();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['fileSetId' => 'id']);
    }
}
