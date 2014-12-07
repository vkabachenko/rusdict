<?php
/* @var Comments[] $items  */
/* @var StatusForm $model  */
/* @var CActiveForm $form  */
/* @var String $selectedStatus  */
?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'status-form',
        'enableAjaxValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <?php echo $form->labelEx($model,'idStatus',
        array('class'=>'inlineLabel')); ?>
    <?php echo $form->dropDownList($model,'idStatus',
        CHtml::listData(Status::model()->findAll(),'id','name'),
        array('options'=>array($selectedStatus=>array('selected'=>true,)),
              'onchange'=>'this.form.submit();'));
    ?>
    <?php echo $form->error($model,'idStatus'); ?>

        <?php $this->endWidget(); ?>

</div>



<div class="form">
    <?php echo CHtml::beginForm(); ?>
    <table class="table table-striped">
        <?php foreach($items as $i=>$item): ?>
            <tr>
                <td><?php echo TbHtml::link($item->idArticle->title,
                        $this->createUrl('article/article',
                            array('id'=>$item->idArticle->id)
                            )); ?></td>

                <td><?php echo TbHtml::link(Utf8::mb_trunc($item->comment,100),
                        $this->createUrl('comment',
                            array('id'=>$item->id)
                            )); ?>

                <td>
                    <?php echo CHtml::activeDropDownList($item,
                        "[$i]id_status",
                        CHtml::listData(Status::model()->findAll(),'id','name')
                        ); ?>
                </td>
                </div>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php echo CHtml::submitButton('Сохранить',array('class'=>'btn btn-primary',)); ?>
    <?php echo CHtml::endForm(); ?>
</div><!-- form -->