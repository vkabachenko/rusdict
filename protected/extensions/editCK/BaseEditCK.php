<?php

abstract class BaseEditCK extends CInputWidget {

/*
 * properties from  CInputWidget
 *
 */
/* @var string name
 * @var string value
 * @var CModel model
 * @var string attribute
 * @var string[] HtmlOptions
 *
 *
 * widget specific properties
 */
    // upload path from the root of the website. Must be writable for all
    public $uploadPath = '/upload';

    //path to folder where KCfinder folder exists (from the root of the website)
    // don't put it in protected folder!
    public $vendorsPath = '/vendors';

    // user's config values for CKEditor. See http://docs.ckeditor.com/#!/api/CKEDITOR.config
    public $config = array();

    protected $jsconfig;

    protected $nameId;


    public function init() {

        // integration with KCFinder

        $baseDir = Yii::app()->baseUrl.$this->vendorsPath;

        $this->config['filebrowserBrowseUrl'] = $baseDir.'/kcfinder/browse.php?opener=ckeditor&type=files';
        $this->config['filebrowserImageBrowseUrl'] = $baseDir.'/kcfinder/browse.php?opener=ckeditor&type=images';
        $this->config['filebrowserFlashBrowseUrl'] = $baseDir.'/kcfinder/browse.php?opener=ckeditor&type=flash';
        $this->config['filebrowserUploadUrl'] = $baseDir.'/kcfinder/browse.php?opener=ckeditor&type=files';
        $this->config['filebrowserImageUploadUrl'] = $baseDir.'/kcfinder/browse.php?opener=ckeditor&type=images';
        $this->config['filebrowserFlashUploadUrl'] = $baseDir.'/kcfinder/browse.php?opener=ckeditor&type=flash';

        $_SESSION['KCFINDER'] = array(
            'disabled' => false,
            'uploadURL' => Yii::app()->baseUrl.$this->uploadPath,
            'uploadDir' => realpath(Yii::app()->basePath . '/..'.$this->uploadPath),
        );
    }


    public function run() {

        // publish assets
        $excludeFiles = Yii::app() -> assetManager -> excludeFiles;
        array_push($excludeFiles,'LICENSE.md','README.md','CHANGES.md','samples');
        Yii::app() -> assetManager -> excludeFiles = $excludeFiles;

        $baseDir = dirname(__FILE__);
        $assets = CHtml::asset($baseDir.DIRECTORY_SEPARATOR.'ckeditor');

        // register js script file
        Yii::app() -> clientScript -> registerScriptFile($assets.'/ckeditor.js');

        // jsconfig array as js object
        $this->jsconfig = CJavaScript::encode($this->config);

        // get widget's name and id
        $this->nameId = $this -> resolveNameID();
        $this->htmlOptions['id'] = $this->nameId[1];

    } // run

} // class

?>