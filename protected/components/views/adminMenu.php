<?php /* @var $this Controller */ ?>

<?php $this->widget('bootstrap.widgets.TbNav', array(
    'type' => TbHtml::NAV_TYPE_PILLS,
    'stacked' => true,
    'items' => $this->menu,
)); ?>
