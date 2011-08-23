<?php
	/**
	 * The abstract ModulesGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Modules subclass which
	 * extends this ModulesGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Modules class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $File the value for strFile (Not Null)
	 * @property string $Type the value for strType (Not Null)
	 * @property integer $SortOrder the value for intSortOrder 
	 * @property string $Configuration the value for strConfiguration 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property QDateTime $Created the value for dttCreated 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ModulesGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_modules.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_modules.file
		 * @var string strFile
		 */
		protected $strFile;
		const FileMaxLength = 64;
		const FileDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_modules.type
		 * @var string strType
		 */
		protected $strType;
		const TypeMaxLength = 16;
		const TypeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_modules.sort_order
		 * @var integer intSortOrder
		 */
		protected $intSortOrder;
		const SortOrderDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_modules.configuration
		 * @var string strConfiguration
		 */
		protected $strConfiguration;
		const ConfigurationDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_modules.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_modules.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


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
		 * Load a Modules from PK Info
		 * @param integer $intRowid
		 * @return Modules
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Modules::QuerySingle(
				QQ::Equal(QQN::Modules()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Moduleses
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Modules[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Modules::QueryArray to perform the LoadAll query
			try {
				return Modules::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Moduleses
		 * @return int
		 */
		public static function CountAll() {
			// Call Modules::QueryCount to perform the CountAll query
			return Modules::QueryCount(QQ::All());
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
			$objDatabase = Modules::GetDatabase();

			// Create/Build out the QueryBuilder object with Modules-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_modules');
			Modules::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_modules');

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
		 * Static Qcodo Query method to query for a single Modules object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Modules the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Modules::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Modules object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Modules::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Modules objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Modules[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Modules::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Modules::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Modules objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Modules::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Modules::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_modules_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Modules-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Modules::GetSelectFields($objQueryBuilder);
				Modules::GetFromFields($objQueryBuilder);

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
			return Modules::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Modules
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_modules';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'file', $strAliasPrefix . 'file');
			$objBuilder->AddSelectItem($strTableName, 'type', $strAliasPrefix . 'type');
			$objBuilder->AddSelectItem($strTableName, 'sort_order', $strAliasPrefix . 'sort_order');
			$objBuilder->AddSelectItem($strTableName, 'configuration', $strAliasPrefix . 'configuration');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Modules from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Modules::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Modules
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the Modules object
			$objToReturn = new Modules();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'file', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'file'] : $strAliasPrefix . 'file';
			$objToReturn->strFile = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'type', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'type'] : $strAliasPrefix . 'type';
			$objToReturn->strType = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sort_order', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sort_order'] : $strAliasPrefix . 'sort_order';
			$objToReturn->intSortOrder = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'configuration', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'configuration'] : $strAliasPrefix . 'configuration';
			$objToReturn->strConfiguration = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'modified', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'modified'] : $strAliasPrefix . 'modified';
			$objToReturn->strModified = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'created', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'created'] : $strAliasPrefix . 'created';
			$objToReturn->dttCreated = $objDbRow->GetColumn($strAliasName, 'DateTime');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_modules__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of Moduleses from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Modules[]
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
					$objItem = Modules::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Modules::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Modules object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Modules
		*/
		public static function LoadByRowid($intRowid) {
			return Modules::QuerySingle(
				QQ::Equal(QQN::Modules()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Modules object,
		 * by File, Type Index(es)
		 * @param string $strFile
		 * @param string $strType
		 * @return Modules
		*/
		public static function LoadByFileType($strFile, $strType) {
			return Modules::QuerySingle(
				QQ::AndCondition(
				QQ::Equal(QQN::Modules()->File, $strFile),
				QQ::Equal(QQN::Modules()->Type, $strType)
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
		 * Save this Modules
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Modules::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_modules` (
							`file`,
							`type`,
							`sort_order`,
							`configuration`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strFile) . ',
							' . $objDatabase->SqlVariable($this->strType) . ',
							' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							' . $objDatabase->SqlVariable($this->strConfiguration) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_modules', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_modules`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Modules');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_modules`
						SET
							`file` = ' . $objDatabase->SqlVariable($this->strFile) . ',
							`type` = ' . $objDatabase->SqlVariable($this->strType) . ',
							`sort_order` = ' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							`configuration` = ' . $objDatabase->SqlVariable($this->strConfiguration) . ',
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
					`xlsws_modules`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Modules
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Modules with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Modules::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_modules`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Moduleses
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Modules::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_modules`');
		}

		/**
		 * Truncate xlsws_modules table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Modules::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_modules`');
		}

		/**
		 * Reload this Modules from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Modules object.');

			// Reload the Object
			$objReloaded = Modules::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strFile = $objReloaded->strFile;
			$this->strType = $objReloaded->strType;
			$this->intSortOrder = $objReloaded->intSortOrder;
			$this->strConfiguration = $objReloaded->strConfiguration;
			$this->strModified = $objReloaded->strModified;
			$this->dttCreated = $objReloaded->dttCreated;
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

				case 'File':
					// Gets the value for strFile (Not Null)
					// @return string
					return $this->strFile;

				case 'Type':
					// Gets the value for strType (Not Null)
					// @return string
					return $this->strType;

				case 'SortOrder':
					// Gets the value for intSortOrder 
					// @return integer
					return $this->intSortOrder;

				case 'Configuration':
					// Gets the value for strConfiguration 
					// @return string
					return $this->strConfiguration;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;

				case 'Created':
					// Gets the value for dttCreated 
					// @return QDateTime
					return $this->dttCreated;


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
				case 'File':
					// Sets the value for strFile (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strFile = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Type':
					// Sets the value for strType (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strType = QType::Cast($mixValue, QType::String));
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

				case 'Configuration':
					// Sets the value for strConfiguration 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strConfiguration = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="Modules"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="File" type="xsd:string"/>';
			$strToReturn .= '<element name="Type" type="xsd:string"/>';
			$strToReturn .= '<element name="SortOrder" type="xsd:int"/>';
			$strToReturn .= '<element name="Configuration" type="xsd:string"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Modules', $strComplexTypeArray)) {
				$strComplexTypeArray['Modules'] = Modules::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Modules::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Modules();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'File'))
				$objToReturn->strFile = $objSoapObject->File;
			if (property_exists($objSoapObject, 'Type'))
				$objToReturn->strType = $objSoapObject->Type;
			if (property_exists($objSoapObject, 'SortOrder'))
				$objToReturn->intSortOrder = $objSoapObject->SortOrder;
			if (property_exists($objSoapObject, 'Configuration'))
				$objToReturn->strConfiguration = $objSoapObject->Configuration;
			if (property_exists($objSoapObject, 'Modified'))
				$objToReturn->strModified = $objSoapObject->Modified;
			if (property_exists($objSoapObject, 'Created'))
				$objToReturn->dttCreated = new QDateTime($objSoapObject->Created);
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Modules::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeModules extends QQNode {
		protected $strTableName = 'xlsws_modules';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Modules';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'File':
					return new QQNode('file', 'File', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Configuration':
					return new QQNode('configuration', 'Configuration', 'string', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);

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

	class QQReverseReferenceNodeModules extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_modules';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Modules';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'File':
					return new QQNode('file', 'File', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Configuration':
					return new QQNode('configuration', 'Configuration', 'string', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);

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