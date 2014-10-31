<?php
class ArticleController extends Controller
{

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
                'actions'=>array('list','article'),
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




    public function actionList($id) {

        $this->listLetters();

        if (!Yii::app()->user->isGuest) {

            $this->adminMenu = array(
                array('label'=>'Добавить статью',
                    'url'=>$this->createUrl('article/edit',array('id'=>'0'))),
            );
        }

        $criteria = new CDbCriteria();
        $criteria->select = "id,title";
        $criteria->order = "title";
        $criteria->condition = "id_letter = '".$id."'";
        if ($idSection = Yii::app()->session['section']) {
            $criteria->condition .= " and id_section = $idSection";
        }
        $titles = Articles::model()->findAll($criteria);

        $this->render('list',array('titles'=>$titles,'letter'=>$id));
    }

    /*
     * Статья для выбранного словарного слова
     */
    public function actionArticle($id) {

        $this->listLetters();

        if (!Yii::app()->user->isGuest) {
            $this->adminMenu = array(
                array('label'=>'Редактировать',
                    'url'=>$this->createUrl('edit',array('id'=>$id)),),
                array('label'=>'Удалить','url'=>'#',
                    'linkOptions' =>array('data-toggle'=>'modal',
                        'data-target'=>'#confirmDeleteForm') )
            );
        }

        $model = Articles::model()->findByPk($id);
        $this->render('article',array('model'=>$model));

    }

    /*
     * Редактирование выбранной статьи или создание новой, если $id == 0
     */
    public function actionEdit($id) {

        $this->listLetters();

        // Для новой статьи ссылки можно добавить только после создания
        if ($id) {
            $this->adminMenu = array(
                array('label'=>'Статья',
                    'url'=>$this->createUrl('article',array('id'=>$id)),),
                array('label'=>'Ссылки',
                'url'=>$this->createUrl('link/index',array('id'=>$id)),),
                array('label'=>'Материал',
                    'url'=>$this->createUrl('file/update',array('id_article'=>$id)),),
             );
        }

        // Найти данную модель или создать новую
        if ($id) {
            $model = Articles::model()->findByPk($id);
        }
        else {
            $model = new Articles();
        }

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='editForm')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }


        if(isset($_POST['Articles'])) {

            $model->attributes=$_POST['Articles'];

            // первая буква заголовка
            $letter = Articles::mb_firstLetter($model->title);

            // если этой буквы еще нет в таблице Letters, добавляем
            if (!Letters::model()->findByPk($letter)) {
                $modelLetter = new Letters();
                $modelLetter->id = $letter;
                $modelLetter->save();
            }

            // заменяем первую букву в модели
            $model->id_letter = $letter;
            $model->title = Articles::mb_ucfirst($model->title);


            if($model->save())
                $this->redirect(array('article','id'=>$model->id));
        }
        $this->render('edit',array('model'=>$model,));

    }


    public function actionDelete($id)
    {
            $model = Articles::model()->findByPk($id);
            $id_letter = $model->id_letter;
            $model->delete();

            $this->redirect(array('list','id'=>$id_letter));
    }



    /*
     * Есть ли ссылки для данной статьи
     */
    public function hasLinks($id) {

        return Links::model()->find("id_article = $id");

    }

    /*
     * Получить все ссылки для данной статьи в виде массива
     * label=>Название, url=>Адрес
     */

    public function articleLinks($id) {

        $allLinks = array();

        $links = Links::model()->with('idLink')->findAll("id_article = $id");

        foreach ($links as $link) {

            $allLinks[] = array('label'=>$link->idLink->title,
                'url'=>$this->createUrl('article',array('id'=>$link->id_link)));

        }

            return $allLinks;
    }


}
?>