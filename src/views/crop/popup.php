<?php

use yii\bootstrap\Modal;

/* @var $this \yii\web\View */

Modal::begin([
    'id' => 'cropper-cmp',
    'header' => Yii::t('modules/filestorage/crop', 'Image cropping'),
    'closeButton' => false,
    'footer' => $this->render('popup/footer'),
]);
echo '<div class="img-wrapper"><img src="" alt=""></div>';
Modal::end();
