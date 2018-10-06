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

    /**
     * @param UploadsHandler $handler
     */
    public function __construct($handler)
    {
        $this->data = [
            'files' => ArrayHelper::toArray($handler->savedFiles, [
                File::className() => ['id', 'url'],
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
