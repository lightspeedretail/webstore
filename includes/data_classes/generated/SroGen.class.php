<?php
	/**
	 * The abstract SroGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Sro subclass which
	 * extends this SroGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Sro class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $LsId the value for strLsId (Unique)
	 * @property string $CustomerName the value for strCustomerName 
	 * @property string $CustomerEmailPhone the value for strCustomerEmailPhone (Not Null)
	 * @property string $Zipcode the value for strZipcode 
	 * @property string $ProblemDescription the value for strProblemDescription 
	 * @property string $PrintedNotes the value for strPrintedNotes 
	 * @property string $WorkPerformed the value for strWorkPerformed 
	 * @property string $AdditionalItems the value for strAdditionalItems 
	 * @property string $Warranty the value for strWarranty 
	 * @property string $WarrantyInfo the value for strWarrantyInfo 
	 * @property string $Status the value for strStatus 
	 * @property integer $CartId the value for intCartId 
	 * @property QDateTime $DatetimeCre the value for dttDatetimeCre 
	 * @property string $DatetimeMod the value for strDatetimeMod (Read-Only Timestamp)
	 * @property Cart $Cart the value for the Cart object referenced by intCartId 
	 * @property SroRepair $_SroRepair the value for the private _objSroRepair (Read-Only) if set due to an expansion on the xlsws_sro_repair.sro_id reverse relationship
	 * @property SroRepair[] $_SroRepairArray the value for the private _objSroRepairArray (Read-Only) if set due to an ExpandAsArray on the xlsws_sro_repair.sro_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class SroGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_sro.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.ls_id
		 * @var string strLsId
		 */
		protected $strLsId;
		const LsIdMaxLength = 20;
		const LsIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.customer_name
		 * @var string strCustomerName
		 */
		protected $strCustomerName;
		const CustomerNameMaxLength = 255;
		const CustomerNameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.customer_email_phone
		 * @var string strCustomerEmailPhone
		 */
		protected $strCustomerEmailPhone;
		const CustomerEmailPhoneMaxLength = 255;
		const CustomerEmailPhoneDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.zipcode
		 * @var string strZipcode
		 */
		protected $strZipcode;
		const ZipcodeMaxLength = 10;
		const ZipcodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.problem_description
		 * @var string strProblemDescription
		 */
		protected $strProblemDescription;
		const ProblemDescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.printed_notes
		 * @var string strPrintedNotes
		 */
		protected $strPrintedNotes;
		const PrintedNotesDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.work_performed
		 * @var string strWorkPerformed
		 */
		protected $strWorkPerformed;
		const WorkPerformedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.additional_items
		 * @var string strAdditionalItems
		 */
		protected $strAdditionalItems;
		const AdditionalItemsDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.warranty
		 * @var string strWarranty
		 */
		protected $strWarranty;
		const WarrantyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.warranty_info
		 * @var string strWarrantyInfo
		 */
		protected $strWarrantyInfo;
		const WarrantyInfoDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.status
		 * @var string strStatus
		 */
		protected $strStatus;
		const StatusMaxLength = 32;
		const StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.cart_id
		 * @var integer intCartId
		 */
		protected $intCartId;
		const CartIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.datetime_cre
		 * @var QDateTime dttDatetimeCre
		 */
		protected $dttDatetimeCre;
		const DatetimeCreDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_sro.datetime_mod
		 * @var string strDatetimeMod
		 */
		protected $strDatetimeMod;
		const DatetimeModDefault = null;


		/**
		 * Private member variable that stores a reference to a single SroRepair object
		 * (of type SroRepair), if this Sro object was restored with
		 * an expansion on the xlsws_sro_repair association table.
		 * @var SroRepair _objSroRepair;
		 */
		private $_objSroRepair;

		/**
		 * Private member variable that stores a reference to an array of SroRepair objects
		 * (of type SroRepair[]), if this Sro object was restored with
		 * an ExpandAsArray on the xlsws_sro_repair association table.
		 * @var SroRepair[] _objSroRepairArray;
		 */
		private $_objSroRepairArray = array();

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
		 * in the database column xlsws_sro.cart_id.
		 *
		 * NOTE: Always use the Cart property getter to correctly retrieve this Cart object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Cart objCart
		 */
		protected $objCart;





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
		 * Load a Sro from PK Info
		 * @param integer $intRowid
		 * @return Sro
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Sro::QuerySingle(
				QQ::Equal(QQN::Sro()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Sros
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sro[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Sro::QueryArray to perform the LoadAll query
			try {
				return Sro::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Sros
		 * @return int
		 */
		public static function CountAll() {
			// Call Sro::QueryCount to perform the CountAll query
			return Sro::QueryCount(QQ::All());
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
			$objDatabase = Sro::GetDatabase();

			// Create/Build out the QueryBuilder object with Sro-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_sro');
			Sro::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_sro');

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
		 * Static Qcodo Query method to query for a single Sro object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Sro the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Sro::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Sro object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Sro::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Sro objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Sro[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Sro::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Sro::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Sro objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Sro::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Sro::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_sro_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Sro-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Sro::GetSelectFields($objQueryBuilder);
				Sro::GetFromFields($objQueryBuilder);

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
			return Sro::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Sro
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_sro';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'ls_id', $strAliasPrefix . 'ls_id');
			$objBuilder->AddSelectItem($strTableName, 'customer_name', $strAliasPrefix . 'customer_name');
			$objBuilder->AddSelectItem($strTableName, 'customer_email_phone', $strAliasPrefix . 'customer_email_phone');
			$objBuilder->AddSelectItem($strTableName, 'zipcode', $strAliasPrefix . 'zipcode');
			$objBuilder->AddSelectItem($strTableName, 'problem_description', $strAliasPrefix . 'problem_description');
			$objBuilder->AddSelectItem($strTableName, 'printed_notes', $strAliasPrefix . 'printed_notes');
			$objBuilder->AddSelectItem($strTableName, 'work_performed', $strAliasPrefix . 'work_performed');
			$objBuilder->AddSelectItem($strTableName, 'additional_items', $strAliasPrefix . 'additional_items');
			$objBuilder->AddSelectItem($strTableName, 'warranty', $strAliasPrefix . 'warranty');
			$objBuilder->AddSelectItem($strTableName, 'warranty_info', $strAliasPrefix . 'warranty_info');
			$objBuilder->AddSelectItem($strTableName, 'status', $strAliasPrefix . 'status');
			$objBuilder->AddSelectItem($strTableName, 'cart_id', $strAliasPrefix . 'cart_id');
			$objBuilder->AddSelectItem($strTableName, 'datetime_cre', $strAliasPrefix . 'datetime_cre');
			$objBuilder->AddSelectItem($strTableName, 'datetime_mod', $strAliasPrefix . 'datetime_mod');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Sro from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Sro::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Sro
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
					$strAliasPrefix = 'xlsws_sro__';


				$strAlias = $strAliasPrefix . 'srorepair__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objSroRepairArray)) {
						$objPreviousChildItem = $objPreviousItem->_objSroRepairArray[$intPreviousChildItemCount - 1];
						$objChildItem = SroRepair::InstantiateDbRow($objDbRow, $strAliasPrefix . 'srorepair__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objSroRepairArray[] = $objChildItem;
					} else
						$objPreviousItem->_objSroRepairArray[] = SroRepair::InstantiateDbRow($objDbRow, $strAliasPrefix . 'srorepair__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_sro__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Sro object
			$objToReturn = new Sro();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'ls_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ls_id'] : $strAliasPrefix . 'ls_id';
			$objToReturn->strLsId = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'customer_name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'customer_name'] : $strAliasPrefix . 'customer_name';
			$objToReturn->strCustomerName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'customer_email_phone', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'customer_email_phone'] : $strAliasPrefix . 'customer_email_phone';
			$objToReturn->strCustomerEmailPhone = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zipcode', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zipcode'] : $strAliasPrefix . 'zipcode';
			$objToReturn->strZipcode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'problem_description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'problem_description'] : $strAliasPrefix . 'problem_description';
			$objToReturn->strProblemDescription = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'printed_notes', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'printed_notes'] : $strAliasPrefix . 'printed_notes';
			$objToReturn->strPrintedNotes = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'work_performed', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'work_performed'] : $strAliasPrefix . 'work_performed';
			$objToReturn->strWorkPerformed = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'additional_items', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'additional_items'] : $strAliasPrefix . 'additional_items';
			$objToReturn->strAdditionalItems = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'warranty', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'warranty'] : $strAliasPrefix . 'warranty';
			$objToReturn->strWarranty = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'warranty_info', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'warranty_info'] : $strAliasPrefix . 'warranty_info';
			$objToReturn->strWarrantyInfo = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'status'] : $strAliasPrefix . 'status';
			$objToReturn->strStatus = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'cart_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'cart_id'] : $strAliasPrefix . 'cart_id';
			$objToReturn->intCartId = $objDbRow->GetColumn($strAliasName, 'Integer');
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
				$strAliasPrefix = 'xlsws_sro__';

			// Check for Cart Early Binding
			$strAlias = $strAliasPrefix . 'cart_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objCart = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			// Check for SroRepair Virtual Binding
			$strAlias = $strAliasPrefix . 'srorepair__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objSroRepairArray[] = SroRepair::InstantiateDbRow($objDbRow, $strAliasPrefix . 'srorepair__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objSroRepair = SroRepair::InstantiateDbRow($objDbRow, $strAliasPrefix . 'srorepair__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Sros from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Sro[]
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
					$objItem = Sro::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Sro::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Sro object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Sro
		*/
		public static function LoadByRowid($intRowid) {
			return Sro::QuerySingle(
				QQ::Equal(QQN::Sro()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Sro object,
		 * by LsId Index(es)
		 * @param string $strLsId
		 * @return Sro
		*/
		public static function LoadByLsId($strLsId) {
			return Sro::QuerySingle(
				QQ::Equal(QQN::Sro()->LsId, $strLsId)
			);
		}
			
		/**
		 * Load an array of Sro objects,
		 * by CartId Index(es)
		 * @param integer $intCartId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sro[]
		*/
		public static function LoadArrayByCartId($intCartId, $objOptionalClauses = null) {
			// Call Sro::QueryArray to perform the LoadArrayByCartId query
			try {
				return Sro::QueryArray(
					QQ::Equal(QQN::Sro()->CartId, $intCartId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Sros
		 * by CartId Index(es)
		 * @param integer $intCartId
		 * @return int
		*/
		public static function CountByCartId($intCartId) {
			// Call Sro::QueryCount to perform the CountByCartId query
			return Sro::QueryCount(
				QQ::Equal(QQN::Sro()->CartId, $intCartId)
			);
		}
			
		/**
		 * Load an array of Sro objects,
		 * by CustomerEmailPhone Index(es)
		 * @param string $strCustomerEmailPhone
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sro[]
		*/
		public static function LoadArrayByCustomerEmailPhone($strCustomerEmailPhone, $objOptionalClauses = null) {
			// Call Sro::QueryArray to perform the LoadArrayByCustomerEmailPhone query
			try {
				return Sro::QueryArray(
					QQ::Equal(QQN::Sro()->CustomerEmailPhone, $strCustomerEmailPhone),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Sros
		 * by CustomerEmailPhone Index(es)
		 * @param string $strCustomerEmailPhone
		 * @return int
		*/
		public static function CountByCustomerEmailPhone($strCustomerEmailPhone) {
			// Call Sro::QueryCount to perform the CountByCustomerEmailPhone query
			return Sro::QueryCount(
				QQ::Equal(QQN::Sro()->CustomerEmailPhone, $strCustomerEmailPhone)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Sro
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_sro` (
							`ls_id`,
							`customer_name`,
							`customer_email_phone`,
							`zipcode`,
							`problem_description`,
							`printed_notes`,
							`work_performed`,
							`additional_items`,
							`warranty`,
							`warranty_info`,
							`status`,
							`cart_id`,
							`datetime_cre`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strLsId) . ',
							' . $objDatabase->SqlVariable($this->strCustomerName) . ',
							' . $objDatabase->SqlVariable($this->strCustomerEmailPhone) . ',
							' . $objDatabase->SqlVariable($this->strZipcode) . ',
							' . $objDatabase->SqlVariable($this->strProblemDescription) . ',
							' . $objDatabase->SqlVariable($this->strPrintedNotes) . ',
							' . $objDatabase->SqlVariable($this->strWorkPerformed) . ',
							' . $objDatabase->SqlVariable($this->strAdditionalItems) . ',
							' . $objDatabase->SqlVariable($this->strWarranty) . ',
							' . $objDatabase->SqlVariable($this->strWarrantyInfo) . ',
							' . $objDatabase->SqlVariable($this->strStatus) . ',
							' . $objDatabase->SqlVariable($this->intCartId) . ',
							' . $objDatabase->SqlVariable($this->dttDatetimeCre) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_sro', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`datetime_mod`
							FROM
								`xlsws_sro`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strDatetimeMod)
							throw new QOptimisticLockingException('Sro');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_sro`
						SET
							`ls_id` = ' . $objDatabase->SqlVariable($this->strLsId) . ',
							`customer_name` = ' . $objDatabase->SqlVariable($this->strCustomerName) . ',
							`customer_email_phone` = ' . $objDatabase->SqlVariable($this->strCustomerEmailPhone) . ',
							`zipcode` = ' . $objDatabase->SqlVariable($this->strZipcode) . ',
							`problem_description` = ' . $objDatabase->SqlVariable($this->strProblemDescription) . ',
							`printed_notes` = ' . $objDatabase->SqlVariable($this->strPrintedNotes) . ',
							`work_performed` = ' . $objDatabase->SqlVariable($this->strWorkPerformed) . ',
							`additional_items` = ' . $objDatabase->SqlVariable($this->strAdditionalItems) . ',
							`warranty` = ' . $objDatabase->SqlVariable($this->strWarranty) . ',
							`warranty_info` = ' . $objDatabase->SqlVariable($this->strWarrantyInfo) . ',
							`status` = ' . $objDatabase->SqlVariable($this->strStatus) . ',
							`cart_id` = ' . $objDatabase->SqlVariable($this->intCartId) . ',
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
					`xlsws_sro`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strDatetimeMod = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Sro
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Sro with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Sros
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro`');
		}

		/**
		 * Truncate xlsws_sro table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_sro`');
		}

		/**
		 * Reload this Sro from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Sro object.');

			// Reload the Object
			$objReloaded = Sro::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strLsId = $objReloaded->strLsId;
			$this->strCustomerName = $objReloaded->strCustomerName;
			$this->strCustomerEmailPhone = $objReloaded->strCustomerEmailPhone;
			$this->strZipcode = $objReloaded->strZipcode;
			$this->strProblemDescription = $objReloaded->strProblemDescription;
			$this->strPrintedNotes = $objReloaded->strPrintedNotes;
			$this->strWorkPerformed = $objReloaded->strWorkPerformed;
			$this->strAdditionalItems = $objReloaded->strAdditionalItems;
			$this->strWarranty = $objReloaded->strWarranty;
			$this->strWarrantyInfo = $objReloaded->strWarrantyInfo;
			$this->strStatus = $objReloaded->strStatus;
			$this->CartId = $objReloaded->CartId;
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

				case 'LsId':
					// Gets the value for strLsId (Unique)
					// @return string
					return $this->strLsId;

				case 'CustomerName':
					// Gets the value for strCustomerName 
					// @return string
					return $this->strCustomerName;

				case 'CustomerEmailPhone':
					// Gets the value for strCustomerEmailPhone (Not Null)
					// @return string
					return $this->strCustomerEmailPhone;

				case 'Zipcode':
					// Gets the value for strZipcode 
					// @return string
					return $this->strZipcode;

				case 'ProblemDescription':
					// Gets the value for strProblemDescription 
					// @return string
					return $this->strProblemDescription;

				case 'PrintedNotes':
					// Gets the value for strPrintedNotes 
					// @return string
					return $this->strPrintedNotes;

				case 'WorkPerformed':
					// Gets the value for strWorkPerformed 
					// @return string
					return $this->strWorkPerformed;

				case 'AdditionalItems':
					// Gets the value for strAdditionalItems 
					// @return string
					return $this->strAdditionalItems;

				case 'Warranty':
					// Gets the value for strWarranty 
					// @return string
					return $this->strWarranty;

				case 'WarrantyInfo':
					// Gets the value for strWarrantyInfo 
					// @return string
					return $this->strWarrantyInfo;

				case 'Status':
					// Gets the value for strStatus 
					// @return string
					return $this->strStatus;

				case 'CartId':
					// Gets the value for intCartId 
					// @return integer
					return $this->intCartId;

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
				case 'Cart':
					// Gets the value for the Cart object referenced by intCartId 
					// @return Cart
					try {
						if ((!$this->objCart) && (!is_null($this->intCartId)))
							$this->objCart = Cart::Load($this->intCartId);
						return $this->objCart;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_SroRepair':
					// Gets the value for the private _objSroRepair (Read-Only)
					// if set due to an expansion on the xlsws_sro_repair.sro_id reverse relationship
					// @return SroRepair
					return $this->_objSroRepair;

				case '_SroRepairArray':
					// Gets the value for the private _objSroRepairArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_sro_repair.sro_id reverse relationship
					// @return SroRepair[]
					return (array) $this->_objSroRepairArray;


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
				case 'LsId':
					// Sets the value for strLsId (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strLsId = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CustomerName':
					// Sets the value for strCustomerName 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCustomerName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CustomerEmailPhone':
					// Sets the value for strCustomerEmailPhone (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCustomerEmailPhone = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Zipcode':
					// Sets the value for strZipcode 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strZipcode = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProblemDescription':
					// Sets the value for strProblemDescription 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strProblemDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PrintedNotes':
					// Sets the value for strPrintedNotes 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPrintedNotes = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WorkPerformed':
					// Sets the value for strWorkPerformed 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strWorkPerformed = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AdditionalItems':
					// Sets the value for strAdditionalItems 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAdditionalItems = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Warranty':
					// Sets the value for strWarranty 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strWarranty = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WarrantyInfo':
					// Sets the value for strWarrantyInfo 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strWarrantyInfo = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Status':
					// Sets the value for strStatus 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strStatus = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CartId':
					// Sets the value for intCartId 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objCart = null;
						return ($this->intCartId = QType::Cast($mixValue, QType::Integer));
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
				case 'Cart':
					// Sets the value for the Cart object referenced by intCartId 
					// @param Cart $mixValue
					// @return Cart
					if (is_null($mixValue)) {
						$this->intCartId = null;
						$this->objCart = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Cart object
						try {
							$mixValue = QType::Cast($mixValue, 'Cart');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Cart object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Cart for this Sro');

						// Update Local Member Variables
						$this->objCart = $mixValue;
						$this->intCartId = $mixValue->Rowid;

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

			
		
		// Related Objects' Methods for SroRepair
		//-------------------------------------------------------------------

		/**
		 * Gets all associated SroRepairs as an array of SroRepair objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return SroRepair[]
		*/ 
		public function GetSroRepairArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return SroRepair::LoadArrayBySroId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated SroRepairs
		 * @return int
		*/ 
		public function CountSroRepairs() {
			if ((is_null($this->intRowid)))
				return 0;

			return SroRepair::CountBySroId($this->intRowid);
		}

		/**
		 * Associates a SroRepair
		 * @param SroRepair $objSroRepair
		 * @return void
		*/ 
		public function AssociateSroRepair(SroRepair $objSroRepair) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateSroRepair on this unsaved Sro.');
			if ((is_null($objSroRepair->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateSroRepair on this Sro with an unsaved SroRepair.');

			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_sro_repair`
				SET
					`sro_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objSroRepair->Rowid) . '
			');
		}

		/**
		 * Unassociates a SroRepair
		 * @param SroRepair $objSroRepair
		 * @return void
		*/ 
		public function UnassociateSroRepair(SroRepair $objSroRepair) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSroRepair on this unsaved Sro.');
			if ((is_null($objSroRepair->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSroRepair on this Sro with an unsaved SroRepair.');

			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_sro_repair`
				SET
					`sro_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objSroRepair->Rowid) . ' AND
					`sro_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all SroRepairs
		 * @return void
		*/ 
		public function UnassociateAllSroRepairs() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSroRepair on this unsaved Sro.');

			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_sro_repair`
				SET
					`sro_id` = null
				WHERE
					`sro_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated SroRepair
		 * @param SroRepair $objSroRepair
		 * @return void
		*/ 
		public function DeleteAssociatedSroRepair(SroRepair $objSroRepair) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSroRepair on this unsaved Sro.');
			if ((is_null($objSroRepair->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSroRepair on this Sro with an unsaved SroRepair.');

			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro_repair`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objSroRepair->Rowid) . ' AND
					`sro_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated SroRepairs
		 * @return void
		*/ 
		public function DeleteAllSroRepairs() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSroRepair on this unsaved Sro.');

			// Get the Database Object for this Class
			$objDatabase = Sro::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro_repair`
				WHERE
					`sro_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Sro"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="LsId" type="xsd:string"/>';
			$strToReturn .= '<element name="CustomerName" type="xsd:string"/>';
			$strToReturn .= '<element name="CustomerEmailPhone" type="xsd:string"/>';
			$strToReturn .= '<element name="Zipcode" type="xsd:string"/>';
			$strToReturn .= '<element name="ProblemDescription" type="xsd:string"/>';
			$strToReturn .= '<element name="PrintedNotes" type="xsd:string"/>';
			$strToReturn .= '<element name="WorkPerformed" type="xsd:string"/>';
			$strToReturn .= '<element name="AdditionalItems" type="xsd:string"/>';
			$strToReturn .= '<element name="Warranty" type="xsd:string"/>';
			$strToReturn .= '<element name="WarrantyInfo" type="xsd:string"/>';
			$strToReturn .= '<element name="Status" type="xsd:string"/>';
			$strToReturn .= '<element name="Cart" type="xsd1:Cart"/>';
			$strToReturn .= '<element name="DatetimeCre" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="DatetimeMod" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Sro', $strComplexTypeArray)) {
				$strComplexTypeArray['Sro'] = Sro::GetSoapComplexTypeXml();
				Cart::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Sro::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Sro();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'LsId'))
				$objToReturn->strLsId = $objSoapObject->LsId;
			if (property_exists($objSoapObject, 'CustomerName'))
				$objToReturn->strCustomerName = $objSoapObject->CustomerName;
			if (property_exists($objSoapObject, 'CustomerEmailPhone'))
				$objToReturn->strCustomerEmailPhone = $objSoapObject->CustomerEmailPhone;
			if (property_exists($objSoapObject, 'Zipcode'))
				$objToReturn->strZipcode = $objSoapObject->Zipcode;
			if (property_exists($objSoapObject, 'ProblemDescription'))
				$objToReturn->strProblemDescription = $objSoapObject->ProblemDescription;
			if (property_exists($objSoapObject, 'PrintedNotes'))
				$objToReturn->strPrintedNotes = $objSoapObject->PrintedNotes;
			if (property_exists($objSoapObject, 'WorkPerformed'))
				$objToReturn->strWorkPerformed = $objSoapObject->WorkPerformed;
			if (property_exists($objSoapObject, 'AdditionalItems'))
				$objToReturn->strAdditionalItems = $objSoapObject->AdditionalItems;
			if (property_exists($objSoapObject, 'Warranty'))
				$objToReturn->strWarranty = $objSoapObject->Warranty;
			if (property_exists($objSoapObject, 'WarrantyInfo'))
				$objToReturn->strWarrantyInfo = $objSoapObject->WarrantyInfo;
			if (property_exists($objSoapObject, 'Status'))
				$objToReturn->strStatus = $objSoapObject->Status;
			if ((property_exists($objSoapObject, 'Cart')) &&
				($objSoapObject->Cart))
				$objToReturn->Cart = Cart::GetObjectFromSoapObject($objSoapObject->Cart);
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
				array_push($objArrayToReturn, Sro::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objCart)
				$objObject->objCart = Cart::GetSoapObjectFromObject($objObject->objCart, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intCartId = null;
			if ($objObject->dttDatetimeCre)
				$objObject->dttDatetimeCre = $objObject->dttDatetimeCre->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeSro extends QQNode {
		protected $strTableName = 'xlsws_sro';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Sro';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'LsId':
					return new QQNode('ls_id', 'LsId', 'string', $this);
				case 'CustomerName':
					return new QQNode('customer_name', 'CustomerName', 'string', $this);
				case 'CustomerEmailPhone':
					return new QQNode('customer_email_phone', 'CustomerEmailPhone', 'string', $this);
				case 'Zipcode':
					return new QQNode('zipcode', 'Zipcode', 'string', $this);
				case 'ProblemDescription':
					return new QQNode('problem_description', 'ProblemDescription', 'string', $this);
				case 'PrintedNotes':
					return new QQNode('printed_notes', 'PrintedNotes', 'string', $this);
				case 'WorkPerformed':
					return new QQNode('work_performed', 'WorkPerformed', 'string', $this);
				case 'AdditionalItems':
					return new QQNode('additional_items', 'AdditionalItems', 'string', $this);
				case 'Warranty':
					return new QQNode('warranty', 'Warranty', 'string', $this);
				case 'WarrantyInfo':
					return new QQNode('warranty_info', 'WarrantyInfo', 'string', $this);
				case 'Status':
					return new QQNode('status', 'Status', 'string', $this);
				case 'CartId':
					return new QQNode('cart_id', 'CartId', 'integer', $this);
				case 'Cart':
					return new QQNodeCart('cart_id', 'Cart', 'integer', $this);
				case 'DatetimeCre':
					return new QQNode('datetime_cre', 'DatetimeCre', 'QDateTime', $this);
				case 'DatetimeMod':
					return new QQNode('datetime_mod', 'DatetimeMod', 'string', $this);
				case 'SroRepair':
					return new QQReverseReferenceNodeSroRepair($this, 'srorepair', 'reverse_reference', 'sro_id');

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

	class QQReverseReferenceNodeSro extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_sro';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Sro';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'LsId':
					return new QQNode('ls_id', 'LsId', 'string', $this);
				case 'CustomerName':
					return new QQNode('customer_name', 'CustomerName', 'string', $this);
				case 'CustomerEmailPhone':
					return new QQNode('customer_email_phone', 'CustomerEmailPhone', 'string', $this);
				case 'Zipcode':
					return new QQNode('zipcode', 'Zipcode', 'string', $this);
				case 'ProblemDescription':
					return new QQNode('problem_description', 'ProblemDescription', 'string', $this);
				case 'PrintedNotes':
					return new QQNode('printed_notes', 'PrintedNotes', 'string', $this);
				case 'WorkPerformed':
					return new QQNode('work_performed', 'WorkPerformed', 'string', $this);
				case 'AdditionalItems':
					return new QQNode('additional_items', 'AdditionalItems', 'string', $this);
				case 'Warranty':
					return new QQNode('warranty', 'Warranty', 'string', $this);
				case 'WarrantyInfo':
					return new QQNode('warranty_info', 'WarrantyInfo', 'string', $this);
				case 'Status':
					return new QQNode('status', 'Status', 'string', $this);
				case 'CartId':
					return new QQNode('cart_id', 'CartId', 'integer', $this);
				case 'Cart':
					return new QQNodeCart('cart_id', 'Cart', 'integer', $this);
				case 'DatetimeCre':
					return new QQNode('datetime_cre', 'DatetimeCre', 'QDateTime', $this);
				case 'DatetimeMod':
					return new QQNode('datetime_mod', 'DatetimeMod', 'string', $this);
				case 'SroRepair':
					return new QQReverseReferenceNodeSroRepair($this, 'srorepair', 'reverse_reference', 'sro_id');

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