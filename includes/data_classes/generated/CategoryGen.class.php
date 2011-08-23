<?php
	/**
	 * The abstract CategoryGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Category subclass which
	 * extends this CategoryGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Category class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Name the value for strName 
	 * @property integer $Parent the value for intParent 
	 * @property integer $Position the value for intPosition (Not Null)
	 * @property integer $ChildCount the value for intChildCount 
	 * @property string $CustomPage the value for strCustomPage 
	 * @property integer $ImageId the value for intImageId 
	 * @property string $MetaKeywords the value for strMetaKeywords 
	 * @property string $MetaDescription the value for strMetaDescription 
	 * @property QDateTime $Created the value for dttCreated 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Category $ParentObject the value for the Category object referenced by intParent 
	 * @property Product $_Product the value for the private _objProduct (Read-Only) if set due to an expansion on the xlsws_product_category_assn association table
	 * @property Product[] $_ProductArray the value for the private _objProductArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_category_assn association table
	 * @property Category $_ChildCategory the value for the private _objChildCategory (Read-Only) if set due to an expansion on the xlsws_category.parent reverse relationship
	 * @property Category[] $_ChildCategoryArray the value for the private _objChildCategoryArray (Read-Only) if set due to an ExpandAsArray on the xlsws_category.parent reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CategoryGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_category.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.name
		 * @var string strName
		 */
		protected $strName;
		const NameMaxLength = 64;
		const NameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.parent
		 * @var integer intParent
		 */
		protected $intParent;
		const ParentDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.position
		 * @var integer intPosition
		 */
		protected $intPosition;
		const PositionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.child_count
		 * @var integer intChildCount
		 */
		protected $intChildCount;
		const ChildCountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.custom_page
		 * @var string strCustomPage
		 */
		protected $strCustomPage;
		const CustomPageMaxLength = 64;
		const CustomPageDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.image_id
		 * @var integer intImageId
		 */
		protected $intImageId;
		const ImageIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.meta_keywords
		 * @var string strMetaKeywords
		 */
		protected $strMetaKeywords;
		const MetaKeywordsMaxLength = 255;
		const MetaKeywordsDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.meta_description
		 * @var string strMetaDescription
		 */
		protected $strMetaDescription;
		const MetaDescriptionMaxLength = 255;
		const MetaDescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_category.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single Product object
		 * (of type Product), if this Category object was restored with
		 * an expansion on the xlsws_product_category_assn association table.
		 * @var Product _objProduct;
		 */
		private $_objProduct;

		/**
		 * Private member variable that stores a reference to an array of Product objects
		 * (of type Product[]), if this Category object was restored with
		 * an ExpandAsArray on the xlsws_product_category_assn association table.
		 * @var Product[] _objProductArray;
		 */
		private $_objProductArray = array();

		/**
		 * Private member variable that stores a reference to a single ChildCategory object
		 * (of type Category), if this Category object was restored with
		 * an expansion on the xlsws_category association table.
		 * @var Category _objChildCategory;
		 */
		private $_objChildCategory;

		/**
		 * Private member variable that stores a reference to an array of ChildCategory objects
		 * (of type Category[]), if this Category object was restored with
		 * an ExpandAsArray on the xlsws_category association table.
		 * @var Category[] _objChildCategoryArray;
		 */
		private $_objChildCategoryArray = array();

		/**
		 * Protected array of virtual attributes for this object (e.g. extra/other calculated and/or non-object bound
		 * columns from the run-time database query result for this object).  Used by InstantiateDbRow and
		 * GetVirtualAttribute.
		 * @var string[] $__strVirtualAttributeArray
		 */
		protected $__strVirtualAttributeArray = array();

		/**
		 * Protected internal member variable that specifies whether or not this object is Restored from the database.
		 * Used by Save() to determine if Save() should perform a db UPDATE or INSERT.
		 * @var bool __blnRestored;
		 */
		protected $__blnRestored;




		///////////////////////////////
		// PROTECTED MEMBER OBJECTS
		///////////////////////////////

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_category.parent.
		 *
		 * NOTE: Always use the ParentObject property getter to correctly retrieve this Category object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Category objParentObject
		 */
		protected $objParentObject;





		///////////////////////////////
		// CLASS-WIDE LOAD AND COUNT METHODS
		///////////////////////////////

		/**
		 * Static method to retrieve the Database object that owns this class.
		 * @return QDatabaseBase reference to the Database object that can query this class
		 */
		public static function GetDatabase() {
			return QApplication::$Database[1];
		}

		/**
		 * Load a Category from PK Info
		 * @param integer $intRowid
		 * @return Category
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Category::QuerySingle(
				QQ::Equal(QQN::Category()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Categories
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Category[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Category::QueryArray to perform the LoadAll query
			try {
				return Category::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Categories
		 * @return int
		 */
		public static function CountAll() {
			// Call Category::QueryCount to perform the CountAll query
			return Category::QueryCount(QQ::All());
		}




		///////////////////////////////
		// QCODO QUERY-RELATED METHODS
		///////////////////////////////

		/**
		 * Internally called method to assist with calling Qcodo Query for this class
		 * on load methods.
		 * @param QQueryBuilder &$objQueryBuilder the QueryBuilder object that will be created
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause object or array of QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with (sending in null will skip the PrepareStatement step)
		 * @param boolean $blnCountOnly only select a rowcount
		 * @return string the query statement
		 */
		protected static function BuildQueryStatement(&$objQueryBuilder, QQCondition $objConditions, $objOptionalClauses, $mixParameterArray, $blnCountOnly) {
			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Create/Build out the QueryBuilder object with Category-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_category');
			Category::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_category');

			// Set "CountOnly" option (if applicable)
			if ($blnCountOnly)
				$objQueryBuilder->SetCountOnlyFlag();

			// Apply Any Conditions
			if ($objConditions)
				try {
					$objConditions->UpdateQueryBuilder($objQueryBuilder);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			// Iterate through all the Optional Clauses (if any) and perform accordingly
			if ($objOptionalClauses) {
				if ($objOptionalClauses instanceof QQClause)
					$objOptionalClauses->UpdateQueryBuilder($objQueryBuilder);
				else if (is_array($objOptionalClauses))
					foreach ($objOptionalClauses as $objClause)
						$objClause->UpdateQueryBuilder($objQueryBuilder);
				else
					throw new QCallerException('Optional Clauses must be a QQClause object or an array of QQClause objects');
			}

			// Get the SQL Statement
			$strQuery = $objQueryBuilder->GetStatement();

			// Prepare the Statement with the Query Parameters (if applicable)
			if ($mixParameterArray) {
				if (is_array($mixParameterArray)) {
					if (count($mixParameterArray))
						$strQuery = $objDatabase->PrepareStatement($strQuery, $mixParameterArray);

					// Ensure that there are no other Unresolved Named Parameters
					if (strpos($strQuery, chr(QQNamedValue::DelimiterCode) . '{') !== false)
						throw new QCallerException('Unresolved named parameters in the query');
				} else
					throw new QCallerException('Parameter Array must be an array of name-value parameter pairs');
			}

			// Return the Objects
			return $strQuery;
		}

		/**
		 * Static Qcodo Query method to query for a single Category object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Category the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Category::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Category object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Category::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Category objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Category[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Category::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Category::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Category objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Category::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and return the row_count
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);

			// Figure out if the query is using GroupBy
			$blnGrouped = false;

			if ($objOptionalClauses) foreach ($objOptionalClauses as $objClause) {
				if ($objClause instanceof QQGroupBy) {
					$blnGrouped = true;
					break;
				}
			}

			if ($blnGrouped)
				// Groups in this query - return the count of Groups (which is the count of all rows)
				return $objDbResult->CountRows();
			else {
				// No Groups - return the sql-calculated count(*) value
				$strDbRow = $objDbResult->FetchRow();
				return QType::Cast($strDbRow[0], QType::Integer);
			}
		}

/*		public static function QueryArrayCached($strConditions, $mixParameterArray = null) {
			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_category_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Category-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Category::GetSelectFields($objQueryBuilder);
				Category::GetFromFields($objQueryBuilder);

				// Ensure the Passed-in Conditions is a string
				try {
					$strConditions = QType::Cast($strConditions, QType::String);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				// Create the Conditions object, and apply it
				$objConditions = eval('return ' . $strConditions . ';');

				// Apply Any Conditions
				if ($objConditions)
					$objConditions->UpdateQueryBuilder($objQueryBuilder);

				// Get the SQL Statement
				$strQuery = $objQueryBuilder->GetStatement();

				// Save the SQL Statement in the Cache
				$objCache->SaveData($strQuery);
			}

			// Prepare the Statement with the Parameters
			if ($mixParameterArray)
				$strQuery = $objDatabase->PrepareStatement($strQuery, $mixParameterArray);

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objDatabase->Query($strQuery);
			return Category::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Category
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_category';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
			$objBuilder->AddSelectItem($strTableName, 'parent', $strAliasPrefix . 'parent');
			$objBuilder->AddSelectItem($strTableName, 'position', $strAliasPrefix . 'position');
			$objBuilder->AddSelectItem($strTableName, 'child_count', $strAliasPrefix . 'child_count');
			$objBuilder->AddSelectItem($strTableName, 'custom_page', $strAliasPrefix . 'custom_page');
			$objBuilder->AddSelectItem($strTableName, 'image_id', $strAliasPrefix . 'image_id');
			$objBuilder->AddSelectItem($strTableName, 'meta_keywords', $strAliasPrefix . 'meta_keywords');
			$objBuilder->AddSelectItem($strTableName, 'meta_description', $strAliasPrefix . 'meta_description');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Category from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Category::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Category
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;

			// See if we're doing an array expansion on the previous item
			$strAlias = $strAliasPrefix . 'rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (($strExpandAsArrayNodes) && ($objPreviousItem) &&
				($objPreviousItem->intRowid == $objDbRow->GetColumn($strAliasName, 'Integer'))) {

				// We are.  Now, prepare to check for ExpandAsArray clauses
				$blnExpandedViaArray = false;
				if (!$strAliasPrefix)
					$strAliasPrefix = 'xlsws_category__';

				$strAlias = $strAliasPrefix . 'product__product_id__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductArray[$intPreviousChildItemCount - 1];
						$objChildItem = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product__product_id__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product__product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}


				$strAlias = $strAliasPrefix . 'childcategory__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objChildCategoryArray)) {
						$objPreviousChildItem = $objPreviousItem->_objChildCategoryArray[$intPreviousChildItemCount - 1];
						$objChildItem = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childcategory__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objChildCategoryArray[] = $objChildItem;
					} else
						$objPreviousItem->_objChildCategoryArray[] = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childcategory__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_category__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Category object
			$objToReturn = new Category();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'name'] : $strAliasPrefix . 'name';
			$objToReturn->strName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'parent', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'parent'] : $strAliasPrefix . 'parent';
			$objToReturn->intParent = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'position', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'position'] : $strAliasPrefix . 'position';
			$objToReturn->intPosition = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'child_count', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'child_count'] : $strAliasPrefix . 'child_count';
			$objToReturn->intChildCount = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'custom_page', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'custom_page'] : $strAliasPrefix . 'custom_page';
			$objToReturn->strCustomPage = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'image_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'image_id'] : $strAliasPrefix . 'image_id';
			$objToReturn->intImageId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'meta_keywords', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'meta_keywords'] : $strAliasPrefix . 'meta_keywords';
			$objToReturn->strMetaKeywords = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'meta_description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'meta_description'] : $strAliasPrefix . 'meta_description';
			$objToReturn->strMetaDescription = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'created', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'created'] : $strAliasPrefix . 'created';
			$objToReturn->dttCreated = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'modified', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'modified'] : $strAliasPrefix . 'modified';
			$objToReturn->strModified = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_category__';

			// Check for ParentObject Early Binding
			$strAlias = $strAliasPrefix . 'parent__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objParentObject = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'parent__', $strExpandAsArrayNodes, null, $strColumnAliasArray);



			// Check for Product Virtual Binding
			$strAlias = $strAliasPrefix . 'product__product_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product__product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProduct = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product__product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}


			// Check for ChildCategory Virtual Binding
			$strAlias = $strAliasPrefix . 'childcategory__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objChildCategoryArray[] = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childcategory__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objChildCategory = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'childcategory__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Categories from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Category[]
		 */
		public static function InstantiateDbResult(QDatabaseResultBase $objDbResult, $strExpandAsArrayNodes = null, $strColumnAliasArray = null) {
			$objToReturn = array();
			
			if (!$strColumnAliasArray)
				$strColumnAliasArray = array();

			// If blank resultset, then return empty array
			if (!$objDbResult)
				return $objToReturn;

			// Load up the return array with each row
			if ($strExpandAsArrayNodes) {
				$objLastRowItem = null;
				while ($objDbRow = $objDbResult->GetNextRow()) {
					$objItem = Category::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Category::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Category object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Category
		*/
		public static function LoadByRowid($intRowid) {
			return Category::QuerySingle(
				QQ::Equal(QQN::Category()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of Category objects,
		 * by Name Index(es)
		 * @param string $strName
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Category[]
		*/
		public static function LoadArrayByName($strName, $objOptionalClauses = null) {
			// Call Category::QueryArray to perform the LoadArrayByName query
			try {
				return Category::QueryArray(
					QQ::Equal(QQN::Category()->Name, $strName),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Categories
		 * by Name Index(es)
		 * @param string $strName
		 * @return int
		*/
		public static function CountByName($strName) {
			// Call Category::QueryCount to perform the CountByName query
			return Category::QueryCount(
				QQ::Equal(QQN::Category()->Name, $strName)
			);
		}
			
		/**
		 * Load an array of Category objects,
		 * by Parent Index(es)
		 * @param integer $intParent
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Category[]
		*/
		public static function LoadArrayByParent($intParent, $objOptionalClauses = null) {
			// Call Category::QueryArray to perform the LoadArrayByParent query
			try {
				return Category::QueryArray(
					QQ::Equal(QQN::Category()->Parent, $intParent),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Categories
		 * by Parent Index(es)
		 * @param integer $intParent
		 * @return int
		*/
		public static function CountByParent($intParent) {
			// Call Category::QueryCount to perform the CountByParent query
			return Category::QueryCount(
				QQ::Equal(QQN::Category()->Parent, $intParent)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
			/**
		 * Load an array of Product objects for a given Product
		 * via the xlsws_product_category_assn table
		 * @param integer $intProductId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Category[]
		*/
		public static function LoadArrayByProduct($intProductId, $objOptionalClauses = null) {
			// Call Category::QueryArray to perform the LoadArrayByProduct query
			try {
				return Category::QueryArray(
					QQ::Equal(QQN::Category()->Product->ProductId, $intProductId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Categories for a given Product
		 * via the xlsws_product_category_assn table
		 * @param integer $intProductId
		 * @return int
		*/
		public static function CountByProduct($intProductId) {
			return Category::QueryCount(
				QQ::Equal(QQN::Category()->Product->ProductId, $intProductId)
			);
		}




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Category
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_category` (
							`name`,
							`parent`,
							`position`,
							`child_count`,
							`custom_page`,
							`image_id`,
							`meta_keywords`,
							`meta_description`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strName) . ',
							' . $objDatabase->SqlVariable($this->intParent) . ',
							' . $objDatabase->SqlVariable($this->intPosition) . ',
							' . $objDatabase->SqlVariable($this->intChildCount) . ',
							' . $objDatabase->SqlVariable($this->strCustomPage) . ',
							' . $objDatabase->SqlVariable($this->intImageId) . ',
							' . $objDatabase->SqlVariable($this->strMetaKeywords) . ',
							' . $objDatabase->SqlVariable($this->strMetaDescription) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_category', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_category`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Category');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_category`
						SET
							`name` = ' . $objDatabase->SqlVariable($this->strName) . ',
							`parent` = ' . $objDatabase->SqlVariable($this->intParent) . ',
							`position` = ' . $objDatabase->SqlVariable($this->intPosition) . ',
							`child_count` = ' . $objDatabase->SqlVariable($this->intChildCount) . ',
							`custom_page` = ' . $objDatabase->SqlVariable($this->strCustomPage) . ',
							`image_id` = ' . $objDatabase->SqlVariable($this->intImageId) . ',
							`meta_keywords` = ' . $objDatabase->SqlVariable($this->strMetaKeywords) . ',
							`meta_description` = ' . $objDatabase->SqlVariable($this->strMetaDescription) . ',
							`created` = ' . $objDatabase->SqlVariable($this->dttCreated) . '
						WHERE
							`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
					');
				}

			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Update __blnRestored and any Non-Identity PK Columns (if applicable)
			$this->__blnRestored = true;

			// Update Local Timestamp
			$objResult = $objDatabase->Query('
				SELECT
					`modified`
				FROM
					`xlsws_category`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Category
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Category with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_category`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Categories
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_category`');
		}

		/**
		 * Truncate xlsws_category table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_category`');
		}

		/**
		 * Reload this Category from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Category object.');

			// Reload the Object
			$objReloaded = Category::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strName = $objReloaded->strName;
			$this->Parent = $objReloaded->Parent;
			$this->intPosition = $objReloaded->intPosition;
			$this->intChildCount = $objReloaded->intChildCount;
			$this->strCustomPage = $objReloaded->strCustomPage;
			$this->intImageId = $objReloaded->intImageId;
			$this->strMetaKeywords = $objReloaded->strMetaKeywords;
			$this->strMetaDescription = $objReloaded->strMetaDescription;
			$this->dttCreated = $objReloaded->dttCreated;
			$this->strModified = $objReloaded->strModified;
		}



		////////////////////
		// PUBLIC OVERRIDERS
		////////////////////

				/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
				case 'Rowid':
					// Gets the value for intRowid (Read-Only PK)
					// @return integer
					return $this->intRowid;

				case 'Name':
					// Gets the value for strName 
					// @return string
					return $this->strName;

				case 'Parent':
					// Gets the value for intParent 
					// @return integer
					return $this->intParent;

				case 'Position':
					// Gets the value for intPosition (Not Null)
					// @return integer
					return $this->intPosition;

				case 'ChildCount':
					// Gets the value for intChildCount 
					// @return integer
					return $this->intChildCount;

				case 'CustomPage':
					// Gets the value for strCustomPage 
					// @return string
					return $this->strCustomPage;

				case 'ImageId':
					// Gets the value for intImageId 
					// @return integer
					return $this->intImageId;

				case 'MetaKeywords':
					// Gets the value for strMetaKeywords 
					// @return string
					return $this->strMetaKeywords;

				case 'MetaDescription':
					// Gets the value for strMetaDescription 
					// @return string
					return $this->strMetaDescription;

				case 'Created':
					// Gets the value for dttCreated 
					// @return QDateTime
					return $this->dttCreated;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;


				///////////////////
				// Member Objects
				///////////////////
				case 'ParentObject':
					// Gets the value for the Category object referenced by intParent 
					// @return Category
					try {
						if ((!$this->objParentObject) && (!is_null($this->intParent)))
							$this->objParentObject = Category::Load($this->intParent);
						return $this->objParentObject;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_Product':
					// Gets the value for the private _objProduct (Read-Only)
					// if set due to an expansion on the xlsws_product_category_assn association table
					// @return Product
					return $this->_objProduct;

				case '_ProductArray':
					// Gets the value for the private _objProductArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_category_assn association table
					// @return Product[]
					return (array) $this->_objProductArray;

				case '_ChildCategory':
					// Gets the value for the private _objChildCategory (Read-Only)
					// if set due to an expansion on the xlsws_category.parent reverse relationship
					// @return Category
					return $this->_objChildCategory;

				case '_ChildCategoryArray':
					// Gets the value for the private _objChildCategoryArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_category.parent reverse relationship
					// @return Category[]
					return (array) $this->_objChildCategoryArray;


				case '__Restored':
					return $this->__blnRestored;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

				/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			switch ($strName) {
				///////////////////
				// Member Variables
				///////////////////
				case 'Name':
					// Sets the value for strName 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Parent':
					// Sets the value for intParent 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objParentObject = null;
						return ($this->intParent = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Position':
					// Sets the value for intPosition (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intPosition = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ChildCount':
					// Sets the value for intChildCount 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intChildCount = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CustomPage':
					// Sets the value for strCustomPage 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCustomPage = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ImageId':
					// Sets the value for intImageId 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intImageId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MetaKeywords':
					// Sets the value for strMetaKeywords 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMetaKeywords = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MetaDescription':
					// Sets the value for strMetaDescription 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMetaDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Created':
					// Sets the value for dttCreated 
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttCreated = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'ParentObject':
					// Sets the value for the Category object referenced by intParent 
					// @param Category $mixValue
					// @return Category
					if (is_null($mixValue)) {
						$this->intParent = null;
						$this->objParentObject = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Category object
						try {
							$mixValue = QType::Cast($mixValue, 'Category');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Category object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved ParentObject for this Category');

						// Update Local Member Variables
						$this->objParentObject = $mixValue;
						$this->intParent = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				default:
					try {
						return parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		/**
		 * Lookup a VirtualAttribute value (if applicable).  Returns NULL if none found.
		 * @param string $strName
		 * @return string
		 */
		public function GetVirtualAttribute($strName) {
			if (array_key_exists($strName, $this->__strVirtualAttributeArray))
				return $this->__strVirtualAttributeArray[$strName];
			return null;
		}



		///////////////////////////////
		// ASSOCIATED OBJECTS' METHODS
		///////////////////////////////

			
		
		// Related Objects' Methods for ChildCategory
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ChildCategories as an array of Category objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Category[]
		*/ 
		public function GetChildCategoryArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Category::LoadArrayByParent($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ChildCategories
		 * @return int
		*/ 
		public function CountChildCategories() {
			if ((is_null($this->intRowid)))
				return 0;

			return Category::CountByParent($this->intRowid);
		}

		/**
		 * Associates a ChildCategory
		 * @param Category $objCategory
		 * @return void
		*/ 
		public function AssociateChildCategory(Category $objCategory) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateChildCategory on this unsaved Category.');
			if ((is_null($objCategory->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateChildCategory on this Category with an unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_category`
				SET
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCategory->Rowid) . '
			');
		}

		/**
		 * Unassociates a ChildCategory
		 * @param Category $objCategory
		 * @return void
		*/ 
		public function UnassociateChildCategory(Category $objCategory) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildCategory on this unsaved Category.');
			if ((is_null($objCategory->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildCategory on this Category with an unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_category`
				SET
					`parent` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCategory->Rowid) . ' AND
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ChildCategories
		 * @return void
		*/ 
		public function UnassociateAllChildCategories() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildCategory on this unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_category`
				SET
					`parent` = null
				WHERE
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ChildCategory
		 * @param Category $objCategory
		 * @return void
		*/ 
		public function DeleteAssociatedChildCategory(Category $objCategory) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildCategory on this unsaved Category.');
			if ((is_null($objCategory->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildCategory on this Category with an unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_category`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCategory->Rowid) . ' AND
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ChildCategories
		 * @return void
		*/ 
		public function DeleteAllChildCategories() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateChildCategory on this unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_category`
				WHERE
					`parent` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		// Related Many-to-Many Objects' Methods for Product
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated Products as an array of Product objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/ 
		public function GetProductArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Product::LoadArrayByCategory($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated Products
		 * @return int
		*/ 
		public function CountProducts() {
			if ((is_null($this->intRowid)))
				return 0;

			return Product::CountByCategory($this->intRowid);
		}

		/**
		 * Checks to see if an association exists with a specific Product
		 * @param Product $objProduct
		 * @return bool
		*/
		public function IsProductAssociated(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProductAssociated on this unsaved Category.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsProductAssociated on this Category with an unsaved Product.');

			$intRowCount = Category::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Category()->Rowid, $this->intRowid),
					QQ::Equal(QQN::Category()->Product->ProductId, $objProduct->Rowid)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a Product
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function AssociateProduct(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProduct on this unsaved Category.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProduct on this Category with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `xlsws_product_category_assn` (
					`category_id`,
					`product_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intRowid) . ',
					' . $objDatabase->SqlVariable($objProduct->Rowid) . '
				)
			');
		}

		/**
		 * Unassociates a Product
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function UnassociateProduct(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProduct on this unsaved Category.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProduct on this Category with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_category_assn`
				WHERE
					`category_id` = ' . $objDatabase->SqlVariable($this->intRowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . '
			');
		}

		/**
		 * Unassociates all Products
		 * @return void
		*/ 
		public function UnassociateAllProducts() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllProductArray on this unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Category::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_category_assn`
				WHERE
					`category_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}




		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Category"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Name" type="xsd:string"/>';
			$strToReturn .= '<element name="ParentObject" type="xsd1:Category"/>';
			$strToReturn .= '<element name="Position" type="xsd:int"/>';
			$strToReturn .= '<element name="ChildCount" type="xsd:int"/>';
			$strToReturn .= '<element name="CustomPage" type="xsd:string"/>';
			$strToReturn .= '<element name="ImageId" type="xsd:int"/>';
			$strToReturn .= '<element name="MetaKeywords" type="xsd:string"/>';
			$strToReturn .= '<element name="MetaDescription" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Category', $strComplexTypeArray)) {
				$strComplexTypeArray['Category'] = Category::GetSoapComplexTypeXml();
				Category::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Category::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Category();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Name'))
				$objToReturn->strName = $objSoapObject->Name;
			if ((property_exists($objSoapObject, 'ParentObject')) &&
				($objSoapObject->ParentObject))
				$objToReturn->ParentObject = Category::GetObjectFromSoapObject($objSoapObject->ParentObject);
			if (property_exists($objSoapObject, 'Position'))
				$objToReturn->intPosition = $objSoapObject->Position;
			if (property_exists($objSoapObject, 'ChildCount'))
				$objToReturn->intChildCount = $objSoapObject->ChildCount;
			if (property_exists($objSoapObject, 'CustomPage'))
				$objToReturn->strCustomPage = $objSoapObject->CustomPage;
			if (property_exists($objSoapObject, 'ImageId'))
				$objToReturn->intImageId = $objSoapObject->ImageId;
			if (property_exists($objSoapObject, 'MetaKeywords'))
				$objToReturn->strMetaKeywords = $objSoapObject->MetaKeywords;
			if (property_exists($objSoapObject, 'MetaDescription'))
				$objToReturn->strMetaDescription = $objSoapObject->MetaDescription;
			if (property_exists($objSoapObject, 'Created'))
				$objToReturn->dttCreated = new QDateTime($objSoapObject->Created);
			if (property_exists($objSoapObject, 'Modified'))
				$objToReturn->strModified = $objSoapObject->Modified;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Category::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objParentObject)
				$objObject->objParentObject = Category::GetSoapObjectFromObject($objObject->objParentObject, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intParent = null;
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeCategoryProduct extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'product';

		protected $strTableName = 'xlsws_product_category_assn';
		protected $strPrimaryKey = 'category_id';
		protected $strClassName = 'Product';

		public function __get($strName) {
			switch ($strName) {
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'ProductId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodeProduct('product_id', 'ProductId', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	class QQNodeCategory extends QQNode {
		protected $strTableName = 'xlsws_category';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Category';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Parent':
					return new QQNode('parent', 'Parent', 'integer', $this);
				case 'ParentObject':
					return new QQNodeCategory('parent', 'ParentObject', 'integer', $this);
				case 'Position':
					return new QQNode('position', 'Position', 'integer', $this);
				case 'ChildCount':
					return new QQNode('child_count', 'ChildCount', 'integer', $this);
				case 'CustomPage':
					return new QQNode('custom_page', 'CustomPage', 'string', $this);
				case 'ImageId':
					return new QQNode('image_id', 'ImageId', 'integer', $this);
				case 'MetaKeywords':
					return new QQNode('meta_keywords', 'MetaKeywords', 'string', $this);
				case 'MetaDescription':
					return new QQNode('meta_description', 'MetaDescription', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Product':
					return new QQNodeCategoryProduct($this);
				case 'ChildCategory':
					return new QQReverseReferenceNodeCategory($this, 'childcategory', 'reverse_reference', 'parent');

				case '_PrimaryKeyNode':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

	class QQReverseReferenceNodeCategory extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_category';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Category';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Parent':
					return new QQNode('parent', 'Parent', 'integer', $this);
				case 'ParentObject':
					return new QQNodeCategory('parent', 'ParentObject', 'integer', $this);
				case 'Position':
					return new QQNode('position', 'Position', 'integer', $this);
				case 'ChildCount':
					return new QQNode('child_count', 'ChildCount', 'integer', $this);
				case 'CustomPage':
					return new QQNode('custom_page', 'CustomPage', 'string', $this);
				case 'ImageId':
					return new QQNode('image_id', 'ImageId', 'integer', $this);
				case 'MetaKeywords':
					return new QQNode('meta_keywords', 'MetaKeywords', 'string', $this);
				case 'MetaDescription':
					return new QQNode('meta_description', 'MetaDescription', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Product':
					return new QQNodeCategoryProduct($this);
				case 'ChildCategory':
					return new QQReverseReferenceNodeCategory($this, 'childcategory', 'reverse_reference', 'parent');

				case '_PrimaryKeyNode':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}

?>