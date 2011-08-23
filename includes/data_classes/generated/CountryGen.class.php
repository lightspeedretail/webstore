<?php
	/**
	 * The abstract CountryGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Country subclass which
	 * extends this CountryGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Country class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Code the value for strCode (Unique)
	 * @property string $CodeA3 the value for strCodeA3 (Not Null)
	 * @property string $Region the value for strRegion (Not Null)
	 * @property string $Avail the value for strAvail (Not Null)
	 * @property integer $SortOrder the value for intSortOrder 
	 * @property string $Country the value for strCountry (Not Null)
	 * @property string $ZipValidatePreg the value for strZipValidatePreg 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CountryGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_country.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.code
		 * @var string strCode
		 */
		protected $strCode;
		const CodeMaxLength = 2;
		const CodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.code_A3
		 * @var string strCodeA3
		 */
		protected $strCodeA3;
		const CodeA3MaxLength = 3;
		const CodeA3Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.region
		 * @var string strRegion
		 */
		protected $strRegion;
		const RegionMaxLength = 2;
		const RegionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.avail
		 * @var string strAvail
		 */
		protected $strAvail;
		const AvailMaxLength = 1;
		const AvailDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.sort_order
		 * @var integer intSortOrder
		 */
		protected $intSortOrder;
		const SortOrderDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.country
		 * @var string strCountry
		 */
		protected $strCountry;
		const CountryMaxLength = 255;
		const CountryDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_country.zip_validate_preg
		 * @var string strZipValidatePreg
		 */
		protected $strZipValidatePreg;
		const ZipValidatePregMaxLength = 255;
		const ZipValidatePregDefault = null;


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
		 * Load a Country from PK Info
		 * @param integer $intRowid
		 * @return Country
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Country::QuerySingle(
				QQ::Equal(QQN::Country()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Countries
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Country[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Country::QueryArray to perform the LoadAll query
			try {
				return Country::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Countries
		 * @return int
		 */
		public static function CountAll() {
			// Call Country::QueryCount to perform the CountAll query
			return Country::QueryCount(QQ::All());
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
			$objDatabase = Country::GetDatabase();

			// Create/Build out the QueryBuilder object with Country-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_country');
			Country::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_country');

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
		 * Static Qcodo Query method to query for a single Country object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Country the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Country::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Country object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Country::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Country objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Country[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Country::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Country::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Country objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Country::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Country::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_country_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Country-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Country::GetSelectFields($objQueryBuilder);
				Country::GetFromFields($objQueryBuilder);

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
			return Country::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Country
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_country';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'code', $strAliasPrefix . 'code');
			$objBuilder->AddSelectItem($strTableName, 'code_A3', $strAliasPrefix . 'code_A3');
			$objBuilder->AddSelectItem($strTableName, 'region', $strAliasPrefix . 'region');
			$objBuilder->AddSelectItem($strTableName, 'avail', $strAliasPrefix . 'avail');
			$objBuilder->AddSelectItem($strTableName, 'sort_order', $strAliasPrefix . 'sort_order');
			$objBuilder->AddSelectItem($strTableName, 'country', $strAliasPrefix . 'country');
			$objBuilder->AddSelectItem($strTableName, 'zip_validate_preg', $strAliasPrefix . 'zip_validate_preg');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Country from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Country::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Country
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the Country object
			$objToReturn = new Country();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code'] : $strAliasPrefix . 'code';
			$objToReturn->strCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'code_A3', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code_A3'] : $strAliasPrefix . 'code_A3';
			$objToReturn->strCodeA3 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'region', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'region'] : $strAliasPrefix . 'region';
			$objToReturn->strRegion = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'avail', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'avail'] : $strAliasPrefix . 'avail';
			$objToReturn->strAvail = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sort_order', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sort_order'] : $strAliasPrefix . 'sort_order';
			$objToReturn->intSortOrder = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'country', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'country'] : $strAliasPrefix . 'country';
			$objToReturn->strCountry = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zip_validate_preg', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zip_validate_preg'] : $strAliasPrefix . 'zip_validate_preg';
			$objToReturn->strZipValidatePreg = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_country__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of Countries from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Country[]
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
					$objItem = Country::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Country::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Country object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Country
		*/
		public static function LoadByRowid($intRowid) {
			return Country::QuerySingle(
				QQ::Equal(QQN::Country()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Country object,
		 * by Code Index(es)
		 * @param string $strCode
		 * @return Country
		*/
		public static function LoadByCode($strCode) {
			return Country::QuerySingle(
				QQ::Equal(QQN::Country()->Code, $strCode)
			);
		}
			
		/**
		 * Load an array of Country objects,
		 * by Avail Index(es)
		 * @param string $strAvail
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Country[]
		*/
		public static function LoadArrayByAvail($strAvail, $objOptionalClauses = null) {
			// Call Country::QueryArray to perform the LoadArrayByAvail query
			try {
				return Country::QueryArray(
					QQ::Equal(QQN::Country()->Avail, $strAvail),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Countries
		 * by Avail Index(es)
		 * @param string $strAvail
		 * @return int
		*/
		public static function CountByAvail($strAvail) {
			// Call Country::QueryCount to perform the CountByAvail query
			return Country::QueryCount(
				QQ::Equal(QQN::Country()->Avail, $strAvail)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Country
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Country::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_country` (
							`code`,
							`code_A3`,
							`region`,
							`avail`,
							`sort_order`,
							`country`,
							`zip_validate_preg`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strCode) . ',
							' . $objDatabase->SqlVariable($this->strCodeA3) . ',
							' . $objDatabase->SqlVariable($this->strRegion) . ',
							' . $objDatabase->SqlVariable($this->strAvail) . ',
							' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							' . $objDatabase->SqlVariable($this->strCountry) . ',
							' . $objDatabase->SqlVariable($this->strZipValidatePreg) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_country', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_country`
						SET
							`code` = ' . $objDatabase->SqlVariable($this->strCode) . ',
							`code_A3` = ' . $objDatabase->SqlVariable($this->strCodeA3) . ',
							`region` = ' . $objDatabase->SqlVariable($this->strRegion) . ',
							`avail` = ' . $objDatabase->SqlVariable($this->strAvail) . ',
							`sort_order` = ' . $objDatabase->SqlVariable($this->intSortOrder) . ',
							`country` = ' . $objDatabase->SqlVariable($this->strCountry) . ',
							`zip_validate_preg` = ' . $objDatabase->SqlVariable($this->strZipValidatePreg) . '
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
		 * Delete this Country
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Country with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Country::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_country`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Countries
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Country::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_country`');
		}

		/**
		 * Truncate xlsws_country table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Country::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_country`');
		}

		/**
		 * Reload this Country from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Country object.');

			// Reload the Object
			$objReloaded = Country::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strCode = $objReloaded->strCode;
			$this->strCodeA3 = $objReloaded->strCodeA3;
			$this->strRegion = $objReloaded->strRegion;
			$this->strAvail = $objReloaded->strAvail;
			$this->intSortOrder = $objReloaded->intSortOrder;
			$this->strCountry = $objReloaded->strCountry;
			$this->strZipValidatePreg = $objReloaded->strZipValidatePreg;
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

				case 'Code':
					// Gets the value for strCode (Unique)
					// @return string
					return $this->strCode;

				case 'CodeA3':
					// Gets the value for strCodeA3 (Not Null)
					// @return string
					return $this->strCodeA3;

				case 'Region':
					// Gets the value for strRegion (Not Null)
					// @return string
					return $this->strRegion;

				case 'Avail':
					// Gets the value for strAvail (Not Null)
					// @return string
					return $this->strAvail;

				case 'SortOrder':
					// Gets the value for intSortOrder 
					// @return integer
					return $this->intSortOrder;

				case 'Country':
					// Gets the value for strCountry (Not Null)
					// @return string
					return $this->strCountry;

				case 'ZipValidatePreg':
					// Gets the value for strZipValidatePreg 
					// @return string
					return $this->strZipValidatePreg;


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
				case 'Code':
					// Sets the value for strCode (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCode = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CodeA3':
					// Sets the value for strCodeA3 (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCodeA3 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Region':
					// Sets the value for strRegion (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strRegion = QType::Cast($mixValue, QType::String));
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

				case 'Country':
					// Sets the value for strCountry (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCountry = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ZipValidatePreg':
					// Sets the value for strZipValidatePreg 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strZipValidatePreg = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="Country"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Code" type="xsd:string"/>';
			$strToReturn .= '<element name="CodeA3" type="xsd:string"/>';
			$strToReturn .= '<element name="Region" type="xsd:string"/>';
			$strToReturn .= '<element name="Avail" type="xsd:string"/>';
			$strToReturn .= '<element name="SortOrder" type="xsd:int"/>';
			$strToReturn .= '<element name="Country" type="xsd:string"/>';
			$strToReturn .= '<element name="ZipValidatePreg" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Country', $strComplexTypeArray)) {
				$strComplexTypeArray['Country'] = Country::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Country::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Country();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Code'))
				$objToReturn->strCode = $objSoapObject->Code;
			if (property_exists($objSoapObject, 'CodeA3'))
				$objToReturn->strCodeA3 = $objSoapObject->CodeA3;
			if (property_exists($objSoapObject, 'Region'))
				$objToReturn->strRegion = $objSoapObject->Region;
			if (property_exists($objSoapObject, 'Avail'))
				$objToReturn->strAvail = $objSoapObject->Avail;
			if (property_exists($objSoapObject, 'SortOrder'))
				$objToReturn->intSortOrder = $objSoapObject->SortOrder;
			if (property_exists($objSoapObject, 'Country'))
				$objToReturn->strCountry = $objSoapObject->Country;
			if (property_exists($objSoapObject, 'ZipValidatePreg'))
				$objToReturn->strZipValidatePreg = $objSoapObject->ZipValidatePreg;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Country::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeCountry extends QQNode {
		protected $strTableName = 'xlsws_country';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Country';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'CodeA3':
					return new QQNode('code_A3', 'CodeA3', 'string', $this);
				case 'Region':
					return new QQNode('region', 'Region', 'string', $this);
				case 'Avail':
					return new QQNode('avail', 'Avail', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Country':
					return new QQNode('country', 'Country', 'string', $this);
				case 'ZipValidatePreg':
					return new QQNode('zip_validate_preg', 'ZipValidatePreg', 'string', $this);

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

	class QQReverseReferenceNodeCountry extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_country';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Country';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'CodeA3':
					return new QQNode('code_A3', 'CodeA3', 'string', $this);
				case 'Region':
					return new QQNode('region', 'Region', 'string', $this);
				case 'Avail':
					return new QQNode('avail', 'Avail', 'string', $this);
				case 'SortOrder':
					return new QQNode('sort_order', 'SortOrder', 'integer', $this);
				case 'Country':
					return new QQNode('country', 'Country', 'string', $this);
				case 'ZipValidatePreg':
					return new QQNode('zip_validate_preg', 'ZipValidatePreg', 'string', $this);

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