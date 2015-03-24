<?php

/**
 * This is the model class for table "{{tax}}".
 *
 * @package application.models
 * @name Tax
 *
 */
class Tax extends BaseTax
{

	const TXIN_STORE_TXIN_CUST = 1;
	const TXIN_STORE_TXOUT_CUST = 2;
	const TXOUT_STORE = 3;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Tax the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function __toString() {
		return sprintf('Tax Object %s',  $this->lsid);
	}


	/**
	 * Calculate taxes given a pre-tax price, the lsid of the TaxCode and the lsid of the TaxStatus.
	 * The returned array looks like this:
	 * Array(
	 *   [fltSellTotalWithTax] => <float>
	 *   [arrTaxValues] => Array(
	 *            [1] => <float>
	 *            [2] => <float>
	 *            [3] => <float>
	 *            [4] => <float>
	 *            [5] => <float>
	 *   )
	 *   [arrTaxRates] => Array(
	 *            [1] => <float>
	 *            [2] => <float>
	 *            [3] => <float>
	 *            [4] => <float>
	 *            [5] => <float>
	 *   )
	 * )
	 *
	 * fltSellTotalWithTax is the sum of the pre-tax value and all members of arrTaxValues
	 * arrTaxValues is an array of values for each tax calculated according to its corresponding rate and the pre-tax value
	 * arrTaxRate is an array of tax rates which are defined by the TaxCode
	 *
	 * @param $fltSellTotal
	 * @param $intTaxCodeId
	 * @param $intTaxStatusId
	 * @return array
	 */
	public static function calculatePricesWithTax($fltSellTotal, $intTaxCodeId, $intTaxStatusId)
	{
		static $objTaxes; // Cached for better performance

		$fltSellTotalTaxed = $fltSellTotal;
		$arrTaxAmount = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
		$arrTaxRates = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
		$objTaxCode = TaxCode::model()->findByAttributes(array('lsid' => $intTaxCodeId));

		if (is_null($objTaxCode))
		{
			if (!is_null($intTaxCodeId)) //Ignore null at this stage
			{
				Yii::log("Unknown tax code passed: $intTaxCodeId", 'error', 'application.'.__CLASS__.".".__FUNCTION__);
			}

			return array(
				'fltSellTotalWithTax' => $fltSellTotalTaxed,
				'arrTaxValues' => $arrTaxAmount,
				'arrTaxRates' => $arrTaxRates
			);
		}

		$objTaxStatus = TaxStatus::model()->findByAttributes(array('lsid' => $intTaxStatusId));

		//For LS Cloud integration, see if we can find the default Tax Status
		if (is_null($objTaxStatus) && Yii::app()->params['LIGHTSPEED_CLOUD'] > 0)
		{
			$objTaxStatus = TaxStatus::getCloudDefault();
		}

		if (is_null($objTaxes))
		{
			$objTaxes = Tax::model()->findAll();
		}

		$taxtypes = 5; // Number of taxes in LS

		// for each exempt, reset the corresponding rate to 0
		if ($objTaxStatus instanceof TaxStatus)
		{
			if ($objTaxStatus->tax1_status == 1) $objTaxCode->tax1_rate = 0;
			if ($objTaxStatus->tax2_status == 1) $objTaxCode->tax2_rate = 0;
			if ($objTaxStatus->tax3_status == 1) $objTaxCode->tax3_rate = 0;
			if ($objTaxStatus->tax4_status == 1) $objTaxCode->tax4_rate = 0;
			if ($objTaxStatus->tax5_status == 1) $objTaxCode->tax5_rate = 0;
		}

		$i = 0;
		foreach ($objTaxes as $objTax)
		{
			$strRate = "tax" . ($i + 1) . "_rate";

			if ($objTax->compounded)
			{
				$fltTaxAmount = $fltSellTotalTaxed * ($objTaxCode->$strRate / 100);
			}
			else
			{
				$fltTaxAmount = $fltSellTotal * ($objTaxCode->$strRate / 100);
			}

			if (($objTax->max_tax > 0) && ($fltTaxAmount >= $objTax->max_tax))
			{
				$fltTaxAmount = $objTax->max_tax;
			}

			$arrTaxAmount[$i + 1] = $fltTaxAmount;
			$arrTaxRates[$i + 1] = $objTaxCode->$strRate;

			$fltSellTotalTaxed = $fltSellTotalTaxed + $fltTaxAmount;

			$i++;
			if ($i >= $taxtypes)
			{
				$i = $taxtypes;
			}
		}

		return array(
			'fltSellTotalWithTax' => round($fltSellTotalTaxed, 2, PHP_ROUND_HALF_UP),
			'arrTaxValues' => $arrTaxAmount,
			'arrTaxRates' => $arrTaxRates
		);
	}

