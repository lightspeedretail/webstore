<?php

/**
 * This is the model class for table "{{sro}}".
 *
 * @package application.models
 * @name Sro
 *
 */
class Sro extends BaseSro
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Sro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function LoadByLsId($strId)
	{
		return SRO::model()->findByAttributes(array('ls_id'=>$strId));
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


		$objItem = new SroItem();

		$objItem->qty = abs($intQty);

		if ($objProduct->id)
			$objItem->product_id = $objProduct->id;

		$objItem->cart_type = $mixCartType;
		$objItem->description = $strDescription;
		$objItem->sell = $fltSell;
		$objItem->sell_discount = $fltSell; //Discount comes in as 0 from LS, but we use this field for override price
		$objItem->sell_base = $fltSell;
		$objItem->sell_total = $objItem->sell_base*$objItem->qty;
		$objItem->code = $objProduct->OriginalCode;
		$objItem->discount = "";


		$objItem->sro_id = $intDocumentId;
		if (!$objItem->save())
		{
			Yii::log("Failed to save soap document item ".print_r($objItem->getErrors(),true),'error', 'application.'.__CLASS__.".".__FUNCTION__);
		}

		return $objItem->id;
	}


	/**
	 * Return link for current sro
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

	/**
	 * Make/Return link for current sro
	 * @return string
	 */
	public function getLink() {

		return Yii::app()->createAbsoluteUrl('sro/view',array('code'=>$this->GenerateLink()));

	}

	/**
	 * Since Validate tests to make sure certain fields have values, populate requirements here such as the modified timestamp
	 * @return boolean from parent
	 */
	protected function beforeValidate() {
		if ($this->isNewRecord)
			$this->datetime_cre = new CDbExpression('NOW()');
		$this->datetime_mod = new CDbExpression('NOW()');


		return parent::beforeValidate();
	}


}