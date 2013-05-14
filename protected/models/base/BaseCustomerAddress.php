<?php

/**
 * This is the base model class for table "{{customer_address}}".
 *
 * The followings are the available columns in table '{{customer_address}}':
 * @property string $id
 * @property string $customer_id
 * @property string $address_label
 * @property integer $active
 * @property string $first_name
 * @property string $last_name
 * @property string $company
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state_id
 * @property string $postal
 * @property string $country_id
 * @property string $phone
 * @property integer $residential
 * @property string $modified
 * @property string $created
 *
 * The followings are the available model relations:
 * @property Cart[] $carts
 * @property Cart[] $carts1
 * @property Customer[] $customers
 * @property Customer[] $customers1
 * @property Customer $customer
 * @property State $state
 * @property Country $country
 * @property Document[] $documents
 * @property Document[] $documents1
 *
 * @package application.models.base
 * @name BaseCustomerAddress
 */
abstract class BaseCustomerAddress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{customer_address}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified', 'required'),
			array('active, residential', 'numerical', 'integerOnly'=>true),
			array('customer_id', 'length', 'max'=>20),
			array('address_label, first_name, last_name, company, address1, address2, city', 'length', 'max'=>255),
			array('state_id, country_id', 'length', 'max'=>11),
			array('postal, phone', 'length', 'max'=>64),
			array('created', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, address_label, active, first_name, last_name, company, address1, address2, city, state_id, postal, country_id, phone, residential, modified, created', 'safe', 'on'=>'search'),
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
			'carts' => array(self::HAS_MANY, 'Cart', 'billaddress_id'),
			'carts1' => array(self::HAS_MANY, 'Cart', 'shipaddress_id'),
			'customers' => array(self::HAS_MANY, 'Customer', 'default_billing_id'),
			'customers1' => array(self::HAS_MANY, 'Customer', 'default_shipping_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'state' => array(self::BELONGS_TO, 'State', 'state_id'),
			'country' => array(self::BELONGS_TO, 'Country', 'country_id'),
			'documents' => array(self::HAS_MANY, 'Document', 'billaddress_id'),
			'documents1' => array(self::HAS_MANY, 'Document', 'shipaddress_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => 'Customer',
			'address_label' => 'Address Label',
			'active' => 'Active',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'company' => 'Company',
			'address1' => 'Address1',
			'address2' => 'Address2',
			'city' => 'City',
			'state_id' => 'State',
			'postal' => 'Postal',
			'country_id' => 'Country',
			'phone' => 'Phone',
			'residential' => 'Residential',
			'modified' => 'Modified',
			'created' => 'Created',
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
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('address_label',$this->address_label,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state_id',$this->state_id,true);
		$criteria->compare('postal',$this->postal,true);
		$criteria->compare('country_id',$this->country_id,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('residential',$this->residential);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('created',$this->created,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}