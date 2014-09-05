<?php
    /* @var $this UsersController */
    /* @var $model Users */
?>

<?php
$this->breadcrumbs=array(
    'Users'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Update',
);

$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage Users', 'url'=>array('admin')),
    array('icon' => 'glyphicon glyphicon-plus-sign','label'=>'Create Users', 'url'=>array('create')),
    array('icon' => 'glyphicon glyphicon-minus-sign','label'=>'Delete Users', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>
<?php $this->pageHeader = 'Update Users '.$model->id; ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>