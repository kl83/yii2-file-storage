<?php

namespace kl83\modules\filestorage;

use Yii;

/**
 * Store component. Saves files and nothing any more.
 */
class Store extends \yii\base\Object
{
    /**
     * Return user directory relative path.
     * It is at the upload dir.
     * @param integer $userId
     * @return string
     * @throws \yii\base\Exception
     */
    public function getUserDir($userId = false)
    {
        if ( $userId === false ) {
            $userId = (int)Yii::$app->user->id;
        } else {
            $userId = (int)$userId;
        }
        $relPath = ( $userId % 1000 ) . "/$userId";
        $userDir = Module::getInstance()->uploadDir."/$relPath";
        if ( ! file_exists($userDir) ) {
            if ( ! mkdir($userDir, 0777, true) ) {
                throw new \yii\base\Exception("Could not create directory '$userDir'");
            }
        }
        return $relPath;
    }

    /**
     * Return generated relative path for new file.
     * Path is relative to upload directory.
     * Name of file non changing.
     * @param string $fileName
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateFilePath($fileName)
    {
        $userDir = $this->getUserDir();
        do {
            $randomDir = Yii::$app->security->generateRandomString();
            if ( Yii::$app->user->getIsGuest() ) {
                $randomDir = substr($randomDir, 0, 2) . "/" . substr($randomDir, 2);
            }
            $relDirPath = "$userDir/" . $randomDir;
            $relFilePath = "$relDirPath/$fileName";
            $filePath = Module::getInstance()->uploadDir."/$relFilePath";
        } while ( file_exists($filePath) );
        $dirPath = Module::getInstance()->uploadDir."/$relDirPath";
        if ( ! file_exists($dirPath) ) {
            if ( ! mkdir($dirPath, 0777, true) ) {
                throw new \yii\base\Exception("Could not create directory '$dirPath'");
            }
        }
        return $relFilePath;
    }

    /**
     * Save file to random directory in upload directory.
     * Name of file is not changing.
     * @param \yii\web\UploadedFile $file
     * @return boolean|array
     * @throws \yii\base\Exception
     */
    public function save($file)
    {
        if ( preg_match('~\.(php|cgi)$~', $file->name) ) {
            return false;
        } else {
            $relFilePath = $this->generateFilePath($file->name);
            $filePath = Module::getInstance()->uploadDir."/$relFilePath";
            if ( ! $file->saveAs($filePath) ) {
                throw new \yii\base\Exception("Could not save file '$filePath'");
            }
            return [
                'path' => $filePath,
                'url' => Module::getInstance()->uploadDirUrl."/$relFilePath",
            ];
        }
    }
}