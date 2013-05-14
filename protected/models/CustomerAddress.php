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
	const BILLING_ADDRESS=1;
	const SHIPPING_ADDRESS=2;
	const BOTH_ADDRESS=3;

	const RESIDENTIAL = 1;
	const BUSINESS = 0;
	/**
	 * Returns the static model of the specified AR class.
	 * @return CustomerAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
				'address_label'=>'Address Label (Home, Work)',
				'residential'=>'This is a residential address',
				'active'=>'This is an active address',

		));
	}


	//Called both from original form when displayed, and from AJAX query as Country changes (via Cart Controller)
	/**
	 * @param string $type
	 * @param null $intCountry
	 * @return array
	 */
	public function getStates($type = 'billing',$intCountry = null) {

		if (is_null($intCountry))
			$intCountry = (int)_xls_get_conf('DEFAULT_COUNTRY',224);

		//These are only on first display so state list defaults to chosen country
		if ($type=='billing') $intCountry = $this->country_id;

		return CHtml::listData(State::model()->findAllByAttributes(array('country_id'=>$intCountry,'active'=>1),array('order'=>'sort_order,state')), 'id', 'code');
	}


	public static function getActiveAddresses()
	{
		//Only valid for logged in users
		if (Yii::app()->user->isGuest)
			return array();

		return CustomerAddress::model()->findAllByAttributes(
			array('customer_id'=>Yii::app()->user->id,'active'=>'1'),
			array('order'=>'modified DESC')
		);



	}


	/**
	 * See if Customer Address already exists by searching for passed information.
	 * If not, we create it.
	 * @param null $config
	 * @return CustomerAddress|null
	 */
	public static function findOrCreate($config = null)
	{
		if(is_null($config)) return null;

		//Search. If successful, return object, otherwise save (to create new object) and return that
		$obj = new CustomerAddress();
		$obj->attributes = $config;

		$dataProvider = $obj->search();
		$arrAdd = $dataProvider->getData();
		if(count($arrAdd))
			return $arrAdd[0];
		else
		{
			$obj->save();
			return $obj;
		}


	}


	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('address_label',$this->address_label);
		$criteria->compare('first_name',$this->first_name);
		$criteria->compare('last_name',$this->last_name);
		$criteria->compare('company',$this->company);
		$criteria->compare('address1',$this->address1);
		$criteria->compare('address2',$this->address2);
		$criteria->compare('city',$this->city);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('postal',$this->postal);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('phone',$this->phone);
		$criteria->compare('residential',$this->residential);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord) {
			$this->created = new CDbExpression('NOW()');
			if (empty($this->address_label))
				$this->address_label = Yii::t('global','Unlabeled Address');
		}
		if (empty($this->state_id))
			$this->state_id=null;
		$this->modified = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}


	public function __get($strName) {
		switch ($strName) {
			case 'state':
				if ($this->state_id)
					return State::CodeById($this->state_id);
				else return null;

			case 'country':
				if ($this->country_id)
					return Country::CodeById($this->country_id);
				else return null;

			case 'mainname':
			case 'fullname':
				return $this->first_name." ".$this->last_name;

			case 'block':
					return $this->address1.chr(13).
					$this->address2.chr(13).
					$this->city.chr(13).
					$this->state." ".$this->postal.chr(13).
					$this->country;

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

			default:
				return parent::__get($strName);
		}
	}



}