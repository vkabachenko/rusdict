<?php
/* @var $this SiteController */
/* @var $model Statics */
/* @var $login String */

?>

<h2>
<?php echo strip_tags($model->title); ?>
</h2>

<div class="article">
<?php echo $model->content; ?>
</div>

<?php
    if ($login) {
        Yii::app()->clientScript->registerScript('showloginForm',
            "$('#loginModalForm').modal('show');", CClientScript::POS_READY);
    }
?>

