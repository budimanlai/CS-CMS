<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form BSActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
    'id'=>'users-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>true,
    'layout' => BSHtml::FORM_LAYOUT_HORIZONTAL,
)); ?>

    <p class="help-block">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <?php echo $form->textFieldControlGroup($model,'avatar',array('maxlength'=>256)); ?>
    
    <?php echo $form->textFieldControlGroup($model,'username',array('maxlength'=>25)); ?>

    <?php echo $form->textFieldControlGroup($model,'email',array('maxlength'=>50)); ?>

    <?php echo $form->passwordFieldControlGroup($model,'password',array('maxlength'=>60)); ?>

    <?php echo $form->passwordFieldControlGroup($model,'repeat_password',array('maxlength'=>60)); ?>
    
    <?php echo $form->dropDownListControlGroup($model,'user_group', CHtml::listData(UserGroups::model()->findAll('id <> "system"'), 'id', 'name')); ?>

    <?php echo $form->dropDownListControlGroup($model,'status', array('active'=> 'Active', 'inactive' => 'Inactive')); ?>

<?php echo BSHtml::formActions(array(
    BSHtml::submitButton('Submit', array('color' => BSHtml::BUTTON_COLOR_PRIMARY)),
)); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->