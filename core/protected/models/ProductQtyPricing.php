<?php

/**
 * This is the model class for table "{{product_qty_pricing}}".
 *
 * @package application.models
 * @name ProductQtyPricing
 *
 */
class ProductQtyPricing extends BaseProductQtyPricing
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ProductQtyPricing the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// Default "to string" handler
	public function __toString() {
		return sprintf('ProductQtyPricing Object %s',  $this->product_id." ".$this->pricing_level." ".$this->qty);
	}

	public function GetPrice($intTaxCode, $intTaxStatus,
		$taxExclusive = false) {

		if ($taxExclusive)
			return $this->price;
		else if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '') == '1') {
			$arrPrice = Tax::CalculatePricesWithTax(
				$this->price, $intTaxCode, $intTaxStatus);
			$intPrice = round($arrPrice[0], 2);
			return $intPrice;
		}
		else
			return $this->price;
	}

	public function __get($strName) {
		switch ($strName) {
		case 'Price':
			return $this->GetPrice(
				TaxCode::GetDefault(),
				$this->product->tax_status_id
			);
		case 'PriceExclusive':
			return $this->GetPrice(
				TaxCode::GetDefault(),
				$this->product->tax_status_id,
				true
			);
		default:
			return parent::__get($strName);
		}
	}

}