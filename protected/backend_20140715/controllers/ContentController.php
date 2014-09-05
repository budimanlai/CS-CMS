<?php

class ContentController extends MyController {

    public $category;
    
    public function init() {
        parent::init();
        $this->category = Yii::app()->request->getParam('category', null);
    }
    
    public function actionMediaupload() {
        Yii::import("ext.jquery_upload.*");
        
        $obj = new OPGUploadHandler(array(
            'script_url' => Yii::app()->createUrl('content/mediaupload'),
            'upload_dir' => "uploads/content/",
            'upload_url' => Yii::app()->request->baseUrl."/uploads/content/"
        ));
        $obj->onHandleFormData(function($file, $index){
            $file->title = @$_REQUEST['title'][$index];
            $file->content_id = @$_REQUEST['content_id'];
        });
        
        $obj->onHandleFileUpload(function($file, $uploaded_file, $name, $size, $type, $error, $index, $content_range){
            if (empty($file->error)) {
                $model = Content::model()->findByPk($file->content_id);
                if ($model != null) {
                    $cm = new ContentMedia();
                    $cm->content_id = $file->content_id;
                    $cm->title = $file->title;
                    $cm->filename = "uploads/content/".$name;
                    $cm->upload_by = Yii::app()->user->id;
                    $cm->upload_datetime = date('Y-m-d H:i:s');
                    $cm->save();
                }
            }
        });
        
        $obj->onDelete(function($response){
            $path = "uploads/log.txt";
            $fo = fopen($path, "w");
            fwrite($fo, print_r($_GET, true));
            fwrite($fo, print_r($_POST, true));
            fclose($fo);
            
            $content_id = Yii::app()->request->getParam('content_id');
            $model = ContentMedia::model()->find($content_id);
            if ($model != null) {
                foreach ($response as $name => $deleted) {
                    if ($deleted) {
                        $mm = ContentMedia::model()->find('content_id = :ID AND filename LIKE :NAME', array(
                            ':ID' => $content_id,
                            ':NAME' => "%{$name}"
                        ));
                        if ($mm != null) {
                            $mm->delete();
                        }
                    }
                }
            } 
        });
        $obj->init();
    }
    
    public function actionDeletemedia() {
        $content_id = Yii::app()->request->getParam('content_id', null);
        $media_id = Yii::app()->request->getParam('media_id', null);
        
        if ($content_id != null && $media_id != null) {
            $model = ContentMedia::model()->find('media_id = :ID AND content_id = :ID2', array(
                ':ID' => $media_id,
                ':ID2' => $content_id,
            ));
            if ($model != null) {
                if (!empty($model->filename) && file_exists($model->filename)) {
                    @unlink($model->filename);
                }
                $model->delete();
            }
        } else {
            echo CJSON::encode(array(
                'success' => false,
                'msg' => 'Invalid Paramenter'
            ));
        }
    }
    
    /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
    public function actionView($id) {
        $model = $this->loadInternModel($id);
        $this->category = $model->mContentGroup->seo_url;
        $this->render('view',array(
            'model'=>$model,
            'category' => $this->category
        ));
    }

    /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
    public function actionCreate() {
        $model=new Content;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Content'])) {
            if (empty($_POST['Content']['seo_url'])) {
                $_POST['Content']['seo_url'] = strtolower(CString::url_title($_POST['Content']['title']));
            } else {
                $_POST['Content']['seo_url'] = strtolower(CString::url_title($_POST['Content']['seo_url']));
            }
            
            $_POST['Content']['create_by'] = Yii::app()->user->id;
            $_POST['Content']['create_datetime'] = date("Y-m-d H:i:s");
            
            $model->attributes=$_POST['Content'];
            
            $image = CUploadedFile::getInstance($model, 'thumb_image');
            if (is_object($image) && get_class($image)==='CUploadedFile') $model->thumb_image = $image;
            
            if($model->save()) {
                
                if (is_object($model->thumb_image)) {
                    
                    $path = "uploads/images/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.strtolower($model->id."_".CString::url_title($model->thumb_image->name, "_"));
                    $model->thumb_image->saveAs($newfile."_temp");
                    
                    $image = new EasyImage($newfile."_temp");
                    $image->resize(Yii::app()->params['content']['width'], Yii::app()->params['content']['height']);
                    $image->save($newfile);

                    $model->thumb_image = $newfile;
                    $model->save();
                    
                    // delete temp file
                    if (file_exists($newfile."_temp")) unlink($newfile."_temp");
                }
                
                $this->redirect(array('view','id'=>$model->id));
            }
        } else {
            if (!empty($this->category)) {
                $gg = ContentGroup::model()->find('seo_url = :SLUG', array(
                    ':SLUG' => $this->category
                ));
                if ($gg != null) {
                    $model->group_id = $gg->id;
                }
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'category' => $this->category
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
        if(isset($_POST['Content'])) {
            $old_image = $model->thumb_image;
            
            if (empty($_POST['Content']['seo_url'])) {
                $_POST['Content']['seo_url'] = strtolower(CString::url_title($_POST['Content']['title']));
            } else {
                $_POST['Content']['seo_url'] = strtolower(CString::url_title($_POST['Content']['seo_url']));
            }
            
            $model->attributes=$_POST['Content'];
            
            $image = CUploadedFile::getInstance($model, 'thumb_image');
            if (is_object($image) && get_class($image)==='CUploadedFile') 
                $model->thumb_image = $image;
            else
                $model->thumb_image = $old_image;
            
            if($model->save()) {
                if (is_object($model->thumb_image)) {
                    
                    // delete old file
                    if (file_exists($old_image)) unlink($old_image);
                    
                    $path = "uploads/images/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.strtolower($model->id."_".CString::url_title($model->thumb_image->name, "_"));
                    $model->thumb_image->saveAs($newfile."_temp");
                    
                    $image = new EasyImage($newfile."_temp");
                    $image->resize(Yii::app()->params['content']['width'], Yii::app()->params['content']['height']);
                    $image->save($newfile);

                    $model->thumb_image = $newfile;
                    $model->save();
                    
                    // delete temp file
                    if (file_exists($newfile."_temp")) unlink($newfile."_temp");
                }
                $this->redirect(array('view','id'=>$model->id));
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
            $this->loadInternModel($id)->delete();

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
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new Content('search');
        $model->unsetAttributes();  // clear any default values
        
        if(isset($_GET['Content']))
            $model->attributes=$_GET['Content'];
        
        $slug = "";
        if (!empty($_GET['Content']['group_id'])) {
            $slug = $_GET['Content']['group_id'];
        } else if (!empty($this->category)) {
            $slug = $this->category;
        }
        if (!empty($slug)) {
            $gg = ContentGroup::model()->find('seo_url = :SLUG', array(
                ':SLUG' => $slug
            ));
            if ($gg != null) {
                $model->group_id = $gg->id;
            }
        }
        $this->render('admin',array(
            'model'=>$model,
            'category' => $this->category
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Content the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=Content::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param Content $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='content-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}