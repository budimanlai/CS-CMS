<?php

/**
 * This is the model class for table "email_template".
 *
 * The followings are the available columns in table 'email_template':
 * @property string $name
 * @property string $title
 * @property string $message
 * @property string $sender
 */
class EmailTemplate extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EmailTemplate the static model class
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
        return 'email_template';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max'=>25),
            array('title', 'length', 'max'=>255),
            array('sender', 'length', 'max'=>50),
            array('message', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, title, message, sender', 'safe', 'on'=>'search'),
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
            'title' => 'Title',
            'message' => 'Message',
            'sender' => 'Sender',
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
        $criteria->compare('title',$this->title,true);
        $criteria->compare('message',$this->message,true);
        $criteria->compare('sender',$this->sender,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Untuk mengirim email dengan body message berasal dari email template
     * <pre>
     * $param = array(
     *      '{username}' => 'budiman_lai',
     *      '{firstname' => 'Budiman',
     *      '{lastname}' => 'Lai',
     *      '{other}' => 'Other value'
     * );
     * EmailTemplate::SendMail('lupa_password', 'budiman.lai@gmail.com', $param);
     * </pre>
     * 
     * @param String $name
     * @param String $to
     * @param Array $data
     * @return boolean
     */
    public static function SendMail($name, $to, $data) {
        $mEmail = EmailTemplate::model()->find('name = :NAME', array(
            ':NAME' => $name
        ));
        if ($mEmail != null) {
            $msg = str_replace(array_keys($data), $data, $mEmail->message);
            
            $subject = $mEmail->subject;
            $headers = "From: " . $mEmail->sender . "\r\n";
            $headers .= "Reply-To: ". $mEmail->sender . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message = '<html><body>';
            $message.= $msg;
            $message.= '</body></html>';
            
            if (mail($to, $subject, $message)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}