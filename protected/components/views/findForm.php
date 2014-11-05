<div id="find">

<?php

echo CHtml::link('Найти','#',array('data-toggle'=>'modal','data-target'=>'#findModalForm'));

?>

</div>

<?php
$this->getController()->widget('bootstrap.widgets.TbModal', array(
    'id' => 'findModalForm',
    'header' => 'Поиск',
    'content' => $this->getController()->renderPartial('//site/find',array(),true),
    'footer' => array(
        TbHtml::button('Найти',
            array( 'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                   'onclick'=>'$("#searchForm").submit()'
                )),
        TbHtml::button('Закрыть', array('data-dismiss' => 'modal')),
    ),
));
?>
