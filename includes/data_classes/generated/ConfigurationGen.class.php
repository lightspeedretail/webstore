<?php
	/**
	 * The abstract ConfigurationGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Configuration subclass which
	 * extends this ConfigurationGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Configuration class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Title the value for strTitle (Not Null)
	 * @property string $Key the value for strKey (Unique)
	 * @property string $Value the value for strValue (Not Null)
	 * @property string $HelperText the value for strHelperText (Not Null)
	 * @property integer $ConfigurationTypeId the value for intConfigurationTypeId (Not Null)
	 * @property integer $SortOrder the value for intSortOrder 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property QDateTime $Created the value for dttCreated 
	 * @property string $Options the value for strOptions 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ConfigurationGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_configuration.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.title
		 * @var string strTitle
		 */
		protected $strTitle;
		const TitleMaxLength = 64;
		const TitleDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.key
		 * @var string strKey
		 */
		protected $strKey;
		const KeyMaxLength = 64;
		const KeyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.value
		 * @var string strValue
		 */
		protected $strValue;
		const ValueDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.helper_text
		 * @var string strHelperText
		 */
		protected $strHelperText;
		const HelperTextMaxLength = 255;
		const HelperTextDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.configuration_type_id
		 * @var integer intConfigurationTypeId
		 */
		protected $intConfigurationTypeId;
		const ConfigurationTypeIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.sort_order
		 * @var integer intSortOrder
		 */
		protected $intSortOrder;
		const SortOrderDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_configuration.options
		 * @var string strOptions
		 */
		protected $strOptions;
		const OptionsMaxLength = 255;
		const OptionsDefault = null;


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
		 * Load a Configuration from PK Info
		 * @param integer $intRowid
		 * @return Configuration
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Configuration::QuerySingle(
				QQ::Equal(QQN::Configuration()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Configurations
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Configuration[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Configuration::QueryArray to perform the LoadAll query
			try {
				return Configuration::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Configurations
		 * @return int
		 */
		public static function CountAll() {
			// Call Configuration::QueryCount to perform the CountAll query
			return Configuration::QueryCount(QQ::All());
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
			$objDatabase = Configuration::GetDatabase();

			// Create/Build out the QueryBuilder object with Configuration-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_configuration');
			Configuration::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_configuration');

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
		 * Static Qcodo Query method to query for a single Configuration object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Configuration the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Configuration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Configuration object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Configuration::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Configuration objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Configuration[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Configuration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Configuration::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Configuration objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Configuration::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Configuration::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_configuration_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Configuration-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Configuration::GetSelectFields($objQueryBuilder);
				Configuration::GetFromFields($objQueryBuilder);

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
			return Configuration::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Configuration
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_configuration';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'title', $strAliasPrefix . 'title');
			$objBuilder->AddSelectItem($strTableName, 'key', $strAliasPrefix . 'key');
			$objBuilder->AddSelectItem($strTableName, 'value', $strAliasPrefix . 'value');
			$objBuilder->AddSelectItem($strTableName, 'helper_text', $strAliasPrefix . 'helper_text');
			$objBuilder->AddSelectItem($strTableName, 'configuration_type_id', $strAliasPrefix . 'configuration_type_id');
			$objBuilder->AddSelectItem($strTableName, 'sort_order', $strAliasPrefix . 'sort_order');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'options', $strAliasPrefix . 'options');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Configuration from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Configuration::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Configuration
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the Configuration object
			$objToReturn = new Configuration();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'title', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'title'] : $strAliasPrefix . 'title';
			$objToReturn->strTitle = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'key', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'key'] : $strAliasPrefix . 'key';
			$objToReturn->strKey = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'value', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'value'] : $strAliasPrefix . 'value';
			$objToReturn->strValue = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'helper_text', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'helper_text'] : $strAliasPrefix . 'helper_text';
			$objToReturn->strHelperText = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'configuration_type_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'configuration_type_id'] : $strAliasPrefix . 'configuration_type_id';
			$objToReturn->intConfigurationTypeId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'sort_order', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sort_order'] : $strAliasPrefix . 'sort_order';
			$objToReturn->intSortOrder = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'modified', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'modified'] : $strAliasPrefix . 'modified';
			$objToReturn->strModified = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'created', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'created'] : $strAliasPrefix . 'created';
			$objToReturn->dttCreated = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'options', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'options'] : $strAliasPrefix . 'options';
			$objToReturn->strOptions = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_configuration__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of Configurations from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Configuration[]
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
					$objItem = Configuration::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Configuration::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Configuration object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Configuration
		*/
		public static function LoadByRowid($intRowid) {
			return Configuration::QuerySingle(
				QQ::Equal(QQN::Configuration()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Configuration object,
		 * by Key Index(es)
		 * @param string $strKey
		 * @return Configuration
		*/
		public static function LoadByKey($strKey) {
			return Configuration::QuerySingle(
				QQ::Equal(QQN::Configuration()->Key, $strKey)
			);
		}
			
		/**
		 * Load an array of Configuration objects,
		 * by ConfigurationTypeId Index(es)
		 * @param integer $intConfigurationTypeId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Configuration[]
		*/
		public static function LoadArrayByConfigurationTypeId($intConfigurationTypeId, $objOptionalClauses = null) {
			// Call Configuration::QueryArray to perform the LoadArrayByConfigurationTypeId query
			try {
				return Configuration::QueryArray(
					QQ::Equal(QQN::Configuration()->ConfigurationTypeId, $intConfigurationTypeId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Configurations
		 * by ConfigurationTypeId Index(es)
		 * @param integer $intConfigurationTypeId
		 * @return int
		*/
		public static function CountByConfigurationTypeId($intConfigurationTypeId) {
			// Call Configuration::QueryCount to perform the CountByConfigurationTypeId query
			return Configuration::QueryCount(
				QQ::Equal(QQN::Configuration()->ConfigurationTypeId, $intConfigurationTypeId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Configuration
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Configuration::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_configuration` (
							`title`,
							`key`,
							`value`,
							`helper_text`,
							`configuration_type_id`,
							`sort_order`,
							`created`,
							`options`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strTitle) . ',
							' . $objDatabase->SqlVariable($this->strKey) . ',
							' . $objDatabase->SqlVariable($this->strValue) . ',
							' . $objDatabase->SqlVariable($this->strHelperText) . ',
							' . $objDatabase->SqlVariable($this->intConfigurationTypeId) . ',
							' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . ',
							' . $objDatabase->SqlVariable($this->strOptions) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_configuration', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_configuration`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Configuration');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_configuration`
						SET
							`title` = ' . $objDatabase->SqlVariable($this->strTitle) . ',
							`key` = ' . $objDatabase->SqlVariable($this->strKey) . ',
							`value` = ' . $objDatabase->SqlVariable($this->strValue) . ',
							`helper_text` = ' . $objDatabase->SqlVariable($this->strHelperText) . ',
							`configuration_type_id` = ' . $objDatabase->SqlVariable($this->intConfigurationTypeId) . ',
							`sort_order` = ' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							`created` = ' . $objDatabase->SqlVariable($this->dttCreated) . ',
							`options` = ' . $objDatabase->SqlVariable($this->strOptions) . '
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
					`xlsws_configuration`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Configuration
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Configuration with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Configuration::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_configuration`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Configurations
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Configuration::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_configuration`');
		}

		/**
		 * Truncate xlsws_configuration table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Configuration::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_configuration`');
		}

		/**
		 * Reload this Configuration from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Configuration object.');

			// Reload the Object
			$objReloaded = Configuration::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strTitle = $objReloaded->strTitle;
			$this->strKey = $objReloaded->strKey;
			$this->strValue = $objReloaded->strValue;
			$this->strHelperText = $objReloaded->strHelperText;
			$this->intConfigurationTypeId = $objReloaded->intConfigurationTypeId;
			$this->intSortOrder = $objReloaded->intSortOrder;
			$this->strModified = $objReloaded->strModified;
			$this->dttCreated = $objReloaded->dttCreated;
			$this->strOptions = $objReloaded->strOptions;
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

				case 'Title':
					// Gets the value for strTitle (Not Null)
					// @return string
					return $this->strTitle;

				case 'Key':
					// Gets the value for strKey (Unique)
					// @return string
					return $this->strKey;

				case 'Value':
					// Gets the value for strValue (Not Null)
					// @return string
					return $this->strValue;

				case 'HelperText':
					// Gets the value for strHelperText (Not Null)
					// @return string
					return $this->strHelperText;

				case 'ConfigurationTypeId':
					// Gets the value for intConfigurationTypeId (Not Null)
					// @return integer
					return $this->intConfigurationTypeId;

				case 'SortOrder':
					// Gets the value for intSortOrder 
					// @return integer
					return $this->intSortOrder;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;

				case 'Created':
					// Gets the value for dttCreated 
					// @return QDateTime
					return $this->dttCreated;

				case 'Options':
					// Gets the value for strOptions 
					// @return string
					return $this->strOptions;


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
				case 'Title':
					// Sets the value for strTitle (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTitle = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Key':
					// Sets the value for strKey (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strKey = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Value':
					// Sets the value for strValue (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strValue = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HelperText':
					// Sets the value for strHelperText (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strHelperText = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ConfigurationTypeId':
					// Sets the value for intConfigurationTypeId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intConfigurationTypeId = QType::Cast($mixValue, QType::Integer));
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

				case 'Options':
					// Sets the value for strOptions 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strOptions = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="Configuration"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Title" type="xsd:string"/>';
			$strToReturn .= '<element name="Key" type="xsd:string"/>';
			$strToReturn .= '<element name="Value" type="xsd:string"/>';
			$strToReturn .= '<element name="HelperText" type="xsd:string"/>';
			$strToReturn .= '<element name="ConfigurationTypeId" type="xsd:int"/>';
			$strToReturn .= '<element name="SortOrder" type="xsd:int"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Options" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Configuration', $strComplexTypeArray)) {
				$strComplexTypeArray['Configuration'] = Configuration::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Configuration::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Configuration();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Title'))
				$objToReturn->strTitle = $objSoapObject->Title;
			if (property_exists($objSoapObject, 'Key'))
				$objToReturn->strKey = $objSoapObject->Key;
			if (property_exists($objSoapObject, 'Value'))
				$objToReturn->strValue = $objSoapObject->Value;
			if (property_exists($objSoapObject, 'HelperText'))
				$objToReturn->strHelperText = $objSoapObject->HelperText;
			if (property_exists($objSoapObject, 'ConfigurationTypeId'))
				$objToReturn->intConfigurationTypeId = $objSoapObject->ConfigurationTypeId;
			if (property_exists($objSoapObject, 'SortOrder'))
				$objToReturn->intSortOrder = $objSoapObject->SortOrder;
			if (property_exists($objSoapObject, 'Modified'))
				$objToReturn->strModified = $objSoapObject->Modified;
			if (property_exists($objSoapObject, 'Created'))
				$objToReturn->dttCreated = new QDateTime($objSoapObject->Created);
			if (property_exists($objSoapObject, 'Options'))
				$objToReturn->strOptions = $objSoapObject->Options;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Configuration::GetSoapObjectFromObject($objObject, true));

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

	class QQNodeConfiguration extends QQNode {
		protected $strTableName = 'xlsws_configuration';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Configuration';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Title':
					return new QQNode('title', 'Title', 'string', $this);
				case 'Key':
					return new QQNode('key', 'Key', 'string', $this);
				case 'Value':
					return new QQNode('value', 'Value', 'string', $this);
				case 'HelperText':
					return new QQNode('helper_text', 'HelperText', 'string', $this);
				case 'ConfigurationTypeId':
					return new QQNode('configuration_type_id', 'ConfigurationTypeId', 'integer', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Options':
					return new QQNode('options', 'Options', 'string', $this);

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

	class QQReverseReferenceNodeConfiguration extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_configuration';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Configuration';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Title':
					return new QQNode('title', 'Title', 'string', $this);
				case 'Key':
					return new QQNode('key', 'Key', 'string', $this);
				case 'Value':
					return new QQNode('value', 'Value', 'string', $this);
				case 'HelperText':
					return new QQNode('helper_text', 'HelperText', 'string', $this);
				case 'ConfigurationTypeId':
					return new QQNode('configuration_type_id', 'ConfigurationTypeId', 'integer', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Options':
					return new QQNode('options', 'Options', 'string', $this);

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