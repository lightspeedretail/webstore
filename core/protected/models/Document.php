<?php

/**
 * This is the model class for table "{{document}}".
 *
 * @package application.models
 * @name Document
 *
 */
class Document extends BaseDocument
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Document the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Load Cart by the Id String (i.e. WO- number)
	 * @param $strIdStr
	 * @return array|CActiveRecord|mixed|null
	 */
	public static function LoadByIdStr($strIdStr) {
		return Document::model()->findByAttributes(array('order_str'=>$strIdStr));
	}

	public static function GetCartLastIdStr() {
		// Since id_str is a text field, we have to read in and strip out nonnumeric
		$intIdStr = Yii::app()->db->createCommand('SELECT SUBSTRING(order_str, 4)
                AS id_num
                FROM xlsws_document
                WHERE order_str LIKE "WO-%"
                ORDER BY (id_num + 0) DESC
                LIMIT 1;')->queryScalar();


		if (empty($intIdStr))
			return 0;
		else
			return $intIdStr;
	}


	/**
	 * Stripped down version of AddItemToCart for use with the SOAP uploader
	 */
	public function AddSoapProduct($intDocumentId,
	                               $objProduct,
	                               $intQty = 1, $strDescription = false,
	                               $fltSell = false, $fltDiscount = 0,
	                               $mixCartType = false, $intGiftItemId = 0) {

		if (!$mixCartType)
			$mixCartType = CartType::cart;


		$objItem = new DocumentItem();

		$objItem->qty = abs($intQty);

		if ($objProduct->id)
			$objItem->product_id = $objProduct->id;

		if(empty($strDescription))
			$strDescription = $objProduct->title;

		$objItem->cart_type = $mixCartType;
		$objItem->description = $strDescription;
		$objItem->gift_registry_item = $intGiftItemId;
		$objItem->sell = $fltSell;
		$objItem->sell_discount = $fltSell; //Discount comes in as 0 from LS, but we use this field for override price
		$objItem->sell_base = $fltSell;
		$objItem->sell_total = $objItem->sell_base*$objItem->qty;
		$objItem->code = $objProduct->OriginalCode;
		$objItem->discount = "";
		$objItem->datetime_added = new CDbExpression('NOW()');



		$objItem->document_id = $intDocumentId;
		if (!$objItem->save())
		{
			Yii::log("Failed to save soap document item ".print_r($objItem->getErrors(),true),'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		$this->UpdateDocument();

		return $objItem->id;
	}
	/**
	 * Perform all Cart Update mechanisms
	 * This is used to ensure that the Cart data remains consistent after
	 * additions and modifications of Products, updates to the Customer
	 * record and Tax Code.
	 */
	public function UpdateDocument()
	{
		$this->save();
		$this->refresh();


		$this->UpdateCountAndSubtotal();
		$this->UpdateTaxInclusive();
		$this->UpdateTaxExclusive();
		$this->UpdateCountAndSubtotal();
		$this->UpdateTotal();

		$this->save();
		$this->refresh();
	}


	/**
	 * Update Cart by setting taxes when in Tax Exclusive
	 */
	public function UpdateTaxExclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			return;

		// Reset taxes
		$this->tax1 = 0;
		$this->tax2 = 0;
		$this->tax3 = 0;
		$this->tax4 = 0;
		$this->tax5 = 0;

		// Get the rowid for "No Tax"
		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->lsid;

		// Dont want taxes, so return
		if (is_null($this->fk_tax_code_id) || $this->fk_tax_code_id == $intNoTax)
			return;

		foreach($this->documentItems as $objItem) {
			$taxes = $objItem->product->CalculateTax($this->fk_tax_code_id, $objItem->sell_total);

			$this->tax1 += $taxes[1];
			$this->tax2 += $taxes[2];
			$this->tax3 += $taxes[3];
			$this->tax4 += $taxes[4];
			$this->tax5 += $taxes[5];
		}
	}

	/**
	 * Update Cart by setting taxes when in Tax Inclusive
	 */
	public function UpdateTaxInclusive() {
		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') != '1')
			return;

		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		// Reset taxes
		$this->tax1 = 0;
		$this->tax2 = 0;
		$this->tax3 = 0;
		$this->tax4 = 0;
		$this->tax5 = 0;

		// Get the rowid for "No Tax"
		$objNoTax = TaxCode::GetNoTaxCode();
		$intNoTax = 999;
		if ($objNoTax) $intNoTax = $objNoTax->lsid;

		// Tax Inclusive && Want taxes, so return and set prices back to inclusive if needed
		if ($this->fk_tax_code_id != $intNoTax) {
			if (!$this->tax_inclusive) { //if the last destination was exclusive, and we have inclusive now, we need to reset the line items
				$this->tax_inclusive = true;
				foreach ($this->documentItems as $objItem) {
					// Set back tax inclusive prices
					$objItem->sell = $objItem->product->GetPrice($objItem->qty);
					$objItem->sell_base = $objItem->sell;
					$objItem->tax_in=true;
					$objItem->save();
				}
			}
			return;
		}

		$this->tax_inclusive = false;

		// Tax Inclusive && Don't want taxes
		foreach ($this->documentItems as $objItem) {
			// For quote to cart, we have to remove prices manually
			if ($objItem->cart_type == CartType::quote && $objItem->tax_in) {
				$taxes = $objItem->product->CalculateTax(
					TaxCode::GetDefault(), $objItem->Sell);

				// Taxes are deducted from cart for LightSpeed
				$this->tax1 -= $taxes[1];
				$this->tax2 -= $taxes[2];
				$this->tax3 -= $taxes[3];
				$this->tax4 -= $taxes[4];
				$this->tax5 -= $taxes[5];

				$objItem->sell -= round(array_sum($taxes), $TAX_DECIMAL);
				$objItem->sell_base = $objItem->Sell;
				$objItem->sell_total =  $objItem->sell * $objItem->qty;
				$objItem->tax_in=false;
				$objItem->save();
			}
			elseif ($objItem->cart_type = CartType::order && $objItem->tax_in) {
				// Set Tax Exclusive price
				$objItem->sell = $objItem->product->GetPrice($objItem->qty,true);
				$objItem->sell_base = $objItem->sell;
				$objItem->tax_in = false;
				$objItem->save();
			}
		}
	}
	/**
	 * Update Cart by counting products and setting the Subtotal
	 */
	public function UpdateCountAndSubtotal() {

		$this->item_count = 0;
		$this->subtotal = 0;

		foreach ($this->documentItems as $objItem) {
			$this->item_count += 1; //How many rows in cart_items
			$this->subtotal += $objItem->sell_total;
		}

	}

	/**
	 * Update Cart by setting the cart Total
	 */
	public function UpdateTotal() {
		$TAX_DECIMAL = _xls_get_conf('TAX_DECIMAL', 2);

		if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '0') == '1')
			$this->total = round($this->subtotal, $TAX_DECIMAL);
		else
			$this->total = round($this->subtotal, $TAX_DECIMAL) +
				round($this->tax1, $TAX_DECIMAL) +
				round($this->tax2, $TAX_DECIMAL) +
				round($this->tax3, $TAX_DECIMAL) +
				round($this->tax4, $TAX_DECIMAL) +
				round($this->tax5, $TAX_DECIMAL);
	}


	/**
	 * Return link for current cart
	 * @return string
	 */
	public function GenerateLink() {
		if (empty($this->linkid)) {
			$this->linkid = _xls_seo_url(_xls_truncate(_xls_encrypt(md5(date("YmdHis"))),31,''));
			$this->save();
			return $this->linkid;
		}
		else
			return $this->linkid;
	}

	public function getLink() {

		return Yii::app()->createAbsoluteUrl('cart/quote',array('code'=>$this->GenerateLink()));



	}


	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->datetime_cre = new CDbExpression('NOW()');
		$this->modified = new CDbExpression('NOW()');


		return parent::beforeValidate();
	}


}