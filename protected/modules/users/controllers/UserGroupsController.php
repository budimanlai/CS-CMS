<?php

class UserGroupsController extends MyController {

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
        $model=new UserGroups;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['UserGroups'])) {
            $model->attributes=$_POST['UserGroups'];
            if($model->save()) {
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
        if(isset($_POST['UserGroups'])) {
            $model->attributes=$_POST['UserGroups'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
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
            if ($model->id == 'system' || $model->id == "administrator") {
                throw new CHttpException(400,"Invalid request. Can't delete Administrator or System user group");
            } else if ($model->mUserCount != 0) {
                throw new CHttpException(400,"Invalid request. Can't delete non empty group.");
            } else {
                $model->delete();
            }

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
        /*$dataProvider=new CActiveDataProvider('UserGroups');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new UserGroups('search');
        
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['UserGroups']))
            $model->attributes=$_GET['UserGroups'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return UserGroups the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=UserGroups::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param UserGroups $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='user-groups-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}