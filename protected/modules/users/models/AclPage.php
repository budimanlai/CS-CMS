<?php

/**
 * This is the model class for table "acl_page".
 *
 * The followings are the available columns in table 'acl_page':
 * @property string $group_id
 * @property string $route
 * @property string $page_type
 */
class AclPage extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AclPage the static model class
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
        return 'acl_page';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('group_id', 'length', 'max'=>15),
            array('route', 'length', 'max'=>256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_groups, route', 'safe', 'on'=>'search'),
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
            'user_groups' => 'User Groups',
            'route' => 'Route',
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

        $criteria->compare('group_id',$this->group_id,true);
        $criteria->compare('route',$this->route,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}