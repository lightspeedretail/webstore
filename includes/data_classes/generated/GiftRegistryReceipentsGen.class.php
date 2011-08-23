<?php
	/**
	 * The abstract GiftRegistryReceipentsGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the GiftRegistryReceipents subclass which
	 * extends this GiftRegistryReceipentsGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the GiftRegistryReceipents class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $RegistryId the value for intRegistryId (Not Null)
	 * @property integer $CustomerId the value for intCustomerId 
	 * @property string $ReceipentName the value for strReceipentName (Not Null)
	 * @property string $ReceipentEmail the value for strReceipentEmail (Not Null)
	 * @property boolean $EmailSent the value for blnEmailSent 
	 * @property QDateTime $Created the value for dttCreated (Not Null)
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property GiftRegistry $Registry the value for the GiftRegistry object referenced by intRegistryId (Not Null)
	 * @property Customer $Customer the value for the Customer object referenced by intCustomerId 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class GiftRegistryReceipentsGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_gift_registry_receipents.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.registry_id
		 * @var integer intRegistryId
		 */
		protected $intRegistryId;
		const RegistryIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.customer_id
		 * @var integer intCustomerId
		 */
		protected $intCustomerId;
		const CustomerIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.receipent_name
		 * @var string strReceipentName
		 */
		protected $strReceipentName;
		const ReceipentNameMaxLength = 100;
		const ReceipentNameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.receipent_email
		 * @var string strReceipentEmail
		 */
		protected $strReceipentEmail;
		const ReceipentEmailMaxLength = 100;
		const ReceipentEmailDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.email_sent
		 * @var boolean blnEmailSent
		 */
		protected $blnEmailSent;
		const EmailSentDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_receipents.modified
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

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_gift_registry_receipents.registry_id.
		 *
		 * NOTE: Always use the Registry property getter to correctly retrieve this GiftRegistry object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var GiftRegistry objRegistry
		 */
		protected $objRegistry;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_gift_registry_receipents.customer_id.
		 *
		 * NOTE: Always use the Customer property getter to correctly retrieve this Customer object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Customer objCustomer
		 */
		protected $objCustomer;





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
		 * Load a GiftRegistryReceipents from PK Info
		 * @param integer $intRowid
		 * @return GiftRegistryReceipents
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return GiftRegistryReceipents::QuerySingle(
				QQ::Equal(QQN::GiftRegistryReceipents()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all GiftRegistryReceipentses
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryReceipents[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call GiftRegistryReceipents::QueryArray to perform the LoadAll query
			try {
				return GiftRegistryReceipents::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all GiftRegistryReceipentses
		 * @return int
		 */
		public static function CountAll() {
			// Call GiftRegistryReceipents::QueryCount to perform the CountAll query
			return GiftRegistryReceipents::QueryCount(QQ::All());
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
			$objDatabase = GiftRegistryReceipents::GetDatabase();

			// Create/Build out the QueryBuilder object with GiftRegistryReceipents-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_gift_registry_receipents');
			GiftRegistryReceipents::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_gift_registry_receipents');

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
		 * Static Qcodo Query method to query for a single GiftRegistryReceipents object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return GiftRegistryReceipents the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistryReceipents::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new GiftRegistryReceipents object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return GiftRegistryReceipents::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of GiftRegistryReceipents objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return GiftRegistryReceipents[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistryReceipents::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return GiftRegistryReceipents::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of GiftRegistryReceipents objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistryReceipents::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = GiftRegistryReceipents::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_gift_registry_receipents_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with GiftRegistryReceipents-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				GiftRegistryReceipents::GetSelectFields($objQueryBuilder);
				GiftRegistryReceipents::GetFromFields($objQueryBuilder);

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
			return GiftRegistryReceipents::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this GiftRegistryReceipents
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_gift_registry_receipents';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'registry_id', $strAliasPrefix . 'registry_id');
			$objBuilder->AddSelectItem($strTableName, 'customer_id', $strAliasPrefix . 'customer_id');
			$objBuilder->AddSelectItem($strTableName, 'receipent_name', $strAliasPrefix . 'receipent_name');
			$objBuilder->AddSelectItem($strTableName, 'receipent_email', $strAliasPrefix . 'receipent_email');
			$objBuilder->AddSelectItem($strTableName, 'email_sent', $strAliasPrefix . 'email_sent');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a GiftRegistryReceipents from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this GiftRegistryReceipents::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return GiftRegistryReceipents
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the GiftRegistryReceipents object
			$objToReturn = new GiftRegistryReceipents();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'registry_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'registry_id'] : $strAliasPrefix . 'registry_id';
			$objToReturn->intRegistryId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'customer_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'customer_id'] : $strAliasPrefix . 'customer_id';
			$objToReturn->intCustomerId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'receipent_name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'receipent_name'] : $strAliasPrefix . 'receipent_name';
			$objToReturn->strReceipentName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'receipent_email', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'receipent_email'] : $strAliasPrefix . 'receipent_email';
			$objToReturn->strReceipentEmail = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'email_sent', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'email_sent'] : $strAliasPrefix . 'email_sent';
			$objToReturn->blnEmailSent = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'created', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'created'] : $strAliasPrefix . 'created';
			$objToReturn->dttCreated = $objDbRow->GetColumn($strAliasName, 'DateTime');
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
				$strAliasPrefix = 'xlsws_gift_registry_receipents__';

			// Check for Registry Early Binding
			$strAlias = $strAliasPrefix . 'registry_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objRegistry = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'registry_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for Customer Early Binding
			$strAlias = $strAliasPrefix . 'customer_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objCustomer = Customer::InstantiateDbRow($objDbRow, $strAliasPrefix . 'customer_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			return $objToReturn;
		}

		/**
		 * Instantiate an array of GiftRegistryReceipentses from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return GiftRegistryReceipents[]
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
					$objItem = GiftRegistryReceipents::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = GiftRegistryReceipents::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single GiftRegistryReceipents object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return GiftRegistryReceipents
		*/
		public static function LoadByRowid($intRowid) {
			return GiftRegistryReceipents::QuerySingle(
				QQ::Equal(QQN::GiftRegistryReceipents()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of GiftRegistryReceipents objects,
		 * by RegistryId Index(es)
		 * @param integer $intRegistryId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryReceipents[]
		*/
		public static function LoadArrayByRegistryId($intRegistryId, $objOptionalClauses = null) {
			// Call GiftRegistryReceipents::QueryArray to perform the LoadArrayByRegistryId query
			try {
				return GiftRegistryReceipents::QueryArray(
					QQ::Equal(QQN::GiftRegistryReceipents()->RegistryId, $intRegistryId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count GiftRegistryReceipentses
		 * by RegistryId Index(es)
		 * @param integer $intRegistryId
		 * @return int
		*/
		public static function CountByRegistryId($intRegistryId) {
			// Call GiftRegistryReceipents::QueryCount to perform the CountByRegistryId query
			return GiftRegistryReceipents::QueryCount(
				QQ::Equal(QQN::GiftRegistryReceipents()->RegistryId, $intRegistryId)
			);
		}
			
		/**
		 * Load an array of GiftRegistryReceipents objects,
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryReceipents[]
		*/
		public static function LoadArrayByCustomerId($intCustomerId, $objOptionalClauses = null) {
			// Call GiftRegistryReceipents::QueryArray to perform the LoadArrayByCustomerId query
			try {
				return GiftRegistryReceipents::QueryArray(
					QQ::Equal(QQN::GiftRegistryReceipents()->CustomerId, $intCustomerId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count GiftRegistryReceipentses
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @return int
		*/
		public static function CountByCustomerId($intCustomerId) {
			// Call GiftRegistryReceipents::QueryCount to perform the CountByCustomerId query
			return GiftRegistryReceipents::QueryCount(
				QQ::Equal(QQN::GiftRegistryReceipents()->CustomerId, $intCustomerId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this GiftRegistryReceipents
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistryReceipents::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_gift_registry_receipents` (
							`registry_id`,
							`customer_id`,
							`receipent_name`,
							`receipent_email`,
							`email_sent`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intRegistryId) . ',
							' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							' . $objDatabase->SqlVariable($this->strReceipentName) . ',
							' . $objDatabase->SqlVariable($this->strReceipentEmail) . ',
							' . $objDatabase->SqlVariable($this->blnEmailSent) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_gift_registry_receipents', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_gift_registry_receipents`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('GiftRegistryReceipents');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_gift_registry_receipents`
						SET
							`registry_id` = ' . $objDatabase->SqlVariable($this->intRegistryId) . ',
							`customer_id` = ' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							`receipent_name` = ' . $objDatabase->SqlVariable($this->strReceipentName) . ',
							`receipent_email` = ' . $objDatabase->SqlVariable($this->strReceipentEmail) . ',
							`email_sent` = ' . $objDatabase->SqlVariable($this->blnEmailSent) . ',
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
					`xlsws_gift_registry_receipents`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this GiftRegistryReceipents
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this GiftRegistryReceipents with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryReceipents::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_receipents`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all GiftRegistryReceipentses
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistryReceipents::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_receipents`');
		}

		/**
		 * Truncate xlsws_gift_registry_receipents table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistryReceipents::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_gift_registry_receipents`');
		}

		/**
		 * Reload this GiftRegistryReceipents from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved GiftRegistryReceipents object.');

			// Reload the Object
			$objReloaded = GiftRegistryReceipents::Load($this->intRowid);

			// Update $this's local variables to match
			$this->RegistryId = $objReloaded->RegistryId;
			$this->CustomerId = $objReloaded->CustomerId;
			$this->strReceipentName = $objReloaded->strReceipentName;
			$this->strReceipentEmail = $objReloaded->strReceipentEmail;
			$this->blnEmailSent = $objReloaded->blnEmailSent;
			$this->dttCreated = $objReloaded->dttCreated;
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

				case 'RegistryId':
					// Gets the value for intRegistryId (Not Null)
					// @return integer
					return $this->intRegistryId;

				case 'CustomerId':
					// Gets the value for intCustomerId 
					// @return integer
					return $this->intCustomerId;

				case 'ReceipentName':
					// Gets the value for strReceipentName (Not Null)
					// @return string
					return $this->strReceipentName;

				case 'ReceipentEmail':
					// Gets the value for strReceipentEmail (Not Null)
					// @return string
					return $this->strReceipentEmail;

				case 'EmailSent':
					// Gets the value for blnEmailSent 
					// @return boolean
					return $this->blnEmailSent;

				case 'Created':
					// Gets the value for dttCreated (Not Null)
					// @return QDateTime
					return $this->dttCreated;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;


				///////////////////
				// Member Objects
				///////////////////
				case 'Registry':
					// Gets the value for the GiftRegistry object referenced by intRegistryId (Not Null)
					// @return GiftRegistry
					try {
						if ((!$this->objRegistry) && (!is_null($this->intRegistryId)))
							$this->objRegistry = GiftRegistry::Load($this->intRegistryId);
						return $this->objRegistry;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Customer':
					// Gets the value for the Customer object referenced by intCustomerId 
					// @return Customer
					try {
						if ((!$this->objCustomer) && (!is_null($this->intCustomerId)))
							$this->objCustomer = Customer::Load($this->intCustomerId);
						return $this->objCustomer;
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
				case 'RegistryId':
					// Sets the value for intRegistryId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						$this->objRegistry = null;
						return ($this->intRegistryId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CustomerId':
					// Sets the value for intCustomerId 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objCustomer = null;
						return ($this->intCustomerId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ReceipentName':
					// Sets the value for strReceipentName (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strReceipentName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ReceipentEmail':
					// Sets the value for strReceipentEmail (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strReceipentEmail = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'EmailSent':
					// Sets the value for blnEmailSent 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnEmailSent = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Created':
					// Sets the value for dttCreated (Not Null)
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
				case 'Registry':
					// Sets the value for the GiftRegistry object referenced by intRegistryId (Not Null)
					// @param GiftRegistry $mixValue
					// @return GiftRegistry
					if (is_null($mixValue)) {
						$this->intRegistryId = null;
						$this->objRegistry = null;
						return null;
					} else {
						// Make sure $mixValue actually is a GiftRegistry object
						try {
							$mixValue = QType::Cast($mixValue, 'GiftRegistry');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED GiftRegistry object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Registry for this GiftRegistryReceipents');

						// Update Local Member Variables
						$this->objRegistry = $mixValue;
						$this->intRegistryId = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				case 'Customer':
					// Sets the value for the Customer object referenced by intCustomerId 
					// @param Customer $mixValue
					// @return Customer
					if (is_null($mixValue)) {
						$this->intCustomerId = null;
						$this->objCustomer = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Customer object
						try {
							$mixValue = QType::Cast($mixValue, 'Customer');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Customer object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Customer for this GiftRegistryReceipents');

						// Update Local Member Variables
						$this->objCustomer = $mixValue;
						$this->intCustomerId = $mixValue->Rowid;

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
			$strToReturn = '<complexType name="GiftRegistryReceipents"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Registry" type="xsd1:GiftRegistry"/>';
			$strToReturn .= '<element name="Customer" type="xsd1:Customer"/>';
			$strToReturn .= '<element name="ReceipentName" type="xsd:string"/>';
			$strToReturn .= '<element name="ReceipentEmail" type="xsd:string"/>';
			$strToReturn .= '<element name="EmailSent" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('GiftRegistryReceipents', $strComplexTypeArray)) {
				$strComplexTypeArray['GiftRegistryReceipents'] = GiftRegistryReceipents::GetSoapComplexTypeXml();
				GiftRegistry::AlterSoapComplexTypeArray($strComplexTypeArray);
				Customer::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, GiftRegistryReceipents::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new GiftRegistryReceipents();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Registry')) &&
				($objSoapObject->Registry))
				$objToReturn->Registry = GiftRegistry::GetObjectFromSoapObject($objSoapObject->Registry);
			if ((property_exists($objSoapObject, 'Customer')) &&
				($objSoapObject->Customer))
				$objToReturn->Customer = Customer::GetObjectFromSoapObject($objSoapObject->Customer);
			if (property_exists($objSoapObject, 'ReceipentName'))
				$objToReturn->strReceipentName = $objSoapObject->ReceipentName;
			if (property_exists($objSoapObject, 'ReceipentEmail'))
				$objToReturn->strReceipentEmail = $objSoapObject->ReceipentEmail;
			if (property_exists($objSoapObject, 'EmailSent'))
				$objToReturn->blnEmailSent = $objSoapObject->EmailSent;
			if (property_exists($objSoapObject, 'Created'))
				$objToReturn->dttCreated = new QDateTime($objSoapObject->Created);
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
				array_push($objArrayToReturn, GiftRegistryReceipents::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objRegistry)
				$objObject->objRegistry = GiftRegistry::GetSoapObjectFromObject($objObject->objRegistry, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intRegistryId = null;
			if ($objObject->objCustomer)
				$objObject->objCustomer = Customer::GetSoapObjectFromObject($objObject->objCustomer, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intCustomerId = null;
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeGiftRegistryReceipents extends QQNode {
		protected $strTableName = 'xlsws_gift_registry_receipents';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'GiftRegistryReceipents';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'RegistryId':
					return new QQNode('registry_id', 'RegistryId', 'integer', $this);
				case 'Registry':
					return new QQNodeGiftRegistry('registry_id', 'Registry', 'integer', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'ReceipentName':
					return new QQNode('receipent_name', 'ReceipentName', 'string', $this);
				case 'ReceipentEmail':
					return new QQNode('receipent_email', 'ReceipentEmail', 'string', $this);
				case 'EmailSent':
					return new QQNode('email_sent', 'EmailSent', 'boolean', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
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

	class QQReverseReferenceNodeGiftRegistryReceipents extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_gift_registry_receipents';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'GiftRegistryReceipents';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'RegistryId':
					return new QQNode('registry_id', 'RegistryId', 'integer', $this);
				case 'Registry':
					return new QQNodeGiftRegistry('registry_id', 'Registry', 'integer', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'ReceipentName':
					return new QQNode('receipent_name', 'ReceipentName', 'string', $this);
				case 'ReceipentEmail':
					return new QQNode('receipent_email', 'ReceipentEmail', 'string', $this);
				case 'EmailSent':
					return new QQNode('email_sent', 'EmailSent', 'boolean', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
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