<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
    public $adminMenu=array();


    public function beforeAction($action) {
        Yii::app()->bootstrap->register();
        Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/main.css');
        return parent::beforeAction($action);
    }



/*
     *Добавление верхнего горизонтального меню
*/

    public function listLetters() {

// Разделы
     $critSection = new CDbCriteria();
     $critSection->order = 'name';
     $critSection->select = 'id,name';
     $sections = Sections::model()->findAll($critSection);

     // заголовок выпадающего меню - из сессии

     if ($id = Yii::app()->session['section']) {
         $label = Sections::model()->findByPk($id)->name;
     }
     else {
         $label = 'Все разделы';
     }


     $dropdownMenu = array();
     $dropdownMenu[] = array('label'=>'Все разделы',
         'url'=>$this->createUrl('section/list',array('id'=>0)));
     foreach ($sections as $section) {
         $dropdownMenu[] = array('label'=>$section->name,
             'url'=>$this->createUrl('section/list',array('id'=>$section->id)));
     }

     $this->menu[] = array('label'=>$label,'items'=>$dropdownMenu);


// Буквы
        $criteria = new CDbCriteria();
        $criteria->order = 'id';
        $letters = Letters::model()->findAll($criteria);

        foreach ($letters as $letter) {
            $this->menu[] = array('label'=>$letter->id,
                'url'=>$this->createUrl('article/list',array('id'=>$letter->id)));
        }

    }



}