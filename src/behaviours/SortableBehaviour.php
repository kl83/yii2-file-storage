<?php

namespace kl83\filestorage\behaviours;

use kl83\behaviours\SortableBehaviour as BaseBehaviour;
use kl83\filestorage\models\File;
use yii\db\Query;

/**
 * @property File $owner
 * @property string $ownerClassName
 * @property string $primaryKey
 * @property string $sortField
 */
class SortableBehaviour extends BaseBehaviour
{
    protected function reorder($parentId = 0)
    {
        $query = call_user_func("$this->ownerClassName::find");
        /* @var $query Query */
        $siblingFilesId = $query->select($this->primaryKey)
            ->orderBy("$this->sortField, $this->primaryKey")
            ->where(['fileSetId' => $this->owner->fileSetId])
            ->column();
        foreach ($siblingFilesId as $idx => $pk) {
            call_user_func(
                "$this->ownerClassName::updateAll",
                [$this->sortField => ($idx + 1) * 100],
                [$this->primaryKey => $pk]
            );
        }
    }
}
