<?php

/**
 * This is the model class for table "acl_menu".
 *
 * The followings are the available columns in table 'acl_menu':
 * @property string $id
 * @property string $group_id
 * @property integer $parent_id
 * @property string $title
 * @property string $url
 * @property integer $position
 */
class AclMenu extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AclMenu the static model class
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
        return 'acl_menu';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('parent_id, position', 'numerical', 'integerOnly'=>true),
            array('group_id', 'length', 'max'=>15),
            array('title', 'length', 'max'=>100),
            array('url', 'length', 'max'=>256),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, group_id, parent_id, title, url, position', 'safe', 'on'=>'search'),
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
            'id' => 'ID',
            'group_id' => 'Group',
            'parent_id' => 'Parent',
            'title' => 'Title',
            'url' => 'Url',
            'position' => 'Position',
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

        $criteria->compare('id',$this->id,true);
        $criteria->compare('group_id',$this->group_id,true);
        $criteria->compare('parent_id',$this->parent_id);
        $criteria->compare('title',$this->title,true);
        $criteria->compare('url',$this->url,true);
        $criteria->compare('position',$this->position);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Fungsi untuk load menu dari table acl_menu berdasarkan group_id (user_group). Fungsi ini akan recrusive dalam
     * load menu jadi setiap menu akan di check apakah memiliki submenu, apabila ada maka akan di load juga.
     * Untuk menentukan sebuah menu adalah submenu dari menu tertentu adalah berdasarkan parent_id
     * <pre>
     * // load menu untuk user group administrator dan parent id = 0
     * $menu = AclMenu::getMenu('administrator', 0);
     * </pre>
     * 
     * @param String $user_group
     * @param Integer $parent_id
     * @return Array
     */
    public static function getMenu($user_group, $parent_id = 0) {
        $model = AclMenu::model()->findAll(array(
            'condition' => 'group_id = :GROUP AND parent_id = :ID',
            'params' => array(
                ':GROUP' => $user_group,
                ':ID' => $parent_id
            ),
            'order'=> 'position ASC'
        ));
        
        if ($model != null) {
            $data = array();
            foreach($model as $row) {
                //array('id'=> 1, 'text'=>'Transaksi', 'iconCls'=>'icon-save'),
                $data[] = array(
                    'id' => $row->id,
                    'text' => $row->title,
                    'iconCls' => 'icon-save',
                    'attributes' => array(
                        'url' => $row->url
                    ),
                    'children' => AclMenu::getMenu($user_group, $row->id)
                );
            }
            return $data;
        } else {
            return null;
        }
    }
}