<?php
/**
 * @var ArticleController $this
 * @var Articles $model
 * @var CActiveDataProvider $comments
 * @var Comments $newcomment
 * @var int $id_article
 */
$this->pageTitle = $model->title;

$this->keywords = Utf8::mb_lowCase(
    $model->delAccent($model->title.', '.$model->terms));

$this->description = Utf8::mb_trunc($model->article,200);
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
echo $model->setTooltip();
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

<!-- Кнопки социальных сетей -->
<p>Поделиться:
    <img class="social_share" data-type="vk"
         src="<?php echo Yii::app()->baseUrl.'/images/vkontakte.png' ?>"
         width="32" height="32" alt="vkontakte">
    <img class="social_share" data-type="fb"
         src="<?php echo Yii::app()->baseUrl.'/images/facebook.png' ?>"
         width="32" height="32" alt="facebook">

    <img class="social_share" data-type="gg"
         src="<?php echo Yii::app()->baseUrl.'/images/googleplus.png' ?>"
         width="32" height="32" alt="googleplus">
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/socbuttons.js',
    CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('socialShare',
    '$(document).on("click", "img.social_share", function(){
    Share.go(this);});',CClientScript::POS_READY);
?>
</p>
<!-- Кнопки социальных сетей -->

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

        $this->renderPartial('//comment/comment',array('model'=>$newcomment,));
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
