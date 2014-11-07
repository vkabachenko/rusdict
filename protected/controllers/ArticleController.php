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
                'actions'=>array('list','article','search'),
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

/*
 * Список заголовков для данной буквы
 */


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

        $navList = array();
        foreach($titles as $title) {
            $navList[] = array('label'=>$title->title,
                'url'=>$this->createUrl('article',array('id'=>$title->id)));
        }


        $this->render('list',array('navList'=>$navList));
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
                    'url'=>$this->createUrl('file/index',array('id'=>$id)),),

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
            $letter = Utf8::mb_firstLetter($model->title);

            // если этой буквы еще нет в таблице Letters, добавляем
            if (!Letters::model()->findByPk($letter)) {
                $modelLetter = new Letters();
                $modelLetter->id = $letter;
                $modelLetter->save();
            }

            // заменяем первую букву в модели
            $model->id_letter = $letter;

            $model->title = CHtml::encode(Utf8::mb_ucfirst($model->title));


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
 * Поиск статьи по заданной строке ввода
 * передается в $_POST['searchString']
 */
    public function actionSearch() {

        $this->listLetters();

        $searchString = trim($_POST['searchString']);

        $navList = array();

        if ($searchString) { // передана непустая строка

             $terms =   Terms::model()->
                with(array('idArticle'=>array('order'=>'idArticle.title')))->
                findAllByAttributes(array('term'=>$searchString));

            // если термины не найдены, переходим на полнотекстовый поиск
            if (!$terms) {
                $this->redirect(array('search/search','term'=>$searchString));
            }

            // выводим найденные статьи
            foreach($terms as $term) {
                $navList[] = array('label'=>$term->idArticle->title,
                'url'=>$this->createUrl('article',array('id'=>$term->id_article)));
            }
        }

        $this->render('list',array('navList'=>$navList));
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


    /*
     * Есть ли файлы для данной статьи
     */
    public function hasFiles($id) {

        return Files::model()->find("id_article = $id");

    }

    /*
     * Получить все файлы для данной статьи в виде массива
     * label=>Название, url=>Адрес
     */

    public function articleFiles($id) {

        $allFiles = array();

        $files = Files::model()->findAll(array(
            'condition'=>"id_article = $id",
            'order'=>'title',));

        foreach ($files as $file) {

            $allFiles[] = array('label'=>$file->title,
                'url'=>$this->createUrl('file/download',array('id'=>$file->id)));

        }

        return $allFiles;
    }

}
?>