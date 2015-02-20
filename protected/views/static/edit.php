<div>

    <?php
    /*
     @var $this StaticController
     @var $model Statics
     */

    $form=$this->beginWidget('CActiveForm', array(
        'id'=>'editForm',
        'enableAjaxValidation'=>false,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),));
    ?>

    <div>
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title'); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>


    <div>

        <?php echo $form->labelEx($model,'content'); ?>
        <?php $this->widget('ext.editCK.EditCK',
            array('model'=>$model,'attribute'=>'content',
                'config'=>array('height'=>'400',),
                ));?>
    </div>


    <div>
        <?php echo CHtml::submitButton('Сохранить'); ?>
    </div>

    <?php $this->endWidget(); ?>


</div>