	/**
	 * Removes the tax from a tax inclusive price. Since our TaxIn prices are generated from our
	 * default tax code, we can use this to undo the tax
	 * @param $fltPrice
	 * @return float
	 */
	public static function StripTaxesFromPrice($fltSellTotal,$intTaxStatusId) {

		static $objTaxes; // Cached for better performance

		$fltSellTotalTaxed = $fltSellTotal;
		$arrTaxAmount = array(1=>0 , 2=>0 , 3=>0 , 4=> 0 , 5=>0);
		$intTaxCodeId = 0;

		$objTaxCodes = TaxCode::model()->findAll(array('order'=>'list_order')); //Default tax code is first in list
		$objTaxCode = $objTaxCodes[0];

		if(!$objTaxCode) {
			if(!is_null($intTaxCodeId)) //Ignore null at this stage
				Yii::log("Unknown tax code passed: $intTaxCodeId", 'error', 'application.'.__CLASS__.".".__FUNCTION__);

			return array($fltSellTotalTaxed , $arrTaxAmount);
		}

		if($intTaxStatusId instanceof TaxStatus)
			$objTaxStatus = $intTaxStatusId;
		elseif($intTaxStatusId >= 0)
			$objTaxStatus = TaxStatus::model()->findByAttributes(array('lsid'=>$intTaxStatusId));
		else
			$objTaxStatus = false;

		if(!$objTaxes)
			$objTaxes = Tax::model()->findAll();

		$taxtypes = 5; // Number of taxes in LS

		// for each exempt, reset the code to 0
		if($objTaxStatus) {
			if($objTaxStatus->tax1_status) $objTaxCode->tax1_rate = 0;
			if($objTaxStatus->tax2_status) $objTaxCode->tax2_rate = 0;
			if($objTaxStatus->tax3_status) $objTaxCode->tax3_rate = 0;
			if($objTaxStatus->tax4_status) $objTaxCode->tax4_rate = 0;
			if($objTaxStatus->tax5_status) $objTaxCode->tax5_rate = 0;
		}

		$i = 0;
		foreach($objTaxes as $objTax) {
			$strRate = "tax" . ($i+1) . "_rate";

			if ($objTaxCode->$strRate>0)
				if($objTax->compounded)
					$fltTaxAmount = $fltSellTotalTaxed / ($objTaxCode->$strRate/100);
				else {
					$fltOrig = $fltSellTotal / (1+($objTaxCode->$strRate/100));
					$fltTaxAmount = round($fltSellTotal - $fltOrig,2);
				}
			else $fltTaxAmount=0;

			if(($objTax->max_tax > 0) && ($fltTaxAmount >= $objTax->max_tax))
			$fltTaxAmount = $objTax->max_tax;
			$arrTaxAmount[$i+1] = $fltTaxAmount;

			$fltSellTotalTaxed = $fltSellTotalTaxed - $fltTaxAmount;

			$i++;
			if($i >= $taxtypes) $i = $taxtypes;
		}

		return $fltSellTotalTaxed;
	}

	public static function LoadByLS($intId)
	{
		return Tax::model()->findByAttributes(array('lsid'=>$intId));
	}


	/**
	 * Return the name of the tax given its lsid
	 *
	 * @param $intLsId
	 * @return mixed|null
	 */
	public static function TaxByLsid($intLsId)
	{
		$obj = Tax::LoadByLS($intLsId);
		if ($obj instanceof Tax === true)
		{
			return $obj->tax;
		}
		else
		{
			Yii::log(sprintf('Tax with lsid: %s not found', $intLsId), 'error', 'application.'.__CLASS__.'.'.__FUNCTION__);
			return null;
		}
	}

	public function __get($strName) {
		switch ($strName) {
		case 'Tax':
			return trim(parent::__get('tax'));

		default:
			try {
				return (parent::__get($strName));
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}


}