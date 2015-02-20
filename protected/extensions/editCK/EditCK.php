<?php
Yii::import('ext.editCK.BaseEditCK');

class EditCK extends BaseEditCK {

    // inline mode, false by default (i.e. replace)
    public $inline = false;

    public function run() {

        parent::run();

        // register css for inline text area (not include in skin)
        if ($this->inline) {
             Yii::app() -> clientScript -> registerCss('CKinlineStyle',
              '.cke_textarea_inline
		            {
		            	padding: 10px;
		            	height: 200px;
		            	overflow: auto;
            			border: 1px solid gray;
            			-webkit-appearance: textfield;
            		}'
        );
    }

        $mode = $this->inline ? 'inline' : 'replace';

        $id = $this -> htmlOptions['id'];
        $name = $this->nameId[0];

        // register script to replace textarea with ckeditor
        Yii::app() -> clientScript ->
             registerScript('Yii.'.get_class($this).'#'.$id,
            'CKEDITOR.'.$mode."('{$name}',{$this->jsconfig});",
             CClientScript::POS_END);

        // make textarea from model or simple form
        if($this->hasModel()) {
            echo CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
        }
        else {
            echo CHtml::textArea($name, $this->value, $this->htmlOptions);
        }

    } // run

} // class

?>