<?php
	/**
	 * The abstract TaxStatusGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the TaxStatus subclass which
	 * extends this TaxStatusGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the TaxStatus class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Status the value for strStatus (Not Null)
	 * @property boolean $Tax1Status the value for blnTax1Status (Not Null)
	 * @property boolean $Tax2Status the value for blnTax2Status (Not Null)
	 * @property boolean $Tax3Status the value for blnTax3Status (Not Null)
	 * @property boolean $Tax4Status the value for blnTax4Status (Not Null)
	 * @property boolean $Tax5Status the value for blnTax5Status (Not Null)
	 * @property Product $_ProductAsFk the value for the private _objProductAsFk (Read-Only) if set due to an expansion on the xlsws_product.fk_tax_status_id reverse relationship
	 * @property Product[] $_ProductAsFkArray the value for the private _objProductAsFkArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product.fk_tax_status_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class TaxStatusGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_tax_status.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_status.status
		 * @var string strStatus
		 */
		protected $strStatus;
		const StatusMaxLength = 32;
		const StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_status.tax1_status
		 * @var boolean blnTax1Status
		 */
		protected $blnTax1Status;
		const Tax1StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_status.tax2_status
		 * @var boolean blnTax2Status
		 */
		protected $blnTax2Status;
		const Tax2StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_status.tax3_status
		 * @var boolean blnTax3Status
		 */
		protected $blnTax3Status;
		const Tax3StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_status.tax4_status
		 * @var boolean blnTax4Status
		 */
		protected $blnTax4Status;
		const Tax4StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_status.tax5_status
		 * @var boolean blnTax5Status
		 */
		protected $blnTax5Status;
		const Tax5StatusDefault = null;


		/**
		 * Private member variable that stores a reference to a single ProductAsFk object
		 * (of type Product), if this TaxStatus object was restored with
		 * an expansion on the xlsws_product association table.
		 * @var Product _objProductAsFk;
		 */
		private $_objProductAsFk;

		/**
		 * Private member variable that stores a reference to an array of ProductAsFk objects
		 * (of type Product[]), if this TaxStatus object was restored with
		 * an ExpandAsArray on the xlsws_product association table.
		 * @var Product[] _objProductAsFkArray;
		 */
		private $_objProductAsFkArray = array();

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
		 * Load a TaxStatus from PK Info
		 * @param integer $intRowid
		 * @return TaxStatus
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return TaxStatus::QuerySingle(
				QQ::Equal(QQN::TaxStatus()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all TaxStatuses
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return TaxStatus[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call TaxStatus::QueryArray to perform the LoadAll query
			try {
				return TaxStatus::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all TaxStatuses
		 * @return int
		 */
		public static function CountAll() {
			// Call TaxStatus::QueryCount to perform the CountAll query
			return TaxStatus::QueryCount(QQ::All());
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
			$objDatabase = TaxStatus::GetDatabase();

			// Create/Build out the QueryBuilder object with TaxStatus-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_tax_status');
			TaxStatus::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_tax_status');

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
		 * Static Qcodo Query method to query for a single TaxStatus object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return TaxStatus the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = TaxStatus::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new TaxStatus object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return TaxStatus::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of TaxStatus objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return TaxStatus[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = TaxStatus::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return TaxStatus::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of TaxStatus objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = TaxStatus::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = TaxStatus::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_tax_status_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with TaxStatus-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				TaxStatus::GetSelectFields($objQueryBuilder);
				TaxStatus::GetFromFields($objQueryBuilder);

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
			return TaxStatus::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this TaxStatus
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_tax_status';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'status', $strAliasPrefix . 'status');
			$objBuilder->AddSelectItem($strTableName, 'tax1_status', $strAliasPrefix . 'tax1_status');
			$objBuilder->AddSelectItem($strTableName, 'tax2_status', $strAliasPrefix . 'tax2_status');
			$objBuilder->AddSelectItem($strTableName, 'tax3_status', $strAliasPrefix . 'tax3_status');
			$objBuilder->AddSelectItem($strTableName, 'tax4_status', $strAliasPrefix . 'tax4_status');
			$objBuilder->AddSelectItem($strTableName, 'tax5_status', $strAliasPrefix . 'tax5_status');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a TaxStatus from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this TaxStatus::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return TaxStatus
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
					$strAliasPrefix = 'xlsws_tax_status__';


				$strAlias = $strAliasPrefix . 'productasfk__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductAsFkArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductAsFkArray[$intPreviousChildItemCount - 1];
						$objChildItem = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfk__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductAsFkArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductAsFkArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfk__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_tax_status__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the TaxStatus object
			$objToReturn = new TaxStatus();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'status'] : $strAliasPrefix . 'status';
			$objToReturn->strStatus = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax1_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax1_status'] : $strAliasPrefix . 'tax1_status';
			$objToReturn->blnTax1Status = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax2_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax2_status'] : $strAliasPrefix . 'tax2_status';
			$objToReturn->blnTax2Status = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax3_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax3_status'] : $strAliasPrefix . 'tax3_status';
			$objToReturn->blnTax3Status = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax4_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax4_status'] : $strAliasPrefix . 'tax4_status';
			$objToReturn->blnTax4Status = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax5_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax5_status'] : $strAliasPrefix . 'tax5_status';
			$objToReturn->blnTax5Status = $objDbRow->GetColumn($strAliasName, 'Bit');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_tax_status__';




			// Check for ProductAsFk Virtual Binding
			$strAlias = $strAliasPrefix . 'productasfk__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductAsFkArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfk__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProductAsFk = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfk__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of TaxStatuses from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return TaxStatus[]
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
					$objItem = TaxStatus::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = TaxStatus::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single TaxStatus object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return TaxStatus
		*/
		public static function LoadByRowid($intRowid) {
			return TaxStatus::QuerySingle(
				QQ::Equal(QQN::TaxStatus()->Rowid, $intRowid)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this TaxStatus
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_tax_status` (
							`status`,
							`tax1_status`,
							`tax2_status`,
							`tax3_status`,
							`tax4_status`,
							`tax5_status`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strStatus) . ',
							' . $objDatabase->SqlVariable($this->blnTax1Status) . ',
							' . $objDatabase->SqlVariable($this->blnTax2Status) . ',
							' . $objDatabase->SqlVariable($this->blnTax3Status) . ',
							' . $objDatabase->SqlVariable($this->blnTax4Status) . ',
							' . $objDatabase->SqlVariable($this->blnTax5Status) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_tax_status', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_tax_status`
						SET
							`status` = ' . $objDatabase->SqlVariable($this->strStatus) . ',
							`tax1_status` = ' . $objDatabase->SqlVariable($this->blnTax1Status) . ',
							`tax2_status` = ' . $objDatabase->SqlVariable($this->blnTax2Status) . ',
							`tax3_status` = ' . $objDatabase->SqlVariable($this->blnTax3Status) . ',
							`tax4_status` = ' . $objDatabase->SqlVariable($this->blnTax4Status) . ',
							`tax5_status` = ' . $objDatabase->SqlVariable($this->blnTax5Status) . '
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
		 * Delete this TaxStatus
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this TaxStatus with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_tax_status`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all TaxStatuses
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_tax_status`');
		}

		/**
		 * Truncate xlsws_tax_status table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_tax_status`');
		}

		/**
		 * Reload this TaxStatus from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved TaxStatus object.');

			// Reload the Object
			$objReloaded = TaxStatus::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strStatus = $objReloaded->strStatus;
			$this->blnTax1Status = $objReloaded->blnTax1Status;
			$this->blnTax2Status = $objReloaded->blnTax2Status;
			$this->blnTax3Status = $objReloaded->blnTax3Status;
			$this->blnTax4Status = $objReloaded->blnTax4Status;
			$this->blnTax5Status = $objReloaded->blnTax5Status;
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

				case 'Status':
					// Gets the value for strStatus (Not Null)
					// @return string
					return $this->strStatus;

				case 'Tax1Status':
					// Gets the value for blnTax1Status (Not Null)
					// @return boolean
					return $this->blnTax1Status;

				case 'Tax2Status':
					// Gets the value for blnTax2Status (Not Null)
					// @return boolean
					return $this->blnTax2Status;

				case 'Tax3Status':
					// Gets the value for blnTax3Status (Not Null)
					// @return boolean
					return $this->blnTax3Status;

				case 'Tax4Status':
					// Gets the value for blnTax4Status (Not Null)
					// @return boolean
					return $this->blnTax4Status;

				case 'Tax5Status':
					// Gets the value for blnTax5Status (Not Null)
					// @return boolean
					return $this->blnTax5Status;


				///////////////////
				// Member Objects
				///////////////////

				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_ProductAsFk':
					// Gets the value for the private _objProductAsFk (Read-Only)
					// if set due to an expansion on the xlsws_product.fk_tax_status_id reverse relationship
					// @return Product
					return $this->_objProductAsFk;

				case '_ProductAsFkArray':
					// Gets the value for the private _objProductAsFkArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product.fk_tax_status_id reverse relationship
					// @return Product[]
					return (array) $this->_objProductAsFkArray;


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
				case 'Status':
					// Sets the value for strStatus (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strStatus = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax1Status':
					// Sets the value for blnTax1Status (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnTax1Status = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax2Status':
					// Sets the value for blnTax2Status (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnTax2Status = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax3Status':
					// Sets the value for blnTax3Status (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnTax3Status = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax4Status':
					// Sets the value for blnTax4Status (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnTax4Status = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax5Status':
					// Sets the value for blnTax5Status (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnTax5Status = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
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

			
		
		// Related Objects' Methods for ProductAsFk
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ProductsAsFk as an array of Product objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/ 
		public function GetProductAsFkArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Product::LoadArrayByFkTaxStatusId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ProductsAsFk
		 * @return int
		*/ 
		public function CountProductsAsFk() {
			if ((is_null($this->intRowid)))
				return 0;

			return Product::CountByFkTaxStatusId($this->intRowid);
		}

		/**
		 * Associates a ProductAsFk
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function AssociateProductAsFk(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductAsFk on this unsaved TaxStatus.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductAsFk on this TaxStatus with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product`
				SET
					`fk_tax_status_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . '
			');
		}

		/**
		 * Unassociates a ProductAsFk
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function UnassociateProductAsFk(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFk on this unsaved TaxStatus.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFk on this TaxStatus with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product`
				SET
					`fk_tax_status_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . ' AND
					`fk_tax_status_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ProductsAsFk
		 * @return void
		*/ 
		public function UnassociateAllProductsAsFk() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFk on this unsaved TaxStatus.');

			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product`
				SET
					`fk_tax_status_id` = null
				WHERE
					`fk_tax_status_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ProductAsFk
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function DeleteAssociatedProductAsFk(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFk on this unsaved TaxStatus.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFk on this TaxStatus with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . ' AND
					`fk_tax_status_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ProductsAsFk
		 * @return void
		*/ 
		public function DeleteAllProductsAsFk() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFk on this unsaved TaxStatus.');

			// Get the Database Object for this Class
			$objDatabase = TaxStatus::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product`
				WHERE
					`fk_tax_status_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="TaxStatus"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Status" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax1Status" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Tax2Status" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Tax3Status" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Tax4Status" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Tax5Status" type="xsd:boolean"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('TaxStatus', $strComplexTypeArray)) {
				$strComplexTypeArray['TaxStatus'] = TaxStatus::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, TaxStatus::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new TaxStatus();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Status'))
				$objToReturn->strStatus = $objSoapObject->Status;
			if (property_exists($objSoapObject, 'Tax1Status'))
				$objToReturn->blnTax1Status = $objSoapObject->Tax1Status;
			if (property_exists($objSoapObject, 'Tax2Status'))
				$objToReturn->blnTax2Status = $objSoapObject->Tax2Status;
			if (property_exists($objSoapObject, 'Tax3Status'))
				$objToReturn->blnTax3Status = $objSoapObject->Tax3Status;
			if (property_exists($objSoapObject, 'Tax4Status'))
				$objToReturn->blnTax4Status = $objSoapObject->Tax4Status;
			if (property_exists($objSoapObject, 'Tax5Status'))
				$objToReturn->blnTax5Status = $objSoapObject->Tax5Status;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, TaxStatus::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeTaxStatus extends QQNode {
		protected $strTableName = 'xlsws_tax_status';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'TaxStatus';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Status':
					return new QQNode('status', 'Status', 'string', $this);
				case 'Tax1Status':
					return new QQNode('tax1_status', 'Tax1Status', 'boolean', $this);
				case 'Tax2Status':
					return new QQNode('tax2_status', 'Tax2Status', 'boolean', $this);
				case 'Tax3Status':
					return new QQNode('tax3_status', 'Tax3Status', 'boolean', $this);
				case 'Tax4Status':
					return new QQNode('tax4_status', 'Tax4Status', 'boolean', $this);
				case 'Tax5Status':
					return new QQNode('tax5_status', 'Tax5Status', 'boolean', $this);
				case 'ProductAsFk':
					return new QQReverseReferenceNodeProduct($this, 'productasfk', 'reverse_reference', 'fk_tax_status_id');

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

	class QQReverseReferenceNodeTaxStatus extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_tax_status';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'TaxStatus';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Status':
					return new QQNode('status', 'Status', 'string', $this);
				case 'Tax1Status':
					return new QQNode('tax1_status', 'Tax1Status', 'boolean', $this);
				case 'Tax2Status':
					return new QQNode('tax2_status', 'Tax2Status', 'boolean', $this);
				case 'Tax3Status':
					return new QQNode('tax3_status', 'Tax3Status', 'boolean', $this);
				case 'Tax4Status':
					return new QQNode('tax4_status', 'Tax4Status', 'boolean', $this);
				case 'Tax5Status':
					return new QQNode('tax5_status', 'Tax5Status', 'boolean', $this);
				case 'ProductAsFk':
					return new QQReverseReferenceNodeProduct($this, 'productasfk', 'reverse_reference', 'fk_tax_status_id');

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