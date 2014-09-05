<?php

/**
 * This is the model class for table "users_login".
 *
 * The followings are the available columns in table 'users_login':
 * @property string $sessions
 * @property string $user_id
 * @property string $from_ip
 * @property string $user_agent
 * @property string $datetime
 * @property string $last_access
 * @property string $platform
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UsersLogin extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UsersLogin the static model class
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
        return 'users_login';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, datetime, last_access', 'required'),
            array('sessions', 'length', 'max'=>32),
            array('user_id, platform', 'length', 'max'=>10),
            array('from_ip', 'length', 'max'=>15),
            array('user_agent', 'length', 'max'=>256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sessions, user_id, from_ip, user_agent, datetime, last_access, platform', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'sessions' => 'Sessions',
            'user_id' => 'User',
            'from_ip' => 'From Ip',
            'user_agent' => 'User Agent',
            'datetime' => 'Datetime',
            'last_access' => 'Last Access',
            'platform' => 'Platform',
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

        $criteria->compare('sessions',$this->sessions,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('from_ip',$this->from_ip,true);
        $criteria->compare('user_agent',$this->user_agent,true);
        $criteria->compare('datetime',$this->datetime,true);
        $criteria->compare('last_access',$this->last_access,true);
        $criteria->compare('platform',$this->platform,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Buat session untuk user yang login sehingga administrator tahu siapa saja yang sedang online.
     * Untuk platform defaultnya adalah web yang artinya user login dari web. Platform bisa saja bernilai
     * mobile, android, ios, dsb tergantung asal platform yang digunakan oleh user.
     * <pre>
     * UsersLogin::createSession();
     * or
     * UsersLogin::createSession('desktop');
     * </pre>
     * 
     * @param String $platform
     */
    public static function createSession($platform = "web") {
        $model = UsersLogin::model()->findByAttributes(array('sessions'=>session_id()));
        if ($model == null) $model = new UsersLogin;
        
        $model->sessions = Yii::app()->session->getSessionID();
        $model->user_id = Yii::app()->user->id;
        $model->from_ip = Yii::app()->request->getUserHostAddress();
        $model->user_agent = Yii::app()->request->getUserAgent();
        $model->datetime = date('Y-m-d H:i:s');
        $model->last_access = $model->datetime;
        $model->platform = $platform;
        $model->save();
    }
    
    /**
     * Update last access dari user sehingga administrator tahu aktifitas terakhir dari user.
     * <pre>
     * UsersLogin::refreshSession();
     * </pre>
     */
    public static function refreshSession() {
        $model = UsersLogin::model()->findByAttributes(array('sessions'=>Yii::app()->session->getSessionID()));
        if ($model != null) {
            $model->last_access = date('Y-m-d H:i:s');
            $model->save();
        }
    }
    
    /**
     * Hapus session dari database
     * <pre>
     * UsersLogin::destroySession();
     * </pre>
     */
    public static function destroySession() {
        $model = UsersLogin::model()->findByAttributes(array('sessions'=>Yii::app()->session->getSessionID()));
        if ($model != null) $model->delete();
    }
}