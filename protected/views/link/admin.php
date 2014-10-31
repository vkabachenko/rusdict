<?php
/* @var $this LinkController */
/* @var $model CActiveDataProvider */
?>


<h2>
    <?php echo Articles::model()->findByPk(Yii::app()->session['article'])->title; ?>
</h2>

<?php

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'linkUpdate',
    'header' => 'Выберите ссылку',
));


 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'links-grid',
	'dataProvider'=>$model,
	'columns'=>array(
        array('name'=>'id_link',
            'type'=>'raw',
            'value'=>'CHtml::link($data->idLink->title,
            array("article/article","id"=>$data->idLink->id),
            array("target"=>"_blank"))',
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons' => array(
                'update' => array(
                    'click'=>"function() {
    var url = $(this).attr('href');
    $.get(url, function(r){
        $('#linkUpdate .modal-body').html(r);
        $('#linkUpdate').modal('show');
    });
    return false;
}",
                ),
            ),
		),
	),
)); ?>
