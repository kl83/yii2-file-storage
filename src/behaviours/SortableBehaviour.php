<?php

namespace kl83\filestorage\behaviours;

use kl83\behaviours\SortableBehaviour as BaseBehaviour;
use kl83\filestorage\models\File;

/**
 * @property File $owner
 */
class SortableBehaviour extends BaseBehaviour
{
    protected function reorder($parentId = 0)
    {
        $files = File::find()
            ->where([
                'fileSetId' => $this->owner->fileSetId,
            ])
            ->orderBy('idx, id')
            ->all();
        foreach ($files as $idx => $file) {
            $file->idx = ($idx + 1) * 100;
            $file->save(false);
        }
    }
 
    protected function getMinIdx($parentId = 0, $moreThen = false)
    {
        $query = File::find()
            ->where(['fileSetId' => $this->owner->fileSetId]);
        if ($moreThen !== false) {
            $query->andWhere(['>', 'idx', $moreThen]);
        }
        $result = $query->min('idx');
        if ($result === null) {
            $result = $moreThen + 100;
        }
        return $result;
    }
}
