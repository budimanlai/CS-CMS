<?php

Yii::import('ext.jquery_upload.*');

class OPGUploadHandler extends UploadHandler {
    
    private $mHandleFormData;
    private $mHandleFileUpload;
    private $mSetAdditionalFileProperties;
    private $mDelete;
    
    public function onHandleFormData($func) { $this->mHandleFormData = $func; }
    public function onHandleFileUpload($func) { $this->mHandleFileUpload = $func; }
    public function onSetAdditionalFileProperties($func) { $this->mSetAdditionalFileProperties = $func; }
    public function onDelete($func) { $this->mDelete = $func; }
    
    public function init() {
        parent::initialize();
    }
    
    protected function handle_form_data($file, $index) {
    	if (is_callable($this->mHandleFormData)) {
            call_user_func_array($this->mHandleFormData, array($file, $index));
        }
    }
    
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        if (is_callable($this->mHandleFileUpload)) {
            call_user_func_array($this->mHandleFileUpload, array($file, $uploaded_file, $name, $size, $type, $error, $index, $content_range));
        }
        return $file;
    }
    
    protected function set_additional_file_properties($file) {
        parent::set_additional_file_properties($file);
        if (is_callable($this->mSetAdditionalFileProperties)) {
            call_user_func_array($this->mSetAdditionalFileProperties, array($file));
        }
    }
    
    public function delete($print_response = true) {
        $response = parent::delete(false);
        if (is_callable($this->mDelete)) {
            call_user_func_array($this->mDelete, array($response));
        }
        return $this->generate_response($response, $print_response);
    }
}