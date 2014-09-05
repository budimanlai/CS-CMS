<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property string $user_id
 * @property string $firstname
 * @property string $lastname
 * @property string $sex
 * @property string $bday_date
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $zipcode
 * @property string $mobile_phone
 * @property string $facebook
 * @property string $twitter
 * @property string $google_plus
 * @property string $ym
 * @property string $bb_pin
 */
class Profile extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Profile the static model class
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
        return 'profile';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('user_id', 'length', 'max'=>11),
            array('firstname', 'required'),
            array('firstname, lastname, mobile_phone', 'length', 'max'=>25),
            array('sex', 'length', 'max'=>1),
            array('address', 'length', 'max'=>100),
            array('city, facebook, twitter, google_plus, ym', 'length', 'max'=>50),
            array('zipcode', 'length', 'max'=>5),
            array('state', 'length', 'max'=>10),
            array('country', 'length', 'max'=>2),
            array('bb_pin', 'length', 'max'=>8),
            array('bday_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, firstname, lastname, sex, bday_date, address, city, state, country, zipcode, mobile_phone, facebook, twitter, google_plus, ym, bb_pin', 'safe', 'on'=>'search'),
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
            'country_mod' => array(self::BELONGS_TO, 'Country', 'country'),
            'state_mod' => array(self::BELONGS_TO, 'CountryCities', 'state'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'user_id' => 'User',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'sex' => 'Sex',
            'bday_date' => 'Bday Date',
            'address' => 'Address',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'zipcode' => 'Zipcode',
            'mobile_phone' => 'Mobile Phone',
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'google_plus' => 'Goole Plus',
            'ym' => 'Ym',
            'bb_pin' => 'Bb Pin',
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

        $criteria->compare('user_id',$this->user_id,true);
        $criteria->compare('firstname',$this->firstname,true);
        $criteria->compare('lastname',$this->lastname,true);
        $criteria->compare('sex',$this->sex,true);
        $criteria->compare('bday_date',$this->bday_date,true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('city',$this->city,true);
        $criteria->compare('state',$this->state,true);
        $criteria->compare('country',$this->country,true);
        $criteria->compare('zipcode',$this->zipcode,true);
        $criteria->compare('mobile_phone',$this->mobile_phone,true);
        $criteria->compare('facebook',$this->facebook,true);
        $criteria->compare('twitter',$this->twitter,true);
        $criteria->compare('google_plus',$this->goole_plus,true);
        $criteria->compare('ym',$this->ym,true);
        $criteria->compare('bb_pin',$this->bb_pin,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}