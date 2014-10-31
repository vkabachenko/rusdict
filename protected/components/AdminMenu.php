<?php

Yii::import('zii.widgets.CPortlet');

class AdminMenu extends CPortlet
{
    public $title = "Меню администратора";
    public $menu;

    protected function renderContent()
    {
        $this->render('adminMenu');
    }
}
?>