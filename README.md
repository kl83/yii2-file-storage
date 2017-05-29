Store component for Yii2
========================
Saves files and nothing any more.

Saved files path for logged in users:

{uploadsDir}/{userId % 1000}/{userId}/[a-zA-Z0-9_-]{32}/{file name}

Saved files path for guests:

{uploadsDir}/0/0/[a-zA-Z0-9_-]{2}/[a-zA-Z0-9_-]{30}/{file name}

REQUIREMENTS
------------
The minimum requirement by this component is PHP 5.4.0 and Yii2.

INSTALLATION
------------
Simplest way to install this component throught composer.
Add this line in composer.json at required section:
~~~
"kl83/yii2-file-storage": "@dev"
~~~

CONFIGURATION
-------------
Add next lines in Yii2 config to components section:
~~~
"store" => [
    'class' => 'kl83\filestorage\Store',
    'uploadDir' => '', // default is @webroot/uploads
    'uploadDirUrl' => '', // default is @web/uploads
]
~~~

USAGE
-----
~~~
$file = UploadedFile::getInstanceByName('attachment');
$result = Yii::$app->store->save($file);
~~~

Save function return array with next keys:
~~~
path: absolute file path
url: download uri
~~~
or false on failure
