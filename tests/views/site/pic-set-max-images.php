<?php

use yii\widgets\ActiveForm;
use kl83\widgets\PicSetWidget;

/* @var $fileSet \kl83\modules\filestorage\models\FileSet */

?>

<h3>Pic set #<?= $fileSet->id ?> 3 picture limit</h3>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($fileSet, 'id')->widget(PicSetWidget::className(), [
    'maxImages' => 3,
]) ?>

<?php ActiveForm::end() ?>
