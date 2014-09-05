<?php

class ImagesCrop extends CWidget {
    public $label;
    public $image_url;
    public $id;
    public $attribute;
    public $data = array();
    
    private $btnId;
    private $btnApply;
    private $cropContainer;
    private $helper;
    private $avatarId;
    private $avatarPreview;
    private $avatarWidth;
    private $avatarHeight;
    private $aspectRatio;
    
    public function init() {
        parent::init();
        if (empty($this->id)) $this->id = $this->getId();
        $this->btnId = $this->id."_images";
        $this->btnApply = $this->id."_apply";
        $this->cropContainer = $this->id."_cropContainer";
        $this->helper = $this->id."_helper";
        $this->avatarId = $this->id."_avatar";
        $this->avatarPreview = $this->id."_preview";
        $this->avatarWidth = Yii::app()->params['user']['width'];
        $this->avatarHeight = Yii::app()->params['user']['height'];
        $this->aspectRatio = Yii::app()->params['user']['aspectRatio'];
        
        if (!empty($this->data)) 
            $this->data = json_encode($this->data);
        else
            $this->data = '{}';
    }
    
    public function run() {
        $this->render('imagescrop', array(
            'btnId' => $this->btnId,
            'label' => $this->label,
            'image_url' => $this->image_url,
            'cropContainer' => $this->cropContainer,
            'helper' => $this->helper,
            'avatarPreview' => $this->avatarPreview,
            'width' => $this->avatarWidth,
            'height' => $this->avatarHeight,
            'btnApply' => $this->btnApply,
        ));
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/SimpleAjaxUploader.min.js");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl."/js/jquery.imgareaselect.pack.js");
        Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl."/css/imgareaselect-default.css");
        Yii::app()->clientScript->registerScript($this->id, "
var imgWidth, imgHeight, crop = {};
function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;
    
    var scaleX = {$this->avatarWidth} / selection.width;
    var scaleY = {$this->avatarHeight} / selection.height;
        
    $('#{$this->avatarPreview}').css({
        width: Math.round(scaleX * imgWidth),
        height: Math.round(scaleY * imgHeight),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    }); 
    crop.x1 = selection.x1;
    crop.y1 = selection.y1;
    crop.x2 = selection.x2;
    crop.y2 = selection.y2;
}
var uploader = new ss.SimpleUpload({
      button: '{$this->btnId}', // HTML element used as upload button
      url: '".Yii::app()->createUrl("users/default/uploadavatar")."', // URL of server-side upload handler
      name: '{$this->attribute}', // Parameter name of the uploaded file
      allowedExtensions: ['jpg', 'jpeg', 'png'],
      data: {$this->data},
      multipart: true,
      disabledClass: 'disabled',
      onSubmit: function( filename, extension, uploadBtn ){
        $(uploadBtn).html('Uploading');
      },
      onError: function( filename, errorType, status, statusText, response, uploadBtn ){
        alert(statusText);
        $(uploadBtn).html('Change');
      },
      onComplete: function( filename, response, uploadBtn ){
        var obj = $.parseJSON(response);
        var img_url = '".Yii::app()->request->baseUrl."/'+obj.avatar_url;
        imgWidth = obj.image.width;
        imgHeight = obj.image.height;
        
        $(uploadBtn).hide().html('Change');
        $('#{$this->btnApply}').show();
        $('#{$this->helper}').hide();
        var html = '<img id=\"{$this->avatarId}\" src=\"'+img_url+'\"/>';
        $('#{$this->cropContainer}').show();
        $('#{$this->cropContainer}_area').html(html);
        $('#{$this->avatarPreview}').attr('src', img_url);
        
        crop = {
            x1: 0,
            y1: 0,
            x2: imgWidth,
            y2: imgHeight,
            avatar_url: obj.avatar_url
        };
        $('img#{$this->avatarId}').imgAreaSelect({
            handles: true,
            aspectRatio: '{$this->aspectRatio}',
            x1: crop.x1, y1: crop.y1, x2: crop.x2, y2: crop.y2,
            onSelectEnd: preview
        });
      }
});
$('#{$this->btnApply}').click(function(e){
    e.preventDefault();
    var data = {
        crop: crop,
        data: {$this->data}
    };
    $.post('".Yii::app()->createUrl('users/default/avatarcrop')."', data, function(response){
        console.log(response);
    }, 'json');
    $('img#{$this->avatarId}').imgAreaSelect({remove: true});
    $('#{$this->btnId}').show();
    $('#{$this->btnApply}').hide();
    $('#{$this->cropContainer}_area').hide().html('');
});
");
    }
}