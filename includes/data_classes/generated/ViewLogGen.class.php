<?php
	/**
	 * The abstract ViewLogGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the ViewLog subclass which
	 * extends this ViewLogGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the ViewLog class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $ResourceId the value for intResourceId 
	 * @property integer $LogTypeId the value for intLogTypeId (Not Null)
	 * @property integer $VisitorId the value for intVisitorId 
	 * @property string $Page the value for strPage 
	 * @property string $Vars the value for strVars 
	 * @property QDateTime $Created the value for dttCreated (Not Null)
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Visitor $Visitor the value for the Visitor object referenced by intVisitorId 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ViewLogGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_view_log.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.resource_id
		 * @var integer intResourceId
		 */
		protected $intResourceId;
		const ResourceIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.log_type_id
		 * @var integer intLogTypeId
		 */
		protected $intLogTypeId;
		const LogTypeIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.visitor_id
		 * @var integer intVisitorId
		 */
		protected $intVisitorId;
		const VisitorIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.page
		 * @var string strPage
		 */
		protected $strPage;
		const PageMaxLength = 255;
		const PageDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.vars
		 * @var string strVars
		 */
		protected $strVars;
		const VarsMaxLength = 32;
		const VarsDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_view_log.modified
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
		 * in the database column xlsws_view_log.visitor_id.
		 *
		 * NOTE: Always use the Visitor property getter to correctly retrieve this Visitor object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Visitor objVisitor
		 */
		protected $objVisitor;





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
		 * Load a ViewLog from PK Info
		 * @param integer $intRowid
		 * @return ViewLog
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return ViewLog::QuerySingle(
				QQ::Equal(QQN::ViewLog()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all ViewLogs
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ViewLog[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call ViewLog::QueryArray to perform the LoadAll query
			try {
				return ViewLog::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all ViewLogs
		 * @return int
		 */
		public static function CountAll() {
			// Call ViewLog::QueryCount to perform the CountAll query
			return ViewLog::QueryCount(QQ::All());
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
			$objDatabase = ViewLog::GetDatabase();

			// Create/Build out the QueryBuilder object with ViewLog-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_view_log');
			ViewLog::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_view_log');

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
		 * Static Qcodo Query method to query for a single ViewLog object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return ViewLog the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ViewLog::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new ViewLog object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return ViewLog::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of ViewLog objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return ViewLog[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ViewLog::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return ViewLog::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of ViewLog objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = ViewLog::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = ViewLog::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_view_log_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with ViewLog-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				ViewLog::GetSelectFields($objQueryBuilder);
				ViewLog::GetFromFields($objQueryBuilder);

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
			return ViewLog::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this ViewLog
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_view_log';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'resource_id', $strAliasPrefix . 'resource_id');
			$objBuilder->AddSelectItem($strTableName, 'log_type_id', $strAliasPrefix . 'log_type_id');
			$objBuilder->AddSelectItem($strTableName, 'visitor_id', $strAliasPrefix . 'visitor_id');
			$objBuilder->AddSelectItem($strTableName, 'page', $strAliasPrefix . 'page');
			$objBuilder->AddSelectItem($strTableName, 'vars', $strAliasPrefix . 'vars');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a ViewLog from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this ViewLog::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return ViewLog
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the ViewLog object
			$objToReturn = new ViewLog();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'resource_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'resource_id'] : $strAliasPrefix . 'resource_id';
			$objToReturn->intResourceId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'log_type_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'log_type_id'] : $strAliasPrefix . 'log_type_id';
			$objToReturn->intLogTypeId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'visitor_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'visitor_id'] : $strAliasPrefix . 'visitor_id';
			$objToReturn->intVisitorId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'page', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'page'] : $strAliasPrefix . 'page';
			$objToReturn->strPage = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'vars', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'vars'] : $strAliasPrefix . 'vars';
			$objToReturn->strVars = $objDbRow->GetColumn($strAliasName, 'VarChar');
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
				$strAliasPrefix = 'xlsws_view_log__';

			// Check for Visitor Early Binding
			$strAlias = $strAliasPrefix . 'visitor_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objVisitor = Visitor::InstantiateDbRow($objDbRow, $strAliasPrefix . 'visitor_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			return $objToReturn;
		}

		/**
		 * Instantiate an array of ViewLogs from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return ViewLog[]
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
					$objItem = ViewLog::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = ViewLog::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single ViewLog object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return ViewLog
		*/
		public static function LoadByRowid($intRowid) {
			return ViewLog::QuerySingle(
				QQ::Equal(QQN::ViewLog()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of ViewLog objects,
		 * by VisitorId Index(es)
		 * @param integer $intVisitorId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ViewLog[]
		*/
		public static function LoadArrayByVisitorId($intVisitorId, $objOptionalClauses = null) {
			// Call ViewLog::QueryArray to perform the LoadArrayByVisitorId query
			try {
				return ViewLog::QueryArray(
					QQ::Equal(QQN::ViewLog()->VisitorId, $intVisitorId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ViewLogs
		 * by VisitorId Index(es)
		 * @param integer $intVisitorId
		 * @return int
		*/
		public static function CountByVisitorId($intVisitorId) {
			// Call ViewLog::QueryCount to perform the CountByVisitorId query
			return ViewLog::QueryCount(
				QQ::Equal(QQN::ViewLog()->VisitorId, $intVisitorId)
			);
		}
			
		/**
		 * Load an array of ViewLog objects,
		 * by LogTypeId Index(es)
		 * @param integer $intLogTypeId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ViewLog[]
		*/
		public static function LoadArrayByLogTypeId($intLogTypeId, $objOptionalClauses = null) {
			// Call ViewLog::QueryArray to perform the LoadArrayByLogTypeId query
			try {
				return ViewLog::QueryArray(
					QQ::Equal(QQN::ViewLog()->LogTypeId, $intLogTypeId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ViewLogs
		 * by LogTypeId Index(es)
		 * @param integer $intLogTypeId
		 * @return int
		*/
		public static function CountByLogTypeId($intLogTypeId) {
			// Call ViewLog::QueryCount to perform the CountByLogTypeId query
			return ViewLog::QueryCount(
				QQ::Equal(QQN::ViewLog()->LogTypeId, $intLogTypeId)
			);
		}
			
		/**
		 * Load an array of ViewLog objects,
		 * by ResourceId Index(es)
		 * @param integer $intResourceId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ViewLog[]
		*/
		public static function LoadArrayByResourceId($intResourceId, $objOptionalClauses = null) {
			// Call ViewLog::QueryArray to perform the LoadArrayByResourceId query
			try {
				return ViewLog::QueryArray(
					QQ::Equal(QQN::ViewLog()->ResourceId, $intResourceId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count ViewLogs
		 * by ResourceId Index(es)
		 * @param integer $intResourceId
		 * @return int
		*/
		public static function CountByResourceId($intResourceId) {
			// Call ViewLog::QueryCount to perform the CountByResourceId query
			return ViewLog::QueryCount(
				QQ::Equal(QQN::ViewLog()->ResourceId, $intResourceId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this ViewLog
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = ViewLog::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_view_log` (
							`resource_id`,
							`log_type_id`,
							`visitor_id`,
							`page`,
							`vars`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intResourceId) . ',
							' . $objDatabase->SqlVariable($this->intLogTypeId) . ',
							' . $objDatabase->SqlVariable($this->intVisitorId) . ',
							' . $objDatabase->SqlVariable($this->strPage) . ',
							' . $objDatabase->SqlVariable($this->strVars) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_view_log', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_view_log`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('ViewLog');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_view_log`
						SET
							`resource_id` = ' . $objDatabase->SqlVariable($this->intResourceId) . ',
							`log_type_id` = ' . $objDatabase->SqlVariable($this->intLogTypeId) . ',
							`visitor_id` = ' . $objDatabase->SqlVariable($this->intVisitorId) . ',
							`page` = ' . $objDatabase->SqlVariable($this->strPage) . ',
							`vars` = ' . $objDatabase->SqlVariable($this->strVars) . ',
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
					`xlsws_view_log`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this ViewLog
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this ViewLog with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = ViewLog::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_view_log`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all ViewLogs
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = ViewLog::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_view_log`');
		}

		/**
		 * Truncate xlsws_view_log table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = ViewLog::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_view_log`');
		}

		/**
		 * Reload this ViewLog from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved ViewLog object.');

			// Reload the Object
			$objReloaded = ViewLog::Load($this->intRowid);

			// Update $this's local variables to match
			$this->intResourceId = $objReloaded->intResourceId;
			$this->LogTypeId = $objReloaded->LogTypeId;
			$this->VisitorId = $objReloaded->VisitorId;
			$this->strPage = $objReloaded->strPage;
			$this->strVars = $objReloaded->strVars;
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

				case 'ResourceId':
					// Gets the value for intResourceId 
					// @return integer
					return $this->intResourceId;

				case 'LogTypeId':
					// Gets the value for intLogTypeId (Not Null)
					// @return integer
					return $this->intLogTypeId;

				case 'VisitorId':
					// Gets the value for intVisitorId 
					// @return integer
					return $this->intVisitorId;

				case 'Page':
					// Gets the value for strPage 
					// @return string
					return $this->strPage;

				case 'Vars':
					// Gets the value for strVars 
					// @return string
					return $this->strVars;

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
				case 'Visitor':
					// Gets the value for the Visitor object referenced by intVisitorId 
					// @return Visitor
					try {
						if ((!$this->objVisitor) && (!is_null($this->intVisitorId)))
							$this->objVisitor = Visitor::Load($this->intVisitorId);
						return $this->objVisitor;
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
				case 'ResourceId':
					// Sets the value for intResourceId 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intResourceId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'LogTypeId':
					// Sets the value for intLogTypeId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intLogTypeId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'VisitorId':
					// Sets the value for intVisitorId 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objVisitor = null;
						return ($this->intVisitorId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Page':
					// Sets the value for strPage 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPage = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Vars':
					// Sets the value for strVars 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strVars = QType::Cast($mixValue, QType::String));
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
				case 'Visitor':
					// Sets the value for the Visitor object referenced by intVisitorId 
					// @param Visitor $mixValue
					// @return Visitor
					if (is_null($mixValue)) {
						$this->intVisitorId = null;
						$this->objVisitor = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Visitor object
						try {
							$mixValue = QType::Cast($mixValue, 'Visitor');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Visitor object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Visitor for this ViewLog');

						// Update Local Member Variables
						$this->objVisitor = $mixValue;
						$this->intVisitorId = $mixValue->Rowid;

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
			$strToReturn = '<complexType name="ViewLog"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="ResourceId" type="xsd:int"/>';
			$strToReturn .= '<element name="LogTypeId" type="xsd:int"/>';
			$strToReturn .= '<element name="Visitor" type="xsd1:Visitor"/>';
			$strToReturn .= '<element name="Page" type="xsd:string"/>';
			$strToReturn .= '<element name="Vars" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('ViewLog', $strComplexTypeArray)) {
				$strComplexTypeArray['ViewLog'] = ViewLog::GetSoapComplexTypeXml();
				Visitor::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, ViewLog::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new ViewLog();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'ResourceId'))
				$objToReturn->intResourceId = $objSoapObject->ResourceId;
			if (property_exists($objSoapObject, 'LogTypeId'))
				$objToReturn->intLogTypeId = $objSoapObject->LogTypeId;
			if ((property_exists($objSoapObject, 'Visitor')) &&
				($objSoapObject->Visitor))
				$objToReturn->Visitor = Visitor::GetObjectFromSoapObject($objSoapObject->Visitor);
			if (property_exists($objSoapObject, 'Page'))
				$objToReturn->strPage = $objSoapObject->Page;
			if (property_exists($objSoapObject, 'Vars'))
				$objToReturn->strVars = $objSoapObject->Vars;
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
				array_push($objArrayToReturn, ViewLog::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objVisitor)
				$objObject->objVisitor = Visitor::GetSoapObjectFromObject($objObject->objVisitor, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intVisitorId = null;
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeViewLog extends QQNode {
		protected $strTableName = 'xlsws_view_log';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'ViewLog';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ResourceId':
					return new QQNode('resource_id', 'ResourceId', 'integer', $this);
				case 'LogTypeId':
					return new QQNode('log_type_id', 'LogTypeId', 'integer', $this);
				case 'VisitorId':
					return new QQNode('visitor_id', 'VisitorId', 'integer', $this);
				case 'Visitor':
					return new QQNodeVisitor('visitor_id', 'Visitor', 'integer', $this);
				case 'Page':
					return new QQNode('page', 'Page', 'string', $this);
				case 'Vars':
					return new QQNode('vars', 'Vars', 'string', $this);
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

	class QQReverseReferenceNodeViewLog extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_view_log';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'ViewLog';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'ResourceId':
					return new QQNode('resource_id', 'ResourceId', 'integer', $this);
				case 'LogTypeId':
					return new QQNode('log_type_id', 'LogTypeId', 'integer', $this);
				case 'VisitorId':
					return new QQNode('visitor_id', 'VisitorId', 'integer', $this);
				case 'Visitor':
					return new QQNodeVisitor('visitor_id', 'Visitor', 'integer', $this);
				case 'Page':
					return new QQNode('page', 'Page', 'string', $this);
				case 'Vars':
					return new QQNode('vars', 'Vars', 'string', $this);
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