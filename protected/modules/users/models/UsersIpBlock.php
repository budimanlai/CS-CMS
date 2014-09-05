<?php

/**
 * This is the model class for table "users_ip_block".
 *
 * The followings are the available columns in table 'users_ip_block':
 * @property string $ip_address
 * @property string $reason
 * @property string $start_datetime
 * @property string $until_datetime
 */
class UsersIpBlock extends CActiveRecord
{
    /**
     * Nama variable $_SESSION untuk mengetahui berapa kali user gagal login
     * @var String 
     */
    public static $flName = "failed_login";
    
    /**
     * Maksimal gagal login yang diperbolehkan oleh system sebelum fungsi Brute Force di aktifkan
     * 
     * @var Integer 
     */
    public static $login_failed_max_count = 3;
    
    /**
     * Apabila user gagal login lebih dari batas maksimal yang telah di tentukan, maka user harus 
     * menunggu selama $login_failed_wait_interval sebelum user diperbolehkan untuk login kembali.
     * Total waktu yang di harus di tunggu oleh user adalah $login_failed_wait_interval * ($total_gagal - $login_failed_max_count).
     * $login_failed_wait_interval dalam satuan detik.
     * 
     * @var Integer 
     */
    public static $login_failed_wait_interval = 300;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UsersIpBlock the static model class
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
        return 'users_ip_block';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start_datetime, until_datetime', 'required'),
            array('ip_address', 'length', 'max'=>15),
            array('reason', 'length', 'max'=>256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ip_address, reason, start_datetime, until_datetime', 'safe', 'on'=>'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'ip_address' => 'Ip Address',
            'reason' => 'Reason',
            'start_datetime' => 'Start Datetime',
            'until_datetime' => 'Until Datetime',
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

        $criteria->compare('ip_address',$this->ip_address,true);
        $criteria->compare('reason',$this->reason,true);
        $criteria->compare('start_datetime',$this->start_datetime,true);
        $criteria->compare('until_datetime',$this->until_datetime,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Fungsi ini untuk mencek seberapa banyak user gagal login ke system. Apabila melebihi batas yang sudah ditentukan
     * maka system akan otomatis memblock IP Address dari user sehingga user harus menunggu beberapa saat untuk
     * bisa login kembali
     */
    public static function doBruteForceProtect() {
        
        $val = Settings::getValue(array(
            'login_failed_max_count' => self::$login_failed_max_count,
            'login_failed_wait_interval' => self::$login_failed_wait_interval
        ));

        if (isset(Yii::app()->session[self::$flName])) {
            Yii::app()->session[self::$flName] = Yii::app()->session[self::$flName] + 1;
        } else {
            Yii::app()->session[self::$flName] = 1;
        }
        
        if (Yii::app()->session[self::$flName] >= $val['login_failed_max_count']) {
            // jika sudah melebihi batas maksimal gagal login yang diijinkan
            $time = Yii::app()->session[self::$flName] * $val['login_failed_wait_interval'];
            
            $model = UsersIpBlock::model()->findByAttributes(array('ip_address' => $_SERVER['REMOTE_ADDR']));
            if ($model == null) $model = new UsersIpBlock;
            
            $model->ip_address = $_SERVER['REMOTE_ADDR'];
            $model->reason = "Gagal login ke system sebanyak ".Yii::app()->session[self::$flName]." kali.";
            $model->start_datetime = date("Y-m-d H:i:s");
            $model->until_datetime = date("Y-m-d H:i:s", strtotime("+{$time} seconds"));
            $model->save();
        }
    }
    
    /**
     * Berfungsi untuk men-reset Brute Force protect
     */
    public static function resetBruteForceProtect() {
        unset(Yii::app()->session[self::$flName]);
    }
}