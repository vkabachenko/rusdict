<div>

<?php

/* @var  Controller $this */
/* @var  Articles $model */


$form=$this->beginWidget('CActiveForm', array(
    'id'=>'editForm',
    'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),));
?>

    <div>
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('size'=>80)); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'id_section'); ?>

        <?php echo $form->dropDownList($model,
            'id_section',
             CHtml::listData(Sections::model()->findAll(),'id','name'),
            array('class'=>'span4')); ?>

    </div>


    <div>

        <?php echo $form->labelEx($model,'article'); ?>
        <?php $this->widget('ext.editCK.EditCK',
            array('model'=>$model,'attribute'=>'article',
                'config'=>array('height'=>'400',
                                'extraPlugins'=>'abbr',),
            ));?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'terms'); ?>
        <?php echo $form->textField($model,'terms',
            array('style'=>'width:450px;')); ?>
        <?php echo $form->error($model,'terms'); ?>
    </div>



    <div>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

<?php $this->endWidget(); ?>


</div>
