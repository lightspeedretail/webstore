<?php

/**
 * This is the model class for table "{{promo_code}}".
 *
 * @package application.models
 * @name PromoCode
 *
 */
class PromoCode extends BasePromoCode
{
	const Percent = 1;
	const Currency = 0;

	//Except - 0=All items must qualify  1=No Items must qualify  2=At least one item
	const QUALIFY_ALL_ITEMS = 0;
	const QUALIFY_NO_ITEMS = 1;
	const QUALIFY_MIN_ONE_ITEM = 2;


	/**
	 * Returns the static model of the specified AR class.
	 * @return PromoCode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	// String representation of the Object
	public function __toString() {
		return sprintf('Promo Code Object %s',  $this->code);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code', 'required', 'on'=>'checkout'),
			array('amount', 'required', 'on'=>'create'),
			array('code', 'validatePromocode', 'on'=>'checkout'),
			array('enabled, exception, type, qty_remaining', 'numerical', 'integerOnly'=>true, 'on'=>'create'),
			array('code,amount,type', 'required', 'on'=>'create'),
			array('amount, threshold', 'numerical', 'on'=>'create'),
			array('code', 'length', 'max'=>255),
			array('enabled,exception,amount,type,valid_from, valid_until, threshold,qty_remaining,lscodes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('code,enabled,amount', 'safe', 'on'=>'search'),
			array('id, enabled, exception, code, type, amount, valid_from, qty_remaining, valid_until, lscodes, threshold', 'safe', 'on'=>'search,searchAllButShipping'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'enabled' => 'Active',
			'exception' => 'Exception',
			'code' => 'Promo Code',
			'type' => 'Type',
			'amount' => 'Discount Amount',
			'valid_from' => 'Valid From (optional)',
			'qty_remaining' => '# Uses Remain',
			'valid_until' => 'Valid Until (optional)',
			'lscodes' => 'Restrictions',
			'threshold' => 'Good Above $',
		);
	}
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchAllButShipping()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition('module IS NULL');

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'id DESC',
			),
			'pagination' => array(
				'pageSize' => 15,
			),
		));


	}

	/**
	 * Test to see if Promo Code is valid, used by Model validation. Note this doesn't mean that all products will use it,
	 * just that at least item in the cart (or free shipping) will accept the code. If this function returns true,
	 * we still have to Apply the code to the cart which will determine which items actually get the discount.
	 * @param $attribute
	 * @param $param
	 */
	public function validatePromocode($attribute,$param) {

		$strCode = $this->$attribute;
		if ($strCode=='') return;

		$objPromoCode = PromoCode::LoadByCode($strCode);

		if (!$objPromoCode) {
			$this->addError($attribute,Yii::t('global','Promo Code is invalid.'));
			return;
		}

		if (!$objPromoCode->enabled) {
			$this->addError($attribute,Yii::t('global','Promo Code is invalid'));
			return;
		}


		$strLabel = Yii::t('global','Promo Code');
		if ($objPromoCode->Shipping)
			$strLabel = Yii::t('global','Free Shipping');


		//If start date is defined, have we reached it yet
		if (!$objPromoCode->Started) {
			$this->addError($attribute,Yii::t('global','{label} is not active yet.',array('{label}'=>$strLabel)));
			return;

		}

		//If end date is defined or remaining uses
		if ($objPromoCode->Expired || !$objPromoCode->HasRemaining) {
			$this->addError($attribute,Yii::t('global','{label} has expired or has been used up.',array('{label}'=>$strLabel)));
			return;
		}


		//Minimum price threshold


		if (!is_null($objPromoCode->threshold))
			if ($objPromoCode->Threshold > Yii::app()->shoppingcart->subtotal) {
				$this->addError($attribute,Yii::t('global','{label} only valid when your purchases total at least {amount}.',
					array('{label}'=>$strLabel,'{amount}'=>_xls_currency($objPromoCode->threshold))));
				return;
			}

		//If this is for shipping, we need to make sure all items in the cart qualify
		if ($objPromoCode->Shipping) {
			//Test our two extremes -- all items or no items. The IsProductAffected() takes care of the reverse logic for No Items
			if ($objPromoCode->exception==PromoCode::QUALIFY_ALL_ITEMS || $objPromoCode==PromoCode::QUALIFY_NO_ITEMS)
			{
				$bolApplied = true;	//We start with true because we want to make sure we don't have a disqualifying item in our cart

				foreach (Yii::app()->shoppingcart->cartItems as $objItem)
					if (!$objPromoCode->IsProductAffected($objItem)) $bolApplied=false;
			}

			//Test for just one qualifying item
			if ($objPromoCode->exception==PromoCode::QUALIFY_MIN_ONE_ITEM)
			{
				$bolApplied = false;
				foreach (Yii::app()->shoppingcart->cartItems as $objItem)
					if ($objPromoCode->IsProductAffected($objItem)) $bolApplied=true;
			}

			if ($bolApplied==false) {
				$this->addError($attribute,Yii::t('yii','We are sorry, but one or more of the items in your cart cannot be used with {label}.',array('{label}'=>$strLabel)));
				return;
			}


		} else { //else for regular promo codes, see if any items in the cart match qualify for this promo code


			$bolApplied = false;
			foreach (Yii::app()->shoppingcart->cartItems as $objItem) {
				if ($objPromoCode->IsProductAffected($objItem))
					$bolApplied = true;
			}

			//If we have reached this point and $bolApplied is still false, none of our items qualify
			if (!$bolApplied) {
				$this->addError($attribute,Yii::t('yii','We are sorry, but one or more of the items in your cart cannot be used with {label}.',array('{label}'=>$strLabel)));
				return;
			}
		}

	}


