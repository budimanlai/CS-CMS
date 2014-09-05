<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

class Controller extends CController{

    public $menu=array();
    public $breadcrumbs=array();
    public $layout = "//layouts/column2";
    public $tabTitle = "Home";
    public $leftContent = "";
    public $futureImage = null;
    
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
}