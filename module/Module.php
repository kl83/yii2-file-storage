<?php
namespace kl83\modules\filestorage;

use Yii;

class Module extends \yii\base\Module
{
    public $uploadDir = '@webroot/uploads';
    public $uploadDirUrl = '@web/uploads';

    public function init()
    {
        parent::init();
        $this->uploadDir = Yii::getAlias($this->uploadDir);
        $this->uploadDirUrl = Yii::getAlias($this->uploadDirUrl);
        if ( ! file_exists($this->uploadDir) ) {
            throw new \yii\base\Exception("Upload dir '$this->uploadDir' does not exist");
        }
        $this->components = [
            'store' => [
                'class' => 'kl83\modules\filestorage\Store',
            ],
        ];
    }
}
