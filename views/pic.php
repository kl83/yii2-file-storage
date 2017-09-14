<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $widget \kl83\filestorage\PicSetWidget */
/* @var $hasModel boolean */
/* @var $value integer */
/* @var $file \kl83\filestorage\models\File */

?>

<?= Html::beginTag('div', $widget->wrapperOptions) ?>

    <?php if ( $hasModel ) : ?>
        <?= Html::activeHiddenInput($widget->model, $widget->attribute) ?>
    <?php else : ?>
        <?= Html::hiddenInput($widget->name, $value) ?>
    <?php endif; ?>

    <?= Html::fileInput("$widget->id-file", null, [
        'id' => "$widget->id-file",
        'accept' => 'image/*',
    ]) ?>

    <span class="remove"><span class='glyphicon glyphicon-remove'></span></span>
    <label
        class="picture"
        for="<?= "$widget->id-file" ?>"
        style="background-image: <?= $value && $file ? "url('$file->url')" : 'none' ?>"
        ></label>
    <label class="upload" for="<?= "$widget->id-file" ?>">
        <span class="glyphicon glyphicon-picture"></span>
    </label>

<?= Html::endTag('div') ?>