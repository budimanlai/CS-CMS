<?php

class MultimediaController extends Controller {
    
    public $layout = "//layouts/column1";
    /**
     * multimedia/photos
     */
    public function actionIndex() {
        $criteria = new CDbCriteria();
        $criteria->condition = '(mime_type = "image" or mime_type = "video") AND status = "active"';
        $criteria->order = "upload_datetime desc";
        
        // Count total records
        $pages = new CPagination(Media::model()->count('(mime_type = "image" or mime_type = "video") AND status = "active"'));

        // Set Page Limit
        $pages->pageSize = 2;

        // Apply page criteria to CDbCriteria
        $pages->applyLimit($criteria);
        $model = Media::model()->findAll($criteria);
        
        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('_index', array(
                'model' => $model,
                'pages' => $pages
            ));
        } else {
            $this->render('index', array(
                'model' => $model,
                'pages' => $pages
            ));
        }
    }
    
    public function actionPhotos() {
        $this->actionIndex();
    }
}