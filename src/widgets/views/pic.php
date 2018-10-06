<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget kl83\filestorage\widgets\PicWidget */
/* @var $input string */
/* @var $file kl83\filestorage\models\File|null */

?>

<?= Html::beginTag('div', $widget->widgetOptions) ?>

    <?= $input ?>

    <?= Html::fileInput($widget->id . '-file', null, [
        'id' => $widget->id . '-file',
        'accept' => 'image/*',
    ]) ?>

    <span class="remove"></span>

    <label
        class="picture"
        for="<?= $widget->id ?>-file"
        <?= $file ? 'style="background-image: url(\'' . $file->url . '\')"' : '' ?>
        ></label>

    <label class="upload" for="<?= $widget->id . '-file' ?>"></label>

<?= Html::endTag('div') ?>
