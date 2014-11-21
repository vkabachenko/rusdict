<?php
/* @var $this SiteController */
/* @var $model Statics */
/* @var $login String */

?>

<h2>
<?php echo strip_tags($model->title); ?>
</h2>

<div class="article">
<?php

$this->beginWidget('CHtmlPurifier');
echo $model->content;
$this->endWidget();

?>
</div>

<?php
    if ($login) {
        Yii::app()->clientScript->registerScript('showloginForm',
            "$('#loginModalForm').modal('show');", CClientScript::POS_READY);
    }
?>

