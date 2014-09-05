<?php
    /* @var $this UserGroupsController */
    /* @var $model UserGroups */
?>

<?php
$this->breadcrumbs=array(
    'User Groups'=>array('index'),
    $model->name=>array('view','id'=>$model->id),
    'Update',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage UserGroups', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create UserGroups', 'url'=>array('create')),
    array('icon' => 'glyphicon glyphicon-minus-sign','label'=>'Delete UserGroups', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>
<?php $this->pageHeader = 'Update UserGroups '.$model->id; ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>