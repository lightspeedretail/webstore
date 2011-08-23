<?php
	/**
	 * The abstract SroRepairGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the SroRepair subclass which
	 * extends this SroRepairGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the SroRepair class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $SroId the value for strSroId 
	 * @property string $Family the value for strFamily 
	 * @property string $Description the value for strDescription 
	 * @property string $PurchaseDate the value for strPurchaseDate 
	 * @property string $SerialNumber the value for strSerialNumber 
	 * @property QDateTime $DatetimeCre the value for dttDatetimeCre 
	 * @property string $DatetimeMod the value for strDatetimeMod (Read-Only Timestamp)
	 * @property Sro $Sro the value for the Sro object referenced by strSroId 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class SroRepairGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_sro_repair.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.sro_id
		 * @var string strSroId
		 */
		protected $strSroId;
		const SroIdMaxLength = 20;
		const SroIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.family
		 * @var string strFamily
		 */
		protected $strFamily;
		const FamilyMaxLength = 255;
		const FamilyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.description
		 * @var string strDescription
		 */
		protected $strDescription;
		const DescriptionMaxLength = 255;
		const DescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.purchase_date
		 * @var string strPurchaseDate
		 */
		protected $strPurchaseDate;
		const PurchaseDateMaxLength = 32;
		const PurchaseDateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.serial_number
		 * @var string strSerialNumber
		 */
		protected $strSerialNumber;
		const SerialNumberMaxLength = 255;
		const SerialNumberDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.datetime_cre
		 * @var QDateTime dttDatetimeCre
		 */
		protected $dttDatetimeCre;
		const DatetimeCreDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro_repair.datetime_mod
		 * @var string strDatetimeMod
		 */
		protected $strDatetimeMod;
		const DatetimeModDefault = null;


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
		 * in the database column xlsws_sro_repair.sro_id.
		 *
		 * NOTE: Always use the Sro property getter to correctly retrieve this Sro object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Sro objSro
		 */
		protected $objSro;





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
		 * Load a SroRepair from PK Info
		 * @param integer $intRowid
		 * @return SroRepair
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return SroRepair::QuerySingle(
				QQ::Equal(QQN::SroRepair()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all SroRepairs
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return SroRepair[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call SroRepair::QueryArray to perform the LoadAll query
			try {
				return SroRepair::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all SroRepairs
		 * @return int
		 */
		public static function CountAll() {
			// Call SroRepair::QueryCount to perform the CountAll query
			return SroRepair::QueryCount(QQ::All());
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
			$objDatabase = SroRepair::GetDatabase();

			// Create/Build out the QueryBuilder object with SroRepair-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_sro_repair');
			SroRepair::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_sro_repair');

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
		 * Static Qcodo Query method to query for a single SroRepair object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return SroRepair the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = SroRepair::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new SroRepair object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return SroRepair::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of SroRepair objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return SroRepair[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = SroRepair::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return SroRepair::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of SroRepair objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = SroRepair::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = SroRepair::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_sro_repair_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with SroRepair-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				SroRepair::GetSelectFields($objQueryBuilder);
				SroRepair::GetFromFields($objQueryBuilder);

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
			return SroRepair::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this SroRepair
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_sro_repair';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'sro_id', $strAliasPrefix . 'sro_id');
			$objBuilder->AddSelectItem($strTableName, 'family', $strAliasPrefix . 'family');
			$objBuilder->AddSelectItem($strTableName, 'description', $strAliasPrefix . 'description');
			$objBuilder->AddSelectItem($strTableName, 'purchase_date', $strAliasPrefix . 'purchase_date');
			$objBuilder->AddSelectItem($strTableName, 'serial_number', $strAliasPrefix . 'serial_number');
			$objBuilder->AddSelectItem($strTableName, 'datetime_cre', $strAliasPrefix . 'datetime_cre');
			$objBuilder->AddSelectItem($strTableName, 'datetime_mod', $strAliasPrefix . 'datetime_mod');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a SroRepair from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this SroRepair::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return SroRepair
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the SroRepair object
			$objToReturn = new SroRepair();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'sro_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sro_id'] : $strAliasPrefix . 'sro_id';
			$objToReturn->strSroId = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'family', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'family'] : $strAliasPrefix . 'family';
			$objToReturn->strFamily = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'description'] : $strAliasPrefix . 'description';
			$objToReturn->strDescription = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'purchase_date', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'purchase_date'] : $strAliasPrefix . 'purchase_date';
			$objToReturn->strPurchaseDate = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'serial_number', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'serial_number'] : $strAliasPrefix . 'serial_number';
			$objToReturn->strSerialNumber = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'datetime_cre', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'datetime_cre'] : $strAliasPrefix . 'datetime_cre';
			$objToReturn->dttDatetimeCre = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'datetime_mod', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'datetime_mod'] : $strAliasPrefix . 'datetime_mod';
			$objToReturn->strDatetimeMod = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_sro_repair__';

			// Check for Sro Early Binding
			$strAlias = $strAliasPrefix . 'sro_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objSro = Sro::InstantiateDbRow($objDbRow, $strAliasPrefix . 'sro_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			return $objToReturn;
		}

		/**
		 * Instantiate an array of SroRepairs from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return SroRepair[]
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
					$objItem = SroRepair::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = SroRepair::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single SroRepair object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return SroRepair
		*/
		public static function LoadByRowid($intRowid) {
			return SroRepair::QuerySingle(
				QQ::Equal(QQN::SroRepair()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of SroRepair objects,
		 * by SroId Index(es)
		 * @param string $strSroId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return SroRepair[]
		*/
		public static function LoadArrayBySroId($strSroId, $objOptionalClauses = null) {
			// Call SroRepair::QueryArray to perform the LoadArrayBySroId query
			try {
				return SroRepair::QueryArray(
					QQ::Equal(QQN::SroRepair()->SroId, $strSroId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count SroRepairs
		 * by SroId Index(es)
		 * @param string $strSroId
		 * @return int
		*/
		public static function CountBySroId($strSroId) {
			// Call SroRepair::QueryCount to perform the CountBySroId query
			return SroRepair::QueryCount(
				QQ::Equal(QQN::SroRepair()->SroId, $strSroId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this SroRepair
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = SroRepair::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_sro_repair` (
							`sro_id`,
							`family`,
							`description`,
							`purchase_date`,
							`serial_number`,
							`datetime_cre`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strSroId) . ',
							' . $objDatabase->SqlVariable($this->strFamily) . ',
							' . $objDatabase->SqlVariable($this->strDescription) . ',
							' . $objDatabase->SqlVariable($this->strPurchaseDate) . ',
							' . $objDatabase->SqlVariable($this->strSerialNumber) . ',
							' . $objDatabase->SqlVariable($this->dttDatetimeCre) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_sro_repair', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`datetime_mod`
							FROM
								`xlsws_sro_repair`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strDatetimeMod)
							throw new QOptimisticLockingException('SroRepair');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_sro_repair`
						SET
							`sro_id` = ' . $objDatabase->SqlVariable($this->strSroId) . ',
							`family` = ' . $objDatabase->SqlVariable($this->strFamily) . ',
							`description` = ' . $objDatabase->SqlVariable($this->strDescription) . ',
							`purchase_date` = ' . $objDatabase->SqlVariable($this->strPurchaseDate) . ',
							`serial_number` = ' . $objDatabase->SqlVariable($this->strSerialNumber) . ',
							`datetime_cre` = ' . $objDatabase->SqlVariable($this->dttDatetimeCre) . '
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
					`datetime_mod`
				FROM
					`xlsws_sro_repair`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strDatetimeMod = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this SroRepair
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this SroRepair with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = SroRepair::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro_repair`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all SroRepairs
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = SroRepair::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro_repair`');
		}

		/**
		 * Truncate xlsws_sro_repair table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = SroRepair::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_sro_repair`');
		}

		/**
		 * Reload this SroRepair from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved SroRepair object.');

			// Reload the Object
			$objReloaded = SroRepair::Load($this->intRowid);

			// Update $this's local variables to match
			$this->SroId = $objReloaded->SroId;
			$this->strFamily = $objReloaded->strFamily;
			$this->strDescription = $objReloaded->strDescription;
			$this->strPurchaseDate = $objReloaded->strPurchaseDate;
			$this->strSerialNumber = $objReloaded->strSerialNumber;
			$this->dttDatetimeCre = $objReloaded->dttDatetimeCre;
			$this->strDatetimeMod = $objReloaded->strDatetimeMod;
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

				case 'SroId':
					// Gets the value for strSroId 
					// @return string
					return $this->strSroId;

				case 'Family':
					// Gets the value for strFamily 
					// @return string
					return $this->strFamily;

				case 'Description':
					// Gets the value for strDescription 
					// @return string
					return $this->strDescription;

				case 'PurchaseDate':
					// Gets the value for strPurchaseDate 
					// @return string
					return $this->strPurchaseDate;

				case 'SerialNumber':
					// Gets the value for strSerialNumber 
					// @return string
					return $this->strSerialNumber;

				case 'DatetimeCre':
					// Gets the value for dttDatetimeCre 
					// @return QDateTime
					return $this->dttDatetimeCre;

				case 'DatetimeMod':
					// Gets the value for strDatetimeMod (Read-Only Timestamp)
					// @return string
					return $this->strDatetimeMod;


				///////////////////
				// Member Objects
				///////////////////
				case 'Sro':
					// Gets the value for the Sro object referenced by strSroId 
					// @return Sro
					try {
						if ((!$this->objSro) && (!is_null($this->strSroId)))
							$this->objSro = Sro::Load($this->strSroId);
						return $this->objSro;
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
				case 'SroId':
					// Sets the value for strSroId 
					// @param string $mixValue
					// @return string
					try {
						$this->objSro = null;
						return ($this->strSroId = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Family':
					// Sets the value for strFamily 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strFamily = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Description':
					// Sets the value for strDescription 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PurchaseDate':
					// Sets the value for strPurchaseDate 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPurchaseDate = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SerialNumber':
					// Sets the value for strSerialNumber 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSerialNumber = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DatetimeCre':
					// Sets the value for dttDatetimeCre 
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttDatetimeCre = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'Sro':
					// Sets the value for the Sro object referenced by strSroId 
					// @param Sro $mixValue
					// @return Sro
					if (is_null($mixValue)) {
						$this->strSroId = null;
						$this->objSro = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Sro object
						try {
							$mixValue = QType::Cast($mixValue, 'Sro');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Sro object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Sro for this SroRepair');

						// Update Local Member Variables
						$this->objSro = $mixValue;
						$this->strSroId = $mixValue->Rowid;

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
			$strToReturn = '<complexType name="SroRepair"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Sro" type="xsd1:Sro"/>';
			$strToReturn .= '<element name="Family" type="xsd:string"/>';
			$strToReturn .= '<element name="Description" type="xsd:string"/>';
			$strToReturn .= '<element name="PurchaseDate" type="xsd:string"/>';
			$strToReturn .= '<element name="SerialNumber" type="xsd:string"/>';
			$strToReturn .= '<element name="DatetimeCre" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="DatetimeMod" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('SroRepair', $strComplexTypeArray)) {
				$strComplexTypeArray['SroRepair'] = SroRepair::GetSoapComplexTypeXml();
				Sro::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, SroRepair::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new SroRepair();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Sro')) &&
				($objSoapObject->Sro))
				$objToReturn->Sro = Sro::GetObjectFromSoapObject($objSoapObject->Sro);
			if (property_exists($objSoapObject, 'Family'))
				$objToReturn->strFamily = $objSoapObject->Family;
			if (property_exists($objSoapObject, 'Description'))
				$objToReturn->strDescription = $objSoapObject->Description;
			if (property_exists($objSoapObject, 'PurchaseDate'))
				$objToReturn->strPurchaseDate = $objSoapObject->PurchaseDate;
			if (property_exists($objSoapObject, 'SerialNumber'))
				$objToReturn->strSerialNumber = $objSoapObject->SerialNumber;
			if (property_exists($objSoapObject, 'DatetimeCre'))
				$objToReturn->dttDatetimeCre = new QDateTime($objSoapObject->DatetimeCre);
			if (property_exists($objSoapObject, 'DatetimeMod'))
				$objToReturn->strDatetimeMod = $objSoapObject->DatetimeMod;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, SroRepair::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objSro)
				$objObject->objSro = Sro::GetSoapObjectFromObject($objObject->objSro, false);
			else if (!$blnBindRelatedObjects)
				$objObject->strSroId = null;
			if ($objObject->dttDatetimeCre)
				$objObject->dttDatetimeCre = $objObject->dttDatetimeCre->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeSroRepair extends QQNode {
		protected $strTableName = 'xlsws_sro_repair';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'SroRepair';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'SroId':
					return new QQNode('sro_id', 'SroId', 'string', $this);
				case 'Sro':
					return new QQNodeSro('sro_id', 'Sro', 'string', $this);
				case 'Family':
					return new QQNode('family', 'Family', 'string', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'PurchaseDate':
					return new QQNode('purchase_date', 'PurchaseDate', 'string', $this);
				case 'SerialNumber':
					return new QQNode('serial_number', 'SerialNumber', 'string', $this);
				case 'DatetimeCre':
					return new QQNode('datetime_cre', 'DatetimeCre', 'QDateTime', $this);
				case 'DatetimeMod':
					return new QQNode('datetime_mod', 'DatetimeMod', 'string', $this);

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

	class QQReverseReferenceNodeSroRepair extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_sro_repair';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'SroRepair';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'SroId':
					return new QQNode('sro_id', 'SroId', 'string', $this);
				case 'Sro':
					return new QQNodeSro('sro_id', 'Sro', 'string', $this);
				case 'Family':
					return new QQNode('family', 'Family', 'string', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'PurchaseDate':
					return new QQNode('purchase_date', 'PurchaseDate', 'string', $this);
				case 'SerialNumber':
					return new QQNode('serial_number', 'SerialNumber', 'string', $this);
				case 'DatetimeCre':
					return new QQNode('datetime_cre', 'DatetimeCre', 'QDateTime', $this);
				case 'DatetimeMod':
					return new QQNode('datetime_mod', 'DatetimeMod', 'string', $this);

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