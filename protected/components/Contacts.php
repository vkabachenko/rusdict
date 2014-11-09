<?php

Yii::import('zii.widgets.CPortlet');

class Contacts extends CPortlet
{
    public $title = "Контакты";

    protected function renderContent()
    {
        echo CHtml::tag('div',array('id'=>'contacts'),
            CHtml::link('ПсковГУ. Филологический факультет',
            Yii::app()->createUrl('site/contacts')));

    }
}
?>