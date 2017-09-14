<?php

/* @var $file \kl83\filestorage\models\File */
/* @var $animate boolean */

?>
<div class="item <?= $animate ? 'animation' : '' ?>" data-id='<?= $file->id ?>'>
    <span class="remove-item"><span class='glyphicon glyphicon-remove'></span></span>
    <div class="image" style="background-image: url('<?= $file->url ?>')"></div>
</div>
