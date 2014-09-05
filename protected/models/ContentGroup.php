<?php

/**
 * This is the model class for table "content_group".
 *
 * The followings are the available columns in table 'content_group':
 * @property integer $id
 * @property string $title
 * @property string $seo_url
 * @property string $layout_name
 * @property string $default_content_id
 */
class ContentGroup extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'content_group';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, seo_url', 'length', 'max'=>256),
            array('layout_name', 'length', 'max'=>50),
            array('default_content_id', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, title, layout_name, default_content_id, seo_url', 'safe', 'on'=>'search'),
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
            'mContent' => array(self::BELONGS_TO, 'Content', 'default_content_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'seo_url' => 'Seo Url',
            'layout_name' => 'Layout Name',
            'default_content_id' => 'Default Content ID'
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

        $criteria->compare('id',$this->id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('seo_url',$this->seo_url,true);
        $criteria->compare('layout_name',$this->layout_name,true);
        $criteria->compare('default_content_id',$this->layout_name,true);
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ContentGroup the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
