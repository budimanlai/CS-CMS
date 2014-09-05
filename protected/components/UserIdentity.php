<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    public $email;
    
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        Yii::import("application.modules.users.models.Users");
        Yii::import("application.modules.users.models.UsersLogin");
        Yii::import("application.vendor.Security");
        
        $record=Users::model()->findByAttributes(array('username' => $this->username));
        
        if($record===null) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        //} else if(!CPasswordHelper::verifyPassword($this->password, $record->password)) {
        } else if(!Security::checkPassword($this->password, $record->password)) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } else if ($record->status == "active") {
            
            $this->_id=$record->id;
            $this->setState('username', $record->username);
            $this->setState('user_group', $record->user_group);
            $this->errorCode=self::ERROR_NONE;
            
            $record->last_access_ip = Yii::app()->request->getUserHostAddress();
            $record->last_access_datetime = date('Y-m-d H:i:s');
            $record->save();
        } else {
            self::ERROR_UNKNOWN_IDENTITY;
        }
        return !$this->errorCode;
    }
    
    public function getId() {
        return $this->_id;
    }
}