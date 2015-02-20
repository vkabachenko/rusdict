<?php
Yii::import('ext.editCK.BaseEditCK');

class InlineCK extends BaseEditCK {

    public $url; // url controller for save data

    public function init() {

        parent::init();

        $this->name = uniqid();
        $this->config['extraPlugins'] = 'savebtn';//savebtn is the plugin's name
        $this->config['saveSubmitURL'] = $this->url;

        // initialize buffer output
        ob_start();
        ob_implicit_flush(false);
    }

    public function run() {

        parent::run();

        $html = ob_get_clean(); // get html from buffer

        // plugin uses jquery
        Yii::app() -> clientScript -> registerCoreScript('jquery');

        $id = $this -> htmlOptions['id'];

        $htmlOptions = CMap::mergeArray($this->htmlOptions,array('contenteditable'=>'true'));

        echo CHtml::tag('div',$htmlOptions,$html);

        // register script to replace div with ckeditor
        Yii::app() -> clientScript ->
            registerScript('Yii.'.get_class($this).'#'.$id,
                "CKEDITOR.disableAutoInline = true;
                CKEDITOR.inline('{$id}',{$this->jsconfig});",
                CClientScript::POS_END);

    }

} 