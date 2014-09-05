<?php

define("APIKEY_NONE", 0);
define("APIKEY_APP_KEY_INVALID", 1);
define("APIKEY_SECURITY_KEY_INVALID", 2);
define("APIKEY_SESSION_INVALID", 3);
define("APIKEY_REQUIRED_APP_KEY", 4);
define("APIKEY_REQUIRED_SECURITY_KEY", 5);
define("APIKEY_REQUIRED_SESSION", 6);
define("APIKEY_ACCESS_DENIED", 7);
define("APIKEY_INACTIVE", 8);

/**
 * This is the model class for table "api_key".
 *
 * The followings are the available columns in table 'api_key':
 * @property string $app_key
 * @property string $security_key
 * @property string $title
 * @property string $description
 * @property string $status
 * @property string $create_datetime
 * @property string $last_access
 * @property string $refferal_url
 */
class ApiKey extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ApiKey the static model class
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
        return 'api_key';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('create_datetime', 'required'),
            array('app_key', 'length', 'max'=>32),
            array('security_key', 'length', 'max'=>8),
            array('title', 'length', 'max'=>100),
            array('description', 'length', 'max'=>500),
            array('status', 'length', 'max'=>10),
            array('refferal_url', 'length', 'max'=>256),
            array('last_access', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('app_key, security_key, title, description, status, create_datetime, last_access, refferal_url', 'safe', 'on'=>'search'),
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
            'app_key' => 'App Key',
            'security_key' => 'Security Key',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'create_datetime' => 'Create Datetime',
            'last_access' => 'Last Access',
            'refferal_url' => 'Refferal Url',
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

        $criteria->compare('app_key',$this->app_key,true);
        $criteria->compare('security_key',$this->security_key,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('create_datetime',$this->create_datetime,true);
        $criteria->compare('last_access',$this->last_access,true);
        $criteria->compare('refferal_url',$this->refferal_url,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * <p>Apabila client ingin mengakses API Service yang disediakan oleh system, maka sebelum API Service dijalankan
     * harus dilakukan validasi apakah client diijinkan untuk mengakses API Service tersebut.
     * Setiap client ingin mengakses API Service maka diperlukan 3 data yaitu API Key, Security Key dan Session,
     * kecuali API Service untuk login tidak memerlukan Session.</p>
     * 
     * <p>Ketiga data tersebut dapat dikirimkan dari client dengan menyertakan-nya melalui HTTP Header. 
     * Disaat user berhasil login, maka system akan memberikan session yang bisa digunakan untuk setiap request API Service.
     * API Service juga bisa dibatasin hanya bisa diakses apabila client memiliki Refferal URL tertentu $_SERVER['HTTP_REFERER']</p>
     * <p>
     * $required_session = Optional. Default bernilai TRUE artinya untuk mengakses API Service memerlukan data session.<br/>
     * $last_access_update = Optional. Default bernilai FALSE artinya apabila bernilai TRUE maka update field last_access ke datetime sekarang
     * </p>
     * <pre>
     * switch(ApiKey::isAllowed()) {
     *      case APIKEY_APP_KEY_INVALID: echo "APIKEY_APP_KEY_INVALID"; break;
     *      case APIKEY_SECURITY_KEY_INVALID: echo "APIKEY_SECURITY_KEY_INVALID"; break;
     *      case APIKEY_SESSION_INVALID: echo "APIKEY_SESSION_INVALID"; break;
     *      case APIKEY_REQUIRED_APP_KEY: echo "APIKEY_REQUIRED_APP_KEY"; break;
     *      case APIKEY_REQUIRED_SECURITY_KEY: echo "APIKEY_SECURITY_KEY_INVALID"; break;
     *      case APIKEY_REQUIRED_SESSION: echo "APIKEY_SESSION_INVALID"; break;
     *      case APIKEY_ACCESS_DENIED: echo "APIKEY_ACCESS_DENIED"; break;
     *      case APIKEY_INACTIVE: echo "APIKEY_INACTIVE"; break;
     *      case APIKEY_NONE: echo "APIKEY_NONE"; break;
     * }
     * </pre>
     * 
     * @param Boolean $required_session
     * @param Boolean $last_access_update
     * @return mix
     */
    public static function isAllowed($required_session = true, $last_access_update = false) {
        $header = apache_request_headers();
        
        // check apakah client ada kirim app_key? jika tidak ada maka return false
        if (!isset($header['app_key'])) return APIKEY_REQUIRED_APP_KEY;
        
        // check apakah client ada kirim security_key? jika tidak ada maka return false
        if (!isset($header['security_key'])) return APIKEY_REQUIRED_SECURITY_KEY;
        
        // check apakah API Service memerlukan session? jika butuh session, check apakah client kirim session
        if ($required_session && !isset($header['session'])) return APIKEY_REQUIRED_SESSION;
        
        // sekarang check apakah app_key yg dikirim client sudah benar?
        $model = ApiKey::model()->findByPk($header['app_key']);
        if ($model == null) return APIKEY_APP_KEY_INVALID;
        
        // check apakah security key yg dikirim client sudah benar?
        if ($model->security_key != $header['security_key']) return APIKEY_SECURITY_KEY_INVALID;
        
        // check apakah API Key active atau tidak
        if ($model->status != "active") return APIKEY_INACTIVE;
        
        // sekarang check apakah session yang dikirm oleh client ada didalam database atau tidak
        if ($required_session) {
            $sess = UsersLogin::model()->findByPk($header['session']);
            if ($sess == null) return APIKEY_SESSION_INVALID;
        }
        
        // check apakah API Key hanya bisa diakses dari refferal URL tertentu??
        if (!empty($model->refferal_url)) {
            
            // check apakah API Service diakses secara direct atau melalui refferal
            if (!isset($_SERVER['HTTP_REFERER'])) return APIKEY_ACCESS_DENIED;
            
            // check apakah refferal yang dikirim oleh client sudah benar?
            if (strtolower(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)) != strtolower($model->refferal_url)) return APIKEY_ACCESS_DENIED;
        }
        
        if ($last_access_update) {
            $model->last_access = date("Y-m-d H:i:s");
            $model->save();
        }
        
        // semua rule yg dibutuhkan sudah memenuhi syarat
        return APIKEY_NONE;
    }
    
    /**
     * Digunakan untuk menvalidasi apakah API key boleh diakses oleh user atau tidak. Ini adalah perintah cepat untuk fungsi
     * ApiKey::isAllowed(). Apabila user tidak berhak untuk mengakses API maka system langsung akan mengeluarkan pesan error.
     * <pre>
     * public function actionAPIGetUser() {
     *      ApiKey::checkApiAllowed();
     *      // kode lain kalau user berhak untuk mengakses method ini
     * }
     * </pre>
     * 
     * @return null;
     * @throws CHttpException
     */
    public static function checkApiAllowed() {
        if (($error = ApiKey::isAllowed(false, true)) != APIKEY_NONE) {
            switch($error) {
                case APIKEY_APP_KEY_INVALID: $str = "APIKEY_APP_KEY_INVALID"; break;
                case APIKEY_SECURITY_KEY_INVALID: $str = "APIKEY_SECURITY_KEY_INVALID"; break;
                case APIKEY_SESSION_INVALID: $str = "APIKEY_SESSION_INVALID"; break;
                case APIKEY_REQUIRED_APP_KEY: $str = "APIKEY_REQUIRED_APP_KEY"; break;
                case APIKEY_REQUIRED_SECURITY_KEY: $str = "APIKEY_SECURITY_KEY_INVALID"; break;
                case APIKEY_REQUIRED_SESSION: $str = "APIKEY_SESSION_INVALID"; break;
                case APIKEY_ACCESS_DENIED: $str = "APIKEY_ACCESS_DENIED"; break;
                case APIKEY_INACTIVE: $str = "APIKEY_INACTIVE"; break;
            }
     
            throw new CHttpException(500, $str);
            return;
        }
    }
}