<?php
	/**
	 * The abstract ProductQtyPricingGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the ProductQtyPricing subclass which
	 * extends this ProductQtyPricingGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the ProductQtyPricing class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $ProductId the value for intProductId (Not Null)
	 * @property integer $PricingLevel the value for intPricingLevel 
	 * @property double $Qty the value for fltQty 
	 * @property double $Price the value for fltPrice 
	 * @property Product $Product the value for the Product object referenced by intProductId (Not Null)
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ProductQtyPricingGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_product_qty_pricing.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_qty_pricing.product_id
		 * @var integer intProductId
		 */
		protected $intProductId;
		const ProductIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_qty_pricing.pricing_level
		 * @var integer intPricingLevel
		 */
		protected $intPricingLevel;
		const PricingLevelDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_qty_pricing.qty
		 * @var double fltQty
		 */
		protected $fltQty;
		const QtyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_qty_pricing.price
		 * @var double fltPrice
		 */
		protected $fltPrice;
		const PriceDefault = null;


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
		 * in the database column xlsws_product_qty_pricing.product_id.
		 *
		 * NOTE: Always use the Product property getter to correctly retrieve this Product object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Product objProduct
		 */
		protected $objProduct;





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
		 * Load a ProductQtyPricing from PK Info
		 * @param integer $intRowid
		 * @return ProductQtyPricing
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return ProductQtyPricing::QuerySingle(
				QQ::Equal(QQN::ProductQtyPricing()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all ProductQtyPricings
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductQtyPricing[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call ProductQtyPricing::QueryArray to perform the LoadAll query
			try {
				return ProductQtyPricing::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all ProductQtyPricings
		 * @return int
		 */
		public static function CountAll() {
			// Call ProductQtyPricing::QueryCount to perform the CountAll query
			return ProductQtyPricing::QueryCount(QQ::All());
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
			$objDatabase = ProductQtyPricing::GetDatabase();

			// Create/Build out the QueryBuilder object with ProductQtyPricing-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_product_qty_pricing');
			ProductQtyPricing::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_product_qty_pricing');

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
		 * Static Qcodo Query method to query for a single ProductQtyPricing object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return ProductQtyPricing the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ProductQtyPricing::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new ProductQtyPricing object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return ProductQtyPricing::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of ProductQtyPricing objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return ProductQtyPricing[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ProductQtyPricing::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return ProductQtyPricing::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of ProductQtyPricing objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ProductQtyPricing::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = ProductQtyPricing::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_product_qty_pricing_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with ProductQtyPricing-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				ProductQtyPricing::GetSelectFields($objQueryBuilder);
				ProductQtyPricing::GetFromFields($objQueryBuilder);

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
			return ProductQtyPricing::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this ProductQtyPricing
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_product_qty_pricing';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'product_id', $strAliasPrefix . 'product_id');
			$objBuilder->AddSelectItem($strTableName, 'pricing_level', $strAliasPrefix . 'pricing_level');
			$objBuilder->AddSelectItem($strTableName, 'qty', $strAliasPrefix . 'qty');
			$objBuilder->AddSelectItem($strTableName, 'price', $strAliasPrefix . 'price');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a ProductQtyPricing from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this ProductQtyPricing::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return ProductQtyPricing
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the ProductQtyPricing object
			$objToReturn = new ProductQtyPricing();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_id'] : $strAliasPrefix . 'product_id';
			$objToReturn->intProductId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'pricing_level', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'pricing_level'] : $strAliasPrefix . 'pricing_level';
			$objToReturn->intPricingLevel = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'qty', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'qty'] : $strAliasPrefix . 'qty';
			$objToReturn->fltQty = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'price', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'price'] : $strAliasPrefix . 'price';
			$objToReturn->fltPrice = $objDbRow->GetColumn($strAliasName, 'Float');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_product_qty_pricing__';

			// Check for Product Early Binding
			$strAlias = $strAliasPrefix . 'product_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objProduct = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			return $objToReturn;
		}

		/**
		 * Instantiate an array of ProductQtyPricings from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return ProductQtyPricing[]
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
					$objItem = ProductQtyPricing::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = ProductQtyPricing::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single ProductQtyPricing object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return ProductQtyPricing
		*/
		public static function LoadByRowid($intRowid) {
			return ProductQtyPricing::QuerySingle(
				QQ::Equal(QQN::ProductQtyPricing()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of ProductQtyPricing objects,
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductQtyPricing[]
		*/
		public static function LoadArrayByProductId($intProductId, $objOptionalClauses = null) {
			// Call ProductQtyPricing::QueryArray to perform the LoadArrayByProductId query
			try {
				return ProductQtyPricing::QueryArray(
					QQ::Equal(QQN::ProductQtyPricing()->ProductId, $intProductId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ProductQtyPricings
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @return int
		*/
		public static function CountByProductId($intProductId) {
			// Call ProductQtyPricing::QueryCount to perform the CountByProductId query
			return ProductQtyPricing::QueryCount(
				QQ::Equal(QQN::ProductQtyPricing()->ProductId, $intProductId)
			);
		}
			
		/**
		 * Load an array of ProductQtyPricing objects,
		 * by ProductId, PricingLevel Index(es)
		 * @param integer $intProductId
		 * @param integer $intPricingLevel
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductQtyPricing[]
		*/
		public static function LoadArrayByProductIdPricingLevel($intProductId, $intPricingLevel, $objOptionalClauses = null) {
			// Call ProductQtyPricing::QueryArray to perform the LoadArrayByProductIdPricingLevel query
			try {
				return ProductQtyPricing::QueryArray(
					QQ::AndCondition(
					QQ::Equal(QQN::ProductQtyPricing()->ProductId, $intProductId),
					QQ::Equal(QQN::ProductQtyPricing()->PricingLevel, $intPricingLevel)
					),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ProductQtyPricings
		 * by ProductId, PricingLevel Index(es)
		 * @param integer $intProductId
		 * @param integer $intPricingLevel
		 * @return int
		*/
		public static function CountByProductIdPricingLevel($intProductId, $intPricingLevel) {
			// Call ProductQtyPricing::QueryCount to perform the CountByProductIdPricingLevel query
			return ProductQtyPricing::QueryCount(
				QQ::AndCondition(
				QQ::Equal(QQN::ProductQtyPricing()->ProductId, $intProductId),
				QQ::Equal(QQN::ProductQtyPricing()->PricingLevel, $intPricingLevel)
				)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this ProductQtyPricing
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = ProductQtyPricing::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_product_qty_pricing` (
							`product_id`,
							`pricing_level`,
							`qty`,
							`price`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intProductId) . ',
							' . $objDatabase->SqlVariable($this->intPricingLevel) . ',
							' . $objDatabase->SqlVariable($this->fltQty) . ',
							' . $objDatabase->SqlVariable($this->fltPrice) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_product_qty_pricing', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_product_qty_pricing`
						SET
							`product_id` = ' . $objDatabase->SqlVariable($this->intProductId) . ',
							`pricing_level` = ' . $objDatabase->SqlVariable($this->intPricingLevel) . ',
							`qty` = ' . $objDatabase->SqlVariable($this->fltQty) . ',
							`price` = ' . $objDatabase->SqlVariable($this->fltPrice) . '
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


			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this ProductQtyPricing
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this ProductQtyPricing with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = ProductQtyPricing::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_qty_pricing`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all ProductQtyPricings
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = ProductQtyPricing::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_qty_pricing`');
		}

		/**
		 * Truncate xlsws_product_qty_pricing table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = ProductQtyPricing::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_product_qty_pricing`');
		}

		/**
		 * Reload this ProductQtyPricing from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved ProductQtyPricing object.');

			// Reload the Object
			$objReloaded = ProductQtyPricing::Load($this->intRowid);

			// Update $this's local variables to match
			$this->ProductId = $objReloaded->ProductId;
			$this->intPricingLevel = $objReloaded->intPricingLevel;
			$this->fltQty = $objReloaded->fltQty;
			$this->fltPrice = $objReloaded->fltPrice;
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

				case 'ProductId':
					// Gets the value for intProductId (Not Null)
					// @return integer
					return $this->intProductId;

				case 'PricingLevel':
					// Gets the value for intPricingLevel 
					// @return integer
					return $this->intPricingLevel;

				case 'Qty':
					// Gets the value for fltQty 
					// @return double
					return $this->fltQty;

				case 'Price':
					// Gets the value for fltPrice 
					// @return double
					return $this->fltPrice;


				///////////////////
				// Member Objects
				///////////////////
				case 'Product':
					// Gets the value for the Product object referenced by intProductId (Not Null)
					// @return Product
					try {
						if ((!$this->objProduct) && (!is_null($this->intProductId)))
							$this->objProduct = Product::Load($this->intProductId);
						return $this->objProduct;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////


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
				case 'ProductId':
					// Sets the value for intProductId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						$this->objProduct = null;
						return ($this->intProductId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PricingLevel':
					// Sets the value for intPricingLevel 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intPricingLevel = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Qty':
					// Sets the value for fltQty 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltQty = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Price':
					// Sets the value for fltPrice 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltPrice = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'Product':
					// Sets the value for the Product object referenced by intProductId (Not Null)
					// @param Product $mixValue
					// @return Product
					if (is_null($mixValue)) {
						$this->intProductId = null;
						$this->objProduct = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Product object
						try {
							$mixValue = QType::Cast($mixValue, 'Product');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Product object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Product for this ProductQtyPricing');

						// Update Local Member Variables
						$this->objProduct = $mixValue;
						$this->intProductId = $mixValue->Rowid;

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





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="ProductQtyPricing"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Product" type="xsd1:Product"/>';
			$strToReturn .= '<element name="PricingLevel" type="xsd:int"/>';
			$strToReturn .= '<element name="Qty" type="xsd:float"/>';
			$strToReturn .= '<element name="Price" type="xsd:float"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('ProductQtyPricing', $strComplexTypeArray)) {
				$strComplexTypeArray['ProductQtyPricing'] = ProductQtyPricing::GetSoapComplexTypeXml();
				Product::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, ProductQtyPricing::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new ProductQtyPricing();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Product')) &&
				($objSoapObject->Product))
				$objToReturn->Product = Product::GetObjectFromSoapObject($objSoapObject->Product);
			if (property_exists($objSoapObject, 'PricingLevel'))
				$objToReturn->intPricingLevel = $objSoapObject->PricingLevel;
			if (property_exists($objSoapObject, 'Qty'))
				$objToReturn->fltQty = $objSoapObject->Qty;
			if (property_exists($objSoapObject, 'Price'))
				$objToReturn->fltPrice = $objSoapObject->Price;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, ProductQtyPricing::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objProduct)
				$objObject->objProduct = Product::GetSoapObjectFromObject($objObject->objProduct, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intProductId = null;
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeProductQtyPricing extends QQNode {
		protected $strTableName = 'xlsws_product_qty_pricing';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'ProductQtyPricing';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'PricingLevel':
					return new QQNode('pricing_level', 'PricingLevel', 'integer', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'double', $this);
				case 'Price':
					return new QQNode('price', 'Price', 'double', $this);

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

	class QQReverseReferenceNodeProductQtyPricing extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_product_qty_pricing';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'ProductQtyPricing';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'PricingLevel':
					return new QQNode('pricing_level', 'PricingLevel', 'integer', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'double', $this);
				case 'Price':
					return new QQNode('price', 'Price', 'double', $this);

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