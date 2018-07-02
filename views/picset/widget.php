<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget kl83\filestorage\PicSetWidget */
/* @var $fileSet kl83\filestorage\models\FileSet */
/* @var $hasModel boolean */

$newItemStyle = $fileSet->getFiles()->count() >= $widget->maxImages ? 'display: none' : '';

?>

<?= Html::beginTag('div', $widget->wrapperOptions) ?>

    <?php if ($hasModel) : ?>
        <?= Html::activeHiddenInput($widget->model, $widget->attribute) ?>
    <?php else : ?>
        <?= Html::hiddenInput($widget->name, $fileSet->id) ?>
    <?php endif; ?>

    <?= Html::fileInput($widget->id . '-file', null, [
        'id' => $widget->id . '-file',
        'accept' => 'image/*',
    ]) ?>

    <div class="items">

        <div class="sortable">
            <?php foreach ($fileSet->files as $file) : ?>
                <?= $this->render('_item', [
                    'file' => $file,
                    'animate' => false,
                ]) ?>
            <?php endforeach; ?>
        </div>

        <label class="item new-item" for="<?= "$widget->id-file" ?>" style='<?= $newItemStyle ?>'>
            <span class="glyphicon glyphicon-picture"></span>
        </label>

        <div class="clearfix"></div>

    </div>

<?= Html::endTag('div') ?>
