<?php
/* @var $this SiteController */
/* @var $model Statics */

?>

<h2>
    <?php echo strip_tags($model->title); ?>
</h2>

<div>
    <?php

    $this->beginWidget('CHtmlPurifier');
    echo $model->content;
    $this->endWidget();

    ?>
</div>