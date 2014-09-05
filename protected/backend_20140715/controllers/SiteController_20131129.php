<?php

class SiteController extends Controller{
    //public $layout = "//layouts/column1";
    public function init(){
        // register class paths for extension captcha extended
        Yii::$classMap = array_merge( Yii::$classMap, array(
            'CaptchaExtendedAction' => Yii::getPathOfAlias('ext.captchaExtended').DIRECTORY_SEPARATOR.'CaptchaExtendedAction.php',
            'CaptchaExtendedValidator' => Yii::getPathOfAlias('ext.captchaExtended').DIRECTORY_SEPARATOR.'CaptchaExtendedValidator.php'
        ));
    }
    
    public function actions(){
        return array(
            'captcha'=>array(
                'class'=>'CaptchaExtendedAction',
                // if needed, modify settings
                'mode'=>CaptchaExtendedAction::MODE_WORDS,
            ),
        );
    }
    
    public function actionIndex() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl("site/login"));
        } else {
            $this->render('index');
        }
        
    }
    
    public function actionGetMenu2() {
        Yii::import('application.modules.users.models.AclMenu');
        $menu = AclMenu::getMenu(Yii::app()->user->getState('user_group'));
        echo CJSON::encode($menu);
    }
    
    public function actionGetMenu() {
        Yii::import('application.modules.users.models.AclMenu');
        $menu = AclMenu::getMenu(Yii::app()->user->getState('user_group'));
        echo CJSON::encode($menu);
    }
    
    public function actionError(){
        if($error=Yii::app()->errorHandler->error) {
            //$error['message'] = "Error";
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message']; 
            else 
                echo $error['message']; 
                //$this->render('error', $error);
        }
    }

    public function actionLoginCheck() {
        Yii::import('application.vendor.Security');
        
        //ApiKey::checkApiAllowed();
        
        $isblocked = Security::isBlocked();
        
        if(isset($_POST['username']) && isset($_POST['password'])) {
            $model = new LoginForm();
            if (Settings::getValue("login_capcha") == "N") {
                $model->setScenario("CapchaDisabled");
            } else {
                $model->setScenario("CapchaEnabled");
            }

            if ($isblocked) {
                // fake login
                $model->username = $_POST['username'].rand();
                $model->password = md5(date('YmdHis').rand());
            } else {
                $model->username = $_POST['username'];
                $model->password = $_POST['password'];
                $model->verifyCode = $_POST['verifyCode'];
            }

            if($model->validate() && $model->login()){
                // create session
                UsersLogin::createSession();
                Security::resetBruteForceProtect();
                UsersActivitiesLog::saveLog("User success Login", "", false);
                echo CJSON::encode(array(
                    'success'=>true,
                    'message'=>Yii::app()->user->returnUrl,
                    //'session'=> Yii::app()->session->getSessionID()
                ));
                return; 
            } else {
                if ($isblocked) {
                    $time = Security::getBruteForceWaitTime();
                    $str = "Silahkan tunggu {$time} menit lagi baru bisa login atau silahkan hubungi Administrator anda";
                } else {
                    $str = "";
                    foreach($model->getErrors() as $index => $row) {
                        $str.= $row[0]."<br/>";
                        if ($index == "password")
                            Security::doBruteForceProtect();
                    }
                }
                
                echo CJSON::encode(array(
                    'success'=>false,
                    'message'=>$str
                ));
                return; 
            }
        } else {
            echo CJSON::encode(array(
                'success'=>false,
                'message'=> "Invalid paramenter"
            ));
        }
    }
    
    public function actionLogin() {
        if (Yii::app()->user->isGuest) {
            $this->layout = "login";
            $this->render('login');
        } else {
            $this->redirect(Yii::app()->createUrl("site/index"));
        }
        
    }

    public function actionLogout(){
        UsersActivitiesLog::saveLog("User '".Yii::app()->user->getState('username')."' success Logout");
        UsersLogin::destroySession();
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    
    public function actionLupapassword() {
        ApiKey::checkApiAllowed();
        
        Yii::import("application.modules.Users.models.Users");
        
        $success = false;
        $message = Yii::t('default', 'Invalid paramenter');
        
        if (!empty($_POST['email'])) {
            $model = Users::model()->find('email = :EMAIL', array(
                ':EMAIL' => $_POST['email'],
            ));
            if ($model != null) {
                if ($model->status == "active" && $model->user_group != "system") {
                    $model->token_reset = md5(date("YmdHis").rand(10, 99).rand(10, 99).$_POST['email']);
                    $model->save();
                    
                    $url = Yii::app()->createAbsoluteUrl("site/resetpassword", array('token' => $model->token_reset));
                    $param = array(
                        '{fullname}' => $model->username,
                        '{reset_password_datetime}' => date('Y-m-d H:i:s'),
                        '{reset_password_url}' => '<a href="'.$url.'">'.$url.'</a>'
                    );
                    $add = EmailTemplate::SendMail("lupa_password", $_POST['email'], $param);
                    $success = true;
                    $message = Yii::t('user', 'Kami telah mengirim sebuah email untuk mengubah password baru. Silahkan check email anda.');
                } else {
                    $success = false;
                    $message = Yii::t('user', 'Username atau email tidak aktif. Silahkan hubungi administrator anda.');
                }
            } else {
                $success = false;
                $message = Yii::t('user', 'Username atau email tidak terdaftar');
            }
        }
        
        echo CJSON::encode(array(
            'success'=>$success,
            'message'=>$message
        ));
    }
    
    public function actionResetpassword() {
        if (($token = Yii::app()->request->getParam("token", "")) == "") {
            $this->redirect(Yii::app()->createUrl('site/index'));
        }
        
        Yii::import("application.modules.Users.models.Users");
        
        $model = Users::model()->find('token_reset = :TOKEN', array(
            ':TOKEN' => $token
        ));
        if ($model == null) {
            $this->redirect(Yii::app()->createUrl('site/index'));
        }
        
        if (Yii::app()->request->isAjaxRequest) {
            $password = Yii::app()->request->getParam('password', '');
            $repeat_password = Yii::app()->request->getParam('repeat_password', ''); 
            $success = true;
            $message = "";
            
            if (empty($password) || empty($repeat_password)) {
                $success = false;
                $message = Yii::t('default', 'Password atau ulangi password tidak boleh kosong');
            }
            if ($password != $repeat_password) {
                $success = false;
                $message = Yii::t('default', 'Password atau ulangi password tidak sama');
            }
            
            if ($success) {
                $model->password = CPasswordHelper::hashPassword($password);
                $model->token_reset = "";
                $model->save();
            }
            echo CJSON::encode(array(
                'success' => $success,
                'message' => $message,
            ));
            Yii::app()->end();
        }
        
        $this->layout = "blank";
        $this->render("reset_password", array(
            'token' => $token
        ));
    }
}