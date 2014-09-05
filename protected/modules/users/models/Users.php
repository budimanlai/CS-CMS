<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $user_group
 * @property string $create_datetime
 * @property integer $create_by
 * @property string $create_ip
 * @property string $last_access_ip
 * @property string $last_access_datetime
 * @property string $status
 * @property string $notes
 * @property string $avatar
 * @property string $path_code
 * @property integer $failed_login
 * @property string $token_reset
 *
 * The followings are the available model relations:
 * @property UsersGroup $userGroup
 * @property Branch $branch
 * @property UsersActivitiesLog[] $usersActivitiesLogs
 * @property UsersLogin[] $usersLogins
 */
class Users extends CActiveRecord
{
    public $repeat_password;
    
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
            // create scenario
            array('password, repeat_password', 'required', 'on' => 'create'),
            array('password, repeat_password', 'length', 'min' => 6, 'on' => 'create'),
            array('repeat_password', 'compare', 'compareAttribute' => 'password', 'on' => 'create'),
            
            // update scenario
            array('password, repeat_password', 'length', 'min' => 6, 'on' => 'update'),
            array('repeat_password', 'compare', 'compareAttribute' => 'password', 'on' => 'update'),
            
            // all scenario
            array('username, email, create_datetime', 'required'),
            array('create_by, failed_login', 'numerical', 'integerOnly'=>true),
            array('username', 'length', 'max'=>25),
            array('email', 'length', 'max'=>50),
            array('email, username', 'unique'),
            array('email', 'email'),
            array('token_reset', 'length', 'max'=>32),
            array('password', 'length', 'max'=>60),
            array('user_group, create_ip, last_access_ip, status, path_code', 'length', 'max'=>15),
            array('avatar', 'length', 'max'=>256),
            array('last_access_datetime, notes', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, username, email, password, user_group, create_datetime, create_by, create_ip, last_access_ip, last_access_datetime, status, notes, avatar, path_code, failed_login', 'safe', 'on'=>'search'),
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
            'userGroup' => array(self::BELONGS_TO, 'UserGroups', 'user_group'),
            'usersActivitiesLogs' => array(self::HAS_MANY, 'UsersActivitiesLog', 'user_id'),
            'usersLogins' => array(self::HAS_MANY, 'UsersLogin', 'user_id'),
            'mCreateBy' => array(self::BELONGS_TO, 'Users', 'create_by'),
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
            'user_group' => 'User Group',
            'create_datetime' => 'Create Datetime',
            'create_by' => 'Create By',
            'create_ip' => 'Create Ip',
            'last_access_ip' => 'Last Access Ip',
            'last_access_datetime' => 'Last Access Datetime',
            'status' => 'Status',
            'notes' => 'Notes',
            'avatar' => 'Avatar',
            'path_code' => 'Path Code',
            'failed_login' => 'Failed Login',
            'token_reset' => 'Token Reset',
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
        $criteria->with = array('userGroup', 'mCreateBy');
        
        $criteria->compare('t.id',$this->id,true);
        $criteria->compare('t.username',$this->username,true);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('user_group',$this->user_group,true);
        $criteria->compare('create_datetime',$this->create_datetime,true);
        $criteria->compare('t.create_by',$this->create_by);
        $criteria->compare('create_ip',$this->create_ip,true);
        $criteria->compare('last_access_ip',$this->last_access_ip,true);
        $criteria->compare('last_access_datetime',$this->last_access_datetime,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('notes',$this->notes,true);
        $criteria->compare('avatar',$this->avatar,true);
        $criteria->compare('path_code',$this->path_code,true);
        $criteria->compare('failed_login',$this->failed_login);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=> array(
                'attributes'=>array(
                    '*',
                    'mCreateBy.username'=> array(
                        'asc'=>'mCreateBy.username',
                        'desc'=>'mCreateBy.username desc',
                    )
                )
            ),
        ));
    }
}