<?php

/**
 * This is the model class for table "{{destination}}".
 *
 * @package application.models
 * @name Destination
 *
 */
class Destination extends BaseDestination
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Destination the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'country' => 'Country',
			'state' => 'State',
			'zipcode1' => 'From Zip/Postal',
			'zipcode2' => 'To Zip/Postal',
			'taxcode' => 'Tax Code',
			'label' => 'Label',
			'base_charge' => 'Base Charge',
			'ship_free' => 'Ship Free',
			'ship_rate' => 'Ship Rate',
			'modified' => 'Modified',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('modified,taxcode', 'required'),
			array('country, state,taxcode', 'numerical', 'integerOnly'=>true),
			array('base_charge, ship_free, ship_rate', 'numerical'),
			array('zipcode1, zipcode2', 'length', 'max'=>10),
			array('label', 'length', 'max'=>32),
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

		$criteria->select=array('*','coalesce(country,0) as country','coalesce(state,0) as state');

		$criteria->compare('id',$this->id,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zipcode1',$this->zipcode1,true);
		$criteria->compare('zipcode2',$this->zipcode2,true);
		$criteria->compare('taxcode',$this->taxcode);
		$criteria->order = 'country,state';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,

			'pagination'=>array(
        'pageSize'=>9999,
			),
		));
	}


	public static function GetDefaultOrdering($sql = false) {
		if ($sql) return "order by country DESC, state DESC, zipcode1 DESC";
			else return array('order'=>'country DESC, state DESC, zipcode1 DESC');
	}

	public static function LoadDefault() {

		return Destination::model()->findByAttributes(array('country'=>null,'state'=>null));

	}

	public static function LoadByCountry($strCountry, $blnRestrict = false) {
		$objCountry = Country::LoadByCode($strCountry);
		if ($blnRestrict) {
			if (count(Destination::model()->countByAttributes(
					array('country'=>$objCountry->id,'state'=>null))))
			{

				$arrStates = State::model()->findAllByAttributes(
					array('country_id'=>$objCountry->id,'active'=>1),
					State::GetDefaultOrdering()
				);

				return Destination::ConvertStatesToDestinations(
					$objCountry->id, $arrStates
				);
			}
		}

		return Destination::model()->findAll('country IS NULL OR country=:t1 '.Destination::GetDefaultOrdering(true), array(':t1'=>$objCountry->id));

	}

	public static function ConvertStatesToDestinations($intCountry,$arrStates) {
		$arrDestinations = array();
		foreach($arrStates as $state)
		{
			$objDestination = new Destination();
			$objDestination->country = $intCountry;
			$objDestination->state = $state->id;
			$objDestination->label = $state->code;
			$objDestination->zipcode1 = "*";
			$objDestination->zipcode2 = "*";
			$objDestination->taxcode = 0;
			$arrDestinations[] = $objDestination;
		}
		return $arrDestinations;
	}

	/**
	 * Match a given address to the most accurate Destination
	 * @param string $country
	 * @param string $state
	 * @param string $zip
	 * @return object :: The matching destination
	 */
	public static function LoadMatching($country, $state, $zip) {
		//We may get id numbers instead of text strings so convert here
		if (is_numeric($country)) $country = Country::CodeById($country);
		if (is_numeric($state)) $state = State::CodeById($state);

		$arrDestinations = Destination::LoadByCountry($country);

		$objState = State::LoadByCode($state,$country);
		$zip = preg_replace('/[^A-Z0-9]/', '',strtoupper($zip));

		foreach ($arrDestinations as $objDestination) {
			if ($objDestination->state == null || $objDestination->state == $objState->id)
			{

				$zipStart = $objDestination->Zipcode1;
				$zipEnd = $objDestination->Zipcode2;

				if (($zipStart <= $zip && $zipEnd >= $zip) ||
					$zipStart=='' ||
					$zipStart=='*' ||
					$zip=='')
					return $objDestination;
			}
		}
		return false;
	}

	protected function beforeValidate() {
		$this->modified = new CDbExpression('NOW()');
		if ($this->base_charge=='') $this->base_charge = null;
		if ($this->ship_free=='') $this->ship_free = null;
		if ($this->ship_rate=='') $this->ship_rate = null;


		return parent::beforeValidate();
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Zipcode1':
				return preg_replace('/[^A-Z0-9]/', '',strtoupper($this->zipcode1));

			case 'Zipcode2':
				return preg_replace('/[^A-Z0-9]/', '',strtoupper($this->zipcode2));

			case 'countryname':
				return Country::CodeById($this->country);

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
}