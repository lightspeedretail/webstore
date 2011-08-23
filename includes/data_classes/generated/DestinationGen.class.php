<?php
	/**
	 * The abstract DestinationGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Destination subclass which
	 * extends this DestinationGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Destination class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Country the value for strCountry 
	 * @property string $State the value for strState 
	 * @property string $Zipcode1 the value for strZipcode1 
	 * @property string $Zipcode2 the value for strZipcode2 
	 * @property integer $Taxcode the value for intTaxcode 
	 * @property string $Name the value for strName 
	 * @property double $BaseCharge the value for fltBaseCharge 
	 * @property double $ShipFree the value for fltShipFree 
	 * @property double $ShipRate the value for fltShipRate 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class DestinationGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_destination.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.country
		 * @var string strCountry
		 */
		protected $strCountry;
		const CountryMaxLength = 5;
		const CountryDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.state
		 * @var string strState
		 */
		protected $strState;
		const StateMaxLength = 5;
		const StateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.zipcode1
		 * @var string strZipcode1
		 */
		protected $strZipcode1;
		const Zipcode1MaxLength = 10;
		const Zipcode1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.zipcode2
		 * @var string strZipcode2
		 */
		protected $strZipcode2;
		const Zipcode2MaxLength = 10;
		const Zipcode2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.taxcode
		 * @var integer intTaxcode
		 */
		protected $intTaxcode;
		const TaxcodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.name
		 * @var string strName
		 */
		protected $strName;
		const NameMaxLength = 32;
		const NameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.base_charge
		 * @var double fltBaseCharge
		 */
		protected $fltBaseCharge;
		const BaseChargeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.ship_free
		 * @var double fltShipFree
		 */
		protected $fltShipFree;
		const ShipFreeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.ship_rate
		 * @var double fltShipRate
		 */
		protected $fltShipRate;
		const ShipRateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_destination.modified
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
		 * Load a Destination from PK Info
		 * @param integer $intRowid
		 * @return Destination
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Destination::QuerySingle(
				QQ::Equal(QQN::Destination()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Destinations
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Destination[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Destination::QueryArray to perform the LoadAll query
			try {
				return Destination::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Destinations
		 * @return int
		 */
		public static function CountAll() {
			// Call Destination::QueryCount to perform the CountAll query
			return Destination::QueryCount(QQ::All());
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
			$objDatabase = Destination::GetDatabase();

			// Create/Build out the QueryBuilder object with Destination-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_destination');
			Destination::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_destination');

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
		 * Static Qcodo Query method to query for a single Destination object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Destination the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Destination::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Destination object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Destination::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Destination objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Destination[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Destination::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Destination::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Destination objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Destination::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Destination::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_destination_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Destination-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Destination::GetSelectFields($objQueryBuilder);
				Destination::GetFromFields($objQueryBuilder);

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
			return Destination::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Destination
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_destination';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'country', $strAliasPrefix . 'country');
			$objBuilder->AddSelectItem($strTableName, 'state', $strAliasPrefix . 'state');
			$objBuilder->AddSelectItem($strTableName, 'zipcode1', $strAliasPrefix . 'zipcode1');
			$objBuilder->AddSelectItem($strTableName, 'zipcode2', $strAliasPrefix . 'zipcode2');
			$objBuilder->AddSelectItem($strTableName, 'taxcode', $strAliasPrefix . 'taxcode');
			$objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
			$objBuilder->AddSelectItem($strTableName, 'base_charge', $strAliasPrefix . 'base_charge');
			$objBuilder->AddSelectItem($strTableName, 'ship_free', $strAliasPrefix . 'ship_free');
			$objBuilder->AddSelectItem($strTableName, 'ship_rate', $strAliasPrefix . 'ship_rate');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Destination from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Destination::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Destination
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the Destination object
			$objToReturn = new Destination();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'country', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'country'] : $strAliasPrefix . 'country';
			$objToReturn->strCountry = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'state', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'state'] : $strAliasPrefix . 'state';
			$objToReturn->strState = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zipcode1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zipcode1'] : $strAliasPrefix . 'zipcode1';
			$objToReturn->strZipcode1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zipcode2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zipcode2'] : $strAliasPrefix . 'zipcode2';
			$objToReturn->strZipcode2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'taxcode', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'taxcode'] : $strAliasPrefix . 'taxcode';
			$objToReturn->intTaxcode = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'name'] : $strAliasPrefix . 'name';
			$objToReturn->strName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'base_charge', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'base_charge'] : $strAliasPrefix . 'base_charge';
			$objToReturn->fltBaseCharge = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_free', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_free'] : $strAliasPrefix . 'ship_free';
			$objToReturn->fltShipFree = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_rate'] : $strAliasPrefix . 'ship_rate';
			$objToReturn->fltShipRate = $objDbRow->GetColumn($strAliasName, 'Float');
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
				$strAliasPrefix = 'xlsws_destination__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of Destinations from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Destination[]
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
					$objItem = Destination::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Destination::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Destination object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Destination
		*/
		public static function LoadByRowid($intRowid) {
			return Destination::QuerySingle(
				QQ::Equal(QQN::Destination()->Rowid, $intRowid)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Destination
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Destination::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_destination` (
							`country`,
							`state`,
							`zipcode1`,
							`zipcode2`,
							`taxcode`,
							`name`,
							`base_charge`,
							`ship_free`,
							`ship_rate`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strCountry) . ',
							' . $objDatabase->SqlVariable($this->strState) . ',
							' . $objDatabase->SqlVariable($this->strZipcode1) . ',
							' . $objDatabase->SqlVariable($this->strZipcode2) . ',
							' . $objDatabase->SqlVariable($this->intTaxcode) . ',
							' . $objDatabase->SqlVariable($this->strName) . ',
							' . $objDatabase->SqlVariable($this->fltBaseCharge) . ',
							' . $objDatabase->SqlVariable($this->fltShipFree) . ',
							' . $objDatabase->SqlVariable($this->fltShipRate) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_destination', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_destination`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Destination');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_destination`
						SET
							`country` = ' . $objDatabase->SqlVariable($this->strCountry) . ',
							`state` = ' . $objDatabase->SqlVariable($this->strState) . ',
							`zipcode1` = ' . $objDatabase->SqlVariable($this->strZipcode1) . ',
							`zipcode2` = ' . $objDatabase->SqlVariable($this->strZipcode2) . ',
							`taxcode` = ' . $objDatabase->SqlVariable($this->intTaxcode) . ',
							`name` = ' . $objDatabase->SqlVariable($this->strName) . ',
							`base_charge` = ' . $objDatabase->SqlVariable($this->fltBaseCharge) . ',
							`ship_free` = ' . $objDatabase->SqlVariable($this->fltShipFree) . ',
							`ship_rate` = ' . $objDatabase->SqlVariable($this->fltShipRate) . '
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
					`xlsws_destination`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Destination
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Destination with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Destination::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_destination`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Destinations
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Destination::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_destination`');
		}

		/**
		 * Truncate xlsws_destination table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Destination::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_destination`');
		}

		/**
		 * Reload this Destination from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Destination object.');

			// Reload the Object
			$objReloaded = Destination::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strCountry = $objReloaded->strCountry;
			$this->strState = $objReloaded->strState;
			$this->strZipcode1 = $objReloaded->strZipcode1;
			$this->strZipcode2 = $objReloaded->strZipcode2;
			$this->intTaxcode = $objReloaded->intTaxcode;
			$this->strName = $objReloaded->strName;
			$this->fltBaseCharge = $objReloaded->fltBaseCharge;
			$this->fltShipFree = $objReloaded->fltShipFree;
			$this->fltShipRate = $objReloaded->fltShipRate;
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

				case 'Country':
					// Gets the value for strCountry 
					// @return string
					return $this->strCountry;

				case 'State':
					// Gets the value for strState 
					// @return string
					return $this->strState;

				case 'Zipcode1':
					// Gets the value for strZipcode1 
					// @return string
					return $this->strZipcode1;

				case 'Zipcode2':
					// Gets the value for strZipcode2 
					// @return string
					return $this->strZipcode2;

				case 'Taxcode':
					// Gets the value for intTaxcode 
					// @return integer
					return $this->intTaxcode;

				case 'Name':
					// Gets the value for strName 
					// @return string
					return $this->strName;

				case 'BaseCharge':
					// Gets the value for fltBaseCharge 
					// @return double
					return $this->fltBaseCharge;

				case 'ShipFree':
					// Gets the value for fltShipFree 
					// @return double
					return $this->fltShipFree;

				case 'ShipRate':
					// Gets the value for fltShipRate 
					// @return double
					return $this->fltShipRate;

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
				case 'Country':
					// Sets the value for strCountry 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCountry = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'State':
					// Sets the value for strState 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strState = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Zipcode1':
					// Sets the value for strZipcode1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strZipcode1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Zipcode2':
					// Sets the value for strZipcode2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strZipcode2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Taxcode':
					// Sets the value for intTaxcode 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intTaxcode = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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

				case 'BaseCharge':
					// Sets the value for fltBaseCharge 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltBaseCharge = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipFree':
					// Sets the value for fltShipFree 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltShipFree = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipRate':
					// Sets the value for fltShipRate 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltShipRate = QType::Cast($mixValue, QType::Float));
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
			$strToReturn = '<complexType name="Destination"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Country" type="xsd:string"/>';
			$strToReturn .= '<element name="State" type="xsd:string"/>';
			$strToReturn .= '<element name="Zipcode1" type="xsd:string"/>';
			$strToReturn .= '<element name="Zipcode2" type="xsd:string"/>';
			$strToReturn .= '<element name="Taxcode" type="xsd:int"/>';
			$strToReturn .= '<element name="Name" type="xsd:string"/>';
			$strToReturn .= '<element name="BaseCharge" type="xsd:float"/>';
			$strToReturn .= '<element name="ShipFree" type="xsd:float"/>';
			$strToReturn .= '<element name="ShipRate" type="xsd:float"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Destination', $strComplexTypeArray)) {
				$strComplexTypeArray['Destination'] = Destination::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Destination::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Destination();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Country'))
				$objToReturn->strCountry = $objSoapObject->Country;
			if (property_exists($objSoapObject, 'State'))
				$objToReturn->strState = $objSoapObject->State;
			if (property_exists($objSoapObject, 'Zipcode1'))
				$objToReturn->strZipcode1 = $objSoapObject->Zipcode1;
			if (property_exists($objSoapObject, 'Zipcode2'))
				$objToReturn->strZipcode2 = $objSoapObject->Zipcode2;
			if (property_exists($objSoapObject, 'Taxcode'))
				$objToReturn->intTaxcode = $objSoapObject->Taxcode;
			if (property_exists($objSoapObject, 'Name'))
				$objToReturn->strName = $objSoapObject->Name;
			if (property_exists($objSoapObject, 'BaseCharge'))
				$objToReturn->fltBaseCharge = $objSoapObject->BaseCharge;
			if (property_exists($objSoapObject, 'ShipFree'))
				$objToReturn->fltShipFree = $objSoapObject->ShipFree;
			if (property_exists($objSoapObject, 'ShipRate'))
				$objToReturn->fltShipRate = $objSoapObject->ShipRate;
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
				array_push($objArrayToReturn, Destination::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeDestination extends QQNode {
		protected $strTableName = 'xlsws_destination';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Destination';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Country':
					return new QQNode('country', 'Country', 'string', $this);
				case 'State':
					return new QQNode('state', 'State', 'string', $this);
				case 'Zipcode1':
					return new QQNode('zipcode1', 'Zipcode1', 'string', $this);
				case 'Zipcode2':
					return new QQNode('zipcode2', 'Zipcode2', 'string', $this);
				case 'Taxcode':
					return new QQNode('taxcode', 'Taxcode', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'BaseCharge':
					return new QQNode('base_charge', 'BaseCharge', 'double', $this);
				case 'ShipFree':
					return new QQNode('ship_free', 'ShipFree', 'double', $this);
				case 'ShipRate':
					return new QQNode('ship_rate', 'ShipRate', 'double', $this);
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

	class QQReverseReferenceNodeDestination extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_destination';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Destination';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Country':
					return new QQNode('country', 'Country', 'string', $this);
				case 'State':
					return new QQNode('state', 'State', 'string', $this);
				case 'Zipcode1':
					return new QQNode('zipcode1', 'Zipcode1', 'string', $this);
				case 'Zipcode2':
					return new QQNode('zipcode2', 'Zipcode2', 'string', $this);
				case 'Taxcode':
					return new QQNode('taxcode', 'Taxcode', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'BaseCharge':
					return new QQNode('base_charge', 'BaseCharge', 'double', $this);
				case 'ShipFree':
					return new QQNode('ship_free', 'ShipFree', 'double', $this);
				case 'ShipRate':
					return new QQNode('ship_rate', 'ShipRate', 'double', $this);
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