<?php
	/**
	 * The abstract ProductRelatedGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the ProductRelated subclass which
	 * extends this ProductRelatedGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the ProductRelated class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $ProductId the value for intProductId (Not Null)
	 * @property integer $RelatedId the value for intRelatedId (Not Null)
	 * @property boolean $Autoadd the value for blnAutoadd 
	 * @property double $Qty the value for fltQty 
	 * @property Product $Product the value for the Product object referenced by intProductId (Not Null)
	 * @property Product $Related the value for the Product object referenced by intRelatedId (Not Null)
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ProductRelatedGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_product_related.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_related.product_id
		 * @var integer intProductId
		 */
		protected $intProductId;
		const ProductIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_related.related_id
		 * @var integer intRelatedId
		 */
		protected $intRelatedId;
		const RelatedIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_related.autoadd
		 * @var boolean blnAutoadd
		 */
		protected $blnAutoadd;
		const AutoaddDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product_related.qty
		 * @var double fltQty
		 */
		protected $fltQty;
		const QtyDefault = null;


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
		 * in the database column xlsws_product_related.product_id.
		 *
		 * NOTE: Always use the Product property getter to correctly retrieve this Product object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Product objProduct
		 */
		protected $objProduct;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_product_related.related_id.
		 *
		 * NOTE: Always use the Related property getter to correctly retrieve this Product object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Product objRelated
		 */
		protected $objRelated;





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
		 * Load a ProductRelated from PK Info
		 * @param integer $intRowid
		 * @return ProductRelated
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return ProductRelated::QuerySingle(
				QQ::Equal(QQN::ProductRelated()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all ProductRelateds
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductRelated[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call ProductRelated::QueryArray to perform the LoadAll query
			try {
				return ProductRelated::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all ProductRelateds
		 * @return int
		 */
		public static function CountAll() {
			// Call ProductRelated::QueryCount to perform the CountAll query
			return ProductRelated::QueryCount(QQ::All());
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
			$objDatabase = ProductRelated::GetDatabase();

			// Create/Build out the QueryBuilder object with ProductRelated-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_product_related');
			ProductRelated::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_product_related');

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
		 * Static Qcodo Query method to query for a single ProductRelated object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return ProductRelated the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ProductRelated::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new ProductRelated object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return ProductRelated::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of ProductRelated objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return ProductRelated[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ProductRelated::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return ProductRelated::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of ProductRelated objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ProductRelated::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = ProductRelated::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_product_related_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with ProductRelated-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				ProductRelated::GetSelectFields($objQueryBuilder);
				ProductRelated::GetFromFields($objQueryBuilder);

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
			return ProductRelated::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this ProductRelated
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_product_related';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'product_id', $strAliasPrefix . 'product_id');
			$objBuilder->AddSelectItem($strTableName, 'related_id', $strAliasPrefix . 'related_id');
			$objBuilder->AddSelectItem($strTableName, 'autoadd', $strAliasPrefix . 'autoadd');
			$objBuilder->AddSelectItem($strTableName, 'qty', $strAliasPrefix . 'qty');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a ProductRelated from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this ProductRelated::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return ProductRelated
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the ProductRelated object
			$objToReturn = new ProductRelated();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_id'] : $strAliasPrefix . 'product_id';
			$objToReturn->intProductId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'related_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'related_id'] : $strAliasPrefix . 'related_id';
			$objToReturn->intRelatedId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'autoadd', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'autoadd'] : $strAliasPrefix . 'autoadd';
			$objToReturn->blnAutoadd = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'qty', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'qty'] : $strAliasPrefix . 'qty';
			$objToReturn->fltQty = $objDbRow->GetColumn($strAliasName, 'Float');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_product_related__';

			// Check for Product Early Binding
			$strAlias = $strAliasPrefix . 'product_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objProduct = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for Related Early Binding
			$strAlias = $strAliasPrefix . 'related_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objRelated = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'related_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			return $objToReturn;
		}

		/**
		 * Instantiate an array of ProductRelateds from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return ProductRelated[]
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
					$objItem = ProductRelated::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = ProductRelated::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single ProductRelated object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return ProductRelated
		*/
		public static function LoadByRowid($intRowid) {
			return ProductRelated::QuerySingle(
				QQ::Equal(QQN::ProductRelated()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single ProductRelated object,
		 * by ProductId, RelatedId Index(es)
		 * @param integer $intProductId
		 * @param integer $intRelatedId
		 * @return ProductRelated
		*/
		public static function LoadByProductIdRelatedId($intProductId, $intRelatedId) {
			return ProductRelated::QuerySingle(
				QQ::AndCondition(
				QQ::Equal(QQN::ProductRelated()->ProductId, $intProductId),
				QQ::Equal(QQN::ProductRelated()->RelatedId, $intRelatedId)
				)
			);
		}
			
		/**
		 * Load an array of ProductRelated objects,
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductRelated[]
		*/
		public static function LoadArrayByProductId($intProductId, $objOptionalClauses = null) {
			// Call ProductRelated::QueryArray to perform the LoadArrayByProductId query
			try {
				return ProductRelated::QueryArray(
					QQ::Equal(QQN::ProductRelated()->ProductId, $intProductId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ProductRelateds
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @return int
		*/
		public static function CountByProductId($intProductId) {
			// Call ProductRelated::QueryCount to perform the CountByProductId query
			return ProductRelated::QueryCount(
				QQ::Equal(QQN::ProductRelated()->ProductId, $intProductId)
			);
		}
			
		/**
		 * Load an array of ProductRelated objects,
		 * by RelatedId Index(es)
		 * @param integer $intRelatedId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductRelated[]
		*/
		public static function LoadArrayByRelatedId($intRelatedId, $objOptionalClauses = null) {
			// Call ProductRelated::QueryArray to perform the LoadArrayByRelatedId query
			try {
				return ProductRelated::QueryArray(
					QQ::Equal(QQN::ProductRelated()->RelatedId, $intRelatedId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ProductRelateds
		 * by RelatedId Index(es)
		 * @param integer $intRelatedId
		 * @return int
		*/
		public static function CountByRelatedId($intRelatedId) {
			// Call ProductRelated::QueryCount to perform the CountByRelatedId query
			return ProductRelated::QueryCount(
				QQ::Equal(QQN::ProductRelated()->RelatedId, $intRelatedId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this ProductRelated
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = ProductRelated::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_product_related` (
							`product_id`,
							`related_id`,
							`autoadd`,
							`qty`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intProductId) . ',
							' . $objDatabase->SqlVariable($this->intRelatedId) . ',
							' . $objDatabase->SqlVariable($this->blnAutoadd) . ',
							' . $objDatabase->SqlVariable($this->fltQty) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_product_related', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_product_related`
						SET
							`product_id` = ' . $objDatabase->SqlVariable($this->intProductId) . ',
							`related_id` = ' . $objDatabase->SqlVariable($this->intRelatedId) . ',
							`autoadd` = ' . $objDatabase->SqlVariable($this->blnAutoadd) . ',
							`qty` = ' . $objDatabase->SqlVariable($this->fltQty) . '
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
		 * Delete this ProductRelated
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this ProductRelated with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = ProductRelated::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_related`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all ProductRelateds
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = ProductRelated::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_related`');
		}

		/**
		 * Truncate xlsws_product_related table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = ProductRelated::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_product_related`');
		}

		/**
		 * Reload this ProductRelated from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved ProductRelated object.');

			// Reload the Object
			$objReloaded = ProductRelated::Load($this->intRowid);

			// Update $this's local variables to match
			$this->ProductId = $objReloaded->ProductId;
			$this->RelatedId = $objReloaded->RelatedId;
			$this->blnAutoadd = $objReloaded->blnAutoadd;
			$this->fltQty = $objReloaded->fltQty;
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

				case 'RelatedId':
					// Gets the value for intRelatedId (Not Null)
					// @return integer
					return $this->intRelatedId;

				case 'Autoadd':
					// Gets the value for blnAutoadd 
					// @return boolean
					return $this->blnAutoadd;

				case 'Qty':
					// Gets the value for fltQty 
					// @return double
					return $this->fltQty;


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

				case 'Related':
					// Gets the value for the Product object referenced by intRelatedId (Not Null)
					// @return Product
					try {
						if ((!$this->objRelated) && (!is_null($this->intRelatedId)))
							$this->objRelated = Product::Load($this->intRelatedId);
						return $this->objRelated;
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

				case 'RelatedId':
					// Sets the value for intRelatedId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						$this->objRelated = null;
						return ($this->intRelatedId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Autoadd':
					// Sets the value for blnAutoadd 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnAutoadd = QType::Cast($mixValue, QType::Boolean));
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
							throw new QCallerException('Unable to set an unsaved Product for this ProductRelated');

						// Update Local Member Variables
						$this->objProduct = $mixValue;
						$this->intProductId = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				case 'Related':
					// Sets the value for the Product object referenced by intRelatedId (Not Null)
					// @param Product $mixValue
					// @return Product
					if (is_null($mixValue)) {
						$this->intRelatedId = null;
						$this->objRelated = null;
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
							throw new QCallerException('Unable to set an unsaved Related for this ProductRelated');

						// Update Local Member Variables
						$this->objRelated = $mixValue;
						$this->intRelatedId = $mixValue->Rowid;

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
			$strToReturn = '<complexType name="ProductRelated"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Product" type="xsd1:Product"/>';
			$strToReturn .= '<element name="Related" type="xsd1:Product"/>';
			$strToReturn .= '<element name="Autoadd" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Qty" type="xsd:float"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('ProductRelated', $strComplexTypeArray)) {
				$strComplexTypeArray['ProductRelated'] = ProductRelated::GetSoapComplexTypeXml();
				Product::AlterSoapComplexTypeArray($strComplexTypeArray);
				Product::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, ProductRelated::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new ProductRelated();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Product')) &&
				($objSoapObject->Product))
				$objToReturn->Product = Product::GetObjectFromSoapObject($objSoapObject->Product);
			if ((property_exists($objSoapObject, 'Related')) &&
				($objSoapObject->Related))
				$objToReturn->Related = Product::GetObjectFromSoapObject($objSoapObject->Related);
			if (property_exists($objSoapObject, 'Autoadd'))
				$objToReturn->blnAutoadd = $objSoapObject->Autoadd;
			if (property_exists($objSoapObject, 'Qty'))
				$objToReturn->fltQty = $objSoapObject->Qty;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, ProductRelated::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objProduct)
				$objObject->objProduct = Product::GetSoapObjectFromObject($objObject->objProduct, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intProductId = null;
			if ($objObject->objRelated)
				$objObject->objRelated = Product::GetSoapObjectFromObject($objObject->objRelated, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intRelatedId = null;
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeProductRelated extends QQNode {
		protected $strTableName = 'xlsws_product_related';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'ProductRelated';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'RelatedId':
					return new QQNode('related_id', 'RelatedId', 'integer', $this);
				case 'Related':
					return new QQNodeProduct('related_id', 'Related', 'integer', $this);
				case 'Autoadd':
					return new QQNode('autoadd', 'Autoadd', 'boolean', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'double', $this);

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

	class QQReverseReferenceNodeProductRelated extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_product_related';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'ProductRelated';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'RelatedId':
					return new QQNode('related_id', 'RelatedId', 'integer', $this);
				case 'Related':
					return new QQNodeProduct('related_id', 'Related', 'integer', $this);
				case 'Autoadd':
					return new QQNode('autoadd', 'Autoadd', 'boolean', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'double', $this);

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