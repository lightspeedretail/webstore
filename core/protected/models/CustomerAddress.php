<?php

/**
 * This is the model class for table "{{customer_address}}".
 *
 * @package application.models
 * @name CustomerAddress
 *
 */
class CustomerAddress extends BaseCustomerAddress
{
	const BILLING_ADDRESS = 1;
	const SHIPPING_ADDRESS = 2;
	const BOTH_ADDRESS = 3;

	const RESIDENTIAL = 1;
	const BUSINESS = 0;

	public $makeDefaultBilling;
	public $makeDefaultShipping;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CustomerAddress the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified, address1, first_name, last_name, city', 'required', 'on' => 'Default'),
			array('active, residential', 'numerical', 'integerOnly' => true),
			array('customer_id', 'length', 'max' => 20),
			array('address_label, first_name, last_name, company, address1, address2, city', 'length', 'max' => 255),
			array('state_id, country_id', 'length', 'max' => 11),
			array('postal', 'length', 'max' => 64),
			array('phone', 'length', 'min' => 7, 'max' => 32),
			array('store_pickup_email', 'email'),
			array('created, makeDefaultBilling, makeDefaultShipping', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, customer_id, address_label, active, first_name, last_name, store_pickup_email, company, address1, address2, city, state_id, postal, country_id, phone, residential, modified, created', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'address_label' => 'Address Label (Home, Work)',
				'residential' => 'This is a residential address',
				'active' => 'Show this address on checkout',
				'address1' => 'Address',
				'address2' => 'Address Line 2 (Apt/Unit)',
				'makeDefaultBilling' => 'Default billing address',
				'makeDefaultShipping' => 'Default shipping address',
				'postal' => 'Postal / Zip Code',
			)
		);
	}

	//Called both from original form when displayed, and from AJAX query as Country changes (via Cart Controller)
	/**
	 * @param string $type
	 * @param null $intCountry
	 * @return array
	 */
	public function getStates($type = 'billing', $intCountry = null)
	{
		$obj = new CheckoutForm();
		return $obj->getStates($type, $intCountry);

	}

	public static function getAllAddresses()
	{
		//Only valid for logged in users
		if(Yii::app()->user->isGuest)
		{
			return array();
		}

		// ignore any addresses created during a store pickup order with the
		// advanced checkout as these addresses will not have address1
		// defined and are hence not valid shipping/billing addresses
		$criteria = new CDbCriteria();
		$criteria->addCondition('customer_id = :userid');
		$criteria->addCondition('address1 IS NOT NULL');
		$criteria->params = array(':userid' => Yii::app()->user->id);
		$criteria->order = 'modified DESC';

		return CustomerAddress::model()->findAll($criteria);
	}

	public static function getActiveAddresses()
	{
		//Only valid for logged in users
		if (Yii::app()->user->isGuest)
		{
			return array();
		}

		// ignore any addresses created during a store pickup order with the
		// advanced checkout as these addresses will not have address1
		// defined and are hence not valid shipping/billing addresses
		$criteria = new CDbCriteria();
		$criteria->addCondition('customer_id = :userid');
		$criteria->addCondition('address1 is NOT NULL');
		$criteria->addCondition('active = 1');
		$criteria->params = array(':userid' => Yii::app()->user->id);
		$criteria->order = 'modified DESC';

		return CustomerAddress::model()->findAll($criteria);
	}

	/**
	 * See if Customer Address already exists by searching for passed information.
	 * If not, we create it.
	 * @param null $config
	 * @return CustomerAddress|null
	 */
	public static function findOrCreate($config = null)
	{
		if (is_null($config))
		{
			return null;
		}

		//Search. If successful, return object, otherwise save (to create new object) and return that
		$obj = new CustomerAddress();
		$obj->attributes = $config;

		if ($obj->validate() === false)
		{
			return $obj;
		}

		$dataProvider = $obj->search();
		$arrAdd = $dataProvider->getData();
		if(count($arrAdd))
		{
			return $arrAdd[0];
		}
		else
		{
			$obj->save();
			return $obj;
		}
	}

	public static function updateAddress($id, $address)
	{
		$model = self::model()->findByPk($id);
		$model->attributes = $address;

		if ($model->validate())
		{
			$model->save();
		}

		return $model;
	}

	public static function updateAddressFromForm($id, CheckoutForm $checkoutForm, $str = 'shipping')
	{
		$obj = self::model()->findByPk($id);

		if ($obj === null)
		{
			Yii::log(
				sprintf(
					'Customer address with id %s not found',
					$id
				),
				'error',
				'application.' . __CLASS__ . '.' . __FUNCTION__
			);
			return;
		}

		switch ($str)
		{
			case 'shipping':
				$obj->first_name = $checkoutForm->shippingFirstName;
				$obj->last_name = $checkoutForm->shippingLastName;
				$obj->company = $checkoutForm->shippingCompany;
				$obj->address1 = $checkoutForm->shippingAddress1;
				$obj->address2 = $checkoutForm->shippingAddress2;
				$obj->city = $checkoutForm->shippingCity;
				$obj->state_id = $checkoutForm->shippingState;
				$obj->country_id = $checkoutForm->shippingCountry;
				$obj->postal = $checkoutForm->shippingPostal;
				$obj->phone = $checkoutForm->contactPhone;
				$obj->residential = $checkoutForm->shippingResidential;
				break;

			case 'billing':
				$obj->address1 = $checkoutForm->billingAddress1;
				$obj->address2 = $checkoutForm->billingAddress2;
				$obj->city = $checkoutForm->billingCity;
				$obj->state_id = $checkoutForm->billingState;
				$obj->country_id = $checkoutForm->billingCountry;
				$obj->postal = $checkoutForm->billingPostal;
				break;
		}

		if ($obj->save() === false)
		{
			Yii::log(
				sprintf(
					"Error saving customer address with id: %s \n %s",
					$id,
					print_r($obj->getErrors(), true)
				),
				'error',
				'application.'.__CLASS__.'.'.__FUNCTION__
			);
		}

		Yii::log(
			sprintf(
				'Updated Customer address with id: %s',
				$id
			),
			'info',
			'application.'  .__CLASS__ . '.' . __FUNCTION__
		);
	}

	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('address_label', $this->address_label);
		$criteria->compare('first_name', $this->first_name);
		$criteria->compare('last_name', $this->last_name);
		$criteria->compare('store_pickup_email', $this->store_pickup_email);
		$criteria->compare('company', $this->company);
		$criteria->compare('address1', $this->address1);
		$criteria->compare('address2', $this->address2);
		$criteria->compare('city', $this->city);
		$criteria->compare('state_id', $this->state_id);
		$criteria->compare('postal', $this->postal);
		$criteria->compare('country_id', $this->country_id);
		$criteria->compare('phone', $this->phone);
		$criteria->compare('residential', $this->residential);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate()
	{
		if ($this->isNewRecord)
		{
			$this->created = new CDbExpression('NOW()');
		}

		if (empty($this->address_label))
		{
			$this->address_label = Yii::t('global', 'Unlabeled Address');
		}

		if (empty($this->state_id))
		{
			$this->state_id = null;
		}

		$this->modified = new CDbExpression('NOW()');

		if (!isset($this->scenario))
		{
			$this->setScenario('Default');
		}

		return parent::beforeValidate();
	}


	public static function deactivateCustomerShippingAddress($address_id, $customer_id)
	{
		return CustomerAddress::model()->updateAll(
			array("active" => 0),
			'id = :id AND customer_id = :customer_id',
			array(
				':id' => $address_id,
				':customer_id' => $customer_id
			)
		);
	}

	public function __get($strName) {
		switch ($strName) {
			case 'state':
				if ($this->state_id)
				{
					return State::CodeById($this->state_id);
				}
				else
				{
					return null;
				}

			case 'country':
				if ($this->country_id)
				{
					return Country::CodeById($this->country_id);
				}
				else
				{
					return null;
				}

			case 'country_name':
				if ($this->country_id)
					return Country::CountryById($this->country_id);
				else
					return null;

			case 'mainname':
			case 'fullname':
				return $this->first_name." ".$this->last_name;

			case 'block':
					return $this->address1.chr(13).
					$this->address2.chr(13).
					$this->city.chr(13).
					$this->state.chr(13).
					$this->postal.chr(13).
					$this->country;

			case 'htmlblock':
				return
				$this->address1.'<br>'.
				(!empty($this->address2) ? $this->address2."<br>" : "").
				$this->city.' '.
				$this->state." ".$this->postal.'<br>'.
				$this->country_name;

			case 'shipblock':
				return $this->first_name." ".$this->last_name.chr(13).
					$this->address1.chr(13).
					(!empty($this->company) ? $this->company." ".$this->address2 : $this->address2).chr(13).
					$this->city.chr(13).
					$this->state." ".$this->postal.chr(13).
					$this->country;

			case 'formattedblock':
				if ($this->customer_id == Yii::app()->user->id)
					return $this->first_name." ".$this->last_name.'<br>'.
						$this->address1.'<br>'.
						(!empty($this->company) ? $this->company."<br>" : "").
						(!empty($this->address2) ? $this->address2."<br>" : "").
						$this->city.' '.
						$this->state." ".$this->postal.'<br>'.
						(_xls_country() != $this->country ? $this->country : "");
				else
					return
						Yii::t('global','Directly to gift recipient').'<br>'.
						$this->first_name." ".$this->last_name;

			case 'formattedblockcountry':
				return
					$this->first_name." ".$this->last_name.'<br>'.
					$this->address1.'<br>'.
					(!empty($this->company) ? $this->company."<br>" : "").
					(!empty($this->address2) ? $this->address2."<br>" : "").
					$this->city.' '.
					$this->state." ".$this->postal.'<br>'.
					(_xls_country() != $this->country ? $this->country_name : "");

			default:
				return parent::__get($strName);
		}
	}

	/**
	 * Finds the customer's address based on his id and the address
	 * id that is requested.
	 * @param $userId The current user id
	 * @param $addressId The address id that was selected
	 * @return CustomerAddress
	 */
	public static function findCustomerAddress($userId, $addressId)
	{
		return CustomerAddress::model()->find(
			'customer_id=:customer_id AND id=:id',
			array(
				':customer_id' => $userId,
				':id' => $addressId
			)
		);
	}
}
