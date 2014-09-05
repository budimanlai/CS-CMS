<?php

/**
 * This is the model class for table "download".
 *
 * The followings are the available columns in table 'download':
 * @property integer $media_id
 * @property integer $group_id
 * @property string $title
 * @property string $description
 * @property string $filename
 * @property integer $upload_by
 * @property string $upload_datetime
 *
 * The followings are the available model relations:
 * @property DownloadGroup $group
 */
class Download extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'download';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('group_id, title, description, filename', 'required'),
            array('group_id, upload_by', 'numerical', 'integerOnly'=>true),
            array('title', 'length', 'max'=>256),
            array('filename', 'file', 'allowEmpty'=> true),
            array('description', 'length', 'max'=>500),
            array('upload_datetime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('media_id, group_id, title, description, filename, upload_by, upload_datetime', 'safe', 'on'=>'search'),
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
            'mGroup' => array(self::BELONGS_TO, 'DownloadGroup', 'group_id'),
            'mUploadBy' => array(self::BELONGS_TO, 'Users', 'upload_by')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'media_id' => 'Media',
            'group_id' => 'Group',
            'title' => 'Title',
            'description' => 'Description',
            'filename' => 'Filename',
            'upload_by' => 'Upload By',
            'upload_datetime' => 'Upload Datetime',
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

        $criteria->compare('media_id',$this->media_id);
        $criteria->compare('group_id',$this->group_id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('filename',$this->filename,true);
        $criteria->compare('upload_by',$this->upload_by);
        $criteria->compare('upload_datetime',$this->upload_datetime,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Download the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
