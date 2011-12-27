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

require(__DATAGEN_CLASSES__ . '/CategoryGen.class.php');

/**
 * The Category class defined here contains any
 * customized code for the Category class in the
 * Object Relational Model.  It represents the "category" table
 * in the database, and extends from the code generated abstract CategoryGen
 * class, which contains all the basic CRUD-type functionality as well as
 * basic methods to handle relationships and index-based loading.
 */
class Category extends CategoryGen {
	// Define the default ordering QClause
	public static $DefaultOrdering;

	// Define the Object Manager for semi-persistent storage
	public static $Manager;

	// Default "to string" handler
	public function __toString() {
		return sprintf('Category Object %s',  $this->strName);
	}

	// Initialize the Object Manager on the class
	public static function InitializeManager() {
		if (!Category::$Manager)
			Category::$Manager =
				XLSCategoryManager::Singleton('XLSCategoryManager');
	}

	/**
	 * These methods make use of the attached Manager
	 */

	protected function GetChildren() {
		return Category::$Manager->GetByAssociation($this->Rowid);
	}

	protected function GetAncestors() {
		$arrResults = array();
		$objCurrent = $this;

		while (!$objCurrent) {
			$objCurrent = $objCurrent->ParentObject;
			if ($objCurrent)
				$arrResults[] = $objCurrent;
		}

		return $arrResults;
	}

	/**
	 * Convenience methods accessed as properties
	 */
    protected function HasChildren() {
        $objSubCategories = $this->GetChildren();
        if (count($objSubCategories) > 0) { 
            if (_xls_get_conf('DISPLAY_EMPTY_CATEGORY', '1'))
                return true;

            foreach ($objSubCategories as $objCategory)
                if ($objCategory->HasChildOrProduct())
                    return true;
        }
		return false;
	}

	protected function HasProducts() {
		if ($this->intChildCount > 0)
			return true;
		return false;
	}

	public function HasChildOrProduct() { // LEGACY should be protected
		if ($this->HasChildren() || $this->HasProducts())
			return true;
		return false;
	}

	protected function IsPrimary() {
		if (empty($this->intParent))
			return true;
		return false;
	}

	protected function GetParent() {
		if ($this->IsPrimary)
			return;
		else if ($this->objParentObject)
			return $this->objParentObject;
		else if ($objParent = Category::$Manager->GetByKey($this->intParent))
			$this->objParentObject = $objParent;
		else {
			$this->objParentObject = Category::LoadByRowid($this->intParent);
		}
		return $this->objParentObject;
	}

	protected function GetSlug() {
		return urlencode(str_replace('/', '_', $this->strName));
	}

	protected function HasImage() {
		if (empty($this->intImageId))
			return false;
		return true;
	}

	protected function GetImageLink($type) {
		return Images::GetImageLink($this->intImageId, $type);
	}

	protected function GetDirLink() {
		if ($this->IsPrimary())
			return $this->Slug . '/';
		else
			return $this->ParentObject->DirLink . $this->Slug . '/';
	}

	protected function GetLink() {
		if (_xls_get_conf('ENABLE_SEO_URL', false))
			if ($this->IsPrimary())
				return $this->Slug . '.html';
			else
				return $this->ParentObject->DirLink . $this->Slug . '.html';
		return 'index.php?c=' . $this->intRowid;
	}

	/**
	 * GetTrail - return array of Category Trail for product
	 * @param $intRowid RowID of Product
	 * @return $arrPath[]
	 */
	public static function GetTrail($intRowid) {
		$arrPath=array();
		
		$objCategory = parent::LoadArrayByProduct($intRowid); 
		if($objCategory && (count($objCategory) > 0))
			$category_id = current($objCategory)->Rowid;
		else
			return $arrPath;

		do {
			$objCategory = parent::Load($category_id);
	
			$strName = $objCategory->Name; 
			if($objCategory)
				array_push($arrPath , array( 'key' => $category_id , 'tag' => 'c' , 'name' => $strName , 'link' => $objCategory->Link));
	
		} while ($objCategory && ($category_id = $objCategory->Parent));
		
		$arrPath = array_reverse($arrPath);			

		return $arrPath;
	}

	/**
	 * Additional functionality
	 */
	public function PrintCategory($arrSelected, $strPrefix = '') {
		if (_xls_get_conf('DISPLAY_EMPTY_CATEGORY', '0') != '1' &&
			!$this->HasChildOrProduct())
				return;

		$category = array(
			'key' => $strPrefix . $this->Rowid,
			'link' => $this->GetLink(),
			'name' => $this->strName,
			'indent' => 20 * substr_count($strPrefix , "." ),
			'dashes' => str_repeat('--', substr_count($strPrefix, '.')),
			'case' => in_array(
				$this->intRowid, $arrSelected)?'selected':'unselected'
			);

		return $category;
	}

	public function HasProduct($intProductId) {
		$strQuery = <<<EOS
SELECT COUNT(product_id) AS total_matches
FROM xlsws_product_category_assn
WHERE product_id={$intProductId}
AND category_id={$this->intRowid};
EOS;
		$objQuery = _dbx($strQuery, 'Query');
		$arrTotal = $objQuery->FetchArray();
		$intCount = $arrTotal['total_matches'];

		if ($intCount > 0)
			return true;
		return false;
	}

