<?php

class SiteController extends Controller
{


    /**
     * Фильтр - действие, выполняемое в начале любого контроллера
     */

    public function filters() {
        return array(
          'accessControl',
          'ajaxOnly + autocomplete',
          'listLetters + index,contacts',
          );
      }

  /*
   *      Контроль доступа (используется в фильтре AccessControl
   */
      public function accessRules()
      {
          return array(
              array('allow',  // allow all users to access 'index' and 'view' actions.
                'actions'=>array('index','login','error','autocomplete','contacts'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated users to access all actions
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
                'deniedCallback' => array($this,'deniedUrl'),
            ),
        );
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($login=null)
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $id_content = $this->action->id;

        if (!Yii::app()->user->isGuest) {

            $this->adminMenu = array(
                array('label'=>'Добавить статью',
                    'url'=>$this->createUrl('article/edit',array('id'=>'0'))),
                array('label'=>'Раздел',
                    'url'=>$this->createUrl('section/grid')),
                array('label'=>'Редактировать',
                    'url'=>$this->createUrl('static/edit',array('id'=>$id_content))),
             );
        }


        $model = Statics::model()->findByPk($id_content);

		$this->render('index',array('model'=>$model,'login'=>$login,));
	}


    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model=new LoginForm;
        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='loginForm')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['LoginForm']))
        {
            $model->attributes=$_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }

    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

/*
 * Автозаполнение поиска (AJAX)
 */
    public function actionAutocomplete() {

        $input = trim($_GET['term']);
        $input = mb_strtolower($input,'utf-8');

        $criteria = new CDbCriteria();
        $criteria->condition = "term LIKE '$input%'";
        $criteria->select = 'term';
        $criteria->order = 'term';
        $criteria->distinct = true;
        $criteria->limit = 8;

        $terms = Terms::model()->findAll($criteria);

        $data = array();
        foreach($terms as $term) {
           $data[]=$term->term;
        }

        echo CJSON::encode($data);
        Yii::app()->end();

    }


/*
 * Контакты
 */
    public function actionContacts()
    {

        $id_content = $this->action->id;

        if (!Yii::app()->user->isGuest) {

            $this->adminMenu = array(

                array('label'=>'Редактировать',
                    'url'=>$this->createUrl('static/edit',array('id'=>$id_content))),
            );
        }


        $model = Statics::model()->findByPk($id_content);

        $this->render('contacts',array('model'=>$model,));
    }


	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

}