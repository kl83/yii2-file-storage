Store component for Yii2
========================
Saves files and nothing any more.

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