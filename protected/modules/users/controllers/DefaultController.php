<?php

class DefaultController extends MyController {

    public function actionAvatarCrop() {
        $result = array(
            'success' => false,
            'message' => ""
        );
        if (isset($_POST['data']) && isset($_POST['crop'])) {
            $crop = $_POST['crop'];
            $data = $_POST['data']['UploadAvatarForm'];
            $image = $crop['avatar_url'];
            
            if (file_exists($image)) {
                $model = Users::model()->findByPk((int) $data['user_id']);
                if ($model != null) {
                    $newFile = "uploads/avatar/".$model->id."_avatar.jpg";
                    //saving the image into memory (for manipulation with GD Library)
                    $myImage = imagecreatefromjpeg($image);
                    
                    $avatarWidth = Yii::app()->params['user']['width'];
                    $avatarHeight = Yii::app()->params['user']['height'];
                    
                    $thumb = imagecreatetruecolor($avatarWidth, $avatarHeight);
                    imagecopyresampled($thumb, $myImage, 0, 0, $crop['x1'], $crop['y1'], $avatarWidth, $avatarHeight, $crop['x2']-$crop['x1'], $crop['y2']-$crop['y1']);
                    imagejpeg($thumb, $newFile);
                    $model->avatar = $newFile;
                    $model->save(false);
                    
                    @unlink($image);
                    $result['success'] = true;
                }
            } else {
                $result['message'] = "File '{$image}' not found";
            }
        }
        
        echo json_encode($result);
    }
    
    public function actionUploadAvatar() {
        $result = array(
            'success' => false,
            'message' => ""
        );
        if (isset($_FILES) && isset($_GET['UploadAvatarForm'])) {
            $model = new UploadAvatarForm();
            $model->attributes = $_POST['UploadAvatarForm'];
            $model->user_id = $_GET['UploadAvatarForm']['user_id'];
            
            // check apakah user id ada didalam database
            $um = Users::model()->findByPk($_GET['UploadAvatarForm']['user_id']);
            if ($um == null) {
                $result['success'] = false;
                $result['message'] = "User ID not found";
                echo CJSON::encode($result);
                Yii::app()->end();
            }
            
            $file = CUploadedFile::getInstance($model, 'fileData');
            if (is_object($file) && get_class($file)==='CUploadedFile') $model->fileData = $file;
            
            if ($model->validate()) {
                if (is_object($model->fileData)) {
                    $path = "uploads/avatar";
                    @mkdir($path, 755, true);
                    $filename = $path."/".$um->id."_".  Utilities::url_title($model->fileData->name, "dash", true);
                    $model->fileData->saveAs($filename);
                    
                    //$um->avatar = $filename;
                    //$um->save();
                    
                    if (Yii::app()->user->id != $um->id) {
                        $text = Yii::app()->user->getState('username')." upload avatar untuk user ".$um->username;
                    } else {
                        $text = "User upload avatar";
                    }
                    UsersActivitiesLog::saveLog($text, CJSON::encode($_FILES));
                } else {
                    $result['message'] = "Failed filedata";
                }
                $prop = getimagesize($filename);
                $result['success'] = true;
                $result['message'] = "Success upload image";
                $result['avatar_url'] = $filename;
                $result['image'] = array(
                    'width' => $prop[0],
                    'height' => $prop[1]
                );
            } else {
                $result['message'] = Utilities::errorToString($model->getErrors());
            }
        } else {
            $result['message'] = $_FILES;
        }
        echo CJSON::encode($result);
    }
    
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
        $model=new Users;
        $model->setScenario('create');
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Users'])) {
            $_POST['Users']['create_datetime'] = date('Y-m-d H:i:s');
            $_POST['Users']['create_by'] = Yii::app()->user->id;
            
            $model->attributes=$_POST['Users'];
            if($model->save()) {
                $model->password = CPasswordHelper::hashPassword($model->password);
                $model->repeat_password = $model->password;
                $model->save();
                $this->redirect(array('view','id'=>$model->id));
            }
        }
        $model->password = "";
        $model->repeat_password = "";
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
        $model->setScenario('update');
        
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_POST['Users'])) {
            $changePass = true;
            if (empty($_POST['Users']['password']) && empty($_POST['Users']['repeat_password'])) {
                $_POST['Users']['password'] = $model->password;
                $_POST['Users']['repeat_password'] = $model->password;
                $changePass = false;
            }
            $model->attributes=$_POST['Users'];
            if($model->save()) {
                if ($changePass) {
                    $model->password = CPasswordHelper::hashPassword($model->password);
                    $model->repeat_password = $model->password;
                    $model->save();
                }
                if (!$model->hasErrors()) 
                    $this->redirect(array('view','id'=>$model->id));
            }
        }
        
        $model->password = "";
        $model->repeat_password = "";
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
        /*$dataProvider=new CActiveDataProvider('Users');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new Users('search');
        
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Users']))
            $model->attributes=$_GET['Users'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Users the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=Users::model()->findByPk($id);
        if($model===null) throw new CHttpException(404,'The requested page does not exist.');
        if($model->user_group == "system") throw new CHttpException(404,'The requested page does not exist.');
        
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param Users $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}