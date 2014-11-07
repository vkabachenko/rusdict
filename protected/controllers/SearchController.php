<?php

class SearchController extends Controller
{
    private $_indexFiles = '/runtime/search';

    public function init(){
        Yii::import('application.vendor.*');
        require_once('Zend/Search/Lucene.php');
        parent::init();
    }


    public function actionIndex() {

    }

    public function actionCreate() {

        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());

        $index = new Zend_Search_Lucene(
            Yii::getPathOfAlias('application').$this->_indexFiles, true);

        // Add documents to the database.
        $articles = Articles::model()->findAll();
        foreach ($articles as $article) {

            $doc = new Zend_Search_Lucene_Document();
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('id_article', $article->id));
            $doc->addField(Zend_Search_Lucene_Field::Text('title', $article->title,'UTF-8'));
            $content = strip_tags($article->article);
            $doc->addField(Zend_Search_Lucene_Field::Unstored('content', $content,'UTF-8'));
            $index->addDocument($doc);
        }

        $index->optimize();
        $index->commit();

        echo "Индекс построен";

    }

    public function actionSearch($term) {

        $this->listLetters();

        setlocale(LC_CTYPE, 'ru_RU.UTF-8');
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());

        $index = new Zend_Search_Lucene(
            Yii::getPathOfAlias('application').$this->_indexFiles);

        $titles = $index->find($term);

        $navList = array();
        foreach($titles as $title) {
            $navList[] = array('label'=>$title->title,
                'url'=>$this->createUrl('article/article',array('id'=>$title->id_article)));
        }


        $this->render('//article/list',array('navList'=>$navList));

    }

    public function actionUpdate() {
        /*
-----------------------------------------------
$pageIdTerm = new Zend_Search_Lucene_Index_Term($intPageId, 'page_id');
$docsToDelete = $this->objLuceneIndex->termDocs($pageIdTerm);

if (sizeof($docsToDelete) == 1) {
    $this->objLuceneIndex->delete(reset($docsToDelete));
} ...
         */




    }

}




?>