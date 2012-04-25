<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

require(__DATAGEN_CLASSES__ . '/PromoCodeGen.class.php');

/**
 * The PromoCode class defined here contains any
 * customized code for the PromoCode class in the
 * Object Relational Model.
 */
class PromoCode extends PromoCodeGen {
	// String representation of the Object
	public function __toString() {
		return sprintf('Promo Code Object %s',  $this->strCode);
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
		if ($this->intQtyRemaining == 0)
			return false;
		return true;
	}

	protected function IsEnabled() {
		if ($this->blnEnabled)
			return true;
		return false;
	}

	public function IsExcept() {
		if ($this->intExcept==1)
			return true;
		return false;
	}

	protected function IsStarted() {
		if ($this->strValidFrom=="" || date("Y-m-d")>=date("Y-m-d",strtotime($this->strValidFrom)))
			return true;
		return false;
	}

	protected function IsExpired() {
		if ($this->strValidUntil != "" && date("Y-m-d",strtotime($this->strValidUntil))<date("Y-m-d"))
			return true;
		return false;
	}
	
	
	protected function IsShipping() {
		if ($this->LsCodeArray[0]=="shipping:")
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
  
             if (substr($strCode, 0,7) == "family:" && 
                trim(substr($strCode,7,255)) == strtolower($objItem->Product->Family)) 
            $boolReturn = true; 
            
            if (substr($strCode, 0,6) == "class:" && 
                trim(substr($strCode,6,255)) == strtolower($objItem->Product->ClassName)) 
             $boolReturn = true; 
             
            if (substr($strCode, 0,8) == "keyword:" && (
                trim(substr($strCode,8,255)) == strtolower($objItem->Product->WebKeyword1) ||
                trim(substr($strCode,8,255)) == strtolower($objItem->Product->WebKeyword2) ||
                trim(substr($strCode,8,255)) == strtolower($objItem->Product->WebKeyword3) )              
                ) 
            $boolReturn = true; 
            
            if (substr($strCode, 0,9) == "category:") {
				$arrTrail = Category::GetTrailByProductId($objItem->Product->Rowid,'names');
				$strTrail = implode("|",$arrTrail);

				$strCompareCode = trim(substr($strCode,9,255));
				if ($strCompareCode == strtolower(substr($strTrail,0,strlen($strCompareCode))))               
					$boolReturn = true;
			}
           
        }  

		  if (_xls_array_search_begin(strtolower($objItem->Code), $arrCode))
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
		return PromoCode::QuerySingle(
			QQ::Equal(QQN::PromoCode()->Code, $strCode)
		);
	}

	/**
	 * Load a PromoCode from code for Shipping Promo Code
	 * Separated from other types of promo codes
	 * @param string $strCode
	 * @return PromoCode
	 */
	public static function LoadByCodeShipping($strCode) {
		return PromoCode::QuerySingle(
			QQ::AndCondition(
				QQ::Equal(QQN::PromoCode()->Code, $strCode),
				QQ::Like(QQN::PromoCode()->Lscodes, "shipping:,%")
				)
		);						
	}
	
	/**
	 * Delete all Shipping PromoCodes
	 * @return void
	 */
	public static function DeleteShippingPromoCodes() {
			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_promo_code`
					WHERE `lscodes` LIKE "shipping:,%"');
		}

	public static function DisableShippingPromoCodes() {
			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				update
					`xlsws_promo_code`
					set `enabled`= 0
					WHERE `lscodes` LIKE "shipping:,%"');
		}


	public static function EnableShippingPromoCodes() {
			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				update
					`xlsws_promo_code`
					set `enabled`= 1
					WHERE `lscodes` LIKE "shipping:,%"');;
		}


	
	/**
	 * Load a PromoCode from cart id
	 * @param string $intCart
	 * @return PromoCode
	 */
	public static function LoadByFkCartId($intCart) {
		return PromoCode::QuerySingle(
			QQ::Equal(QQN::PromoCode()->FkCartId, $intCart)
		);
	}

	public function __get($strName) {
		switch ($strName) {
			case 'Code':
				return $this->strCode;

			case 'LsCodeArray':
				$arrCodes = explode(",", $this->strLscodes);
				array_walk($arrCodes, '_xls_trim');
				return $arrCodes;

			case 'Active':
				return $this->IsActive();

			case 'Enabled':
				return $this->IsEnabled();

			case 'Except':
				return $this->intExcept;

			case 'HasRemaining':
				return $this->HasRemaining();

			case 'Started':
				return $this->IsStarted();

			case 'Expired':
				return $this->IsExpired();
				
			case 'Shipping':
				return $this->IsShipping();
				

			case 'Threshold':
				return $this->strThreshold;

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
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
