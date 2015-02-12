<?php

/**
 * This is the model class for table "{{modules}}".
 *
 * @package application.models
 * @name Modules
 *
 */
class Modules extends BaseModules
{
	public $instanceHandle;
	public $arrConfig;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Modules the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// String representation of the object
	//We use this in the sidebar foreach for loaded sidebars
	public function __toString() {
		return $this->module;
	}

	public static function LoadByName($strName) {

		return Modules::model()->findByAttributes(array('module'=>$strName));

	}

	/**
	 * A straightforward way to get the value of a known
	 * configuration item of a Module given the module name
	 * and item name
	 *
	 * @param $strModuleName
	 * @param $strItem
	 * @return null|string
	 */
	public static function GetModuleConfig($strModuleName, $strItem)
	{
		$obj = self::LoadByName($strModuleName);
		$config = unserialize($obj->configuration);
		if (isset($config[$strItem]))
		{
			return $config[$strItem];
		}

		return null;
	}

	public function getConfig($item)
	{

		$this->arrConfig = $this->GetConfigValues();
		if (isset($this->arrConfig[$item]))
			return $this->arrConfig[$item];
		else
			return null;

	}

	public function GetConfigValues() {
		try{

			$arr = unserialize($this->configuration);
			if($arr===false) $arr = array(); //If not a valid serialized string, return blank array

		}catch(Exception $e){
			Yii::log("Could not unserialize " . $this->configuration . " . Error : " . $e, 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			return array();
		}
		$this->arrConfig = $arr;
		return $arr;
	}

	public function SaveConfigValues($arr) {
		$this->configuration = serialize($arr);
		if (!$this->save())
			Yii::log("Error saving module config ".print_r($this->getErrors(),true), 'error', 'application.'.__CLASS__.".".__FUNCTION__);
	}


	protected function GetMarkup()
	{
		$config = $this->GetConfigValues();
		if (isset($config['markup']))
			return $config['markup'];
		else return 0;

	}
	protected function GetShippingProduct()
	{
		$config = $this->GetConfigValues();
		if (isset($config['product']))
			return $config['product'];
		else return 'SHIPPING';

	}

	protected function GetPaymentMethod()
	{
		$config = $this->GetConfigValues();
		if (isset($config['ls_payment_method']))
			return $config['ls_payment_method'];
		else return "Cash";


	}

	public static function isActive($str, $category)
	{
		foreach(self::getModulesByCategory(true, $category) as $obj)
		{
			if($obj->module == $str)
			{
				return true;
			}
		}

		return false;
	}

	public static function getModulesByCategory($active = true, $category = WsExtension::SIDEBAR)
	{
		$criteria = new CDbCriteria();
		if ($active)
		{
			$criteria->condition = "active=1 AND category='".$category."'";
		}
		else
		{
			$criteria->condition = "category='" . $category . "'";
		}

		$criteria->order = 'sort_order';
		return Modules::model()->findAll($criteria);

	}

	/**
	 * Checks if payment method is the only active payment method, if so returns true
	 * otherwise returns false
	 * @return bool true if only active payment method
	 * false otherwise.
	 */
	public function isOnlyActivePaymentMethod()
	{
		$activePaymentMethods = Modules::model()->payment()->findAll();

		if (count($activePaymentMethods) === 1)
		{
			$currentPaymentMethod = current($activePaymentMethods);
			if ($currentPaymentMethod->module === $this->module)
			{
				return true;
			}
		}

		return false;
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchEvents()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('active',$this->active);
		$criteria->compare('category',$this->category,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'sort_order ASC',
			),
		));
	}
	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->created = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');

		return parent::beforeValidate();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'markup':
				return $this->GetMarkup();

			case 'payment_method':
				return $this->GetPaymentMethod();

			case 'product':
				return $this->GetShippingProduct();

			default:
				return parent::__get($strName);
		}
	}

	/**
	 * Define some specialized query scopes to make searching for specific db
	 * info easier
	 */
	public function scopes() {
		return array(
			'shipping' => array(
				'condition' => 'active = 1 AND category = "shipping"',
				'order' => 'sort_order'
			),
			'notStorePickup' => array(
				'condition' => 'module != "storepickup"'
			),
			'freeshipping' => array(
				'condition' => 'active = 1 AND module = "freeshipping"'
			),
			'payment' => array(
				'condition' => 'active = 1 AND category = "payment"',
				'order' => 'sort_order'
			)
		);
	}
}
