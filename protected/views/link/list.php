<?php
/**
 * Выпадающий список заголовков статей для выбора
 */
/* @var $this LinkController */
/* @var $model Links */
/*
 $this->widget('bootstrap.widgets.TbNav', array(
    'type' => TbHtml::NAV_TYPE_TABS,
    'stacked'=>true,
    'items'=> $this->createLinkList($model->id_article),
));

bootstrap работает через раз
*/

$this->widget('ext.emenu.EMenu',array(
    'id'=>'letters',
    'vertical'=>true,
    'items'=> $this->createLinkList(Yii::app()->session['article']),
));

$url = $this->createUrl('update',
    array('id'=>$model->isNewRecord ? 0 : $model->id));

Yii::app()->clientScript->registerScript('articleSelect', "
      $('#letters a').click(function(){

        $.ajax({
            url:'$url',
            dataType:'text',
            type:'POST',
            data: 'id_link=' + $(this).attr('data-id'),
            success: function(r) {
               if(r=='success'){
                 window.location.reload();
                 }
                 else{
                 alert('Ошибка AJAX'); return false;
                  }
                   }
                });
        return false;
    });", CClientScript::POS_READY);

?>