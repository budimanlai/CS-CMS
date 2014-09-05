<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

                    <?php echo $form->textFieldControlGroup($model,'id',array('maxlength'=>11)); ?>

                    <?php echo $form->textFieldControlGroup($model,'username',array('maxlength'=>25)); ?>

                    <?php echo $form->textFieldControlGroup($model,'email',array('maxlength'=>50)); ?>

                            <?php echo $form->textFieldControlGroup($model,'user_group',array('maxlength'=>15)); ?>

                    <?php echo $form->textFieldControlGroup($model,'create_datetime'); ?>

                    <?php echo $form->textFieldControlGroup($model,'create_by'); ?>

                    <?php echo $form->textFieldControlGroup($model,'create_ip',array('maxlength'=>15)); ?>

                    <?php echo $form->textFieldControlGroup($model,'last_access_ip',array('maxlength'=>15)); ?>

                    <?php echo $form->textFieldControlGroup($model,'last_access_datetime'); ?>

                    <?php echo $form->textFieldControlGroup($model,'status',array('maxlength'=>15)); ?>

                    <?php echo $form->textAreaControlGroup($model,'notes',array('rows'=>6)); ?>

                    <?php echo $form->textFieldControlGroup($model,'avatar',array('maxlength'=>256)); ?>

                    <?php echo $form->textFieldControlGroup($model,'path_code',array('maxlength'=>15)); ?>

                    <?php echo $form->textFieldControlGroup($model,'failed_login'); ?>

                    <?php echo $form->textFieldControlGroup($model,'token_reset',array('maxlength'=>32)); ?>

        <div class="form-actions">
        <?php echo BSHtml::submitButton('Search',  array('color' => BSHtml::BUTTON_COLOR_PRIMARY,));?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->