	protected function IsActive() {
		if ($this->IsEnabled() &&
			$this->IsStarted() &&
			!$this->IsExpired() &&
			$this->HasRemaining())
			return true;
		return false;
	}

	protected function HasRemaining() {
		// Above 0 we have some left, below 0 is unlimited
		if (!is_null($this->qty_remaining) && $this->qty_remaining == 0)
			return false;
		return true;
	}

	protected function IsEnabled() {
		if ($this->enabled)
			return true;
		return false;
	}

	public function IsExcept() {
		if ($this->exception==1)
			return true;
		return false;
	}

	protected function IsStarted() {
		if ($this->valid_from=="" || date("Y-m-d")>=date("Y-m-d",strtotime($this->valid_from)))
			return true;
		return false;
	}

	protected function IsExpired() {
		if ($this->valid_until != "" && date("Y-m-d",strtotime($this->valid_until))<date("Y-m-d"))
			return true;
		return false;
	}


	protected function IsShipping() {
		if($this->module == "freeshipping")
				return true;
		return false;
	}

	public function IsProductAffected($objItem) {

		$arrCode = unserialize(strtolower(serialize($this->LsCodeArray)));
		if (empty($arrCode)) //no product restrictions
			return true;



		$boolReturn = false;

		foreach($arrCode as $strCode) {
			$strCode=strtolower($strCode);

			if (isset($objItem->product->family) && substr($strCode, 0,7) == "family:" &&
				trim(substr($strCode,7,255)) == strtolower($objItem->product->family->family))
				$boolReturn = true;

			if (isset($objItem->product->class) && substr($strCode, 0,6) == "class:" &&
				trim(substr($strCode,6,255)) == strtolower($objItem->product->class->class_name))
				$boolReturn = true;

			if (substr($strCode, 0,8) == "keyword:") {
				$productTags = ProductTags::model()->findAllByAttributes(array('product_id'=>$objItem->product->id));
				$strKeyword = trim(substr($strCode,8,255));
				foreach ($productTags as $tag)
					if ($tag->tag->tag==$strKeyword)
						$boolReturn = true;
			}


			if (substr($strCode, 0,9) == "category:") {
				$arrTrail = Category::GetTrailByProductId($objItem->product->id,'names');
				$strTrail = implode("|",$arrTrail);

				$strCompareCode = trim(substr($strCode,9,255));
				if ($strCompareCode == strtolower(substr($strTrail,0,strlen($strCompareCode))))
					$boolReturn = true;
			}

		}

		if (_xls_array_search_begin(strtolower($objItem->code), $arrCode))
			$boolReturn = true;

		//We normally return true if it's a match. If this code uses Except, then the logic is reversed
		if ($this->IsExcept())
			$boolReturn = ($boolReturn == true ? false : true);

		return $boolReturn;
	}

	/**
	 * Load a PromoCode from code
	 * @param string $strCode
	 * @return PromoCode
	 */
	public static function LoadByCode($strCode) {
		return PromoCode::model()->findByAttributes(array('code'=>$strCode));

	}

	/**
	 * Load a PromoCode from code for Shipping Promo Code
	 * Separated from other types of promo codes
	 * @param string $strCode
	 * @return PromoCode
	 */
	public static function LoadByShipping($className) {

		return PromoCode::model()->find(array(
			'condition'=>'module=:shipcode',
			'params'=>array(':shipcode'=>$className),
		));

	}
	/**
	 * Load a PromoCode from code for Shipping Promo Code
	 * Separated from other types of promo codes
	 * @param string $strCode
	 * @return PromoCode
	 */
	public static function LoadByCodeShipping($strCode) {

		return PromoCode::model()->find(array(
			'condition'=>'code=:code AND lscodes LIKE :shipcode',
			'params'=>array(':code'=>$strCode, ':shipcode'=>"shipping:,%"),
		));

	}

	/**
	 * Delete all Shipping PromoCodes
	 * @return void
	 */
	public static function DeleteShippingPromoCodes() {

		PromoCode::model()->deleteAll("lscodes LIKE 'shipping:,%'");
	}

	public static function DisableShippingPromoCodes() {

		PromoCode::model()->updateAll(array('enabled'=>0),"lscodes LIKE 'shipping:,%'");

	}

	public static function EnableShippingPromoCodes() {
		PromoCode::model()->updateAll(array('enabled'=>1),"lscodes LIKE 'shipping:,%'");
	}



	public function __get($strName) {
		switch ($strName) {
			case 'Code':
				return $this->code;

			case 'LsCodeArray':
				if (is_null($this->lscodes)) return array();
				$arrCodes = explode(",", $this->lscodes);
				array_walk($arrCodes, '_xls_trim');
				return $arrCodes;

			case 'Active':
				return $this->IsActive();

			case 'Enabled':
				return $this->IsEnabled();

			case 'Except':
			case 'Exception':
				return $this->IsExcept();

			case 'HasRemaining':
				return $this->HasRemaining();

			case 'Started':
				return $this->IsStarted();

			case 'Expired':
				return $this->IsExpired();

			case 'Shipping':
				return $this->IsShipping();


			case 'Threshold':
				if (!is_null($this->threshold))
					return $this->threshold;
				else return 0;

			default:
				return parent::__get($strName);
		}
	}

	public function __set($strName, $mixValue) {
		switch ($strName) {
			case 'Code':
				$mixValue = trim($mixValue);
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			default:
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

}