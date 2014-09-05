<?php

/**
 * This is the model class for table "Users_activities_log".
 *
 * The followings are the available columns in table 'Users_activities_log':
 * @property string $id
 * @property string $sessions
 * @property string $user_id
 * @property string $description
 * @property string $url
 * @property string $post_data
 * @property string $additional_data
 * @property string $activities_datetime
 * @property string $from_ip
 * @property string $user_agent
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class UsersActivitiesLog extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UsersActivitiesLog the static model class
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
        return 'users_activities_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, activities_datetime', 'required'),
            array('sessions', 'length', 'max'=>32),
            array('user_id', 'length', 'max'=>11),
            array('description', 'length', 'max'=>1024),
            array('url', 'length', 'max'=>4048),
            array('from_ip', 'length', 'max'=>15),
            array('user_agent', 'length', 'max'=>256),
            array('post_data, additional_data', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sessions, user_id, description, url, post_data, additional_data, activities_datetime, from_ip, user_agent', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'sessions' => 'Sessions',
            'user_id' => 'User',
            'description' => 'Description',
            'url' => 'Url',
            'post_data' => 'Post Data',
            'additional_data' => 'Additional Data',
            'activities_datetime' => 'Activities Datetime',
            'from_ip' => 'From Ip',
            'user_agent' => 'User Agent',
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
        $criteria->compare('sessions',$this->sessions,true);
        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('post_data',$this->post_data,true);
        $criteria->compare('additional_data',$this->additional_data,true);
        $criteria->compare('activities_datetime',$this->activities_datetime,true);
        $criteria->compare('from_ip',$this->from_ip,true);
        $criteria->compare('user_agent',$this->user_agent,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Berfungsi untuk menyimpan aktifitas user kedalam log database, sehingga admin/system bisa mengetahui
     * aktifitas dari user.
     * <pre>
     * UsersActivitiesLog::saveLog("User mengganti data produk");
     * </pre>
     * 
     * @param String $text
     * @param Any $data
     */
    public static function saveLog($text, $data = "", $savePost = true) {
        $model = new UsersActivitiesLog();
        $model->sessions = Yii::app()->session->getSessionID();
        $model->user_id = Yii::app()->user->id;
        $model->description = $text;
        $model->url = Utilities::getFullURL();
        if ($savePost)
            $model->post_data = isset($_POST) && count($_POST) >= 1 ? CJSON::encode($_POST) : "";
        else
            $model->post_data = "";
        $model->additional_data = is_array($data) ? CJSON::encode($data) : $data;
        $model->activities_datetime = date('Y-m-d H:i:s');
        $model->from_ip = Yii::app()->request->getUserHostAddress();
        $model->user_agent = Yii::app()->request->getUserAgent();
        $model->save();
    }
}