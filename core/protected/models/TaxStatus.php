<?php

/**
 * This is the model class for table "{{tax_status}}".
 *
 * @package application.models
 * @name TaxStatus
 *
 */
class TaxStatus extends BaseTaxStatus
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TaxStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// String representation of this object
	public function __toString() {
		return sprintf('TaxStatus Object %s',  $this->intRowid);
	}

	public static function GetNoTaxStatus() {
		foreach (TaxStatus::model()->FindAll() as $objTax)
			if ($objTax->IsNoTax())
				return $objTax;
		return;
	}

	public function IsNoTax() {
		if (strtolower($this->status) == 'no tax' or
			strtolower($this->status) == 'exempt')
			return true;

		for ($i = 1; $i <= 5; $i++) {
			$strField = 'tax' . $i . '_status';

			if ($this->$strField == '0')
				return false;
		}

		return true;
	}

	public static function LoadByLS($intId)
	{
		return TaxStatus::model()->findByAttributes(array('lsid'=>$intId));
	}

	public static function getCloudDefault()
	{
		$objStatus = TaxStatus::model()->findByAttributes(array(
			'status'=>'Default',
			'tax1_status'=>'0',
			'tax2_status'=>'1',
			'tax3_status'=>'1',
			'tax4_status'=>'1',
			'tax5_status'=>'1'
		));
		if(is_null($objStatus))
			return false;
		else return $objStatus;
	}

}