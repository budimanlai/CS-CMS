<?php

class OGPUploadWidget extends CWidget {
    
    public $id;
    public $action;
    public $formData = array();
    private $jsFormData;
    private $inputFormData;
    
    public function init() {
        parent::init();
        if (empty($this->id)) { $this->id = $this->getId(); }
        
        $this->jsFormData = "";
        $this->inputFormData = "";
            
        if (is_array($this->formData)) {
            foreach($this->formData as $key => $row) {
                $this->jsFormData.= $key.": '{$row}',";
                $this->inputFormData.= "<input type=\"hidden\" name=\"{$key}\" value=\"{$row}\"/>";
            }
        }
        $this->jsFormData = "{".$this->jsFormData.Yii::app()->request->csrfTokenName.": '".Yii::app()->request->csrfToken."'}";
        $this->inputFormData.= "<input type=\"hidden\" name=\"".Yii::app()->request->csrfTokenName."\" value=\"".Yii::app()->request->csrfToken."\"/>";
    }
    
    public function run() {
        echo '<!-- The file upload form used as target for the file upload widget -->
    <form id="'.$this->id.'" action="'.$this->action.'" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>';
        
        $this->renderScript();
        $this->templateUpload();
        $this->templateDownload();
    }
    
    private function renderScript() {
        echo '<script type="text/javascript">
$(document).ready(function(){
    $("#'.$this->id.'").fileupload({
        disableImageResize: false,
        url: "'.$this->action.'",
    }).on("fileuploadsubmit", function(e, data){
        data.formData = data.context.find(\':input\').serializeArray();
    });
    $("#'.$this->id.'").bind("fileuploaddestroy", function (e, data) {
        data.data = '.$this->jsFormData.';
    });
});
</script>';
    }
    
    private function templateUpload() {
        echo '<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <label class="title">
                <span>Title:</span><br>
                <input name="title[]" class="form-control" style="width:300px;" required maxlength="256">
            </label>
            <label class="title">
                <span>Description:</span><br>
                <input name="description[]" class="form-control" style="width:300px;" required maxlength="500">'.$this->inputFormData.'
            </label>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>';
    }
    
    public function templateDownload() {
        echo '<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                <p class="title"><strong>{%=file.title||\'\'%}</strong></p>
                <p>{%=file.description||\'\'%}</p>
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?\'data-gallery\':\'\'%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields=\'{"withCredentials":true}\'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>';
    }
}