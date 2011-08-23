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
 * XLSObjectManager Singletons
 * These are the base classes used to generate a persistent cache of loaded
 * objects. We employ these as a object storage in order to reduce the amount
 * of superfluous database hits.
 */
class XLSObjectManager {
	// Array of Objects :: [$obj->$strObjectKey] = $obj;
	public $Populated;
	protected $Objects;
	protected $strObjectKey;

	/**
	 * Instances of XLSObjectManager are Singletons
	 * @param string $strClass :: The class for each we are returning
	 * @return obj :: Instance of the singleton
	 */
	public static function &Singleton($strClass) {
		static $Instances = array();

		$strClassKey = strtolower($strClass);
		$arrArguments = func_get_args();
		array_shift($arrArguments);

		if (!array_key_exists($strClassKey, $Instances))
			$Instances[$strClassKey] = new $strClass($arrArguments);

		$objInstance =& $Instances[$strClassKey];
		return $objInstance;
	}

	/**
	 * To allow for subclassing the XLSObjectManager, we take in
	 * a variable amount of arguments.
	 * @param0 string $this->strObjectKey :: The object's unique key
	 */
	protected function __construct($arrArguments) {
		if (count($arrArguments) < 1)
			throw new QUndefinedPropertyException(
				'__construct', __CLASS__, 'strObjectKey'
			);

		$this->strObjectKey = $arrArguments[0];
		$this->Objects = array();
	}

	/**
	 * Verify whether the object exists within Cache
	 */
	public function HasKey($mixKeyValue) {
		return array_key_exists($mixKeyValue, $this->Objects);
	}

	/**
	 * Verify whether the cache is contains objects
	 */
	public function HasObjects() {
		if (count($this->Objects) > 0)
			return true;
		return false;
	}

	/**
	 * Return a reference to our array of objects
	 * @return &array();
	 */
	public function &GetAll() {
		return $this->Objects;
	}

	/**
	 * Return a reference to the cached object by it's strObjectKey value
	 * @param mix $mixKeyValue :: The value to search by
	 * @return &obj
	 */
	public function GetByKey($mixKeyValue) {
		if ($this->HasKey($mixKeyValue)) {
			$obj =& $this->Objects[$mixKeyValue];
			return $obj;
		}
	}

	/**
	 * Return an array of objects matching on a given field and value
	 * @param string $strObjectKey :: Field to search by
	 * @param mix $mixKeyValue :: Value to search for
	 * @return array
	 */
	public function GetByProperty($strObjectKey, $mixKeyValue) {
		if ($strObjectKey == $this->strObjectKey)
			return array($this->GetByKey($mixKeyValue));

		$results = array();

		foreach ($this->Objects as $obj)
			if ($obj->$strObjectKey == $mixKeyValue)
				$results[] = $obj;

		return $results;
	}

	/**
	 * Return the first matching object given a field and value
	 * @param string $strObjectKey :: Field to search by
	 * @param mix $mixKeyValue :: Value to search for
	 * @return obj
	 */
	public function GetByUniqueProperty($strObjectKey, $mixKeyValue) {
		if ($strObjectKey == $this->strObjectKey)
			return $this->GetByKey($mixKeyValue);

		foreach ($this->Objects as $obj)
			if ($obj->$strObjectKey == $mixKeyValue)
				return $obj;

		return;
	}

	/**
	 * Add an object to the cache, indexed by it's strObjectKey
	 * @param obj $obj
	 */
	public function Add($obj) {
		$strObjectKey = $this->strObjectKey;
		$this->Objects[$obj->$strObjectKey] = $obj;
		$this->Populated = true;
	}

	/**
	 * Convenience method to Add an array of objects.
	 * @param array $arrObj
	 */
	public function AddArray($arrObj) {
		foreach ($arrObj as $obj)
			$this->Add($obj);
	}

	/**
	 * Remove an object from the cache
	 * @param obj $obj
	 */
	public function Remove($obj) {
		$strObjectKey = $this->strObjectKey;
		unset($this->Objects[$obj->$strObjectKey]);
	}
}

/**
 * The XLSNestedObjectManager extends the standard XLSObjectManager by adding
 * provisions for a Parent->Child relationship.
 */
class XLSNestedObjectManager extends XLSObjectManager {
	protected $Associations;
	protected $strAssociationKey;

	/**
	 * Overload the XLSObjectManager construct method to add a requirement
	 * for the association key.
	 * @param0 string $this->strObjectKey :: The object's unique key
	 * @param0 string $this->strAssociationKey :: The association key
	 */
	protected function __construct($arrArguments) {
		if (count($arrArguments) < 2)
			throw new QUndefinedPropertyException(
				'__construct', __CLASS__, 'strObjectKey, $strAssociationKey'
			);

		parent::__construct($arrArguments);

		$this->strAssociationKey = $arrArguments[1];
		$this->Associations = array();
	}

	/**
	 * Verify whether the association exists within Cache
	 */
	public function HasAssociation($mixAssociationKeyValue) {
		return array_key_exists($mixAssociationKeyValue,
			$this->Associations);
	}

	/**
	 * Return a reference to an Object's associations
	 * @param string $strAssociationKey :: Key of the object to return for
	 * @return array;
	 */
	public function &GetByAssociation($strAssociationKey) {
		if (array_key_exists($strAssociationKey, $this->Associations)) {
			$arrAssociations =& $this->Associations[$strAssociationKey];
			return $arrAssociations;
		}
		else
			return array();
	}

	/**
	 * Cache an Object's association
	 * @param obj $obj
	 */
	public function Associate($obj) {
		$strObjectKey = $this->strObjectKey;
		$strAssociationKey = $this->strAssociationKey;

		$objReference = $this->GetByKey($obj->$strObjectKey);
		$mixAssociation = $obj->$strAssociationKey;
		$mixKey = $obj->$strObjectKey;

		if (!array_key_exists($mixAssociation, $this->Associations))
			$this->Associations[$mixAssociation] = array();

		$this->Associations[$mixAssociation][$mixKey] = $objReference;
	}

	/**
	 * Unset association for Object
	 * @param obj $obj
	 */
	public function Unassociate($obj) {
		$strObjectKey = $this->strObjectKey;
		$strAssociationKey = $this->strAssociationKey;

		$mixAssociation = $obj->$strAssociationKey;
		$mixKey = $obj->$strObjectKey;

		unset($this->Associations[$mixAssociation][$mixKey]);
	}

	/**
	 * Overload the Add function to ensure that we also create the
	 * association
	 * @param obj $obj
	 */
	public function Add($obj) {
		parent::Add($obj);
		$this->Associate($obj);
	}

	/**
	 * Overload the Remove function to ensure that we also remove the
	 * association
	 * @param obj $obj
	 */
	public function Remove($obj) {
		parent::Remove($obj);
		$this->Unassociate($obj);
	}
}
