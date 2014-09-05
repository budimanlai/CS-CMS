<?php
/* @var $this UserGroupsController */
/* @var $model UserGroups */
?>

<?php
$this->breadcrumbs=array(
    'User Groups'=>array('index'),
    $model->name,
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage UserGroups', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create UserGroups', 'url'=>array('create')),
    array('icon' => 'glyphicon glyphicon-edit','label'=>'Update UserGroups', 'url'=>array('update', 'id'=>$model->id)),
    array('icon' => 'glyphicon glyphicon-minus-sign','label'=>'Delete UserGroups', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<?php echo $this->pageHeader = 'View UserGroups '.$model->id; ?>

<?php $this->widget('zii.widgets.CDetailView',array(
    'htmlOptions' => array(
        'class' => 'table table-striped table-condensed table-hover',
    ),
    'data'=>$model,
    'attributes'=>array(
    	'id',
	'name',
        array(
            'header' => 'User Count',
            'value' => $model->mUserCount
        )
    ),
)); ?>