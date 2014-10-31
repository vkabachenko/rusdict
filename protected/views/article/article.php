<?php
/**
 * @var $this ArticleController
 * @var $model Articles
 */
$this->pageTitle=Yii::app()->name;
?>
<h3>
    Раздел: <?php
    echo $model->idSection->name;
    ?>
</h3>

<h2>
    <?php echo $model->title; ?>

</h2>


<?php if ($model->article): ?>

<div class="article">
<?php echo $model->article; ?>
</div>

<?php endif; ?>


<?php if ($this->hasLinks($model->id)): ?>

<h3>Ссылки</h3>
<?php echo TbHtml::stackedPills($this->articleLinks($model->id)); ?>

<?php endif; ?>

<?php if ($model->terms): ?>

<h3>Термины</h3>
<p>
    <?php echo $model->terms ?>
</p>

<?php endif; ?>

<?php
// модальное окно подтверждения удаления
$this->widget('bootstrap.widgets.TbModal', array(
'id' => 'confirmDeleteForm',
'header' => 'Подтвердите удаление',
'content' => 'Словарное слово: '.$model->title,
'footer' => array(
TbHtml::button('Удалить',
array( 'submit'=>array('delete','id'=>$model->id),
'color' => TbHtml::BUTTON_COLOR_PRIMARY,)),
TbHtml::button('Отмена', array('data-dismiss' => 'modal')),
),
));
?>
