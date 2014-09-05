<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $user_group
 * @property string $create_datetime
 * @property integer $create_by
 * @property string $create_ip
 * @property string $last_access_ip
 * @property string $last_access_datetime
 * @property string $status
 * @property string $notes
 * @property Strubg $avatar
 * @property String $path_code
 * @property integer $failed_login
 */
class Users extends CActiveRecord
{
    public $password_repeat;
    public $verifyCode;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Users the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, email', 'required'),
            array('username, email', 'unique'),
            array('username', 'length', 'max'=>25),
            array('email', 'length', 'max'=>50),
            array('email', 'email'),
            
            array('password, password_repeat','required','on' => 'update_password, insert'),
            array('password, salt, password_repeat','length', 'min' => 6, 'max' => 32),
            array('password','compare','compareAttribute' => 'password_repeat', 'on' => 'update_password, insert'),

            array('create_by', 'numerical', 'integerOnly'=>true),
            array('avatar', 'file', 'types' => 'jpg, gif, png, jpeg', 'allowEmpty' => true),
            array('user_group, create_ip, last_access_ip, status, path_code', 'length', 'max'=>15),
            array('create_datetime, last_access_datetime, notes', 'safe'),
            array('verifyCode', 'captcha', 'on' => 'frontend_insert','allowEmpty'=>!CCaptcha::checkRequirements()),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, email, password, salt, user_group, create_datetime, create_by, create_ip, last_access_ip, last_access_datetime, status, notes', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'create_by_user' => array(self::BELONGS_TO, 'Users', 'create_by'),
            'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'salt' => 'Salt',
            'user_group' => 'User Group',
            'create_datetime' => 'Create Datetime',
            'create_by' => 'Create By',
            'create_ip' => 'Create IP',
            'last_access_ip' => 'Last Access IP',
            'last_access_datetime' => 'Last Access Datetime',
            'status' => 'Status',
            'notes' => 'Notes',
            'avatar' => 'Avatar',
            'path_code' => 'Path Code',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id,true);
        $criteria->compare('username',$this->username,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('user_group',$this->user_group,true);
        $criteria->compare('create_datetime',$this->create_datetime,true);
        $criteria->compare('create_by',$this->create_by);
        $criteria->compare('create_ip',$this->create_ip,true);
        $criteria->compare('last_access_ip',$this->last_access_ip,true);
        $criteria->compare('last_access_datetime',$this->last_access_datetime,true);
        
        if ($this->status == "")
            $this->status = "active";
        
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Reset user password
     * @param String $new_password
     * @return Array
     */
    public static function resetPassword($new_password) {
        $salt = md5(date('YmdHis').rand(10,99));
        $password = md5($new_password.$salt);
        return array('salt' => $salt, 'password' => $password);
    }
}