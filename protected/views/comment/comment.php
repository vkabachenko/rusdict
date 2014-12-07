<div>

    <?php

    /*  @var Controller $this  */
    /* @var  Comments $model */


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

        <?php if(CCaptcha::checkRequirements() &&
                Yii::app()->user->isGuest): ?>
    <div class="captcha">
        <?php
            echo $form->labelEx($model,'verificationCode');
            $this->widget('CCaptcha');
        ?>
        <p>
        <?php
            echo $form->textField($model,'verificationCode');
            echo $form->error($model,'verificationCode');
        ?>
        </p>
    </div>
        <?php endif ?>

    <div>
        <?php echo CHtml::submitButton('Отправить',array('class'=>'btn btn-primary',)); ?>
    </div>

    <?php $this->endWidget(); ?>


</div>