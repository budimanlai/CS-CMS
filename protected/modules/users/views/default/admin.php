<?php
/* @var $this UsersController */
/* @var $model Users */


$this->breadcrumbs=array(
    'Users'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage Users', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create New Users', 'url'=>array('create')),
);

$this->widget('bootstrap.widgets.BsGridView',array(
    'id'=>'users-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
    	'id',
	'username',
	'email',
	array(
            'name' => 'user_group',
            'filter' => CHtml::listData(UserGroups::model()->findAll(), 'id','name')
        ),
	'create_datetime',
        'last_access_ip',
	'last_access_datetime',
	/*
	'create_by',
	'create_ip',
	'status',
	'notes',
	'avatar',
	'path_code',
	'failed_login',
	'token_reset',
	*/
        array(
            'class'=>'bootstrap.widgets.BsButtonColumn',
        ),
    ),
)); ?>