<?php

/**
 * This is the model class for table "slider".
 *
 * The followings are the available columns in table 'slider':
 * @property integer $id
 * @property integer $group_id
 * @property string $title
 * @property string $description
 * @property string $image_file
 * @property string $url
 * @property integer $nourut
 */
class Slider extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'slider';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('group_id, title, url, image_file', 'required'),
            array('group_id, nourut', 'numerical', 'integerOnly'=>true),
            array('title', 'length', 'max'=>50),
            array('image_file', 'file', 'types'=>'jpg, jpeg, png', 'allowEmpty'=>true),
            array('description, url', 'length', 'max'=>255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, group_id, title, description, image_file, url, nourut', 'safe', 'on'=>'search'),
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
            'mGroup'=>array(self::BELONGS_TO, 'SliderGroup', 'group_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'group_id' => 'Group',
            'title' => 'Title',
            'description' => 'Description',
            'image_file' => 'Image File',
            'url' => 'URL',
            'nourut' => 'Nourut',
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
        $criteria->compare('group_id',$this->group_id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('image_file',$this->image_file,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('nourut',$this->nourut);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Slider the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
