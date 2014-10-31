<?php
/*
 * @var $this SiteController
 * @var $letter String
 * @var $titles[] Articles
 */
$this->pageTitle=Yii::app()->name.' - '.$letter;

    if (!count($titles)):
?>
<h2>Словарных слов не найдено</h2>

<?php
    else:
        $navList = array();
        foreach($titles as $title) {
            $navList[] = array('label'=>$title->title,
                'url'=>$this->createUrl('article',array('id'=>$title->id)));
        }
        echo TbHtml::navList($navList);

    endif;
?>


