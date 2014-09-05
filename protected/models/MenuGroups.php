<?php

/**
 * This is the model class for table "menu_groups".
 *
 * The followings are the available columns in table 'menu_groups':
 * @property string $group_name
 * @property string $title
 */
class MenuGroups extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'menu_groups';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, group_name', 'required'),
            array('group_name', 'unique'),
            array('group_name', 'length', 'max'=>15),
            array('title', 'length', 'max'=>256),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('group_name, title', 'safe', 'on'=>'search'),
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
            'group_name' => 'Group Name',
            'title' => 'Title',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('group_name',$this->group_name,true);
        $criteria->compare('title',$this->title,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MenuGroups the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
