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
    <span class="rotate-left"></span>
    <span class="rotate-right"></span>

    <?= Html::tag('label', '', [
        'class' => 'picture',
        'for' => $widget->id . '-file',
        'style' => [
            'background-image' => ($thumbUrl = $widget->getThumbnailUrl())
                ? 'url(\'' . $thumbUrl . '\')'
                : null,
        ],
    ]) ?>

    <label class="upload" for="<?= $widget->id . '-file' ?>"></label>

<?= Html::endTag('div') ?>
