<?php
    /* @var $this UserGroupsController */
    /* @var $model UserGroups */
?>

<?php
$this->breadcrumbs=array(
    'User Groups'=>array('index'),
    'Create',
);
$this->menu=array(
    array('icon' => 'glyphicon glyphicon-home','label'=>'Manage UserGroups', 'url'=>array('admin')),
);
?>
<?php $this->pageHeader = 'Create UserGroups'; ?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>