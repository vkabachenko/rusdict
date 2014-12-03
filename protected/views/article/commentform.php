<div>

    <?php
    /*
     @var $this Controller
     @var $model Comments
     */

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'commentform',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
        'htmlOptions'=>array(
            'style'=>'display:none',),
    ));
    ?>

    <div>
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username',array('class'=>'span6')); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email',array('class'=>'span6')); ?>
        <?php echo $form->error($model,'email'); ?>
    </div>


    <div>
        <?php echo $form->labelEx($model,'comment'); ?>
        <?php echo $form->textArea($model,'comment',array('class'=>'span6')); ?>
        <?php echo $form->error($model,'comment'); ?>
    </div>



    <div>
        <?php echo CHtml::submitButton('Отправить'); ?>
    </div>

    <?php $this->endWidget(); ?>


</div>