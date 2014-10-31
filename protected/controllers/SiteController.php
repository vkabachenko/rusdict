<?php

class SiteController extends Controller
{


    /**
     * Фильтр - действие, выполняемое в начале любого контроллера
     */

    public function filters() {
        return array(
          'accessControl',
        );
    }

/*
 *      Контроль доступа (используется в фильтре AccessControl
 */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to access 'index' and 'view' actions.
                'actions'=>array('index','login','error'),
                'users'=>array('*'),
            ),
            array('allow', // allow authenticated users to access all actions
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'

        if (!Yii::app()->user->isGuest) {

            $this->adminMenu = array(
                array('label'=>'Добавить статью',
                    'url'=>$this->createUrl('article/edit',array('id'=>'0'))),
                array('label'=>'Раздел',
                    'url'=>$this->createUrl('section/grid')),
             );
        }

        $this->listLetters();


		$this->render('index');
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