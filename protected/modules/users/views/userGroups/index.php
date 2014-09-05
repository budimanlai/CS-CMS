<?php
/* @var $this UserGroupsController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
    'User Groups',
);

$this->menu=array(
    array('label'=>'Create UserGroups','url'=>array('create')),
    array('label'=>'Manage UserGroups','url'=>array('admin')),
);
?>
<?php $this->pageHeader = 'User Groups'; ?>
<?php $this->widget('bootstrap.widgets.BsListView',array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?>