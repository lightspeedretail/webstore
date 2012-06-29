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

class DeltaUpdates extends QBaseClass {

	//Fields that we can pass as part of the delta update
	public $strObjectName;
	public $intRowid;
	public $intInventory;
	public $intInventoryTotal;


	////////////////////////////////////////
	// METHODS for SOAP-BASED WEB SERVICES
	////////////////////////////////////////

	public static function GetSoapComplexTypeXml() {
		$strToReturn = '<complexType name="DeltaUpdates"><sequence>';
    	$strToReturn .= '<element name="Result" type="xsd:boolean"/>';
    	$strToReturn .= '<element name="Msg" type="xsd:string"/>';
		$strToReturn .= '</sequence></complexType>';
		return $strToReturn;
	}

	public static function AlterSoapComplexTypeArray($strComplexTypeArray) {
		if (!array_key_exists('DeltaUpdates', $strComplexTypeArray)) {
			$strComplexTypeArray['DeltaUpdates'] = DeltaUpdates::GetSoapComplexTypeXml();
		}
	}

	//This is called from cached .php
	public static function GetArrayFromSoapArray($objSoapArray) {
		$objArrayToReturn = array();

		foreach ($objSoapArray as $objSoapObject)
			array_push($objArrayToReturn, DeltaUpdates::GetObjectFromSoapObject($objSoapObject));

		return $objArrayToReturn;
	}

	public static function GetObjectFromSoapObject($objSoapObject) {
		$objToReturn = new DeltaUpdates();
		if (property_exists($objSoapObject, 'ObjectName'))
			$objToReturn->strObjectName = $objSoapObject->ObjectName;
		if (property_exists($objSoapObject, 'intRowid'))
			$objToReturn->intRowid = $objSoapObject->intRowid;
		if (property_exists($objSoapObject, 'intInventory'))
			$objToReturn->intInventory = $objSoapObject->intInventory;
		if (property_exists($objSoapObject, 'intInventoryTotal'))
			$objToReturn->intInventoryTotal = $objSoapObject->intInventoryTotal;
		return $objToReturn;
	}

	public static function GetSoapArrayFromArray($objArray) { 
		if (!$objArray)
			return null;

		$objArrayToReturn = array();

		foreach ($objArray as $objObject)
			array_push($objArrayToReturn, DeltaUpdates::GetSoapObjectFromObject($objObject, true));

		return unserialize(serialize($objArrayToReturn));
	}

	public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
		//if ($objObject->dttCreated)
		//	$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
		return $objObject;
	}



	public function __get($strName) {
		switch ($strName) {
			case 'ObjectName':
				return $this->strObjectName;

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
