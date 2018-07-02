<?php
namespace kl83\filestorage;

/**
 * {@inheritdoc}
 */
class UploadedFile extends \yii\web\UploadedFile
{
    /**
     * Inner browser does not send file properly and
     * so php function is_uploaded_file return false.
     * Its method fix this thing.
     * @param string $file
     * @param boolean $deleteTempFile
     * @return boolean
     */
    public function saveAs($file, $deleteTempFile = true)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            return copy($this->tempName, $file);
        }
        return false;
    }
}
