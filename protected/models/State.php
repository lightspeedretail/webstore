<?php

/**
 * This is the model class for table "{{state}}".
 *
 * @package application.models
 * @name State
 *
 */
class State extends BaseState
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return State the static model class
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
		return array_merge(parent::attributeLabels(), array(
			'state' => 'State/Region',
		));
	}
	/**
	 * @param $intRowid
	 * @return CActiveRecord
	 */
	public static function Load($intRowid) {
		return State::model()->findByPk($intRowid);
	}

	/**
	 * @param $strCode
	 * @param null $intCountryId
	 * @return CActiveRecord
	 */
	public static function LoadByCode($strCode, $intCountryId = null) {
		if (is_null($intCountryId)) $intCountryId=_xls_get_conf('DEFAULT_COUNTRY',224);
		if (!is_numeric($intCountryId)) $intCountryId = Country::IdByCode($intCountryId);
		return State::model()->find('code=:code_id AND country_id=:c_id',	array(':code_id'=>$strCode,':c_id'=>$intCountryId));
	}

	/**
	 * Load a state by row id, return Code (state abbreviation)
	 * @param $intId
	 * @return string
	 */
	public static function CodeById($intId) {
		$objState =  State::model()->findByPk($intId);
		if ($objState instanceof State)
			return $objState->code;
		else return "";
	}

	/**
	 * Extension of CodeById but will return "Any" with blank result
	 */
	public static function CodeByIdAny($intId) {
		return (self::CodeById($intId)=="" ? "Any" : self::CodeById($intId));

	}
	/**
	 * Return the default sorting order clause
	 * @return array
	 */
	public static function GetDefaultOrdering($sql = false) {
		if ($sql) return "order by sort_order, state";
			else return array('order'=>'sort_order, state');

	}

	/**
	 * @return array
	 */
	public static function getStatesForTaxes($countryId=null)
	{

		$arrList['0']="Any";

		if (is_null($countryId)) return $arrList;

		if ($countryId=="0")
			$arrList = $arrList + CHtml::listData(  State::model()->findAllByAttributes(
				array('active'=>1),array('order'=>'sort_order,state')), 'id', 'state');
		else
			$arrList = $arrList + CHtml::listData(  State::model()->findAllByAttributes(
				array('active'=>1,'country_id'=>$countryId),array('order'=>'sort_order,state')), 'id', 'state');

		return $arrList;


	}


	protected function beforeValidate() {
		$this->country_code = Country::CodeById($this->country_id);

		return parent::beforeValidate();
	}

}