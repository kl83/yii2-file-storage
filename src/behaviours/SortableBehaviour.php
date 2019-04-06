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
}
