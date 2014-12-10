<?php
/* @var SectionController $this */
/* @var  Sections $model */

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'idUpdate',
    'header' => 'Раздел',
));

 $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'post-grid',
    'ajaxUpdate'=>false,
    //'dataProvider'=>$model->search(),
    'dataProvider'=>$model,
    'columns'=>array(
        'name',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons' => array(
                'update' => array(
                    'click'=>"function() {
    var url = $(this).attr('href');
    $.get(url, function(r){
        $('#idUpdate .modal-body').html(r);
        $('#idUpdate').modal('show');
    });
    return false;
}",
                ),
            ),
        ),
    ),
));

 if (Yii::app()->user->hasFlash('errorDelete')) {

    $this->widget('bootstrap.widgets.TbModal', array(
        'id' => 'errorDelete',
        'header' => 'Ошибка удаления',
        'content' => Yii::app()->user->getFlash('errorDelete'),
        'show'=>true,
        'footer' => array(
            TbHtml::button('Закрыть', array('data-dismiss' => 'modal')),
        ),
    ));
}
?>