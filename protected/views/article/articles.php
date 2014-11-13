
<h3>Поиск: <?php echo $searchTitle ?></h3>
<?php
/* @var $model CActiveDataProvider */
/* @var $searchTitle String */

$this->widget('bootstrap.widgets.TbListView', array(
    'dataProvider'=>$model,
    'id'=>'articleList',
    'itemView'=>'_item',
    'itemsTagName'=>'ul',
    'template' => '{items}{pager}',
    'emptyText'=>'<em> Статьи не найдены</em>',
));

?>