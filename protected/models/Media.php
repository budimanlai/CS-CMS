<?php

/**
 * This is the model class for table "media".
 *
 * The followings are the available columns in table 'media':
 * @property integer $media_id
 * @property string $filename
 * @property string $thumb_file
 * @property string $title
 * @property string $description
 * @property string $status
 * @property integer $download_hits
 * @property integer $upload_by
 * @property string $upload_datetime
 */
class Media extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'media';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('filename, title', 'required'),
            array('download_hits, upload_by', 'numerical', 'integerOnly'=>true),
            array('filename, thumb_file, title', 'length', 'max'=>256),
            array('filename', 'file', 'types' => 'jpg, gif, png, doc, zip, rar, pdf, xls, docx, xlsx, mov', 'allowEmpty' => true),
            array('status', 'length', 'max'=>10),
            array('mime_type', 'length', 'max'=>50),
            array('description, upload_datetime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('media_id, filename, title, mime_type, description, status, download_hits, upload_by, upload_datetime', 'safe', 'on'=>'search'),
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
            'media_id' => 'ID',
            'filename' => 'Filename',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'download_hits' => 'Download Hits',
            'mime_type' => 'Mime Type',
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
        $criteria->compare('filename',$this->filename,true);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('status',$this->status,true);
        $criteria->compare('download_hits',$this->download_hits);
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
     * @return Media the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Get Mime Type of file
     * <pre>
     * echo Media::getMimeType("uploads/media/file.jpg");   // image
     * echo Media::getMimeType("uploads/media/file.pdf");   // pdf
     * </pre>
     * 
     * @param type $filename
     * @return string
     */
    public static function getMimeType($filename) {
        if (function_exists("mime_content_type")) {
            $mime = mime_content_type($filename);
        } else if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mime = finfo_file($finfo, $filename);
            finfo_close($finfo);
        } else {
            $t = explode(".", $filename);
            switch($t[count($t)-1]) {
                case "gif": case "jpg": case "png": return "image";
                case "pdf": return "pdf";
                case "xls": case "xlsx": return "excel";
                case "doc": case "docx": return "word";
                case "zip": case "rar": return "zip";
                case "mov": case "3gp": return "video";
            }
        }
        
        if (strpos("pdf", $mime) === true) {
            return "pdf";
        } else if (strpos("image", $mime) === true) {
            return "image";
        } else if (strpos("excel", $mime) === true) {
            return "excel";
        } else if (strpos("zip", $mime) === true) {
            return "zip";
        } else if (strpos("compressed", $mime) === true) {
            return "zip";
        } else if (strpos("msword", $mime) === true) {
            return "word";
        } else if (strpos("video", $mime) === true) {
            return "video";
        }
    }
    
    public static function getThumb($model) {
        if ($model->mime_type == "image") {
            return $model->thumb_file;
        } else {
            return "http://img.youtube.com/vi/{$model->filename}/default.jpg";;
        }
    }
}
