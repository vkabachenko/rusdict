<?php
echo CHtml::beginForm('site/search');

$controller = $this->getController();
$controller->widget('zii.widgets.jui.CJuiAutoComplete', array(
'name'=>'terms',
'htmlOptions'=>array('class'=>'span2',),
'source'=>array('ac1', 'ac2', 'ac3'),
));

echo CHtml::submitButton('Поиск');
echo CHtml::endForm();
?>
