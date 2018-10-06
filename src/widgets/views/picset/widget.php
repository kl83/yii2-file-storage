<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget kl83\filestorage\widgets\PicSetWidget */
/* @var $input string */
/* @var $fileSet kl83\filestorage\models\FileSet */

$mustache = new Mustache_Engine();
$itemTemplate = file_get_contents(__DIR__ . '/_item.mustache');

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
                <?php foreach ($fileSet->getFiles()->orderBy('idx')->all() as $file) : ?>
                    <?= $mustache->render($itemTemplate, $file) ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <label class="item new-item" for="<?= $widget->id . '-file' ?>"></label>

    </div>

    <div class="progress-bar-container">
        <div class="progress">
            <div class="progress-bar"></div>
        </div>
    </div>

<?= Html::endTag('div') ?>
