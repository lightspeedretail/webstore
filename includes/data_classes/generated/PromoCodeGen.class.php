<?php
	/**
	 * The abstract PromoCodeGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the PromoCode subclass which
	 * extends this PromoCodeGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the PromoCode class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property boolean $Enabled the value for blnEnabled (Not Null)
	 * @property integer $Except the value for intExcept (Not Null)
	 * @property string $Code the value for strCode 
	 * @property integer $Type the value for intType 
	 * @property string $Amount the value for strAmount (Not Null)
	 * @property string $ValidFrom the value for strValidFrom (Not Null)
	 * @property integer $QtyRemaining the value for intQtyRemaining (Not Null)
	 * @property string $ValidUntil the value for strValidUntil 
	 * @property string $Lscodes the value for strLscodes (Not Null)
	 * @property string $Threshold the value for strThreshold (Not Null)
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class PromoCodeGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_promo_code.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.enabled
		 * @var boolean blnEnabled
		 */
		protected $blnEnabled;
		const EnabledDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.enabled
		 * @var boolean blnEnabled
		 */
		protected $intExcept;
		const ExceptDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.code
		 * @var string strCode
		 */
		protected $strCode;
		const CodeMaxLength = 255;
		const CodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.type
		 * @var integer intType
		 */
		protected $intType;
		const TypeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.amount
		 * @var string strAmount
		 */
		protected $strAmount;
		const AmountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.valid_from
		 * @var string strValidFrom
		 */
		protected $strValidFrom;
		const ValidFromDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.qty_remaining
		 * @var integer intQtyRemaining
		 */
		protected $intQtyRemaining;
		const QtyRemainingDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.valid_until
		 * @var string strValidUntil
		 */
		protected $strValidUntil;
		const ValidUntilDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.lscodes
		 * @var string strLscodes
		 */
		protected $strLscodes;
		const LscodesDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_promo_code.threshold
		 * @var string strThreshold
		 */
		protected $strThreshold;
		const ThresholdDefault = null;


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
		 * Load a PromoCode from PK Info
		 * @param integer $intRowid
		 * @return PromoCode
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return PromoCode::QuerySingle(
				QQ::Equal(QQN::PromoCode()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all PromoCodes
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return PromoCode[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call PromoCode::QueryArray to perform the LoadAll query
			try {
				return PromoCode::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all PromoCodes
		 * @return int
		 */
		public static function CountAll() {
			// Call PromoCode::QueryCount to perform the CountAll query
			return PromoCode::QueryCount(QQ::All());
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
			$objDatabase = PromoCode::GetDatabase();

			// Create/Build out the QueryBuilder object with PromoCode-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_promo_code');
			PromoCode::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_promo_code');

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
		 * Static Qcodo Query method to query for a single PromoCode object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return PromoCode the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = PromoCode::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new PromoCode object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return PromoCode::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of PromoCode objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return PromoCode[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = PromoCode::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return PromoCode::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of PromoCode objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = PromoCode::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = PromoCode::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_promo_code_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with PromoCode-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				PromoCode::GetSelectFields($objQueryBuilder);
				PromoCode::GetFromFields($objQueryBuilder);

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
			return PromoCode::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this PromoCode
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_promo_code';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'enabled', $strAliasPrefix . 'enabled');
			$objBuilder->AddSelectItem($strTableName, 'except', $strAliasPrefix . 'except');
			$objBuilder->AddSelectItem($strTableName, 'code', $strAliasPrefix . 'code');
			$objBuilder->AddSelectItem($strTableName, 'type', $strAliasPrefix . 'type');
			$objBuilder->AddSelectItem($strTableName, 'amount', $strAliasPrefix . 'amount');
			$objBuilder->AddSelectItem($strTableName, 'valid_from', $strAliasPrefix . 'valid_from');
			$objBuilder->AddSelectItem($strTableName, 'qty_remaining', $strAliasPrefix . 'qty_remaining');
			$objBuilder->AddSelectItem($strTableName, 'valid_until', $strAliasPrefix . 'valid_until');
			$objBuilder->AddSelectItem($strTableName, 'lscodes', $strAliasPrefix . 'lscodes');
			$objBuilder->AddSelectItem($strTableName, 'threshold', $strAliasPrefix . 'threshold');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a PromoCode from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this PromoCode::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return PromoCode
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the PromoCode object
			$objToReturn = new PromoCode();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'enabled', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'enabled'] : $strAliasPrefix . 'enabled';
			$objToReturn->blnEnabled = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'except', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'except'] : $strAliasPrefix . 'except';
			$objToReturn->intExcept = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code'] : $strAliasPrefix . 'code';
			$objToReturn->strCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'type', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'type'] : $strAliasPrefix . 'type';
			$objToReturn->intType = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'amount', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'amount'] : $strAliasPrefix . 'amount';
			$objToReturn->strAmount = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'valid_from', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'valid_from'] : $strAliasPrefix . 'valid_from';
			$objToReturn->strValidFrom = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'qty_remaining', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'qty_remaining'] : $strAliasPrefix . 'qty_remaining';
			$objToReturn->intQtyRemaining = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'valid_until', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'valid_until'] : $strAliasPrefix . 'valid_until';
			$objToReturn->strValidUntil = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'lscodes', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'lscodes'] : $strAliasPrefix . 'lscodes';
			$objToReturn->strLscodes = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'threshold', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'threshold'] : $strAliasPrefix . 'threshold';
			$objToReturn->strThreshold = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_promo_code__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of PromoCodes from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return PromoCode[]
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
					$objItem = PromoCode::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = PromoCode::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single PromoCode object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return PromoCode
		*/
		public static function LoadByRowid($intRowid) {
			return PromoCode::QuerySingle(
				QQ::Equal(QQN::PromoCode()->Rowid, $intRowid)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this PromoCode
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_promo_code` (
							`enabled`,
							`except`,
							`code`,
							`type`,
							`amount`,
							`valid_from`,
							`qty_remaining`,
							`valid_until`,
							`lscodes`,
							`threshold`
						) VALUES (
							' . $objDatabase->SqlVariable($this->blnEnabled) . ',
							' . $objDatabase->SqlVariable($this->intExcept) . ',
							' . $objDatabase->SqlVariable($this->strCode) . ',
							' . $objDatabase->SqlVariable($this->intType) . ',
							' . $objDatabase->SqlVariable($this->strAmount) . ',
							' . $objDatabase->SqlVariable($this->strValidFrom) . ',
							' . $objDatabase->SqlVariable($this->intQtyRemaining) . ',
							' . $objDatabase->SqlVariable($this->strValidUntil) . ',
							' . $objDatabase->SqlVariable($this->strLscodes) . ',
							' . $objDatabase->SqlVariable($this->strThreshold) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_promo_code', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_promo_code`
						SET
							`enabled` = ' . $objDatabase->SqlVariable($this->blnEnabled) . ',
							`except` = ' . $objDatabase->SqlVariable($this->intExcept) . ',
							`code` = ' . $objDatabase->SqlVariable($this->strCode) . ',
							`type` = ' . $objDatabase->SqlVariable($this->intType) . ',
							`amount` = ' . $objDatabase->SqlVariable($this->strAmount) . ',
							`valid_from` = ' . $objDatabase->SqlVariable($this->strValidFrom) . ',
							`qty_remaining` = ' . $objDatabase->SqlVariable($this->intQtyRemaining) . ',
							`valid_until` = ' . $objDatabase->SqlVariable($this->strValidUntil) . ',
							`lscodes` = ' . $objDatabase->SqlVariable($this->strLscodes) . ',
							`threshold` = ' . $objDatabase->SqlVariable($this->strThreshold) . '
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
		 * Delete this PromoCode
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this PromoCode with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_promo_code`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all PromoCodes
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_promo_code`');
		}

		/**
		 * Truncate xlsws_promo_code table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = PromoCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_promo_code`');
		}

		/**
		 * Reload this PromoCode from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved PromoCode object.');

			// Reload the Object
			$objReloaded = PromoCode::Load($this->intRowid);

			// Update $this's local variables to match
			$this->blnEnabled = $objReloaded->blnEnabled;
			$this->intExcept = $objReloaded->intExcept;
			$this->strCode = $objReloaded->strCode;
			$this->intType = $objReloaded->intType;
			$this->strAmount = $objReloaded->strAmount;
			$this->strValidFrom = $objReloaded->strValidFrom;
			$this->intQtyRemaining = $objReloaded->intQtyRemaining;
			$this->strValidUntil = $objReloaded->strValidUntil;
			$this->strLscodes = $objReloaded->strLscodes;
			$this->strThreshold = $objReloaded->strThreshold;
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

				case 'Enabled':
					// Gets the value for blnEnabled (Not Null)
					// @return boolean
					return $this->blnEnabled;

				case 'Except':
					// Gets the value for intExcept (Not Null)
					// @return integer
					return $this->intExcept;
					
				case 'Code':
					// Gets the value for strCode 
					// @return string
					return $this->strCode;

				case 'Type':
					// Gets the value for intType 
					// @return integer
					return $this->intType;

				case 'Amount':
					// Gets the value for strAmount (Not Null)
					// @return string
					return $this->strAmount;

				case 'ValidFrom':
					// Gets the value for strValidFrom (Not Null)
					// @return string
					return $this->strValidFrom;

				case 'QtyRemaining':
					// Gets the value for intQtyRemaining (Not Null)
					// @return integer
					return $this->intQtyRemaining;

				case 'ValidUntil':
					// Gets the value for strValidUntil 
					// @return string
					return $this->strValidUntil;

				case 'Lscodes':
					// Gets the value for strLscodes (Not Null)
					// @return string
					return $this->strLscodes;

				case 'Threshold':
					// Gets the value for strThreshold (Not Null)
					// @return string
					return $this->strThreshold;


				///////////////////
				// Member Objects
				///////////////////

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
				case 'Enabled':
					// Sets the value for blnEnabled (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnEnabled = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case 'Except':
					// Sets the value for intExcept (Not Null)
					// @param boolean $mixValue
					// @return integer
					try {
						return ($this->intExcept = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case 'Code':
					// Sets the value for strCode 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCode = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Type':
					// Sets the value for intType 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intType = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Amount':
					// Sets the value for strAmount (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAmount = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ValidFrom':
					// Sets the value for strValidFrom (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strValidFrom = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'QtyRemaining':
					// Sets the value for intQtyRemaining (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intQtyRemaining = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ValidUntil':
					// Sets the value for strValidUntil 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strValidUntil = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Lscodes':
					// Sets the value for strLscodes (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strLscodes = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Threshold':
					// Sets the value for strThreshold (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strThreshold = QType::Cast($mixValue, QType::String));
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





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="PromoCode"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Enabled" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Except" type="xsd:int"/>';
			$strToReturn .= '<element name="Code" type="xsd:string"/>';
			$strToReturn .= '<element name="Type" type="xsd:int"/>';
			$strToReturn .= '<element name="Amount" type="xsd:string"/>';
			$strToReturn .= '<element name="ValidFrom" type="xsd:string"/>';
			$strToReturn .= '<element name="QtyRemaining" type="xsd:int"/>';
			$strToReturn .= '<element name="ValidUntil" type="xsd:string"/>';
			$strToReturn .= '<element name="Lscodes" type="xsd:string"/>';
			$strToReturn .= '<element name="Threshold" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('PromoCode', $strComplexTypeArray)) {
				$strComplexTypeArray['PromoCode'] = PromoCode::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, PromoCode::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new PromoCode();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Enabled'))
				$objToReturn->blnEnabled = $objSoapObject->Enabled;
			if (property_exists($objSoapObject, 'Except'))
				$objToReturn->intExcept = $objSoapObject->Except;
			if (property_exists($objSoapObject, 'Code'))
				$objToReturn->strCode = $objSoapObject->Code;
			if (property_exists($objSoapObject, 'Type'))
				$objToReturn->intType = $objSoapObject->Type;
			if (property_exists($objSoapObject, 'Amount'))
				$objToReturn->strAmount = $objSoapObject->Amount;
			if (property_exists($objSoapObject, 'ValidFrom'))
				$objToReturn->strValidFrom = $objSoapObject->ValidFrom;
			if (property_exists($objSoapObject, 'QtyRemaining'))
				$objToReturn->intQtyRemaining = $objSoapObject->QtyRemaining;
			if (property_exists($objSoapObject, 'ValidUntil'))
				$objToReturn->strValidUntil = $objSoapObject->ValidUntil;
			if (property_exists($objSoapObject, 'Lscodes'))
				$objToReturn->strLscodes = $objSoapObject->Lscodes;
			if (property_exists($objSoapObject, 'Threshold'))
				$objToReturn->strThreshold = $objSoapObject->Threshold;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, PromoCode::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodePromoCode extends QQNode {
		protected $strTableName = 'xlsws_promo_code';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'PromoCode';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Enabled':
					return new QQNode('enabled', 'Enabled', 'boolean', $this);
				case 'Except':
					return new QQNode('except', 'Except', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'integer', $this);
				case 'Amount':
					return new QQNode('amount', 'Amount', 'string', $this);
				case 'ValidFrom':
					return new QQNode('valid_from', 'ValidFrom', 'string', $this);
				case 'QtyRemaining':
					return new QQNode('qty_remaining', 'QtyRemaining', 'integer', $this);
				case 'ValidUntil':
					return new QQNode('valid_until', 'ValidUntil', 'string', $this);
				case 'Lscodes':
					return new QQNode('lscodes', 'Lscodes', 'string', $this);
				case 'Threshold':
					return new QQNode('threshold', 'Threshold', 'string', $this);

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

	class QQReverseReferenceNodePromoCode extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_promo_code';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'PromoCode';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Enabled':
					return new QQNode('enabled', 'Enabled', 'boolean', $this);
				case 'Except':
					return new QQNode('except', 'Except', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'integer', $this);
				case 'Amount':
					return new QQNode('amount', 'Amount', 'string', $this);
				case 'ValidFrom':
					return new QQNode('valid_from', 'ValidFrom', 'string', $this);
				case 'QtyRemaining':
					return new QQNode('qty_remaining', 'QtyRemaining', 'integer', $this);
				case 'ValidUntil':
					return new QQNode('valid_until', 'ValidUntil', 'string', $this);
				case 'Lscodes':
					return new QQNode('lscodes', 'Lscodes', 'string', $this);
				case 'Threshold':
					return new QQNode('threshold', 'Threshold', 'string', $this);

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