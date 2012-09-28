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

//This file contains classes that are are using for Delta Updates (partial record updates) from LightSpeed

class UpdateInventory extends QBaseClass {

	//Fields that we can pass as part of the delta update
	public $productID;
	public $inventory;
	public $inventoryTotal;


	////////////////////////////////////////
	// METHODS for SOAP-BASED WEB SERVICES
	////////////////////////////////////////

	public static function GetSoapComplexTypeXml() {
		$strToReturn = '<complexType name="UpdateInventory"><sequence>';
    	$strToReturn .= '<element name="productID" type="xsd:int"/>';
    	$strToReturn .= '<element name="inventory" type="xsd:int"/>';
    	$strToReturn .= '<element name="inventoryTotal" type="xsd:int"/>';
		$strToReturn .= '</sequence></complexType>';
		return $strToReturn;
	}

	public static function AlterSoapComplexTypeArray($strComplexTypeArray) {
		if (!array_key_exists('UpdateInventory', $strComplexTypeArray)) {
			$strComplexTypeArray['UpdateInventory'] = UpdateInventory::GetSoapComplexTypeXml();
		}

	}

	//This is called from cached .php
	public static function GetArrayFromSoapArray($objSoapArray) {
		$objArrayToReturn = array();

		foreach ($objSoapArray as $objSoapObject)
			array_push($objArrayToReturn, UpdateInventory::GetObjectFromSoapObject($objSoapObject));

		return $objArrayToReturn;
	}

	public static function GetObjectFromSoapObject($objSoapObject) {
		$objToReturn = new UpdateInventory();
		if (property_exists($objSoapObject, 'productID'))
			$objToReturn->productID = $objSoapObject->productID;
		if (property_exists($objSoapObject, 'inventory'))
			$objToReturn->inventory = $objSoapObject->inventory;
		if (property_exists($objSoapObject, 'inventoryTotal'))
			$objToReturn->inventoryTotal = $objSoapObject->inventoryTotal;
		return $objToReturn;
	}

	public static function GetSoapArrayFromArray($objArray) { 
		if (!$objArray)
			return null;

		$objArrayToReturn = array();

		foreach ($objArray as $objObject)
			array_push($objArrayToReturn, UpdateInventory::GetSoapObjectFromObject($objObject, true));

		return unserialize(serialize($objArrayToReturn));
	}

	public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objUpdateInventory)
				$objObject->objUpdateInventory = UpdateInventory::GetSoapObjectFromObject($objObject->objUpdateInventory, false);
			return $objObject;
		}

	public function __get($strName) {
		switch ($strName) {

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
