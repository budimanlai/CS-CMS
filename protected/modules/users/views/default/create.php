<?php
    /* @var $this UsersController */
    /* @var $model Users */
?>

<?php
$this->breadcrumbs=array(
    'Users'=>array('index'),
    'Create',
);
$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage Users', 'url'=>array('admin')),
);
?>
<?php $this->pageHeader = 'Create Users'; ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>