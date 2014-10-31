<?php
/* @var $this FileController */
/* @var $model Files */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm',array(
    'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>
<?php /* текстовое поле названия элемента */ ?>
    <div>
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title'); ?>
        <?php echo $form->error($model,'title'); ?>
    </div>

<?php /* поле для загрузки файла */ ?>
    <div>
        <?php if($model->document): ?>
            <p><?php echo CHtml::encode($model->document); ?></p>
        <?php endif; ?>
        <?php echo $form->labelEx($model,'document'); ?>
        <?php echo $form->fileField($model,'document'); ?>
        <?php echo $form->error($model,'document'); ?>
    </div>

<?php /* кнопка отправки */ ?>
    <div>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
    </div>
<?php $this->endWidget(); ?>