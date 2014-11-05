<?php
/* @var $this Controller */
echo TbHtml::beginForm($this->createUrl('article/search'),
    'post',array('id'=>'searchForm'));

echo TbHtml::label('Строка поиска','searchString');
$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'id'=>'searchString',
            'name'=>'searchString',
            'sourceUrl'=>$this->createUrl('site/autocomplete'),
            'options'=>array(
                'minLength'=>'2',
                'autoFocus'=>true,
                'delay'=>'100',
            )
));

echo TbHtml::endForm();
?>