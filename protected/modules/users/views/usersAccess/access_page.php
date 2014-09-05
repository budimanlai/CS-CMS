<?php
$this->breadcrumbs = array(
    'links' => array('System' => '#', 'Users Access'),
);
$this->menu=array(
    array('label' => 'USERS'),
    array('label' => 'List Users','url'=> Yii::app()->createUrl('UsersManagement/admin/Users/index')),
    array('label' => 'Create Users','url' => Yii::app()->createUrl('UsersManagement/admin/Users/create')),
    array('label' => 'USERS GROUP'),
    array('label' => 'Manage User Group', 'url' => Yii::app()->createUrl('UsersManagement/admin/UserGroups/admin')),
    array('label' => 'Create User Group', 'url' => Yii::app()->createUrl('UsersManagement/admin/UserGroup/create')),
    array('label' => 'ACCESS PAGE'),
    array('label' => 'Access Page', 'url' => Yii::app()->createUrl('UsersManagement/admin/UsersAccess/accessPage')),
);
?>
<script type="text/javascript">
$(document).ready(function(){
    var path = new Array();
    path[0] = {"name": "Backend", "path": "protected/backend/controllers"};
    path[1] = {"name": "Backend Modules", "path": "protected/modules"};
    path[2] = {"name": "FrontEnd", "path": "protected/controllers"};
    
    $.each(path, function(index, value){
        //alert("index: " + index + " --> " + value.path);
        var url = "index.php?r=UsersManagement/admin/UsersAccess/ScanDirectory&path=" + value.path + "&user_id=1&name=" + value.name;
        $.get(url, function(data){
            $('#id-access').append(data);
        });
    });
});
</script>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'access-form',
    'enableAjaxValidation'=>false,
)); ?>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Save',
    )); ?>
</div>
<table class="table" id="id-access"></table>
<div class="form-actions">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => 'Save',
    )); ?>
</div>
<?php $this->endWidget(); ?>