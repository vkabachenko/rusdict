<div>

<?php
/*
 @var $this Controller
 @var $model Articles
 */

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
        <?php $this->widget('ext.editMe.widgets.ExtEditMe',
            array('model'=>$model,'attribute'=>'article',
            'height'=>'400',));?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'terms'); ?>
        <?php echo $form->textField($model,'terms',
            array('size'=>255,'maxlength'=>255,'style'=>'width:450px;')); ?>
        <?php echo $form->error($model,'terms'); ?>
    </div>



    <div>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>

<?php $this->endWidget(); ?>


</div>