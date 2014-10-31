<?php

 /* @var $form CActiveForm */

$model = new LoginForm();
?>

<div class="form">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'loginForm',
        'action'=> $this->createUrl('site/login'),
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <div>
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username'); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>

    </div>

    <div>
        <?php echo $form->checkBox($model,'rememberMe'); ?>
        <?php echo $form->label($model,'rememberMe'); ?>
        <?php echo $form->error($model,'rememberMe'); ?>
    </div>


    <?php $this->endWidget(); ?>
</div><!-- form -->