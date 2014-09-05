<?php

class SettingController extends MyController {
    public function actionIndex() {
        if (isset($_POST['Setting'])) {
            foreach($_POST['Setting'] as $key => $value) {
                $model = Settings::model()->findByPk($key);
                if ($model == null) {
                    $model = new Settings();
                    $model->name = $key;
                }
                $model->value = $value;
                $model->save();
            }
        }
        $this->render('index');
    }
}