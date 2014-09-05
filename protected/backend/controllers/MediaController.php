<?php

class MediaController extends MyController {

    /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
    public function actionView($id) {
        $this->render('view',array(
            'model'=>$this->loadInternModel($id),
        ));
    }

    /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
    public function actionCreate() {
        $model=new Media;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Media'])) {
            $_POST['Media']['upload_by'] = Yii::app()->user->id;
            $_POST['Media']['upload_datetime'] = date("Y-m-d H:i:s");
            
            $model->attributes=$_POST['Media'];
            $image = CUploadedFile::getInstance($model, 'filename');
            if (is_object($image) && get_class($image)==='CUploadedFile') 
                $model->filename = $image;
            else if ($_POST['Media']['youtube_id'] != "") {
                $model->filename = $_POST['Media']['youtube_id'];
            }
            
            if($model->save()) {
                if (is_object($model->filename)) {
                    $path = "uploads/media/".date("Y")."/".date("m")."/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.$model->media_id."_".strtolower(CString::url_title($model->filename->name, "_"));
                    $model->filename->saveAs($newfile);
                    $model->filename = $newfile;
                    
                    // create thumb_file
                    $mime_type = Media::getMimeType($newfile);
                    if ($mime_type == "image") {
                        if (is_object($model->thumb_file)) {
                            $newfile = $path.$model->media_id."_".strtolower(CString::url_title($model->thumb_file->name, "_"));
                            $model->thumb_file->saveAs($newfile);
                            $model->thumb_file = $newfile;
                        } else {
                            $t = explode(".", $model->filename);
                            unset($t[count($t)-1]);
                            $thumb_file = implode("", $t)."_thumb.jpg";
                            $image = new EasyImage($newfile);
                            $image->resize(Yii::app()->params['media']['thumb_width'], Yii::app()->params['media']['thumb_width']);
                            $image->save($thumb_file);
                            $model->thumb_file = $thumb_file;
                        }
                    }
                    $model->mime_type = $mime_type;
                    $model->save();
                }
                $this->redirect(array('view','id'=>$model->media_id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
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
        if(isset($_POST['Media'])) {
            $old_thumb = $model->thumb_file;
            $old_file = $model->filename;
            $model->attributes=$_POST['Media'];
            
            $image = CUploadedFile::getInstance($model, 'filename');
            if (is_object($image) && get_class($image)==='CUploadedFile')
                $model->filename = $image;
            else
                $model->filename = $old_file;
            
            $thumb = CUploadedFile::getInstance($model, 'thumb_file');
            if (is_object($image) && get_class($image)==='CUploadedFile')
                $model->thumb_file = $thumb;
            else
                $model->thumb_file = $old_thumb;
            
            if($model->save()) {
                if (is_object($model->filename)) {
                    $path = "uploads/media/".date("Y")."/".date("m")."/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.$model->media_id."_".strtolower(CString::url_title($model->filename->name, "_"));
                    $model->filename->saveAs($newfile);
                    $model->filename = $newfile;
                }
                
                // create thumb_file
                $mime_type = Media::getMimeType($model->filename);
                if ($mime_type == "image") {
                    if (is_object($model->thumb_file)) {
                        $newfile = $path.$model->media_id."_".strtolower(CString::url_title($model->thumb_file->name, "_"));
                        $model->thumb_file->saveAs($newfile);
                        $model->thumb_file = $newfile;
                    } else if (empty($model->thumb_file)) {
                        $t = explode(".", $model->filename);
                        unset($t[count($t)-1]);
                        $thumb_file = implode("", $t)."_thumb.jpg";
                        $image = new EasyImage($model->filename);
                        $image->resize(Yii::app()->params['media']['thumb_width'], Yii::app()->params['media']['thumb_width']);
                        $image->save($thumb_file);
                        $model->thumb_file = $thumb_file;
                    }
                }
                    
                $model->save();
                
                $this->redirect(array('view','id'=>$model->media_id));
            }
        }

        $this->render('update',array(
            'model'=>$model,
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
            if (file_exists($model->thumb_file)) { @unlink($model->thumb_file); }
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
        /*$dataProvider=new CActiveDataProvider('Media');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new Media('search');
        
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Media']))
            $model->attributes=$_GET['Media'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Media the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=Media::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param Media $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='media-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}