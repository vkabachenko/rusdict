<?php

class FileController extends Controller{

    public function actionUpdate($id_article, $id=null){

        Yii::app()->session['article'] = $id_article;
        // в зависимости от аргумента создаем модель или ищем уже существующую
        if($id===null)
            $model=new Files();
        else if(!$model=Files::model()->findByPk($id))
            throw new CHttpException(404);

        if(isset($_POST['Files'])){
            $model->attributes=$_POST['Files'];

            if($model->save()){
                $this->refresh();
            }
        }

        $this->render('update',array('model'=>$model));
    }
}

?>