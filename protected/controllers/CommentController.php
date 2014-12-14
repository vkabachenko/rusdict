<?php
class CommentController extends Controller
{

    public function filters() {
        return array(
            'accessControl',
            'listLetters',
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
            $this->denyFilter($this),
        );
    }

    public function actions() {
        return array(
            'captcha'=>array('class'=>'CCaptchaAction',)
        );
    }


    public function actionComment($id) {

        $model = Comments::model()->with('idArticle')->findByPk($id);

        if(isset($_POST['Comments'])) {
            $model->attributes=$_POST['Comments'];
            if($model->validate()) {
                $model->save();
            }
        }

        $this->render('edit',array('model'=>$model,));
    }



    public function actionUpdate()
    {
        $model=new StatusForm();

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='status-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if(isset($_POST['StatusForm'])) {
            $model->attributes=$_POST['StatusForm'];
            $selectedStatus = $model->idStatus;
        }
        else {
            if (!isset(Yii::app()->session['selectedStatus']))
                 $selectedStatus = Status::model()->getStatusCodeFromValue('Ожидание');
            else
                 $selectedStatus = Yii::app()->session['selectedStatus'];
        }

        Yii::app()->session['selectedStatus'] = $selectedStatus;

        /*
         * http://www.yiiframework.com/doc/guide/1.1/ru/form.table
         *
         */
        // извлекаем элементы, которые будем обновлять в пакетном режиме,
        // предполагая, что каждый элемент является экземпляром класса модели
        $items=$this->getItemsToUpdate($selectedStatus );
        if(isset($_POST['Comments']))
        {
            foreach($items as $i=>$item)
            {
                if(isset($_POST['Comments'][$i]))
                    $item->attributes=$_POST['Comments'][$i];
                    $item->save();
            }
            $this->refresh();
        }
        // отображаем представление с формой для ввода табличных данных
        $this->render('update',array('items'=>$items,
            'model'=>$model,'selectedStatus'=>"$selectedStatus"));
    }


    private function getItemsToUpdate($id) {

        $criteria = new CDbCriteria;
        $criteria->with = array('idArticle','idStatus'=>array(
            'condition'=>"idStatus.id = $id"));
        $criteria->order = 'idArticle.title,t.date_created DESC';

        $items = Comments::model()->findAll($criteria);

        return $items;

    }



}