<?php
/* @var $this FileController */
/* @var $model Files */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm',array(
    'htmlOptions'=>array('enctype'=>'multipart/form-data',
        'id'=>'uploadForm','name'=>'uploadForm',
    ),
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

    <div>
        <?php /*echo TbHtml::button($model->isNewRecord ? 'Загрузить' : 'Сохранить',
            array(
                'onclick'=>'js:send();'
            ));
        */?>
    </div>
<?php $this->endWidget(); ?>

<script>
    function send() {

        var form = document.forms.uploadForm;
        var formData = new FormData(form);

        <?php $path = $model->isNewRecord ? $this->createAbsoluteUrl('update', array('id'=>0)) :
    $this->createAbsoluteUrl('update', array('id'=>$model->id));
        $path = json_encode($path);?>

        var path = <?php echo $path ?>;

        $.ajax({
            url:path,
            type:'POST',
            data:formData,
            contentType: false, // обязательно
            processData: false, // для FormData
            success: function (r) {
                    if(r=="success"){
                        window.location.reload();
                    }
                    else{
                        alert("Ошибка загрузки"); return false;
                    }
                }
            }
        )
    }


</script>