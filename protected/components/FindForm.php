<?php

Yii::import('zii.widgets.CPortlet');

class FindForm extends CPortlet
{
    public $title = "Поиск статьи";

    protected function renderContent()
    {
        $this->render('findForm');
    }
}
?>