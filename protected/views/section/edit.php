<?php
/* @var $this SectionController */
/* @var $model Sections */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'section-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div>
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>128,'maxlength'=>128,'style'=>'width:450px;')); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>



            <div>
                <?php echo
                TbHtml::ajaxButton(
                    $model->isNewRecord ? 'Создать' : 'Сохранить',
                    $model->isNewRecord ? $this->createUrl('update', array('id'=>0)) :
                                        $this->createUrl('update', array('id'=>$model->id)),
                    array(
                        'dataType'=>'text',
                        'type'=>'POST',
                        'data'=>'js:jQuery(this).parents("form").serialize()',
                        'success'=>'function(r){
                            if(r=="success"){
                                window.location.reload();
                            }
                            else{
                                alert("Ошибка AJAX"); return false;
                            }
                        }',
                        array( // $htmlOptions
                            // to avoid multiple ajax request
                            // http://www.yiiframework.com/wiki/178/how-to-avoid-multiple-ajax-request/
                            'id' => 'open-modal-'.uniqid(),
                        )
                    )
                );

                ?>
            </div>



    <?php $this->endWidget(); ?>

</div><!-- form -->