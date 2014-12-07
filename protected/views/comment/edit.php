<div>

    <?php
    /* @var  Controller $this */
    /* @var  Comments $model */
    ?>
    <h3>
        Комментарий к статье &nbsp;
        <?php echo TbHtml::link($model->idArticle->title,
            $this->createUrl('article/article',
                array('id'=>$model->idArticle->id)
                ));?>
    </h3>

    <?php
        $this->renderPartial('comment',array('model'=>$model));
    ?>
    <script>
        $('#commentform').show(); /* по умолчанию форма скрыта */
    </script>

</div>