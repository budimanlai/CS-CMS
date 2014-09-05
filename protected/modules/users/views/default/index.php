<?php
/* @var $this UsersController */
/* @var $dataProvider CActiveDataProvider */
?>

<?php
$this->breadcrumbs=array(
    'Users',
);

$this->menu=array(
    array('label'=>'Create Users','url'=>array('create')),
    array('label'=>'Manage Users','url'=>array('admin')),
);
?>
<?php $this->pageHeader = 'Users'; ?>
<?php $this->widget('bootstrap.widgets.BsListView',array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?>