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

/**
 * ObjectManager for the Configuration Objects
 */
class XLSConfigurationManager extends XLSObjectManager {
	/**
	 * Overload the constructor to pass it the object's
	 * unique identifier field.
	 */
	protected function __construct($arrArguments = null) {
		parent::__construct(array('Key'));
	}

	/**
	 * Return the value for a given Configuration key
	 * @param string strKeyValue
	 * @param mix mixDefault :: Default value in case the value is empty
	 * @return mix value
	 */
	public function GetValue($strKeyValue, $mixDefault) {
		$objConfiguration = $this->GetByKey($strKeyValue);

		if (!$objConfiguration)
			return $mixDefault;
		return $objConfiguration->Value;
	}
}

/**
 * Nested ObjectManager for the Category Objects
 */
class XLSCategoryManager extends XLSNestedObjectManager {
	// Array containing a all of the Primary categories
	// TODO :: Should this really be an array or a getter to a search
	public $Primary;

	/**
	 * Overload the constructor to pass it the object's
	 * unique identifier field.
	 */
	protected function __construct($arrArguments = null) {
		parent::__construct(array('Rowid','Parent'));
		$this->Primary = array();
	}

	/**
	 * Overload the Add function to ensure that we are setting the Primaries
	 * @param obj $objCategory
	 */
	public function Add($objCategory) {
		parent::Add($objCategory);

		if ($objCategory->IsPrimary)
			$this->Primary[$objCategory->Rowid] =
				$this->GetByKey($objCategory->Rowid);
	}


	/**
	 * Get a Category by case insensitive Name
	 * @param string $strName
	 * @return array
	 */
	public function GetByName($strName) {
		$results = array();

		foreach ($this->Objects as $obj)
			if (strcasecmp($obj->Name, $strName) == 0)
				$results[] = $obj;

		return $results;
	}

	/**
	 * Get a Category by case insensitive Slug
	 * @param string $strSlug
	 * @return array
	 */
	public function GetBySlug($strSlug) {
		$results = array();

		foreach ($this->Objects as $obj)
			if (strcasecmp($obj->Slug, $strSlug) == 0)
				$results[] = $obj;

		return $results;
	}
}

/**
 * Nested ObjectManager for the Product Objects
 */
class XLSProductManager extends XLSNestedObjectManager {
	/**
	 * Overload the constructor to pass it the object's
	 * unique identifier field.
	 */
	protected function __construct($arrArguments = null) {
		parent::__construct(array('Rowid', 'FkProductMasterId'));
	}
}

/**
 * ObjectManager for the Customer Objects
 */
class XLSCustomerManager extends XLSObjectManager {
	/**
	 * Overload the constructor to pass it the object's
	 * unique identifier field.
	 */
	protected function __construct($arrArguments = null) {
		parent::__construct(array('Email'));
	}
}

/**
 * Nested ObjectManager for the CartItem Objects
 */
class XLSCartItemManager extends XLSNestedObjectManager {
	/**
	 * Overload the constructor to pass it the object's
	 * unique identifier field.
	 */
	protected function __construct($arrArguments = null) {
		parent::__construct(array('Rowid', 'CartId'));
	}

	public static function CompareByPrice($objA, $objB) {
		if ($objA->SellBase == $objB->SellBase)
			return 0;

		return ($objA->SellBase < $objB->SellBase) ? +1 : -1;
	}
}
