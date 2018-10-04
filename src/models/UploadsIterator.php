<?php

namespace kl83\filestorage\models;

use Iterator;
use yii\web\UploadedFile;
use yii\base\InvalidConfigException;

/**
 * The class iterates uploaded files specified in the attribute property
 */
class UploadsIterator implements Iterator
{
    /**
     * @var \yii\web\UploadedFile[]
     */
    private $files = [];

    /**
     * @param string[]|string|null $attributes Attrubutes to iterate
     * If null then iterate all uploaded files
     * @throws InvalidConfigException
     */
    public function __construct($attributes = null)
    {
        if ($attributes === null) {
            $attributes = array_keys($_FILES);
        } elseif (is_string($attributes)) {
            $attributes = [$attributes];
        } elseif (!is_array($attributes)) {
            throw new InvalidConfigException();
        }
        foreach ($attributes as $attribute) {
            $this->files[$attribute] =
                UploadedFile::getInstancesByName($attribute);
        }
    }

    public function current()
    {
        return current($this->files);
    }

    public function next()
    {
        next($this->files);
    }

    public function key()
    {
        return key($this->files);
    }

    public function valid()
    {
        return key($this->files) !== null;
    }

    public function rewind()
    {
        reset($this->files);
    }
}
