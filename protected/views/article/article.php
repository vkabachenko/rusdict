<?php
/**
 * @var $this ArticleController
 * @var $model Articles
 */
$this->pageTitle=Yii::app()->name;
?>
<h3>
    Раздел: <?php
    echo strip_tags($model->idSection->name);
    ?>
</h3>

<h2>
    <?php echo strip_tags($model->title); ?>

</h2>


<?php if ($model->article): ?>

<div class="article">
<?php
$this->beginWidget('CHtmlPurifier');
echo $model->article;
$this->endWidget();

?>
</div>

<?php endif; ?>


<?php if ($model->terms): ?>

<h3>Термины</h3>
<p>
    <?php echo strip_tags($model->terms) ?>
</p>

<?php endif; ?>

 <?php if ($this->hasLinks($model->id)): ?>

        <h3>Ссылки</h3>
        <?php echo TbHtml::stackedPills($this->articleLinks($model->id)); ?>

 <?php endif; ?>

<?php if ($this->hasFiles($model->id)): ?>

    <h3>Материалы</h3>
    <?php echo TbHtml::stackedPills($this->articleFiles($model->id)); ?>

<?php endif; ?>



<?php
if (!Yii::app()->user->isGuest) {
// модальное окно подтверждения удаления
$this->widget('bootstrap.widgets.TbModal', array(
'id' => 'confirmDeleteForm',
'header' => 'Подтвердите удаление',
'content' => 'Заголовок: '.$model->title,
'footer' => array(
TbHtml::button('Удалить',
array( 'submit'=>array('delete','id'=>$model->id),
'color' => TbHtml::BUTTON_COLOR_PRIMARY,)),
TbHtml::button('Отмена', array('data-dismiss' => 'modal')),
),
));
}
?>
