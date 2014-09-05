<?php
/* @var $this UsersController */
/* @var $model Users */
?>

<?php
$this->breadcrumbs=array(
    'Users'=>array('index'),
    $model->id,
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage Users', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create New Users', 'url'=>array('create')),
    array('icon' => 'glyphicon glyphicon-edit','label'=>'Update Users', 'url'=>array('update', 'id'=>$model->id)),
    array('icon' => 'glyphicon glyphicon-minus-sign','label'=>'Delete Users', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<?php $this->pageHeader = 'View Users '.$model->id; ?>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
    	'id',
	'username',
	'email',
	'user_group',
	'create_datetime',
	array(
            'name' => 'create_by',
            'value' => $model->mCreateBy->username,
        ),
	'create_ip',
	'last_access_ip',
	'last_access_datetime',
	'status',
    ),
)); ?>