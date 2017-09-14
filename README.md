# Yii2 file storage kit
[![Latest Stable Version](https://poser.pugx.org/kl83/yii2-file-storage/v/stable)](https://packagist.org/packages/kl83/yii2-file-storage)
[![Total Downloads](https://poser.pugx.org/kl83/yii2-file-storage/downloads)](https://packagist.org/packages/kl83/yii2-file-storage)
[![License](https://poser.pugx.org/kl83/yii2-file-storage/license)](https://packagist.org/packages/kl83/yii2-file-storage)

Module and widgets to upload files. The module uploads files to a random directory, saving their names. Urls, owners, uploading date and unique ID of files are stored in database. Also, several files may be concatenate at fileset which has own ID, owner and creation date. Files uploaded by authenticated users are stored in separate directories. Module has permissions check to manage files.

## Installation
The preferred way to install this extension is through [composer](https://getcomposer.org/).

Either run
~~~
php composer.phar require kl83/yii2-file-storage ~1.1.0
~~~
or add
~~~
"kl83/yii2-file-storage": "~1.1.0"
~~~
to the require section of your composer.json file.

And apply migrations.
~~~
./yii migrate --migrationPath=@vendor/kl83/yii2-file-storage/migrations
~~~

## Module configuration
config/web.php
~~~
...
"modules" => [
    ...
    "filestorage" => "kl83\filestorage\Module",
    ...
]
...
~~~

Option|Default|Description
------|-------|-----------
**(string) uploadDir**|@webroot/uploads|Directory to save files.
**(string) uploadDirUrl**|@web/uploads|Base url to access uploaded files.
**(integer) maxImageWidth**|1920|If uploaded file is image and his width more then specified value, then image will be decresed.
**(integer) maxImageHeight**|1080|If uploaded file is image and his height more then specified value, then image will be decresed.
**(array) managerRoles**|[ 'admin', 'administrator' ]|Names of roles or permissions to manage files.

## Module actions
```
null defaut/delete-file (int $id)
$id - id of file to delete
```
Deletes specified file.
***
```
null defaut/move (int $id, int $afterId)
$id - id of file to move
$afterId - set file position to be after that file, if value is empty, then file will be the first
```
Moves specified file to some position inside fileset.
***
```
json defaut/upload (string|array $attributes = null)
$attributes - key's of $_FILES array to be save, if not set, then all files to be saved
return [
    '$_FILES attribute' => [
        'id' => int file id,
        'url' => url to download file,
    ],
]
```
Uploads files.
***
```
json defaut/upload-to-file-set (int $fileSetId = null, string|array $attributes = null)
$fileSetId - fileset id, if null, then new fileset will be created
$attributes - see default/upload
return [
    'files' => [
        '$_FILES attribute' => [
            'id' => int file id,
            'url' => url to download file,
        ],
    ],
    'fileSetId' => int fileset id,
]
```
Uploads files and stores his in specified fileset.
***

## PicWidget usage
Widget to select and upload some one image.
~~~
$form->field($model, 'picId')->widget('kl83\filestorage\PicWidget');
~~~
picId must be integer attribute
### Example model methods to get uploaded file
~~~
public function getPic()
{
    return $this->hasOne('kl83\filestorage\models\File', [ 'id' => 'picId' ]);
}
public function getPicUrl()
{
    return $this->getPic()->url;
}
~~~

## PicSetWidget usage
Widget to select and upload some images.
~~~
$form->field($model, 'picSetId')->widget('kl83\filestorage\PicSetWidget', [
    'maxImages' => 3, // default is unlimited
]);
~~~
picSetId must be integer attribute
### Example model methods to get uploaded files
~~~
public function getPicSet()
{
    return $this->hasOne('kl83\filestorage\models\FileSet', [ 'id' => 'picSetId' ]);
}
public function getPics()
{
    // returns array of kl83\filestorage\models\File
    return $this->getPicSet()->getFiles();
}
public function getCover()
{
    $pics = $this->getPics();
    if ( is_array($pics) ) {
        return current($pics);
    }
}
public fucntion getCoverUrl()
{
    return $this->getCover()->url;
}
~~~

## License
MIT License
