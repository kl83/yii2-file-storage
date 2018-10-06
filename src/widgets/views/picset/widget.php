<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget kl83\filestorage\widgets\PicSetWidget */
/* @var $input string */
/* @var $fileSet kl83\filestorage\models\FileSet */

$newItemStyle = $fileSet && $fileSet->getFiles()->count() >= $widget->maxImages ? 'display: none' : '';

?>

<?= Html::beginTag('div', $widget->widgetOptions) ?>

    <?= $input ?>

    <?= Html::fileInput($widget->id . '-file[]', null, [
        'id' => $widget->id . '-file',
        'accept' => 'image/*',
        'multiple' => true,
    ]) ?>

    <div class="items">

        <div class="sortable">
            <?php if ($fileSet) : ?>
                <?php foreach ($fileSet->files as $file) : ?>
                    <?= $this->render('_item', [
                        'file' => $file,
                        'animate' => false,
                    ]) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <label
                class="item new-item"
                for="<?= $widget->id . '-file' ?>"
                style='<?= $newItemStyle ?>'>
        </label>

    </div>

    <div class="progress-bar-container">
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </div>

<?= Html::endTag('div') ?>
