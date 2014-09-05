<?php
/* @var $this UserGroupsController */
/* @var $model UserGroups */


$this->breadcrumbs=array(
    'User Groups'=>array('index'),
    'Manage',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage UserGroups', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create UserGroups', 'url'=>array('create')),
    array('icon' => 'glyphicon glyphicon-search','label'=>'Adv. Search', 'url'=>'#', 'htmlOptions'=>array('class'=>'search-button')),
);
$this->widget('bootstrap.widgets.BsGridView',array(
    'id'=>'user-groups-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
    	'id',
	'name',
        array(
            'class'=>'bootstrap.widgets.BsButtonColumn',
        ),
    ),
)); ?>