<?php

use yii\widgets\ActiveForm;
use kl83\widgets\PicSetWidget;

/* @var $fileSet \kl83\modules\filestorage\models\FileSet */

?>

<h3>Pic set #<?= $fileSet->id ?></h3>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($fileSet, 'id')->widget(PicSetWidget::className()) ?>

<?php ActiveForm::end() ?>
