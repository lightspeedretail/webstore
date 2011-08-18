<?php
	/**
	 * The abstract SessionsGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Sessions subclass which
	 * extends this SessionsGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Sessions class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $IntSessionId the value for intIntSessionId (Read-Only PK)
	 * @property string $VchName the value for strVchName (Not Null)
	 * @property integer $UxtExpires the value for intUxtExpires (Not Null)
	 * @property string $TxtData the value for strTxtData 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class SessionsGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_sessions.intSessionId
		 * @var integer intIntSessionId
		 */
		protected $intIntSessionId;
		const IntSessionIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sessions.vchName
		 * @var string strVchName
		 */
		protected $strVchName;
		const VchNameMaxLength = 255;
		const VchNameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sessions.uxtExpires
		 * @var integer intUxtExpires
		 */
		protected $intUxtExpires;
		const UxtExpiresDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sessions.txtData
		 * @var string strTxtData
		 */
		protected $strTxtData;
		const TxtDataDefault = null;


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
		 * Load a Sessions from PK Info
		 * @param integer $intIntSessionId
		 * @return Sessions
		 */
		public static function Load($intIntSessionId) {
			// Use QuerySingle to Perform the Query
			return Sessions::QuerySingle(
				QQ::Equal(QQN::Sessions()->IntSessionId, $intIntSessionId)
			);
		}

		/**
		 * Load all Sessionses
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sessions[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Sessions::QueryArray to perform the LoadAll query
			try {
				return Sessions::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Sessionses
		 * @return int
		 */
		public static function CountAll() {
			// Call Sessions::QueryCount to perform the CountAll query
			return Sessions::QueryCount(QQ::All());
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
			$objDatabase = Sessions::GetDatabase();

			// Create/Build out the QueryBuilder object with Sessions-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_sessions');
			Sessions::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_sessions');

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
		 * Static Qcodo Query method to query for a single Sessions object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Sessions the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Sessions::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Sessions object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Sessions::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Sessions objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Sessions[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Sessions::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Sessions::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Sessions objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Sessions::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Sessions::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_sessions_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Sessions-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Sessions::GetSelectFields($objQueryBuilder);
				Sessions::GetFromFields($objQueryBuilder);

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
			return Sessions::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Sessions
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_sessions';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'intSessionId', $strAliasPrefix . 'intSessionId');
			$objBuilder->AddSelectItem($strTableName, 'vchName', $strAliasPrefix . 'vchName');
			$objBuilder->AddSelectItem($strTableName, 'uxtExpires', $strAliasPrefix . 'uxtExpires');
			$objBuilder->AddSelectItem($strTableName, 'txtData', $strAliasPrefix . 'txtData');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Sessions from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Sessions::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Sessions
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the Sessions object
			$objToReturn = new Sessions();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'intSessionId', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'intSessionId'] : $strAliasPrefix . 'intSessionId';
			$objToReturn->intIntSessionId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'vchName', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'vchName'] : $strAliasPrefix . 'vchName';
			$objToReturn->strVchName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'uxtExpires', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'uxtExpires'] : $strAliasPrefix . 'uxtExpires';
			$objToReturn->intUxtExpires = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'txtData', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'txtData'] : $strAliasPrefix . 'txtData';
			$objToReturn->strTxtData = $objDbRow->GetColumn($strAliasName, 'Blob');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_sessions__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of Sessionses from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Sessions[]
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
					$objItem = Sessions::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Sessions::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Sessions object,
		 * by IntSessionId Index(es)
		 * @param integer $intIntSessionId
		 * @return Sessions
		*/
		public static function LoadByIntSessionId($intIntSessionId) {
			return Sessions::QuerySingle(
				QQ::Equal(QQN::Sessions()->IntSessionId, $intIntSessionId)
			);
		}
			
		/**
		 * Load an array of Sessions objects,
		 * by VchName Index(es)
		 * @param string $strVchName
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sessions[]
		*/
		public static function LoadArrayByVchName($strVchName, $objOptionalClauses = null) {
			// Call Sessions::QueryArray to perform the LoadArrayByVchName query
			try {
				return Sessions::QueryArray(
					QQ::Equal(QQN::Sessions()->VchName, $strVchName),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Sessionses
		 * by VchName Index(es)
		 * @param string $strVchName
		 * @return int
		*/
		public static function CountByVchName($strVchName) {
			// Call Sessions::QueryCount to perform the CountByVchName query
			return Sessions::QueryCount(
				QQ::Equal(QQN::Sessions()->VchName, $strVchName)
			);
		}
			
		/**
		 * Load an array of Sessions objects,
		 * by UxtExpires Index(es)
		 * @param integer $intUxtExpires
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sessions[]
		*/
		public static function LoadArrayByUxtExpires($intUxtExpires, $objOptionalClauses = null) {
			// Call Sessions::QueryArray to perform the LoadArrayByUxtExpires query
			try {
				return Sessions::QueryArray(
					QQ::Equal(QQN::Sessions()->UxtExpires, $intUxtExpires),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Sessionses
		 * by UxtExpires Index(es)
		 * @param integer $intUxtExpires
		 * @return int
		*/
		public static function CountByUxtExpires($intUxtExpires) {
			// Call Sessions::QueryCount to perform the CountByUxtExpires query
			return Sessions::QueryCount(
				QQ::Equal(QQN::Sessions()->UxtExpires, $intUxtExpires)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Sessions
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Sessions::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_sessions` (
							`vchName`,
							`uxtExpires`,
							`txtData`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strVchName) . ',
							' . $objDatabase->SqlVariable($this->intUxtExpires) . ',
							' . $objDatabase->SqlVariable($this->strTxtData) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intIntSessionId = $objDatabase->InsertId('xlsws_sessions', 'intSessionId');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_sessions`
						SET
							`vchName` = ' . $objDatabase->SqlVariable($this->strVchName) . ',
							`uxtExpires` = ' . $objDatabase->SqlVariable($this->intUxtExpires) . ',
							`txtData` = ' . $objDatabase->SqlVariable($this->strTxtData) . '
						WHERE
							`intSessionId` = ' . $objDatabase->SqlVariable($this->intIntSessionId) . '
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
		 * Delete this Sessions
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intIntSessionId)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Sessions with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Sessions::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sessions`
				WHERE
					`intSessionId` = ' . $objDatabase->SqlVariable($this->intIntSessionId) . '');
		}

		/**
		 * Delete all Sessionses
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Sessions::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sessions`');
		}

		/**
		 * Truncate xlsws_sessions table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Sessions::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_sessions`');
		}

		/**
		 * Reload this Sessions from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Sessions object.');

			// Reload the Object
			$objReloaded = Sessions::Load($this->intIntSessionId);

			// Update $this's local variables to match
			$this->strVchName = $objReloaded->strVchName;
			$this->intUxtExpires = $objReloaded->intUxtExpires;
			$this->strTxtData = $objReloaded->strTxtData;
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
				case 'IntSessionId':
					// Gets the value for intIntSessionId (Read-Only PK)
					// @return integer
					return $this->intIntSessionId;

				case 'VchName':
					// Gets the value for strVchName (Not Null)
					// @return string
					return $this->strVchName;

				case 'UxtExpires':
					// Gets the value for intUxtExpires (Not Null)
					// @return integer
					return $this->intUxtExpires;

				case 'TxtData':
					// Gets the value for strTxtData 
					// @return string
					return $this->strTxtData;


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
				case 'VchName':
					// Sets the value for strVchName (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strVchName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'UxtExpires':
					// Sets the value for intUxtExpires (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intUxtExpires = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'TxtData':
					// Sets the value for strTxtData 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTxtData = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="Sessions"><sequence>';
			$strToReturn .= '<element name="IntSessionId" type="xsd:int"/>';
			$strToReturn .= '<element name="VchName" type="xsd:string"/>';
			$strToReturn .= '<element name="UxtExpires" type="xsd:int"/>';
			$strToReturn .= '<element name="TxtData" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Sessions', $strComplexTypeArray)) {
				$strComplexTypeArray['Sessions'] = Sessions::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Sessions::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Sessions();
			if (property_exists($objSoapObject, 'IntSessionId'))
				$objToReturn->intIntSessionId = $objSoapObject->IntSessionId;
			if (property_exists($objSoapObject, 'VchName'))
				$objToReturn->strVchName = $objSoapObject->VchName;
			if (property_exists($objSoapObject, 'UxtExpires'))
				$objToReturn->intUxtExpires = $objSoapObject->UxtExpires;
			if (property_exists($objSoapObject, 'TxtData'))
				$objToReturn->strTxtData = $objSoapObject->TxtData;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Sessions::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeSessions extends QQNode {
		protected $strTableName = 'xlsws_sessions';
		protected $strPrimaryKey = 'intSessionId';
		protected $strClassName = 'Sessions';
		public function __get($strName) {
			switch ($strName) {
				case 'IntSessionId':
					return new QQNode('intSessionId', 'IntSessionId', 'integer', $this);
				case 'VchName':
					return new QQNode('vchName', 'VchName', 'string', $this);
				case 'UxtExpires':
					return new QQNode('uxtExpires', 'UxtExpires', 'integer', $this);
				case 'TxtData':
					return new QQNode('txtData', 'TxtData', 'string', $this);

				case '_PrimaryKeyNode':
					return new QQNode('intSessionId', 'IntSessionId', 'integer', $this);
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

	class QQReverseReferenceNodeSessions extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_sessions';
		protected $strPrimaryKey = 'intSessionId';
		protected $strClassName = 'Sessions';
		public function __get($strName) {
			switch ($strName) {
				case 'IntSessionId':
					return new QQNode('intSessionId', 'IntSessionId', 'integer', $this);
				case 'VchName':
					return new QQNode('vchName', 'VchName', 'string', $this);
				case 'UxtExpires':
					return new QQNode('uxtExpires', 'UxtExpires', 'integer', $this);
				case 'TxtData':
					return new QQNode('txtData', 'TxtData', 'string', $this);

				case '_PrimaryKeyNode':
					return new QQNode('intSessionId', 'IntSessionId', 'integer', $this);
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