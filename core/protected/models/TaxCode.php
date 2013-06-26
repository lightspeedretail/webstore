<?php

/**
 * This is the model class for table "{{tax_code}}".
 *
 * @package application.models
 * @name TaxCode
 *
 */
class TaxCode extends BaseTaxCode
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TaxCode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/* Define some specialized query scopes to make searching for specific db info easier */
	public function scopes()
	{
		return array(
			'tax_total'=>array(
				'select'=>'*,tax1_rate+tax2_rate+tax3_rate+tax4_rate+tax5_rate as taxtotal_rate',
			),
			'active'=>array(
				'condition'=>'id>-1',
			),
			'notax'=>array(
				'condition'=>'(tax1_rate+tax2_rate+tax3_rate+tax4_rate+tax5_rate)=0',
			),
			'default_tax'=>array(
				'order'=>'list_order, lsid',
				'limit'=>1,
			),
		);
	}

	// String representation of the object
	public function __toString() {
		return sprintf('TaxCode Object %s',  $this->intRowid);
	}

	/**
	 * Get the default tax code (always appears first sequentially)
	 * @return $objTax the tax object
	 */
	public static function GetDefault() {
		$objTax = TaxCode::model()->default_tax()->find();
		return $objTax;
	}
	/**
	 * Get the default tax code (always appears first sequentially)
	 * @return $objTax the tax object
	 */
	public static function getDefaultCode() {
		$objTax = TaxCode::model()->default_tax()->find();
		return $objTax->lsid;
	}


	/**
	 * Get the tax code defined as NoTax code (evaluated by all zeroes)
	 * @return $objTax the tax object
	*/
	public static function GetNoTaxCode() {
		$objTax = TaxCode::model()->tax_total()->notax()->find();
			if ($objTax)
				return $objTax;
		return;
	}

	/**
	 * Evaluate if the currently loaded tax is a No Tax code. Can be true by name or values
	 * @return bool
	 */
	public function IsNoTax() {

		$total = $this->tax1_rate +
			$this->tax2_rate +
			$this->tax3_rate +
			$this->tax4_rate +
			$this->tax5_rate;

		if ($total == 0)
			return true;

		if (strtolower($this->code) == 'no tax')
			return true;

		if (strtolower($this->code) == 'notax')
			return true;

		return false;
	}

	public static function LoadByLS($intId)
	{
		return TaxCode::model()->findByAttributes(array('lsid'=>$intId));
	}

	public static function VerifyAnyDestination() {

		$objAnyDest = Destination::model()->findByAttributes(array('country'=>null,'state'=>null));

		if (!($objAnyDest instanceof Destination)) {
			$objTax = TaxCode::GetNoTaxCode();
			if ($objTax) {
				$objNewAny = new Destination;
				$objNewAny->country=null;
				$objNewAny->state=null;
				$objNewAny->zipcode1='';
				$objNewAny->zipcode2='';
				$objNewAny->taxcode=$objTax->lsid;
				$objNewAny->save();
			}
		}


	}
}