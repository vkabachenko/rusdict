<?php
/* @var AbbrevController $this */
/* @var  Abbrev $model */

$this->widget('bootstrap.widgets.TbModal', array(
    'id' => 'abbrEdit',
    'header' => 'Сокращения',
));

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'abbrGrid',
    'filter'=>$model,
    'dataProvider'=>$model->search(),
    'columns'=>array(
        array(
            'name'=>'abbrev',
            ),
        array(
            'name'=>'text',
            ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons' => array(
                'update' => array(
                    'click'=>"function() {
    var url = $(this).attr('href');
    $.get(url, function(r){
        $('#abbrEdit .modal-body').html(r);
        $('#abbrEdit').modal('show');
    });
    return false;
}",
                ),
            ),
        ),
    ),
));

?>