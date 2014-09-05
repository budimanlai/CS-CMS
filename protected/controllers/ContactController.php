<?php

class ContactController extends Controller {
    
    public function actionIndex() {
        $success = false;
        $message = "";
        $error = "";
        
        if (Yii::app()->request->isAjaxRequest && isset($_POST['Contact'])) {
            $model = new Contactus();
            $_POST['Contact']['create_datetime'] = date('Y-m-d H:i:s');
            $_POST['Contact']['from_ip'] = Yii::app()->request->userHostAddress;
            $_POST['Contact']['user_agent'] = Yii::app()->request->userAgent;
            
            $model->attributes=$_POST['Contact'];
            
            if ($model->save()) {
                $success = true;
                $message = Settings::getValue("tentang_kami_thanks", "Terima kasih sudah mengirim pesan pada kami");
            } else {
                $success = false;
                $message = "Maaf ada kesalahan pengisian form kontak kami. Silahkan coba lagi";
                $error = $model->getErrors();
            }
            
            echo CJSON::encode(array(
                'success' => $success,
                'message' => $message,
                'post' => $_POST,
                'error' => $error
            ));
            Yii::app()->end();
        }
        $this->layout = "//layouts/column0";
        $model = new Contactus();
        
        $this->render('index', array(
            'model' => $model
        ));
    }
}