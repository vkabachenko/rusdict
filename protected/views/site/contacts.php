<?php
/* @var SiteController $this */
/* @var Statics $model  */
/* @var Controller $this */

$this->pageTitle = 'Контакты';

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