<?php
/* @var $this FileController */
/* @var $model CActiveDataProvider */
?>


<h2>
    <?php echo Articles::model()->findByPk(Yii::app()->session['article'])->title; ?>
</h2>

<?php

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'fileUpdate',
    'header' => 'Загрузка материала',
    'footer' => array(
        TbHtml::button( 'Сохранить',
            array( 'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'onclick'=>'js:send()',)), // send() - upload.php
        TbHtml::button('Закрыть', array('data-dismiss' => 'modal')),
    ),
));



$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'files-grid',
    'dataProvider'=>$model,
    'columns'=>array(
        array('name'=>'title',
               'type'=>'raw',
               'value'=>'CHtml::link($data->title,array("file/download","id"=>$data->id))'
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons' => array(
                'update' => array(
                    'click'=>"function() {
    var url = $(this).attr('href');
    $.get(url, function(r){
        $('#fileUpdate .modal-body').html(r);
        $('#fileUpdate').modal('show');
    });
    return false;
}",
                ),
            ),
        ),
    ),
)); ?>
