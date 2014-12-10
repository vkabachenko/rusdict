<?php
/* @var AbbrevController $this */
/* @var Abbrev $model */
/* @var CActiveForm $form */
?>

<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'abbrEditForm',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>true,
    )); ?>

    <?php echo $form->errorSummary($model); ?>

    <div>
        <?php echo $form->labelEx($model,'abbrev'); ?>
        <?php echo $form->textField($model,'abbrev'); ?>
        <?php echo $form->error($model,'abbrev'); ?>
    </div>

    <div>
        <?php echo $form->labelEx($model,'text'); ?>
        <?php echo $form->textField($model,'text'); ?>
        <?php echo $form->error($model,'text'); ?>
    </div>


    <div>
        <?php echo
        TbHtml::ajaxButton(
            $model->isNewRecord ? 'Создать' : 'Сохранить',
            $model->isNewRecord ? $this->createUrl('update') :
                $this->createUrl('update', array('id'=>$model->abbrev)),
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