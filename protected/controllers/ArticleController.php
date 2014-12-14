<?php
class ArticleController extends Controller
{

    public function filters() {
        return array(
            'accessControl',
            'listLetters + list,article,edit,search',
        );
    }

    /*
     *      Контроль доступа (используется в фильтре AccessControl
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to access
                'actions'=>array('list','article','search','captcha'),
                'users'=>array('*'),
            ),
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

/*
 * Список заголовков для данной буквы
 */


    public function actionList($id) {


        if (!Yii::app()->user->isGuest) {

            $this->adminMenu = array(
                array('label'=>'Добавить статью',
                    'url'=>$this->createUrl('article/edit',array('id'=>'0'))),
            );
        }

        $criteria = new CDbCriteria();
        $criteria->select = "id,title";
        $criteria->order = "pure_title";

        $criteria->addCondition('id_letter = :id_letter');
        $criteria->params = array(':id_letter'=>"$id");
        if ($idSection = Yii::app()->session['section']) {
            $criteria->addCondition('id_section = :id_section');
            $criteria->params=CMap::mergeArray($criteria->params,
                array(':id_section'=>$idSection));
        }

        $model = new CActiveDataProvider("Articles",array('criteria'=>$criteria));
        $this->render('articles',array('model'=>$model,'searchTitle'=>"буква $id"));
    }

    /*
     * Статья для выбранного словарного слова
     */
    public function actionArticle($id) {

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

        if ($model) {
            // Комментарии
            $criteria = new CDbCriteria();
            $criteria->order = "date_created DESC";
            $criteria->addCondition('id_article = :id_article');
            $criteria->addCondition('id_status = :status');
            $criteria->params = array(':id_article'=>$id,
                ':status'=>Status::model()->getStatusCodeFromValue('Опубликовано'),);
            $comments = new CActiveDataProvider("Comments",array('criteria'=>$criteria));

            // Новый комментарий
            $newcomment = new Comments;
            $newcomment->id_article = $id;
            $newcomment->id_status = Status::model()->getStatusCodeFromValue('Ожидание');

            if(isset($_POST['Comments'])) {
                $newcomment->attributes=$_POST['Comments'];
                if($newcomment->validate()) {
                    $newcomment->save();
                    $this->mailComment($newcomment);
                    Yii::app()->user->setFlash('comment',strip_tags($newcomment->comment));
                }
            }

            $this->render('article',array('model'=>$model,
                'comments'=>$comments,'newcomment'=>$newcomment,
                ));
        }
        else {
            throw new CHttpException(404,'Указанная запись не найдена');
        }

    }

    /*
     * Редактирование выбранной статьи или создание новой, если $id == 0
     */
    public function actionEdit($id) {

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
            $model->pure_title = $model->delAccent($model->title);


            if($model->save()) {

                // удаление всех терминов, связанных с данной статьей
                Terms::model()->deleteAllByAttributes(array('id_article'=>$model->id));
                // добавляем новые термины
                $model->fillTerms();

                // добавляем к индексу Zend Lucene Search
                $lucene = new Lucene();
                $lucene->Update($model->id);

                $this->redirect(array('article','id'=>$model->id));
            }
        }

        $this->render('edit',array('model'=>$model,));

    }


    public function actionDelete($id)
    {
            $model = Articles::model()->findByPk($id);
            $id_letter = $model->id_letter;

            // удаляем из индекса Lucene
            $lucene = new Lucene();
            $lucene->Delete($model->id);

            $model->delete();

            $this->redirect(array('list','id'=>$id_letter));
    }

/*
 * Поиск статьи по заданной строке ввода
 * передается в $_POST['searchString']
 */
    public function actionSearch() {


        $searchString = trim($_POST['searchString']);
        $model = null;

        if ($searchString) { // передана непустая строка

             $terms =   Terms::model()->
                with('idArticle')->
                findAllByAttributes(array('term'=>$searchString));

            // если термины не найдены, переходим на полнотекстовый поиск
            if (!$terms) {
                $lucene = new Lucene();
                $model = $lucene->Search($searchString);
            }
            else {
               // выводим найденные статьи
               $articles = array();
               foreach($terms as $term) {
                   $articles[] = $term->idArticle->id;
                 }
               $criteria = new CDbCriteria();
               $criteria->select = "id,title";
               $criteria->order = "title";
               $criteria->addInCondition('id',$articles);

               $model = new CActiveDataProvider("Articles",array('criteria'=>$criteria));


            }
        }

        $this->render('articles',array('model'=>$model,'searchTitle'=>$searchString));
    }



    /*
     * Есть ли ссылки для данной статьи
     */
    public function hasLinks($id) {

        return Links::model()->find('id_article = :id_article',array(':id_article'=>$id,));

    }

    /*
     * Получить все ссылки для данной статьи в виде массива
     * label=>Название, url=>Адрес
     */

    public function articleLinks($id) {

        $allLinks = array();

        $links = Links::model()->with('idLink')
            ->findAll('id_article = :id_article',array(':id_article'=>$id,));

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

        return Files::model()->find('id_article = :id_article',array(':id_article'=>$id,));

    }

    /*
     * Получить все файлы для данной статьи в виде массива
     * label=>Название, url=>Адрес
     */

    public function articleFiles($id) {

        $allFiles = array();

        $files = Files::model()->findAll(array(
            'condition'=>"id_article = :id_article",
            'order'=>'title',
            'params'=>array(':id_article'=>$id,),));

        foreach ($files as $file) {

            $allFiles[] = array('label'=>$file->title,
                'url'=>$this->createUrl('file/download',array('id'=>$file->id)));

        }

        return $allFiles;
    }

    // Сообщить админу о присланном комменте

    private function mailComment($comment) {

        $article = Articles::model()->findByPk($comment->id_article);

        $name='=?UTF-8?B?'.base64_encode($comment->username).'?=';
        $subject='=?UTF-8?B?'.base64_encode('Rusdict: комментарий к статье '.
                $article->title).'?=';
        $headers="From: $name <{$comment->email}>\r\n".
            "Reply-To: {$comment->email}\r\n".
            "MIME-Version: 1.0\r\n".
            "Content-Type: text/plain; charset=UTF-8";

        mail(Yii::app()->params['adminEmail'],$subject,
            CHtml::encode($comment->comment),$headers);

        /* В зависимости от настроек адрес отправителя и Reply-To могут
            не соответствовать установленным в этом скрипте
        */

    }

}
?>