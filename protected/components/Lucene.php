<?php

    class Lucene {

        private $_indexFiles;

        function __construct() {
            Yii::import('application.vendor.*');
            require_once('Zend/Search/Lucene.php');

            setlocale(LC_CTYPE, 'ru_RU.UTF-8');
            Zend_Search_Lucene_Analysis_Analyzer::setDefault(
                new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive());

            $this->_indexFiles = Yii::getPathOfAlias('application').'/runtime/search';
        }

        /*
         * Первоначальное создание индекса
         */

        public function Create() {

            $index = new Zend_Search_Lucene($this->_indexFiles, true);

            // Add documents to the database.
            $articles = Articles::model()->findAll();
            foreach ($articles as $article) {

                $this->addDocument($index,$article);

            }

            $index->optimize();
            $index->commit();
        }

        /*
         * Поиск по строке.
         * Возврат - массив пунктов меню со списком найденных статей
         */

        public function Search($term) {

            $index = new Zend_Search_Lucene($this->_indexFiles);

            $termQuery = Zend_Search_Lucene_Search_QueryParser::parse($term, 'utf-8');

            $items = $index->find($termQuery);

            $articles = array();
            foreach($items as $item) {
                $articles[] = $item->id_article;
            }
            $criteria = new CDbCriteria();
            $criteria->select = "id,title";
            $criteria->order = "title";
            $criteria->addInCondition('id',$articles);

            $model = new CActiveDataProvider("Articles",array('criteria'=>$criteria));

        return $model;

         }

        /*
         * Обновление индекса для одного документа
         */


        public function Update($id_article) {

            $this->Delete($id_article);
            $this->Insert($id_article);
        }


        /*
         * Удаление индекса для одного документа
         */

        public function Delete($id_article) {

            $index = new Zend_Search_Lucene($this->_indexFiles);

            $hits = $index->find('id_article:'.$id_article);
            foreach ($hits as $hit) {
                $index->delete($hit->id);
            }

            $index->commit();

        }

        /*
         * Добавление индекса для одного документа
         */

        public function Insert($id_article) {

            $index = new Zend_Search_Lucene($this->_indexFiles);

            // Add documents to the database.
            $article = Articles::model()->findByPk($id_article);

            $this->addDocument($index,$article);

            $index->commit();
        }


        /*
         * Добавление данных текущего документа к индексу.
         * -- id - Keyword, для поиска без разбиения на лексемы
         * (используется для поиска текущего док при удалении)
         * -- title - Text, разбивается на лексемы, сохраняется в результатах поиска
         * -- content - Unstored, разбивается на лексемы, не сохраняется в результатах поиска
         */

        private function addDocument($index,$article) {

            $doc = new Zend_Search_Lucene_Document();
            $doc->addField(Zend_Search_Lucene_Field::Keyword('id_article', $article->id));
            $doc->addField(Zend_Search_Lucene_Field::Text('title', $article->title,'UTF-8'));
            $content = strip_tags($article->article);
            $doc->addField(Zend_Search_Lucene_Field::Unstored('content', $content,'UTF-8'));
            $index->addDocument($doc);

        }

}