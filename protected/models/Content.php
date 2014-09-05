<?php

/**
 * This is the model class for table "content".
 *
 * The followings are the available columns in table 'content':
 * @property integer $id
 * @property integer $group_id
 * @property string $title
 * @property string $seo_url
 * @property string $short_description
 * @property string $long_description
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $status
 * @property string $nourut
 * @property integer $create_by
 * @property string $create_datetime
 */
class Content extends CActiveRecord
{
    /**
    * @return string the associated database table name
    */
   public function tableName()
   {
        return 'content';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules()
   {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('thumb_image', 'file', 'types' => 'jpg, jpeg, png', 'allowEmpty' => true),
            array('title, seo_url, long_description, group_id', 'required'),
            array('group_id, nourut, create_by', 'numerical', 'integerOnly'=>true),
            array('title, seo_url, meta_keyword, meta_description', 'length', 'max'=>256),
            array('short_description', 'length', 'max'=>500),
            array('status', 'length', 'max'=>10),
            array('long_description, create_datetime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, group_id, nourut, title, seo_url, short_description, long_description, meta_keyword, meta_description, status, create_by, create_datetime', 'safe', 'on'=>'search'),
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
            'mContentGroup' => array(self::BELONGS_TO, 'ContentGroup', 'group_id'),
            'mUser' => array(self::BELONGS_TO, 'Users', 'create_by'),
            'mMedia' => array(self::HAS_MANY, 'ContentMedia', 'content_id'),
        );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels()
   {
        return array(
            'id' => 'ID',
            'group_id' => 'Content Group',
            'title' => 'Title',
            'seo_url' => 'SEO URL',
            'short_description' => 'Short Description',
            'long_description' => 'Long Description',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'nourut' => 'No Urut',
            'status' => 'Status',
            'create_by' => 'Create By',
            'create_datetime' => 'Create Datetime',
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
        $criteria->with = array('mContentGroup', 'mUser');
        $criteria->compare('t.id',$this->id);
        $criteria->compare('group_id',$this->group_id);
        $criteria->compare('t.title',$this->title,true);
        $criteria->compare('t.seo_url',$this->seo_url,true);
        $criteria->compare('short_description',$this->short_description,true);
        $criteria->compare('long_description',$this->long_description,true);
        $criteria->compare('meta_keyword',$this->meta_keyword,true);
        $criteria->compare('meta_description',$this->meta_description,true);
        $criteria->compare('t.status',$this->status,true);
        $criteria->compare('create_by',$this->create_by);
        $criteria->compare('create_datetime',$this->create_datetime,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
   }

   /**
    * Returns the static model of the specified AR class.
    * Please note that you should have this exact method in all your CActiveRecord descendants!
    * @param string $className active record class name.
    * @return Content the static model class
    */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public static function getLink($model) {
        if (is_array($model))
            return Yii::app()->createUrl($model['seo_url']);
        else
            return Yii::app()->createUrl($model->seo_url);
    }
    
    public static function createAbsoulteUrl($row) {
        return Yii::app()->createAbsoluteUrl($row->seo_url);
    }
    public static function createUrl($row) {
        return Yii::app()->createUrl($row->seo_url);
    }
    
    public static function getThumb($row) {
        return !empty($row->thumb_image) && file_exists($row->thumb_image) ? Yii::app()->request->baseUrl."/".$row->thumb_image : Yii::app()->request->baseUrl."/uploads/no_image.gif";
    }
    
    public static function getResumeDescription($row) {
        $cc = '<div style="page-break-after: always;"><span style="display:none">&nbsp;</span></div>';
        $pos = strpos($row->long_description, $cc);
        $length = $pos === false ? strlen($row->long_description) : $pos;
        return substr($row->long_description, 0, $length);
    }
    
    public static function normalizeTagsRead($keyword) {
        if (!empty($keyword)) {
            $keyword = str_replace("|", ", ", $keyword);
        }
        return $keyword;
    }
    
    public static function normalizeTags($tags) {
        if (!empty($tags)) {
            $tt = explode(",", $tags);
            $tags = "";
            foreach($tt as $row) {
                $tags.= trim($row)."|";
            }
            $tags = substr($tags, 0, strlen($tags)-1);
        }
        return $tags;
    }
    
    public static function getAuthor($user_id) {
        return Users::model()->findByPk($user_id);
    }
    
    public static function getContentBySEO($seo) {
        return Content::model()->find('seo_url = :SEO AND status = "active"', array(':SEO' => $seo));
    }
}
