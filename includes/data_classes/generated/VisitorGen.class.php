<?php
	/**
	 * The abstract VisitorGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Visitor subclass which
	 * extends this VisitorGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Visitor class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $CustomerId the value for intCustomerId 
	 * @property string $Host the value for strHost 
	 * @property string $Ip the value for strIp 
	 * @property string $Browser the value for strBrowser 
	 * @property string $ScreenRes the value for strScreenRes 
	 * @property QDateTime $Created the value for dttCreated 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Customer $Customer the value for the Customer object referenced by intCustomerId 
	 * @property ViewLog $_ViewLog the value for the private _objViewLog (Read-Only) if set due to an expansion on the xlsws_view_log.visitor_id reverse relationship
	 * @property ViewLog[] $_ViewLogArray the value for the private _objViewLogArray (Read-Only) if set due to an ExpandAsArray on the xlsws_view_log.visitor_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class VisitorGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_visitor.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.customer_id
		 * @var integer intCustomerId
		 */
		protected $intCustomerId;
		const CustomerIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.host
		 * @var string strHost
		 */
		protected $strHost;
		const HostMaxLength = 255;
		const HostDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.ip
		 * @var string strIp
		 */
		protected $strIp;
		const IpMaxLength = 32;
		const IpDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.browser
		 * @var string strBrowser
		 */
		protected $strBrowser;
		const BrowserMaxLength = 255;
		const BrowserDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.screen_res
		 * @var string strScreenRes
		 */
		protected $strScreenRes;
		const ScreenResMaxLength = 12;
		const ScreenResDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_visitor.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single ViewLog object
		 * (of type ViewLog), if this Visitor object was restored with
		 * an expansion on the xlsws_view_log association table.
		 * @var ViewLog _objViewLog;
		 */
		private $_objViewLog;

		/**
		 * Private member variable that stores a reference to an array of ViewLog objects
		 * (of type ViewLog[]), if this Visitor object was restored with
		 * an ExpandAsArray on the xlsws_view_log association table.
		 * @var ViewLog[] _objViewLogArray;
		 */
		private $_objViewLogArray = array();

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
		 * in the database column xlsws_visitor.customer_id.
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
		 * Load a Visitor from PK Info
		 * @param integer $intRowid
		 * @return Visitor
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Visitor::QuerySingle(
				QQ::Equal(QQN::Visitor()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Visitors
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Visitor[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Visitor::QueryArray to perform the LoadAll query
			try {
				return Visitor::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Visitors
		 * @return int
		 */
		public static function CountAll() {
			// Call Visitor::QueryCount to perform the CountAll query
			return Visitor::QueryCount(QQ::All());
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
			$objDatabase = Visitor::GetDatabase();

			// Create/Build out the QueryBuilder object with Visitor-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_visitor');
			Visitor::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_visitor');

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
		 * Static Qcodo Query method to query for a single Visitor object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Visitor the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Visitor::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Visitor object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Visitor::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Visitor objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Visitor[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Visitor::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Visitor::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Visitor objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Visitor::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Visitor::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_visitor_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Visitor-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Visitor::GetSelectFields($objQueryBuilder);
				Visitor::GetFromFields($objQueryBuilder);

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
			return Visitor::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Visitor
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_visitor';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'customer_id', $strAliasPrefix . 'customer_id');
			$objBuilder->AddSelectItem($strTableName, 'host', $strAliasPrefix . 'host');
			$objBuilder->AddSelectItem($strTableName, 'ip', $strAliasPrefix . 'ip');
			$objBuilder->AddSelectItem($strTableName, 'browser', $strAliasPrefix . 'browser');
			$objBuilder->AddSelectItem($strTableName, 'screen_res', $strAliasPrefix . 'screen_res');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Visitor from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Visitor::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Visitor
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;

			// See if we're doing an array expansion on the previous item
			$strAlias = $strAliasPrefix . 'rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (($strExpandAsArrayNodes) && ($objPreviousItem) &&
				($objPreviousItem->intRowid == $objDbRow->GetColumn($strAliasName, 'Integer'))) {

				// We are.  Now, prepare to check for ExpandAsArray clauses
				$blnExpandedViaArray = false;
				if (!$strAliasPrefix)
					$strAliasPrefix = 'xlsws_visitor__';


				$strAlias = $strAliasPrefix . 'viewlog__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objViewLogArray)) {
						$objPreviousChildItem = $objPreviousItem->_objViewLogArray[$intPreviousChildItemCount - 1];
						$objChildItem = ViewLog::InstantiateDbRow($objDbRow, $strAliasPrefix . 'viewlog__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objViewLogArray[] = $objChildItem;
					} else
						$objPreviousItem->_objViewLogArray[] = ViewLog::InstantiateDbRow($objDbRow, $strAliasPrefix . 'viewlog__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_visitor__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Visitor object
			$objToReturn = new Visitor();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'customer_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'customer_id'] : $strAliasPrefix . 'customer_id';
			$objToReturn->intCustomerId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'host', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'host'] : $strAliasPrefix . 'host';
			$objToReturn->strHost = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ip', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ip'] : $strAliasPrefix . 'ip';
			$objToReturn->strIp = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'browser', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'browser'] : $strAliasPrefix . 'browser';
			$objToReturn->strBrowser = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'screen_res', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'screen_res'] : $strAliasPrefix . 'screen_res';
			$objToReturn->strScreenRes = $objDbRow->GetColumn($strAliasName, 'VarChar');
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
				$strAliasPrefix = 'xlsws_visitor__';

			// Check for Customer Early Binding
			$strAlias = $strAliasPrefix . 'customer_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objCustomer = Customer::InstantiateDbRow($objDbRow, $strAliasPrefix . 'customer_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			// Check for ViewLog Virtual Binding
			$strAlias = $strAliasPrefix . 'viewlog__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objViewLogArray[] = ViewLog::InstantiateDbRow($objDbRow, $strAliasPrefix . 'viewlog__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objViewLog = ViewLog::InstantiateDbRow($objDbRow, $strAliasPrefix . 'viewlog__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Visitors from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Visitor[]
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
					$objItem = Visitor::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Visitor::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Visitor object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Visitor
		*/
		public static function LoadByRowid($intRowid) {
			return Visitor::QuerySingle(
				QQ::Equal(QQN::Visitor()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of Visitor objects,
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Visitor[]
		*/
		public static function LoadArrayByCustomerId($intCustomerId, $objOptionalClauses = null) {
			// Call Visitor::QueryArray to perform the LoadArrayByCustomerId query
			try {
				return Visitor::QueryArray(
					QQ::Equal(QQN::Visitor()->CustomerId, $intCustomerId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Visitors
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @return int
		*/
		public static function CountByCustomerId($intCustomerId) {
			// Call Visitor::QueryCount to perform the CountByCustomerId query
			return Visitor::QueryCount(
				QQ::Equal(QQN::Visitor()->CustomerId, $intCustomerId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Visitor
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_visitor` (
							`customer_id`,
							`host`,
							`ip`,
							`browser`,
							`screen_res`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							' . $objDatabase->SqlVariable($this->strHost) . ',
							' . $objDatabase->SqlVariable($this->strIp) . ',
							' . $objDatabase->SqlVariable($this->strBrowser) . ',
							' . $objDatabase->SqlVariable($this->strScreenRes) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_visitor', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_visitor`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Visitor');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_visitor`
						SET
							`customer_id` = ' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							`host` = ' . $objDatabase->SqlVariable($this->strHost) . ',
							`ip` = ' . $objDatabase->SqlVariable($this->strIp) . ',
							`browser` = ' . $objDatabase->SqlVariable($this->strBrowser) . ',
							`screen_res` = ' . $objDatabase->SqlVariable($this->strScreenRes) . ',
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
					`xlsws_visitor`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Visitor
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Visitor with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_visitor`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Visitors
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_visitor`');
		}

		/**
		 * Truncate xlsws_visitor table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_visitor`');
		}

		/**
		 * Reload this Visitor from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Visitor object.');

			// Reload the Object
			$objReloaded = Visitor::Load($this->intRowid);

			// Update $this's local variables to match
			$this->CustomerId = $objReloaded->CustomerId;
			$this->strHost = $objReloaded->strHost;
			$this->strIp = $objReloaded->strIp;
			$this->strBrowser = $objReloaded->strBrowser;
			$this->strScreenRes = $objReloaded->strScreenRes;
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

				case 'CustomerId':
					// Gets the value for intCustomerId 
					// @return integer
					return $this->intCustomerId;

				case 'Host':
					// Gets the value for strHost 
					// @return string
					return $this->strHost;

				case 'Ip':
					// Gets the value for strIp 
					// @return string
					return $this->strIp;

				case 'Browser':
					// Gets the value for strBrowser 
					// @return string
					return $this->strBrowser;

				case 'ScreenRes':
					// Gets the value for strScreenRes 
					// @return string
					return $this->strScreenRes;

				case 'Created':
					// Gets the value for dttCreated 
					// @return QDateTime
					return $this->dttCreated;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;


				///////////////////
				// Member Objects
				///////////////////
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

				case '_ViewLog':
					// Gets the value for the private _objViewLog (Read-Only)
					// if set due to an expansion on the xlsws_view_log.visitor_id reverse relationship
					// @return ViewLog
					return $this->_objViewLog;

				case '_ViewLogArray':
					// Gets the value for the private _objViewLogArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_view_log.visitor_id reverse relationship
					// @return ViewLog[]
					return (array) $this->_objViewLogArray;


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

				case 'Host':
					// Sets the value for strHost 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strHost = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Ip':
					// Sets the value for strIp 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strIp = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Browser':
					// Sets the value for strBrowser 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strBrowser = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ScreenRes':
					// Sets the value for strScreenRes 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strScreenRes = QType::Cast($mixValue, QType::String));
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
							throw new QCallerException('Unable to set an unsaved Customer for this Visitor');

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

			
		
		// Related Objects' Methods for ViewLog
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ViewLogs as an array of ViewLog objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ViewLog[]
		*/ 
		public function GetViewLogArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return ViewLog::LoadArrayByVisitorId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ViewLogs
		 * @return int
		*/ 
		public function CountViewLogs() {
			if ((is_null($this->intRowid)))
				return 0;

			return ViewLog::CountByVisitorId($this->intRowid);
		}

		/**
		 * Associates a ViewLog
		 * @param ViewLog $objViewLog
		 * @return void
		*/ 
		public function AssociateViewLog(ViewLog $objViewLog) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateViewLog on this unsaved Visitor.');
			if ((is_null($objViewLog->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateViewLog on this Visitor with an unsaved ViewLog.');

			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_view_log`
				SET
					`visitor_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objViewLog->Rowid) . '
			');
		}

		/**
		 * Unassociates a ViewLog
		 * @param ViewLog $objViewLog
		 * @return void
		*/ 
		public function UnassociateViewLog(ViewLog $objViewLog) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateViewLog on this unsaved Visitor.');
			if ((is_null($objViewLog->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateViewLog on this Visitor with an unsaved ViewLog.');

			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_view_log`
				SET
					`visitor_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objViewLog->Rowid) . ' AND
					`visitor_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ViewLogs
		 * @return void
		*/ 
		public function UnassociateAllViewLogs() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateViewLog on this unsaved Visitor.');

			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_view_log`
				SET
					`visitor_id` = null
				WHERE
					`visitor_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ViewLog
		 * @param ViewLog $objViewLog
		 * @return void
		*/ 
		public function DeleteAssociatedViewLog(ViewLog $objViewLog) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateViewLog on this unsaved Visitor.');
			if ((is_null($objViewLog->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateViewLog on this Visitor with an unsaved ViewLog.');

			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_view_log`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objViewLog->Rowid) . ' AND
					`visitor_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ViewLogs
		 * @return void
		*/ 
		public function DeleteAllViewLogs() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateViewLog on this unsaved Visitor.');

			// Get the Database Object for this Class
			$objDatabase = Visitor::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_view_log`
				WHERE
					`visitor_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Visitor"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Customer" type="xsd1:Customer"/>';
			$strToReturn .= '<element name="Host" type="xsd:string"/>';
			$strToReturn .= '<element name="Ip" type="xsd:string"/>';
			$strToReturn .= '<element name="Browser" type="xsd:string"/>';
			$strToReturn .= '<element name="ScreenRes" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Visitor', $strComplexTypeArray)) {
				$strComplexTypeArray['Visitor'] = Visitor::GetSoapComplexTypeXml();
				Customer::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Visitor::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Visitor();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Customer')) &&
				($objSoapObject->Customer))
				$objToReturn->Customer = Customer::GetObjectFromSoapObject($objSoapObject->Customer);
			if (property_exists($objSoapObject, 'Host'))
				$objToReturn->strHost = $objSoapObject->Host;
			if (property_exists($objSoapObject, 'Ip'))
				$objToReturn->strIp = $objSoapObject->Ip;
			if (property_exists($objSoapObject, 'Browser'))
				$objToReturn->strBrowser = $objSoapObject->Browser;
			if (property_exists($objSoapObject, 'ScreenRes'))
				$objToReturn->strScreenRes = $objSoapObject->ScreenRes;
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
				array_push($objArrayToReturn, Visitor::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
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

	class QQNodeVisitor extends QQNode {
		protected $strTableName = 'xlsws_visitor';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Visitor';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'Host':
					return new QQNode('host', 'Host', 'string', $this);
				case 'Ip':
					return new QQNode('ip', 'Ip', 'string', $this);
				case 'Browser':
					return new QQNode('browser', 'Browser', 'string', $this);
				case 'ScreenRes':
					return new QQNode('screen_res', 'ScreenRes', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'ViewLog':
					return new QQReverseReferenceNodeViewLog($this, 'viewlog', 'reverse_reference', 'visitor_id');

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

	class QQReverseReferenceNodeVisitor extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_visitor';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Visitor';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'Host':
					return new QQNode('host', 'Host', 'string', $this);
				case 'Ip':
					return new QQNode('ip', 'Ip', 'string', $this);
				case 'Browser':
					return new QQNode('browser', 'Browser', 'string', $this);
				case 'ScreenRes':
					return new QQNode('screen_res', 'ScreenRes', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'ViewLog':
					return new QQReverseReferenceNodeViewLog($this, 'viewlog', 'reverse_reference', 'visitor_id');

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