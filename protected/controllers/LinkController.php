<?php

class LinkController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
            'ajaxOnly + update,delete'
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(

			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Updates a particular model. --- AJAX ONLY ! ------
	 */
	public function actionUpdate($id)
	{
		if ($id) {
            $model=$this->loadModel($id);
        }
        else {
            $model = new Links();
        }


        if(isset($_POST['id_link'])){
            $model->id_link=$_POST['id_link'];
            $model->id_article = Yii::app()->session['article'];

            if($model->save()){
                    echo 'success';
                    Yii::app()->end();
            }
        }
            $this->renderPartial('list',array('model'=>$model), false, true);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

	}

	/**
	 * Lists all models.
	 */
	public function actionIndex($id)
	{

        $this->listLetters();

        // Запомнить текущую статью
        Yii::app()->session['article'] = $id;

        // Меню Редактировать, Добаавить
        $this->adminMenu = array(
            array('label'=>'Статья',
                'url'=>$this->createUrl('article/article',array('id'=>$id)),),
            array('label'=>'Редактировать статью',
                'url'=>$this->createUrl('article/edit',array('id'=>$id)),),
            array('label'=>'Добавить ссылку',
                'url'=>array('#'), 'linkOptions'=>array(
                'ajax' => array(
                    'url'=>$this->createUrl('update',array('id'=>0)),
                    'success'=>'function(r){$("#linkUpdate .modal-body").html(r);
                    $("#linkUpdate").modal("show");return false;}',
                ))),
        );

        // получить модель для grid
        $criteria = new CDbCriteria;
        $criteria->condition = "id_article = $id";
        $criteria->with = "idLink";

        $sort = new CSort();
        $sort->attributes = array(
            'id_link'=>array(
                'asc'=>'idLink.title',
                'desc'=>'idLink.title desc',
            ),
        );
        $sort->defaultOrder = 'id_link';

        $model = new CActiveDataProvider('Links',
                array('criteria'=>$criteria,'sort'=>$sort,));

        // вывести grid
        $this->render('admin',array(
            'model'=>$model,
        ));

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Links the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Links::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    /*
     * Создание выпадающего списка ссылок за исключением выбранной статьи
     */

    public function createLinkList($id) {

// Статьи
        $criteria = new CDbCriteria();
        $criteria->order = 'title';
        $criteria->condition = "id <> $id";
        $criteria->select = 'id, id_letter, title';
        $articles = Articles::model()->findAll($criteria);


        // массив уникальных первых букв

        $letters = array();
        foreach ($articles as $article) {
            $letters[] = $article['id_letter'];
        }

        $letters = array_unique($letters);

        // Организация выпадающего списка
        $linkList = array();

        foreach ($letters as $letter) {
            $item = array('label'=>$letter,'items'=>array());
            foreach ($articles as $article) {
                if ($article['id_letter'] == $letter) {
                    $item['items'][] = array('label'=>$article['title'],
                        'url'=>array('article/article',
                            'id'=>$article['id']),
                        'linkOptions'=>array('data-id'=>$article['id'])
                        );
                }
            }
            $linkList[] = $item;
        }

        return $linkList;
    }

}
