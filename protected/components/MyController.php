<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

class MyController extends Controller{
    
    public $pageHeader;
    
    public function init() {
        parent::init();
        $this->layout = "//layouts/column1";
    }
    public function render($view, $data = NULL, $return = false) {
        if (Yii::app()->request->isAjaxRequest) {
            if ($view == "update" || $view == "create") {
                $view = "_form";
            }
            Yii::app()->clientScript->scriptMap = Yii::app()->params['scriptMap'];
            $this->renderPartial($view, $data, false, true);
        } else {
            parent::render($view, $data, $return);
        }
    }
    
    /**
    * @return array action filters
    */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
//            array(
//                'application.filters.cshtml.CSCompressHtmlFilter',
//                'actions' => '*'
//            ),
        );
    }
    
    public function accessRules(){
        return CAccessPage::AccessRules();
    }
}