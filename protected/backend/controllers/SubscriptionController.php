<?php

class SubscriptionController extends MyController {

    public function actionDownload() {
        Yii::import("ext.ECSVExport");
        $model = Subscription::model()->findAll();
        $csv = new ECSVExport($model);
        $content = $csv->toCSV();                   
        Yii::app()->getRequest()->sendFile("subscription_".date("Ymd_His").".csv", $content, "text/csv", false);
        exit();
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
        $model=new Subscription;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Subscription'])) {
            $model->attributes=$_POST['Subscription'];
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
        if(isset($_POST['Subscription'])) {
            $model->attributes=$_POST['Subscription'];
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
        /*$dataProvider=new CActiveDataProvider('Subscription');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));*/
        $this->actionAdmin();
    }

    /**
    * Manages all models.
    */
    public function actionAdmin() {
        $model=new Subscription('search');
        
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Subscription']))
            $model->attributes=$_GET['Subscription'];

        $this->render('admin',array(
            'model'=>$model,
        ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Subscription the loaded model
    * @throws CHttpException
    */
    public function loadInternModel($id) {
        $model=Subscription::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param Subscription $model the model to be validated
    */
    protected function performAjaxValidation($model) {
        if(isset($_POST['ajax']) && $_POST['ajax']==='subscription-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}