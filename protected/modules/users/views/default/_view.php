<?php
/* @var $this UsersController */
/* @var $data Users */
?>

<div class="view">

    	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id),array('view','id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_group')); ?>:</b>
	<?php echo CHtml::encode($data->user_group); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->create_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_by')); ?>:</b>
	<?php echo CHtml::encode($data->create_by); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('create_ip')); ?>:</b>
	<?php echo CHtml::encode($data->create_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_access_ip')); ?>:</b>
	<?php echo CHtml::encode($data->last_access_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_access_datetime')); ?>:</b>
	<?php echo CHtml::encode($data->last_access_datetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notes')); ?>:</b>
	<?php echo CHtml::encode($data->notes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avatar')); ?>:</b>
	<?php echo CHtml::encode($data->avatar); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('path_code')); ?>:</b>
	<?php echo CHtml::encode($data->path_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('failed_login')); ?>:</b>
	<?php echo CHtml::encode($data->failed_login); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('token_reset')); ?>:</b>
	<?php echo CHtml::encode($data->token_reset); ?>
	<br />

	*/ ?>

</div>