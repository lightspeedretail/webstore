<?php
	/**
	 * The abstract CreditCardGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the CreditCard subclass which
	 * extends this CreditCardGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the CreditCard class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Name the value for strName (Unique)
	 * @property string $Length the value for strLength (Not Null)
	 * @property string $Prefix the value for strPrefix (Not Null)
	 * @property integer $SortOrder the value for intSortOrder (Not Null)
	 * @property boolean $Enabled the value for blnEnabled (Not Null)
	 * @property string $ValidFunc the value for strValidFunc 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CreditCardGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_credit_card.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.name
		 * @var string strName
		 */
		protected $strName;
		const NameMaxLength = 32;
		const NameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.length
		 * @var string strLength
		 */
		protected $strLength;
		const LengthMaxLength = 16;
		const LengthDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.prefix
		 * @var string strPrefix
		 */
		protected $strPrefix;
		const PrefixMaxLength = 64;
		const PrefixDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.sort_order
		 * @var integer intSortOrder
		 */
		protected $intSortOrder;
		const SortOrderDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.enabled
		 * @var boolean blnEnabled
		 */
		protected $blnEnabled;
		const EnabledDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.validFunc
		 * @var string strValidFunc
		 */
		protected $strValidFunc;
		const ValidFuncMaxLength = 32;
		const ValidFuncDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_credit_card.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


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
		 * Load a CreditCard from PK Info
		 * @param integer $intRowid
		 * @return CreditCard
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return CreditCard::QuerySingle(
				QQ::Equal(QQN::CreditCard()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all CreditCards
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CreditCard[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call CreditCard::QueryArray to perform the LoadAll query
			try {
				return CreditCard::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all CreditCards
		 * @return int
		 */
		public static function CountAll() {
			// Call CreditCard::QueryCount to perform the CountAll query
			return CreditCard::QueryCount(QQ::All());
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
			$objDatabase = CreditCard::GetDatabase();

			// Create/Build out the QueryBuilder object with CreditCard-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_credit_card');
			CreditCard::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_credit_card');

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
		 * Static Qcodo Query method to query for a single CreditCard object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return CreditCard the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CreditCard::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new CreditCard object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return CreditCard::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of CreditCard objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return CreditCard[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CreditCard::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return CreditCard::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of CreditCard objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CreditCard::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = CreditCard::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_credit_card_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with CreditCard-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				CreditCard::GetSelectFields($objQueryBuilder);
				CreditCard::GetFromFields($objQueryBuilder);

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
			return CreditCard::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this CreditCard
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_credit_card';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
			$objBuilder->AddSelectItem($strTableName, 'length', $strAliasPrefix . 'length');
			$objBuilder->AddSelectItem($strTableName, 'prefix', $strAliasPrefix . 'prefix');
			$objBuilder->AddSelectItem($strTableName, 'sort_order', $strAliasPrefix . 'sort_order');
			$objBuilder->AddSelectItem($strTableName, 'enabled', $strAliasPrefix . 'enabled');
			$objBuilder->AddSelectItem($strTableName, 'validFunc', $strAliasPrefix . 'validFunc');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a CreditCard from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this CreditCard::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return CreditCard
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the CreditCard object
			$objToReturn = new CreditCard();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'name'] : $strAliasPrefix . 'name';
			$objToReturn->strName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'length', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'length'] : $strAliasPrefix . 'length';
			$objToReturn->strLength = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'prefix', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'prefix'] : $strAliasPrefix . 'prefix';
			$objToReturn->strPrefix = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sort_order', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sort_order'] : $strAliasPrefix . 'sort_order';
			$objToReturn->intSortOrder = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'enabled', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'enabled'] : $strAliasPrefix . 'enabled';
			$objToReturn->blnEnabled = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'validFunc', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'validFunc'] : $strAliasPrefix . 'validFunc';
			$objToReturn->strValidFunc = $objDbRow->GetColumn($strAliasName, 'VarChar');
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
				$strAliasPrefix = 'xlsws_credit_card__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of CreditCards from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return CreditCard[]
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
					$objItem = CreditCard::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = CreditCard::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single CreditCard object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return CreditCard
		*/
		public static function LoadByRowid($intRowid) {
			return CreditCard::QuerySingle(
				QQ::Equal(QQN::CreditCard()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single CreditCard object,
		 * by Name Index(es)
		 * @param string $strName
		 * @return CreditCard
		*/
		public static function LoadByName($strName) {
			return CreditCard::QuerySingle(
				QQ::Equal(QQN::CreditCard()->Name, $strName)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this CreditCard
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = CreditCard::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_credit_card` (
							`name`,
							`length`,
							`prefix`,
							`sort_order`,
							`enabled`,
							`validFunc`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strName) . ',
							' . $objDatabase->SqlVariable($this->strLength) . ',
							' . $objDatabase->SqlVariable($this->strPrefix) . ',
							' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							' . $objDatabase->SqlVariable($this->blnEnabled) . ',
							' . $objDatabase->SqlVariable($this->strValidFunc) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_credit_card', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_credit_card`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('CreditCard');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_credit_card`
						SET
							`name` = ' . $objDatabase->SqlVariable($this->strName) . ',
							`length` = ' . $objDatabase->SqlVariable($this->strLength) . ',
							`prefix` = ' . $objDatabase->SqlVariable($this->strPrefix) . ',
							`sort_order` = ' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							`enabled` = ' . $objDatabase->SqlVariable($this->blnEnabled) . ',
							`validFunc` = ' . $objDatabase->SqlVariable($this->strValidFunc) . '
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
					`xlsws_credit_card`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this CreditCard
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this CreditCard with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = CreditCard::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_credit_card`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all CreditCards
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = CreditCard::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_credit_card`');
		}

		/**
		 * Truncate xlsws_credit_card table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = CreditCard::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_credit_card`');
		}

		/**
		 * Reload this CreditCard from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved CreditCard object.');

			// Reload the Object
			$objReloaded = CreditCard::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strName = $objReloaded->strName;
			$this->strLength = $objReloaded->strLength;
			$this->strPrefix = $objReloaded->strPrefix;
			$this->intSortOrder = $objReloaded->intSortOrder;
			$this->blnEnabled = $objReloaded->blnEnabled;
			$this->strValidFunc = $objReloaded->strValidFunc;
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
					// Gets the value for strName (Unique)
					// @return string
					return $this->strName;

				case 'Length':
					// Gets the value for strLength (Not Null)
					// @return string
					return $this->strLength;

				case 'Prefix':
					// Gets the value for strPrefix (Not Null)
					// @return string
					return $this->strPrefix;

				case 'SortOrder':
					// Gets the value for intSortOrder (Not Null)
					// @return integer
					return $this->intSortOrder;

				case 'Enabled':
					// Gets the value for blnEnabled (Not Null)
					// @return boolean
					return $this->blnEnabled;

				case 'ValidFunc':
					// Gets the value for strValidFunc 
					// @return string
					return $this->strValidFunc;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;


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
				case 'Name':
					// Sets the value for strName (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Length':
					// Sets the value for strLength (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strLength = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Prefix':
					// Sets the value for strPrefix (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPrefix = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SortOrder':
					// Sets the value for intSortOrder (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intSortOrder = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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

				case 'ValidFunc':
					// Sets the value for strValidFunc 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strValidFunc = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="CreditCard"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Name" type="xsd:string"/>';
			$strToReturn .= '<element name="Length" type="xsd:string"/>';
			$strToReturn .= '<element name="Prefix" type="xsd:string"/>';
			$strToReturn .= '<element name="SortOrder" type="xsd:int"/>';
			$strToReturn .= '<element name="Enabled" type="xsd:boolean"/>';
			$strToReturn .= '<element name="ValidFunc" type="xsd:string"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('CreditCard', $strComplexTypeArray)) {
				$strComplexTypeArray['CreditCard'] = CreditCard::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, CreditCard::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new CreditCard();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Name'))
				$objToReturn->strName = $objSoapObject->Name;
			if (property_exists($objSoapObject, 'Length'))
				$objToReturn->strLength = $objSoapObject->Length;
			if (property_exists($objSoapObject, 'Prefix'))
				$objToReturn->strPrefix = $objSoapObject->Prefix;
			if (property_exists($objSoapObject, 'SortOrder'))
				$objToReturn->intSortOrder = $objSoapObject->SortOrder;
			if (property_exists($objSoapObject, 'Enabled'))
				$objToReturn->blnEnabled = $objSoapObject->Enabled;
			if (property_exists($objSoapObject, 'ValidFunc'))
				$objToReturn->strValidFunc = $objSoapObject->ValidFunc;
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
				array_push($objArrayToReturn, CreditCard::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeCreditCard extends QQNode {
		protected $strTableName = 'xlsws_credit_card';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'CreditCard';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Length':
					return new QQNode('length', 'Length', 'string', $this);
				case 'Prefix':
					return new QQNode('prefix', 'Prefix', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Enabled':
					return new QQNode('enabled', 'Enabled', 'boolean', $this);
				case 'ValidFunc':
					return new QQNode('validFunc', 'ValidFunc', 'string', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);

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

	class QQReverseReferenceNodeCreditCard extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_credit_card';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'CreditCard';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Length':
					return new QQNode('length', 'Length', 'string', $this);
				case 'Prefix':
					return new QQNode('prefix', 'Prefix', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Enabled':
					return new QQNode('enabled', 'Enabled', 'boolean', $this);
				case 'ValidFunc':
					return new QQNode('validFunc', 'ValidFunc', 'string', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);

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