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
		return TaxCode::model()->tax_total()->notax()->find();
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

	/**
	 * Find the tax code associated with the provided address
	 *
	 * When the shipping country is empty, the Store Default tax code is used.
	 * This is generally used before an address is entered and for store
	 * pickup.
	 *
	 * If the provided address is not matched to any destination, the tax code
	 * for ANY/ANY is used.
	 *
	 * @param mixed $shippingCountry The 2-letter country code for the country or the country ID.
	 * @param mixed $shippingState The 2-letter code for the state or the state ID.
	 * @param string $shippingPostal The postal code with all spaces removed.
	 * @return TaxCode|null  The taxcode object, or null if no corresponding tax code.
	 * @throws CException If tax destinations are not configured.
	 */
	public static function getTaxCodeByAddress(
		$shippingCountry,
		$shippingState,
		$shippingPostal
	)
	{
		if (empty($shippingCountry))
		{
			// Without a shipping country, we use the default tax code.
			// This is only likely to occur for store pickup.
			return static::getDefault();
		}

		// Calculate tax since that may change depending on shipping address.
		Yii::log(
			sprintf(
				"Attempting to match with a defined Destination to Country/State/Postal %s/%s/%s",
				$shippingCountry,
				$shippingState,
				$shippingPostal
			),
			'info',
			'application.'.__CLASS__.".".__FUNCTION__
		);

		$objDestination = Destination::LoadMatching(
			$shippingCountry,
			$shippingState,
			$shippingPostal
		);

		if ($objDestination === null)
		{
			Yii::log('Destination not matched, going with default (Any/Any)', 'info', 'application.'.__CLASS__.".".__FUNCTION__);
			$objDestination = Destination::getAnyAny();
		}

		if ($objDestination === null)
		{
			// TODO: We shoudn't need to check for this here, since it should be a
			// configuration error to not have a tax destination configured.
			throw new CException(
				Yii::t(
					'checkout',
					'Website configuration error. No tax destinations have been defined by the Store Administrator. Cannot continue.'
				)
			);
		}

		Yii::log(
			'Matched Destination destination.id='.$objDestination->id.' to tax code destination.taxcode='.$objDestination->taxcode,
			'info',
			'application.'.__CLASS__.'.'.__FUNCTION__
		);

		return static::LoadByLS($objDestination->taxcode);
	}
}
