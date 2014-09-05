<?php

class DownloadController extends MyController {

    public $group;
    public $group_id;
    
    public function init() {
        parent::init();
        $this->group = Yii::app()->request->getParam('group', 'news');
        $dm = DownloadGroup::model()->find('seo_url = :SEO', array(
            ':SEO' => $this->group
        ));
        if ($dm != null) {
            $this->group_id = $dm->group_id;
        } else {
            throw new CHttpException(400,'Invalid request. Download group invalid');
        }
    }
    
    /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
    public function actionView($id) {
        $this->render('view',array(
            'model'=>$this->loadInternModel($id),
            'group' => $this->group
        ));
    }

    /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
    public function actionCreate() {
        $model=new Download;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Download'])) {
            $_POST['Download']['upload_by'] = Yii::app()->user->id;
            $_POST['Download']['group_id'] = $this->group_id;
            
            $model->attributes=$_POST['Download'];
            
            $file = CUploadedFile::getInstance($model, 'filename');
            if (is_object($file) && get_class($file)==='CUploadedFile') $model->filename = $file;
            
            if($model->save()) {
                if (is_object($model->filename)) {
                    $path = "uploads/content/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.strtolower($model->media_id."_".CString::url_title($model->filename->name, "_"));
                    $model->filename->saveAs($newfile);
                    
                    $model->filename = $newfile;
                    $model->save();
                }
                
                $this->redirect(array('view','id'=>$model->media_id, 'group' => $this->group));
            }
        } else {
            $model->upload_datetime = date('Y-m-d');
        }

        $this->render('create',array(
            'model'=>$model,
            'group' => $this->group
        ));
    }

    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id the ID of the model to be updated
    */
    public function actionUpdate($id) {
        $model=$this->loadInternModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['Download'])) {
            $old_file = $model->filename;
            
            $model->attributes=$_POST['Download'];
            
            $file = CUploadedFile::getInstance($model, 'filename');
            if (is_object($file) && get_class($file)==='CUploadedFile') 
                $model->filename = $file;
            else
                $model->filename = $old_file;
            
            if($model->save()) {
                if (is_object($model->filename)) {
                    $path = "uploads/content/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.strtolower($model->media_id."_".CString::url_title($model->filename->name, "_"));
                    $model->filename->saveAs($newfile);
                    
                    $model->filename = $newfile;
                    $model->save();
                    
                    if (file_exists($old_file)) {
                        @unlink($old_file);
                    }
                }
                $this->redirect(array('view','id'=>$model->media_id, 'group'=>$this->group));
            }
        }

        $model->upload_datetime = date('Y-m-d', strtotime($model->upload_datetime));
        $this->render('update',array(
            'model'=>$model,
            'group' => $this->group
        ));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'admin' page.
    * @param integer $id the ID of the model to be deleted
    */
    public function actionDelete($id) {
        if(Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadInternModel($id);
            if (file_exists($model->filename)) { @unlink($model->filename); }
            $model->delete();
            
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
    * Lists all models.
    */
    public function actionIndex() {
        /*$dataProvider=new CActiveDataProvider('Download');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new Download('search');
        
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Download']))
            $model->attributes=$_GET['Download'];

        $model->group_id = $this->group_id;
        $this->render('admin',array(
            'model'=>$model,
            'group' => $this->group
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Download the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=Download::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param Download $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='download-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}