<?php

/**
 * This is the model class for table "{{country}}".
 *
 * @package application.models
 * @name Country
 *
 */
class Country extends BaseCountry
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Country the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function Load($intRowid) {
		return Country::model()->findByPk($intRowid);
	}

	public static function LoadByCode($strCode) {
		return Country::model()->find('code=:code_id',	array(':code_id'=>$strCode));
	}

	/**
	 * Extension of CodeById but will return "Any" with blank result
	 */
	public static function CodeByIdAny($intId) {
		return (self::CodeById($intId)=="" ? "Any" : self::CodeById($intId));

	}

	/**
	 * Get the country code given the country ID.
	 * Returns an empty string if the code is not found.
	 * @param integer $intCountryId The country ID.
	 * @return string|null The country code.
	 * TODO Update this to return null instead of empty string.
	 */
	public static function CodeById($intCountryId) {
		$objCountry = Country::model()->findByPk($intCountryId);
		if ($objCountry instanceof Country)
			return $objCountry->code;

		return '';
	}

	/**
	 * Get the country ID given the country code.
	 * Returns an empty string if the code is not found.
	 * @param string $strCode The country code.
	 * @return integer|string The country Id.
	 * TODO Update this to return null instead of empty string.
	 */
	public static function IdByCode($strCode) {
		$objCountry = Country::LoadByCode($strCode);
		if ($objCountry instanceof Country)
			return $objCountry->id;

		return '';
	}

	public static function CountryById($intId)
	{
		$objCountry =  Country::model()->findByPk($intId);
		if ($objCountry instanceof Country)
			return $objCountry->country;
		else return '';
	}

	/**
	 * Return the country name (e.g. "Canada") given the country code (e.g. "CA").
	 * @param string $strCode The country code.
	 * @return The country name.
	 */
	public static function CountryByCode($strCode)
	{
		$objCountry = Country::LoadByCode($strCode);
		if ($objCountry instanceof Country)
			return $objCountry->country;

		else return '';
	}

	public static function getAdminRestrictionList($blnAddContinental=false)
	{


		$ret['null']='Everywhere (no restriction)';
		$ret[self::CodeById(_xls_get_conf('DEFAULT_COUNTRY'))]='My Country ('. self::CodeById(_xls_get_conf('DEFAULT_COUNTRY')).')';
		$ret['NORAM']='North America (US/CA)';
		if (self::CodeById(_xls_get_conf('DEFAULT_COUNTRY'))=="US")
			$ret['CUS']='Continental US';
		if (
			self::CodeById(_xls_get_conf('DEFAULT_COUNTRY'))=="AU" ||
			self::CodeById(_xls_get_conf('DEFAULT_COUNTRY'))=="NZ"

		)
			$ret['AUNZ']='Australia/New Zealand';

		$ret['OUTSIDE']='Only outside of '.self::CodeById(_xls_get_conf('DEFAULT_COUNTRY'));

		return $ret;

	}

	/**
	 * @return array
	 */
	public static function getCountriesForTaxes($blnIncludeDelete = true) {

		if ($blnIncludeDelete) $arrList['DELETE']="DELETE THIS ENTRY";
		$arrList['0']="Any";
		$arrList = $arrList + CHtml::listData(Country::model()->findAllByAttributes(array('active'=>1),array('order'=>'sort_order,country')), 'id', 'country');

		return $arrList;
	}

	/**
	 * Get the list of countries that can be shipped to.
	 * @return CActiveRecord[] An array of countries.
	 */
	public static function getShippingCountries() {
		$criteria = new CDbCriteria();
		$criteria->select = "t1.id,t1.code,t1.country";
		$criteria->alias = "t1";
		$criteria->compare('active','1');
		$criteria->order = "sort_order,t1.country";

		if (Yii::app()->params['SHIP_RESTRICT_DESTINATION'])
		{
			$criteria->join =
				"JOIN " .
				Destination::model()->tableName() .
				" ON `" .
				Destination::model()->tableName() .
				"`.`country`  = `t1`.`id`";
		}

		return Country::model()->findAll($criteria);
	}


	/**
	 * Return a list of countries with the passed
	 * country code as the first option
	 *
	 * @param $code
	 * @return array|CActiveRecord[]
	 */
	public static function sortShippingCountries($code)
	{
		if (is_null($code) === true)
		{
			$code = _xls_country();
		}

		$arrCountry = self::getShippingCountries();

		$objMatchingCountry = null;

		foreach ($arrCountry as $key => $country)
		{
			if ($country->code === $code)
			{
				$objMatchingCountry = $country;
				unset($arrCountry[$key]);
				break;
			}
		}

		if (is_null($objMatchingCountry) === true)
		{
			// something has gone wrong
			Yii::log(sprintf('Country with code %s not found in list', $code), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return $arrCountry;
		}

		return array_merge(array($objMatchingCountry), $arrCountry);
	}

	/**
	 * Get the available shipping states for a given country.
	 *
	 * Respects SHIP_RESTRICT_DESTINATION - if this configuration is enable
	 * then only the states that are defined will be returned.
	 *
	 * @param string $intCountryId The ID of the country.
	 * @return string[] An associative array mapping the state ID to the state
	 * code.
	 */
	public static function getCountryShippingStates($intCountryId) {
		$noStatesAvailable = array(0 => 'n/a');

		if (is_null($intCountryId))
		{
			$intCountryId = _xls_get_conf('DEFAULT_COUNTRY', 224);
		}

		$criteria = new CDbCriteria();
		$criteria->alias = 'state';
		$criteria->select = 'state.id, state.code';
		$criteria->addCondition('country_id = :country_id');
		$criteria->addCondition('active = 1');
		$criteria->order = 'state.sort_order, state.state';

		// Check for 'only ship to defined destinations'.
		if (Yii::app()->params['SHIP_RESTRICT_DESTINATION'])
		{
			// If we have an entry for the country in xlsws_destination with a
			// null state, that means that the destination is defined for the
			// entire country so we don't need to restrict by state.
			// That's because state IS NULL corresponds to State=ANY.
			$destinations = Destination::model()->findAll(
				'country=:country_id AND state IS NULL',
				array('country_id' => $intCountryId)
			);

			// If there are no results then we don't have a destination for the
			// country as a whole so we restrict further by state.
			if (count($destinations) === 0)
			{
				// Filter the results further by state destinations.
				$criteria->join = 'JOIN xlsws_destination ON (xlsws_destination.state = state.id)';
			}
		}

		$criteria->params[':country_id'] = $intCountryId;
		$states = State::model()->findAll($criteria);

		if (count($states) === 0)
		{
			return $noStatesAvailable;
		}

		return CHtml::listData($states, 'id', 'code');
	}

	/**
	 * Very specific function meant to return a specifically structured array.
	 * When called, it will populate the <option> values in the country dropdown
	 * with a 'code' attribute that will be read by jquery and used in the api
	 * request for the dynamic city and state population feature.
	 *
	 * @return mixed
	 */
	public static function getCountryCodes()
	{
		$models = Country::model()->findAll();
		$arr = array();

		foreach ($models as $model)
		{
			$arr[$model->id] = array('code' => $model->code);
		}

		return $arr;
	}


	/**
	 * @return null
	 */
	protected function noCountryForOldMen() {

		$comment = "Wasn't that a disturbing movie?";

		return null;
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
		$criteria->compare('code',$this->code,true);
		$criteria->compare('country',$this->country,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
