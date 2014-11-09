<?php
class StaticController extends Controller
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

array('allow', // allow authenticated users to access all actions
'users'=>array('@'),
),
array('deny',  // deny all users
'users'=>array('*'),
),
);
}

    /*
     * Редактирование выбранного контента
     */
    public function actionEdit($id) {


        $model = Statics::model()->findByPk($id);

        if(isset($_POST['Statics'])) {

            $model->attributes=$_POST['Statics'];
            if($model->save()) {
                $this->redirect(array("site/$id"));
            }
        }
        $this->render('edit',array('model'=>$model,));

    }



}
?>