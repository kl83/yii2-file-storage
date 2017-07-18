<?php

use yii\widgets\ActiveForm;
use kl83\widgets\PicSetWidget;
use kl83\modules\filestorage\models\FileSet;

$fileSet = new FileSet;

?>

<h3>New pic set</h3>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($fileSet, 'id')->widget(PicSetWidget::className()) ?>

<?php ActiveForm::end() ?>
