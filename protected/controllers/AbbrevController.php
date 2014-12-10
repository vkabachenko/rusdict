<?php
class AbbrevController extends Controller
{

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
            array('allow', // allow authenticated users to access all actions
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
                'deniedCallback' => array($this,'deniedUrl'),
            ),
        );
    }


    // Вывод списка сокращений (grid) с возможностью добавления, редактирования, удаления
    public function actionIndex() {

        $this->adminMenu = array(
            array('label'=>'Добавить',
                'url'=>array('create'),
                'linkOptions'=>array(
                    'ajax' => array(
                       'url'=>$this->createUrl('update'),
                       'success'=>'function(r){$("#abbrEdit .modal-body").html(r);
                       $("#abbrEdit").modal("show");return false;}',
                ))),
        );

        $model = new Abbrev('Search');

        $this->render('grid',array(
            'model'=>$model,
        ));
    }

    public function actionUpdate($id=null) {

        // $id - первичный ключ (поле abbrev)
        if ($id == null) {
            $model=new Abbrev(); // создание
        }
        else {
            $model=$this->loadModel($id);
        }

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='abbrEditForm')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['Abbrev'])){
            $model->attributes=$_POST['Abbrev'];



            if($model->save()){
                echo 'success';
                Yii::app()->end();
            }
        }

        $this->renderPartial('edit',array('model'=>$model), false, true);
    }


    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

    }



    public function loadModel($id)
    {
        $model=Abbrev::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'Указанная страница не найдена.');
        return $model;
    }

}
?>