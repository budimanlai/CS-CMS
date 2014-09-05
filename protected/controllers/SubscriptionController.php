<?php

class SubscriptionController extends Controller {
    public function actionIndex() {
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        
        if (!empty($email)) {
            $model = Subscription::model()->find('email = :EMAIL', array(
                ':EMAIL' => $email
            ));
            if ($model == null) {
                $model = new Subscription();
                $model->email = $email;
                $model->subs_datetime = date("Y-m-d H:i:s");
                $model->save();
            }
            
            echo Yii::t('label', 'Terima kasih. Email anda sudah kami daftarkan.');
        } else {
            echo "Invalid Paramenter";
        }
    }
}