<?php

/**
 * This is the model class for table "branch".
 *
 * The followings are the available columns in table 'branch':
 * @property integer $branch_id
 * @property string $name
 * @property string $branch_code
 * @property string $address
 * @property string $phone_number
 * @property string $fax_number
 * @property integer $city
 * @property integer $state
 * @property integer $country
 * @property integer $zipcode
 * @property string $status
 * @property string $remarks
 * @property integer $create_by
 * @property string $create_datetime
 * @property integer $update_by
 * @property string $update_datetime
 * @property integer $delete_by
 * @property string $delete_datetime
 *
 * The followings are the available model relations:
 * @property Customer[] $customers
 * @property HistoryBarang[] $historyBarangs
 * @property HistoryBarangCopy[] $historyBarangCopies
 * @property Pembelian[] $pembelians
 * @property Penjualan[] $penjualans
 * @property ReturPenjualan[] $returPenjualans
 * @property Product[] $products
 * @property Users[] $users
 */
class Branch extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Branch the static model class
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
		return 'branch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city, state, country, zipcode, create_by, update_by, delete_by', 'numerical', 'integerOnly'=>true),
			array('name, phone_number, fax_number', 'length', 'max'=>100),
			array('branch_code', 'length', 'max'=>25),
			array('address', 'length', 'max'=>256),
			array('status, remarks, create_datetime, update_datetime, delete_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('branch_id, name, branch_code, address, phone_number, fax_number, city, state, country, zipcode, status, remarks, create_by, create_datetime, update_by, update_datetime, delete_by, delete_datetime', 'safe', 'on'=>'search'),
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
			'customers' => array(self::HAS_MANY, 'Customer', 'owner_branch_id'),
			'historyBarangs' => array(self::HAS_MANY, 'HistoryBarang', 'branch_id'),
			'historyBarangCopies' => array(self::HAS_MANY, 'HistoryBarangCopy', 'branch_id'),
			'pembelians' => array(self::HAS_MANY, 'Pembelian', 'branch_id'),
			'penjualans' => array(self::HAS_MANY, 'Penjualan', 'branch_id'),
			'returPenjualans' => array(self::HAS_MANY, 'ReturPenjualan', 'branch_id'),
			'products' => array(self::MANY_MANY, 'Product', 'stock_rusak(branch_id, product_id)'),
			'users' => array(self::HAS_MANY, 'Users', 'branch_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'branch_id' => 'Branch',
			'name' => 'Name',
			'branch_code' => 'Branch Code',
			'address' => 'Address',
			'phone_number' => 'Phone Number',
			'fax_number' => 'Fax Number',
			'city' => 'City',
			'state' => 'State',
			'country' => 'Country',
			'zipcode' => 'Zipcode',
			'status' => 'Status',
			'remarks' => 'Remarks',
			'create_by' => 'Create By',
			'create_datetime' => 'Create Datetime',
			'update_by' => 'Update By',
			'update_datetime' => 'Update Datetime',
			'delete_by' => 'Delete By',
			'delete_datetime' => 'Delete Datetime',
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

		$criteria->compare('branch_id',$this->branch_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('branch_code',$this->branch_code,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone_number',$this->phone_number,true);
		$criteria->compare('fax_number',$this->fax_number,true);
		$criteria->compare('city',$this->city);
		$criteria->compare('state',$this->state);
		$criteria->compare('country',$this->country);
		$criteria->compare('zipcode',$this->zipcode);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('remarks',$this->remarks,true);
		$criteria->compare('create_by',$this->create_by);
		$criteria->compare('create_datetime',$this->create_datetime,true);
		$criteria->compare('update_by',$this->update_by);
		$criteria->compare('update_datetime',$this->update_datetime,true);
		$criteria->compare('delete_by',$this->delete_by);
		$criteria->compare('delete_datetime',$this->delete_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}