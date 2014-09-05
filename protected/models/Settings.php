<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property string $name
 * @property string $value
 */
class Settings extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Settings the static model class
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
        return 'settings';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'length', 'max'=>50),
            array('value', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, value', 'safe', 'on'=>'search'),
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
            'name' => 'Name',
            'value' => 'Value',
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

        $criteria->compare('name',$this->name,true);
        $criteria->compare('value',$this->value,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Get single setting value or many setting value from database
     * <pre>
     * echo Settings::getValue('key1'); // value 1
     * echo Settings::getValue('key2', 'default value');    // return "default value" jika key 'key2' tidak ada di dalam database 
     * or
     * echo Settings::getValueArray(array('key1', 'key2', 'key3'));
     * // output
     * array(
     *  'key1' => 'value 1',
     *  'key2' => 'value 2',
     *  'key3' => 'value 3'
     * );
     * or
     * echo Settings::getValueArray(array(
     * 'key1' => 'default 1', 
     * 'key2' => 'default 2, 
     * 'key3'));
     * // output
     * array(
     *  'key1' => 'default 1',  // jika key1 tidak ada didalam database maka nilai key1 adalah default 1
     *  'key2' => 'value 2',    // jika key2 ada didalam database, maka nila key2 sesuai dengan yang didatabase
     *  'key3' => 'value 3'
     * );
     * </pre>
     * 
     * @param mix $key
     * @return mix
     */
    public static function getValue($keys, $default = "") {
        if (is_array($keys)) {
            $f = array();
            
            foreach($keys as $index => $row) {
                if (!is_numeric($index)) {
                    $key[$index] = $row;
                    $f[] = $row;
                } else {
                    $key[$row] = "";
                    $f[] = $index;
                }
            }
            $criteria = new CDbCriteria;
            $criteria->addInCondition("name", $f);
            
            $k = array();
            
            $sett = Settings::model()->findAll($criteria);
            if ($sett != null) {
                foreach($sett as $row) {
                    $k[$row->name] = $row->value;
                }
            }
            return array_merge($key, $k);
        } else {
            $sett = Settings::model()->findByAttributes(array('name' => $keys));
            if ($sett != null) {
                return $sett->value;
            } else {
                return $default;
            }
        }
    }
}