	public function UpdateChildCount(){
		$strQuery = <<<EOS
SELECT COUNT(prod.rowid) AS total_matches
FROM xlsws_product_category_assn AS assn
JOIN xlsws_product AS prod
ON assn.product_id = prod.rowid
WHERE assn.category_id='{$this->intRowid}'
AND prod.web=1
AND (
	(prod.master_model=1) OR
	(prod.master_model=0 AND prod.fk_product_master_id=0)
);
EOS;
		$objQuery = _dbx($strQuery, 'Query');
		$arrTotal = $objQuery->FetchArray();
		$intCount = $arrTotal['total_matches'];

		$this->intChildCount = $intCount;

		$strQuery = <<<EOS
UPDATE xlsws_category
SET `child_count`='{$this->intChildCount}'
WHERE `rowid`='{$this->intRowid}';
EOS;
		_dbx($strQuery, 'NonQuery');

		if(!$this->IsPrimary() && $this->ParentObject)
			$this->ParentObject->UpdateChildCount();
	}

	public static function QueryArray(QQCondition $objConditions,
		$objOptionalClauses = null, $mixParameterArray = null) {

			if (is_null($objOptionalClauses))
				$objOptionalClauses = Category::$DefaultOrdering;

			return parent::QueryArray($objConditions, $objOptionalClauses,
				$mixParameterArray);
	}

	public static function LoadByNameParent($strName, $intParentId) {
		return Category::QuerySingle(
			QQ::AndCondition(
				QQ::Equal(QQN::Category()->Name, $strName),
				QQ::Equal(QQN::Category()->Parent, $intParentId)
			)
		);
	}

	public function Delete() {
		$this->UnassociateAllProducts();

		foreach ($this->GetChildCategoryArray() as $objCategory)
			$objCategory->Delete();

		parent::Delete();
	}

	/**
	 * Define legacy functions
	 */
	public function print_category(//LEGACY
		&$arrCategories, $arrSelected, $strPrefix = '') {
			QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);

			$arrCategories[] = $this;
			if (_xls_get_conf('DISPLAY_EMPTY_CATEGORY', '0') != '1' &&
				!$this->HasChildOrProduct())
					return;

			foreach ($this->GetChildren() as $objCategory) {
				$objChild = $objCategory->PrintCategory(
					$arrSelected, $strPrefix);

				if ($objChild)
					$arrCategories[] = $objChild;
			}

	}

	public function get_childs_array() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);

		if (!$this->HasChildren())
			Category::$Manager->AddArray(
				$this->LoadArrayByParent($this->intRowid));

		$results = array($this->intRowid);
		foreach ($this->GetChildren() as $objCategory) {
			$results[] = $objCategory->intRowid;
			$results = array_merge($results,
				$objCategory->get_childs_array());
		}

		return $results;
	}

	public function GetChildIds(){
		$results = array();
		foreach ($this->GetChildren() as $objCategory) {
			$results[] = $objCategory->intRowid;
			$results = array_merge($results,
				$objCategory->GetChildIds());
		}

		return $results;
	}

	public function add_childs() {
		QApplication::Log(E_USER_NOTICE, 'legacy', __FUNCTION__);
		if (!$this->HasChildren())
			Category::$Manager->AddArray(
				$this->LoadArrayByParent($this->intRowid));

		foreach ($this->GetChildren() as $objCategory)
			$objCategory->add_childs();
	}

	/**
	 * Define getter / setter
	 */

	public function __get($strName) {
		switch ($strName) {
			case 'IsPrimary':
				return $this->IsPrimary();

			case 'Slug':
				return $this->GetSlug();

			case 'HasChildren':
				return $this->HasChildren();

			case 'HasProducts':
				return $this->HasProducts();

			case 'HasChildOrProduct':
				return $this->HasChildOrProduct();

			case 'ParentObject':
				return $this->GetParent();

			case 'HasImage':
			case 'ImageExist': // LEGACY
				return $this->HasImage();

			case 'ListingImage':
				return $this->GetImageLink('listingimage');

			case 'MiniImage':
				return $this->GetImageLink('miniimage');

			case 'PDetailImage':
				return $this->GetImageLink('pdetailimage');

			case 'SmallImage':
				return $this->GetImageLink('smallimage');

			case 'Image':
				return $this->GetImageLink('image');

			case 'DirLink':
				return $this->GetDirLink();

			case 'Link':
				return $this->GetLink();

			case 'Children':
			case 'categ_childs': // LEGACY
				return $this->GetChildren();

            case 'MetaDescription':
            case 'MetaKeywords':
                try { 
                    return trim(parent::__get($strName));
                }
                catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	public function __call($strName, $args = array()) {
		switch ($strName) {
			case 'get_childs_array': // LEGACY
				return $this->get_childs_array();

			case 'add_childs': // LEGACY
				$this->add_Childs();
		}
	}
}

Category::$DefaultOrdering = QQ::Clause(
	QQ::OrderBy(QQN::Category()->Position, QQN::Category()->Name)
);
