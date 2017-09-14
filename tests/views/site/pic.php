<?php

use yii\widgets\ActiveForm;
use kl83\filestorage\PicWidget;

/* @var $file \kl83\filestorage\models\File */

?>

<h3>New pic</h3>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($file, 'id')->widget(PicWidget::className()) ?>

<?php ActiveForm::end() ?>
