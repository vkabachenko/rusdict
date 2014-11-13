<?php
/* @var $data Articles */
/* @var $this CController */

echo TbHtml::tag('li',array(),
        TbHtml::link($data->title,
            $this->createUrl('article',array('id'=>$data->id))));

?>