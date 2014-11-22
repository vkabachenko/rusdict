<?php

class FileController extends Controller{

    public function filters() {
        return array(
            'accessControl',
            'ajaxOnly + update',
            'listLetters + index',
        );
    }

    /*
     *      Контроль доступа (используется в фильтре AccessControl
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to access 'list' action.
                'actions'=>array('download'),
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

    // Скачивание файла
    public function actionDownload($id) {

     try {
        $file = Files::model()->findByPk($id);

        $path = Yii::getPathOfAlias('files').DIRECTORY_SEPARATOR.$file->filename;

        $content = file_get_contents($path);

        Yii::app()->getRequest()->sendFile($file->document,$content);
     }
     catch (CException $e){
         throw new CHttpException(404,'Файл не найден');
     }


    }

    /**
     * Lists all models.
     */
    public function actionIndex($id)
    {

        // Запомнить текущую статью
        Yii::app()->session['article'] = $id;

        // Меню Редактировать, Добаавить
        $this->adminMenu = array(
            array('label'=>'Статья',
                'url'=>$this->createUrl('article/article',array('id'=>$id)),),
            array('label'=>'Редактировать статью',
                'url'=>$this->createUrl('article/edit',array('id'=>$id)),),
            array('label'=>'Ссылки',
                'url'=>$this->createUrl('link/index',array('id'=>$id)),),
            array('label'=>'Добавить материал',
                'url'=>array('#'), 'linkOptions'=>array(
                'ajax' => array(
                    'url'=>$this->createUrl('update',array('id'=>0)),
                    'success'=>'function(r){$("#fileUpdate .modal-body").html(r);
                    $("#fileUpdate").modal("show");return false;}',
                ))),
        );

        // получить модель для grid
        $criteria = new CDbCriteria;
        $criteria->condition = "id_article = :id_article";
        $criteria->params = array(':id_article'=>$id);
        $criteria->order = "title";

        $model = new CActiveDataProvider('Files',
            array('criteria'=>$criteria,));

        // вывести grid
        $this->render('admin',array(
            'model'=>$model,
        ));

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
            $model = new Files();
        }


        if(isset($_POST['Files'])){
            $model->attributes=$_POST['Files'];

            if($model->save()){
                echo 'success';
                Yii::app()->end();
            }
        }
        $this->renderPartial('upload',array('model'=>$model), false, true);
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


    public function loadModel($id)
    {
        $model=Files::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Файл не найден.');
        return $model;
    }


}
?>