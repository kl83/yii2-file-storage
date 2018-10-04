<?php

namespace kl83\filestorage\assets;

/**
 * Plugin to submit form with ajax
 * http://malsup.com/jquery/form/
 */
class JqueryFormAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bower/jquery-form';
    public $js = ['dist/jquery.form.min.js'];
    public $depends = ['yii\web\JqueryAsset'];
}
