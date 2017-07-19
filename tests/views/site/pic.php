<?php

use yii\widgets\ActiveForm;
use kl83\widgets\PicWidget;

/* @var $file \kl83\modules\filestorage\models\File */

?>

<h3>New pic</h3>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($file, 'id')->widget(PicWidget::className()) ?>

<?php ActiveForm::end() ?>
