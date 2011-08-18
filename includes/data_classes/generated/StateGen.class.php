<?php
	/**
	 * The abstract StateGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the State subclass which
	 * extends this StateGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the State class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $CountryCode the value for strCountryCode (Not Null)
	 * @property string $Code the value for strCode (Not Null)
	 * @property string $Avail the value for strAvail (Not Null)
	 * @property integer $SortOrder the value for intSortOrder 
	 * @property string $State the value for strState (Not Null)
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class StateGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_state.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_state.country_code
		 * @var string strCountryCode
		 */
		protected $strCountryCode;
		const CountryCodeMaxLength = 2;
		const CountryCodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_state.code
		 * @var string strCode
		 */
		protected $strCode;
		const CodeMaxLength = 32;
		const CodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_state.avail
		 * @var string strAvail
		 */
		protected $strAvail;
		const AvailMaxLength = 1;
		const AvailDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_state.sort_order
		 * @var integer intSortOrder
		 */
		protected $intSortOrder;
		const SortOrderDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_state.state
		 * @var string strState
		 */
		protected $strState;
		const StateMaxLength = 255;
		const StateDefault = null;


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
		 * Load a State from PK Info
		 * @param integer $intRowid
		 * @return State
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return State::QuerySingle(
				QQ::Equal(QQN::State()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all States
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return State[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call State::QueryArray to perform the LoadAll query
			try {
				return State::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all States
		 * @return int
		 */
		public static function CountAll() {
			// Call State::QueryCount to perform the CountAll query
			return State::QueryCount(QQ::All());
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
			$objDatabase = State::GetDatabase();

			// Create/Build out the QueryBuilder object with State-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_state');
			State::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_state');

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
		 * Static Qcodo Query method to query for a single State object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return State the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = State::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new State object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return State::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of State objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return State[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = State::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return State::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of State objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = State::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = State::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_state_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with State-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				State::GetSelectFields($objQueryBuilder);
				State::GetFromFields($objQueryBuilder);

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
			return State::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this State
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_state';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'country_code', $strAliasPrefix . 'country_code');
			$objBuilder->AddSelectItem($strTableName, 'code', $strAliasPrefix . 'code');
			$objBuilder->AddSelectItem($strTableName, 'avail', $strAliasPrefix . 'avail');
			$objBuilder->AddSelectItem($strTableName, 'sort_order', $strAliasPrefix . 'sort_order');
			$objBuilder->AddSelectItem($strTableName, 'state', $strAliasPrefix . 'state');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a State from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this State::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return State
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the State object
			$objToReturn = new State();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'country_code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'country_code'] : $strAliasPrefix . 'country_code';
			$objToReturn->strCountryCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code'] : $strAliasPrefix . 'code';
			$objToReturn->strCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'avail', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'avail'] : $strAliasPrefix . 'avail';
			$objToReturn->strAvail = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sort_order', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sort_order'] : $strAliasPrefix . 'sort_order';
			$objToReturn->intSortOrder = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'state', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'state'] : $strAliasPrefix . 'state';
			$objToReturn->strState = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_state__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of States from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return State[]
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
					$objItem = State::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = State::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single State object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return State
		*/
		public static function LoadByRowid($intRowid) {
			return State::QuerySingle(
				QQ::Equal(QQN::State()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single State object,
		 * by CountryCode, Code Index(es)
		 * @param string $strCountryCode
		 * @param string $strCode
		 * @return State
		*/
		public static function LoadByCountryCodeCode($strCountryCode, $strCode) {
			return State::QuerySingle(
				QQ::AndCondition(
				QQ::Equal(QQN::State()->CountryCode, $strCountryCode),
				QQ::Equal(QQN::State()->Code, $strCode)
				)
			);
		}
			
		/**
		 * Load an array of State objects,
		 * by Code Index(es)
		 * @param string $strCode
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return State[]
		*/
		public static function LoadArrayByCode($strCode, $objOptionalClauses = null) {
			// Call State::QueryArray to perform the LoadArrayByCode query
			try {
				return State::QueryArray(
					QQ::Equal(QQN::State()->Code, $strCode),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count States
		 * by Code Index(es)
		 * @param string $strCode
		 * @return int
		*/
		public static function CountByCode($strCode) {
			// Call State::QueryCount to perform the CountByCode query
			return State::QueryCount(
				QQ::Equal(QQN::State()->Code, $strCode)
			);
		}
			
		/**
		 * Load an array of State objects,
		 * by CountryCode Index(es)
		 * @param string $strCountryCode
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return State[]
		*/
		public static function LoadArrayByCountryCode($strCountryCode, $objOptionalClauses = null) {
			// Call State::QueryArray to perform the LoadArrayByCountryCode query
			try {
				return State::QueryArray(
					QQ::Equal(QQN::State()->CountryCode, $strCountryCode),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count States
		 * by CountryCode Index(es)
		 * @param string $strCountryCode
		 * @return int
		*/
		public static function CountByCountryCode($strCountryCode) {
			// Call State::QueryCount to perform the CountByCountryCode query
			return State::QueryCount(
				QQ::Equal(QQN::State()->CountryCode, $strCountryCode)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this State
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = State::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_state` (
							`country_code`,
							`code`,
							`avail`,
							`sort_order`,
							`state`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strCountryCode) . ',
							' . $objDatabase->SqlVariable($this->strCode) . ',
							' . $objDatabase->SqlVariable($this->strAvail) . ',
							' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							' . $objDatabase->SqlVariable($this->strState) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_state', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_state`
						SET
							`country_code` = ' . $objDatabase->SqlVariable($this->strCountryCode) . ',
							`code` = ' . $objDatabase->SqlVariable($this->strCode) . ',
							`avail` = ' . $objDatabase->SqlVariable($this->strAvail) . ',
							`sort_order` = ' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							`state` = ' . $objDatabase->SqlVariable($this->strState) . '
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
		 * Delete this State
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this State with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = State::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_state`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all States
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = State::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_state`');
		}

		/**
		 * Truncate xlsws_state table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = State::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_state`');
		}

		/**
		 * Reload this State from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved State object.');

			// Reload the Object
			$objReloaded = State::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strCountryCode = $objReloaded->strCountryCode;
			$this->strCode = $objReloaded->strCode;
			$this->strAvail = $objReloaded->strAvail;
			$this->intSortOrder = $objReloaded->intSortOrder;
			$this->strState = $objReloaded->strState;
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

				case 'CountryCode':
					// Gets the value for strCountryCode (Not Null)
					// @return string
					return $this->strCountryCode;

				case 'Code':
					// Gets the value for strCode (Not Null)
					// @return string
					return $this->strCode;

				case 'Avail':
					// Gets the value for strAvail (Not Null)
					// @return string
					return $this->strAvail;

				case 'SortOrder':
					// Gets the value for intSortOrder 
					// @return integer
					return $this->intSortOrder;

				case 'State':
					// Gets the value for strState (Not Null)
					// @return string
					return $this->strState;


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
				case 'CountryCode':
					// Sets the value for strCountryCode (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCountryCode = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Code':
					// Sets the value for strCode (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCode = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Avail':
					// Sets the value for strAvail (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAvail = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SortOrder':
					// Sets the value for intSortOrder 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intSortOrder = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'State':
					// Sets the value for strState (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strState = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="State"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="CountryCode" type="xsd:string"/>';
			$strToReturn .= '<element name="Code" type="xsd:string"/>';
			$strToReturn .= '<element name="Avail" type="xsd:string"/>';
			$strToReturn .= '<element name="SortOrder" type="xsd:int"/>';
			$strToReturn .= '<element name="State" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('State', $strComplexTypeArray)) {
				$strComplexTypeArray['State'] = State::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, State::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new State();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'CountryCode'))
				$objToReturn->strCountryCode = $objSoapObject->CountryCode;
			if (property_exists($objSoapObject, 'Code'))
				$objToReturn->strCode = $objSoapObject->Code;
			if (property_exists($objSoapObject, 'Avail'))
				$objToReturn->strAvail = $objSoapObject->Avail;
			if (property_exists($objSoapObject, 'SortOrder'))
				$objToReturn->intSortOrder = $objSoapObject->SortOrder;
			if (property_exists($objSoapObject, 'State'))
				$objToReturn->strState = $objSoapObject->State;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, State::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeState extends QQNode {
		protected $strTableName = 'xlsws_state';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'State';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'CountryCode':
					return new QQNode('country_code', 'CountryCode', 'string', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Avail':
					return new QQNode('avail', 'Avail', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'State':
					return new QQNode('state', 'State', 'string', $this);

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

	class QQReverseReferenceNodeState extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_state';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'State';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'CountryCode':
					return new QQNode('country_code', 'CountryCode', 'string', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Avail':
					return new QQNode('avail', 'Avail', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'State':
					return new QQNode('state', 'State', 'string', $this);

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