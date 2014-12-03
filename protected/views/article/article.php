<?php
/**
 * @var $this ArticleController
 * @var $model Articles
 * @var $comments CActiveDataProvider
 * @var $newcomment Comments
 * @var $id_article int
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

    <h3>Комментарии</h3>

<?php if(Yii::app()->user->hasFlash('comment')): ?>
    <?php $yourComment = true; ?>
    <a name="anchorComment"></a>
    <div id="yourComment">
        <p>Ваш комментарий (отправлен на премодерацию):</p>
        <p>
            <?php echo Yii::app()->user->getFlash('comment'); ?>
        </p>
    </div>
    <?php
    Yii::app()->clientScript->registerScript('anchorComment',
        'location.hash="anchorComment";', CClientScript::POS_READY);
    ?>

<?php endif; ?>



    <?php
    $this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider'=>$comments,
    'id'=>'comments',
    'itemView'=>'_comment',
    'itemsTagName'=>'div',
    'template' => '{items}{pager}',
    'emptyText'=>'<em>Комментариев пока нет</em>',
    ));

    if (!isset($yourComment)) {
        // Кнопка нового комментария
        echo TbHtml::button('Оставить комментарий',
        array( 'id'=>'btnComment',
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'onclick'=>'$("#commentform").show();$(this).hide();'
        ));

        $this->renderPartial('commentform',array('model'=>$newcomment,));
    }
    ?>

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
