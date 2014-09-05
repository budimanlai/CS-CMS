<?php

class SliderController extends MyController {

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
        $model=new Slider;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Slider'])) {
            $model->attributes=$_POST['Slider'];
            
            $image = CUploadedFile::getInstance($model, 'image_file');
            if (is_object($image) && get_class($image)==='CUploadedFile') $model->image_file = $image;
            
            if($model->save()) {
                
                if (is_object($model->image_file)) {
                    $path = "uploads/images/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.strtolower(CString::url_title($model->image_file->name, "_"));
                    $model->image_file->saveAs($newfile);
                    $model->image_file = $newfile;
                    $model->save();
                }
                
                $this->redirect(array('view','id'=>$model->id));
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
        if(isset($_POST['Slider'])) {
            $old_image = $model->image_file;
            
            $model->attributes=$_POST['Slider'];
            
            $image = CUploadedFile::getInstance($model, 'image_file');
            if (is_object($image) && get_class($image)==='CUploadedFile') 
                $model->image_file = $image;
            else
                $model->image_file = $old_image;
            
            if($model->save()) {
                if (is_object($model->image_file)) {
                    
                    // delete old file
                    if (file_exists($old_image)) unlink($old_image);
                    
                    $path = "uploads/images/";
                    if (!is_dir($path)) { mkdir($path, 0777, true); }
                    $newfile = $path.strtolower(CString::url_title($model->image_file->name, "_"));
                    $model->image_file->saveAs($newfile);
                    $model->image_file = $newfile;
                    $model->save();
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
            $model = $this->loadInternModel($id);
            if (!empty($model->image_file) && file_exists($model->image_file)) {
                unlink($model->image_file);
            }
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
        /*$dataProvider=new CActiveDataProvider('Slider');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new Slider('search');
        
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Slider']))
            $model->attributes=$_GET['Slider'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Slider the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=Slider::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param Slider $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='slider-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}