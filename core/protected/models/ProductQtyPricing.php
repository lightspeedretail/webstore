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

	public function GetPrice($objTaxCode, $intTaxStatus, $taxExclusive = false)
	{
		if ($taxExclusive)
		{
			return $this->price;
		}
		elseif (_xls_get_conf('TAX_INCLUSIVE_PRICING', '') == '1')
		{
			$arrPrice = Tax::calculatePricesWithTax($this->price, $objTaxCode->id, $intTaxStatus);

			return $arrPrice['fltSellTotalWithTax'];
		}
		else
		{
			return $this->price;
		}
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