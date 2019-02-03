<?php

namespace kl83\filestorage\models;

use Iterator;
use yii\helpers\ArrayHelper;

class UploadsResponse implements Iterator
{
    /**
     * @var array
     */
    private $data;

    public function __construct(UploadsHandler $handler, string $thumbnail = null)
    {
        $this->data = [
            'files' => ArrayHelper::toArray($handler->savedFiles, [
                File::class => [
                    'id',
                    'url',
                    'thumbUrl' => function (File $file) use ($thumbnail) {
                        return $file->getThumbUrl($thumbnail);
                    }
                ],
            ]),
            'fileset' => $handler->fileset ? $handler->fileset->id : null,
        ];
    }

    public function current()
    {
        return current($this->data);
    }

    public function next()
    {
        next($this->data);
    }

    public function key()
    {
        return key($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null;
    }

    public function rewind()
    {
        reset($this->data);
    }
}
