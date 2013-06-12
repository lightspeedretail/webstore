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
	 * Load a state by row id, return Code (Country Abbreviation)
	 * @param $intId
	 * @return string
	 */
	public static function CodeById($intId) {
		$objCountry =  Country::model()->findByPk($intId);
		if ($objCountry instanceof Country)
			return $objCountry->code;
		else return "";
	}

	/**
	 * Extension of CodeById but will return "Any" with blank result
	 */
	public static function CodeByIdAny($intId) {
		return (self::CodeById($intId)=="" ? "Any" : self::CodeById($intId));

	}
	public static function IdByCode($strCode) {
		$objCountry =  Country::LoadByCode($strCode);
		if ($objCountry instanceof Country)
			return $objCountry->id;
		else return "";
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