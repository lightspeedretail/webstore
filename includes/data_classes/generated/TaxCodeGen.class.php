<?php
	/**
	 * The abstract TaxCodeGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the TaxCode subclass which
	 * extends this TaxCodeGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the TaxCode class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Code the value for strCode (Unique)
	 * @property integer $ListOrder the value for intListOrder (Not Null)
	 * @property string $Tax1Rate the value for strTax1Rate (Not Null)
	 * @property string $Tax2Rate the value for strTax2Rate (Not Null)
	 * @property string $Tax3Rate the value for strTax3Rate (Not Null)
	 * @property string $Tax4Rate the value for strTax4Rate (Not Null)
	 * @property string $Tax5Rate the value for strTax5Rate (Not Null)
	 * @property Cart $_CartAsFk the value for the private _objCartAsFk (Read-Only) if set due to an expansion on the xlsws_cart.fk_tax_code_id reverse relationship
	 * @property Cart[] $_CartAsFkArray the value for the private _objCartAsFkArray (Read-Only) if set due to an ExpandAsArray on the xlsws_cart.fk_tax_code_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class TaxCodeGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_tax_code.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.code
		 * @var string strCode
		 */
		protected $strCode;
		const CodeMaxLength = 32;
		const CodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.list_order
		 * @var integer intListOrder
		 */
		protected $intListOrder;
		const ListOrderDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.tax1_rate
		 * @var string strTax1Rate
		 */
		protected $strTax1Rate;
		const Tax1RateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.tax2_rate
		 * @var string strTax2Rate
		 */
		protected $strTax2Rate;
		const Tax2RateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.tax3_rate
		 * @var string strTax3Rate
		 */
		protected $strTax3Rate;
		const Tax3RateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.tax4_rate
		 * @var string strTax4Rate
		 */
		protected $strTax4Rate;
		const Tax4RateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_tax_code.tax5_rate
		 * @var string strTax5Rate
		 */
		protected $strTax5Rate;
		const Tax5RateDefault = null;


		/**
		 * Private member variable that stores a reference to a single CartAsFk object
		 * (of type Cart), if this TaxCode object was restored with
		 * an expansion on the xlsws_cart association table.
		 * @var Cart _objCartAsFk;
		 */
		private $_objCartAsFk;

		/**
		 * Private member variable that stores a reference to an array of CartAsFk objects
		 * (of type Cart[]), if this TaxCode object was restored with
		 * an ExpandAsArray on the xlsws_cart association table.
		 * @var Cart[] _objCartAsFkArray;
		 */
		private $_objCartAsFkArray = array();

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
		 * Load a TaxCode from PK Info
		 * @param integer $intRowid
		 * @return TaxCode
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return TaxCode::QuerySingle(
				QQ::Equal(QQN::TaxCode()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all TaxCodes
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return TaxCode[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call TaxCode::QueryArray to perform the LoadAll query
			try {
				return TaxCode::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all TaxCodes
		 * @return int
		 */
		public static function CountAll() {
			// Call TaxCode::QueryCount to perform the CountAll query
			return TaxCode::QueryCount(QQ::All());
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
			$objDatabase = TaxCode::GetDatabase();

			// Create/Build out the QueryBuilder object with TaxCode-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_tax_code');
			TaxCode::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_tax_code');

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
		 * Static Qcodo Query method to query for a single TaxCode object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return TaxCode the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = TaxCode::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new TaxCode object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return TaxCode::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of TaxCode objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return TaxCode[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = TaxCode::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return TaxCode::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of TaxCode objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = TaxCode::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = TaxCode::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_tax_code_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with TaxCode-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				TaxCode::GetSelectFields($objQueryBuilder);
				TaxCode::GetFromFields($objQueryBuilder);

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
			return TaxCode::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this TaxCode
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_tax_code';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'code', $strAliasPrefix . 'code');
			$objBuilder->AddSelectItem($strTableName, 'list_order', $strAliasPrefix . 'list_order');
			$objBuilder->AddSelectItem($strTableName, 'tax1_rate', $strAliasPrefix . 'tax1_rate');
			$objBuilder->AddSelectItem($strTableName, 'tax2_rate', $strAliasPrefix . 'tax2_rate');
			$objBuilder->AddSelectItem($strTableName, 'tax3_rate', $strAliasPrefix . 'tax3_rate');
			$objBuilder->AddSelectItem($strTableName, 'tax4_rate', $strAliasPrefix . 'tax4_rate');
			$objBuilder->AddSelectItem($strTableName, 'tax5_rate', $strAliasPrefix . 'tax5_rate');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a TaxCode from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this TaxCode::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return TaxCode
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
					$strAliasPrefix = 'xlsws_tax_code__';


				$strAlias = $strAliasPrefix . 'cartasfk__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objCartAsFkArray)) {
						$objPreviousChildItem = $objPreviousItem->_objCartAsFkArray[$intPreviousChildItemCount - 1];
						$objChildItem = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartasfk__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objCartAsFkArray[] = $objChildItem;
					} else
						$objPreviousItem->_objCartAsFkArray[] = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartasfk__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_tax_code__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the TaxCode object
			$objToReturn = new TaxCode();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code'] : $strAliasPrefix . 'code';
			$objToReturn->strCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'list_order', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'list_order'] : $strAliasPrefix . 'list_order';
			$objToReturn->intListOrder = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax1_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax1_rate'] : $strAliasPrefix . 'tax1_rate';
			$objToReturn->strTax1Rate = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax2_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax2_rate'] : $strAliasPrefix . 'tax2_rate';
			$objToReturn->strTax2Rate = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax3_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax3_rate'] : $strAliasPrefix . 'tax3_rate';
			$objToReturn->strTax3Rate = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax4_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax4_rate'] : $strAliasPrefix . 'tax4_rate';
			$objToReturn->strTax4Rate = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax5_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax5_rate'] : $strAliasPrefix . 'tax5_rate';
			$objToReturn->strTax5Rate = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_tax_code__';




			// Check for CartAsFk Virtual Binding
			$strAlias = $strAliasPrefix . 'cartasfk__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCartAsFkArray[] = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartasfk__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCartAsFk = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartasfk__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of TaxCodes from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return TaxCode[]
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
					$objItem = TaxCode::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = TaxCode::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single TaxCode object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return TaxCode
		*/
		public static function LoadByRowid($intRowid) {
			return TaxCode::QuerySingle(
				QQ::Equal(QQN::TaxCode()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single TaxCode object,
		 * by Code Index(es)
		 * @param string $strCode
		 * @return TaxCode
		*/
		public static function LoadByCode($strCode) {
			return TaxCode::QuerySingle(
				QQ::Equal(QQN::TaxCode()->Code, $strCode)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this TaxCode
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_tax_code` (
							`code`,
							`list_order`,
							`tax1_rate`,
							`tax2_rate`,
							`tax3_rate`,
							`tax4_rate`,
							`tax5_rate`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strCode) . ',
							' . $objDatabase->SqlVariable($this->intListOrder) . ',
							' . $objDatabase->SqlVariable($this->strTax1Rate) . ',
							' . $objDatabase->SqlVariable($this->strTax2Rate) . ',
							' . $objDatabase->SqlVariable($this->strTax3Rate) . ',
							' . $objDatabase->SqlVariable($this->strTax4Rate) . ',
							' . $objDatabase->SqlVariable($this->strTax5Rate) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_tax_code', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_tax_code`
						SET
							`code` = ' . $objDatabase->SqlVariable($this->strCode) . ',
							`list_order` = ' . $objDatabase->SqlVariable($this->intListOrder) . ',
							`tax1_rate` = ' . $objDatabase->SqlVariable($this->strTax1Rate) . ',
							`tax2_rate` = ' . $objDatabase->SqlVariable($this->strTax2Rate) . ',
							`tax3_rate` = ' . $objDatabase->SqlVariable($this->strTax3Rate) . ',
							`tax4_rate` = ' . $objDatabase->SqlVariable($this->strTax4Rate) . ',
							`tax5_rate` = ' . $objDatabase->SqlVariable($this->strTax5Rate) . '
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
		 * Delete this TaxCode
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this TaxCode with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_tax_code`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all TaxCodes
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_tax_code`');
		}

		/**
		 * Truncate xlsws_tax_code table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_tax_code`');
		}

		/**
		 * Reload this TaxCode from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved TaxCode object.');

			// Reload the Object
			$objReloaded = TaxCode::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strCode = $objReloaded->strCode;
			$this->intListOrder = $objReloaded->intListOrder;
			$this->strTax1Rate = $objReloaded->strTax1Rate;
			$this->strTax2Rate = $objReloaded->strTax2Rate;
			$this->strTax3Rate = $objReloaded->strTax3Rate;
			$this->strTax4Rate = $objReloaded->strTax4Rate;
			$this->strTax5Rate = $objReloaded->strTax5Rate;
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

				case 'ListOrder':
					// Gets the value for intListOrder (Not Null)
					// @return integer
					return $this->intListOrder;

				case 'Tax1Rate':
					// Gets the value for strTax1Rate (Not Null)
					// @return string
					return $this->strTax1Rate;

				case 'Tax2Rate':
					// Gets the value for strTax2Rate (Not Null)
					// @return string
					return $this->strTax2Rate;

				case 'Tax3Rate':
					// Gets the value for strTax3Rate (Not Null)
					// @return string
					return $this->strTax3Rate;

				case 'Tax4Rate':
					// Gets the value for strTax4Rate (Not Null)
					// @return string
					return $this->strTax4Rate;

				case 'Tax5Rate':
					// Gets the value for strTax5Rate (Not Null)
					// @return string
					return $this->strTax5Rate;


				///////////////////
				// Member Objects
				///////////////////

				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_CartAsFk':
					// Gets the value for the private _objCartAsFk (Read-Only)
					// if set due to an expansion on the xlsws_cart.fk_tax_code_id reverse relationship
					// @return Cart
					return $this->_objCartAsFk;

				case '_CartAsFkArray':
					// Gets the value for the private _objCartAsFkArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_cart.fk_tax_code_id reverse relationship
					// @return Cart[]
					return (array) $this->_objCartAsFkArray;


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

				case 'ListOrder':
					// Sets the value for intListOrder (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intListOrder = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax1Rate':
					// Sets the value for strTax1Rate (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax1Rate = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax2Rate':
					// Sets the value for strTax2Rate (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax2Rate = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax3Rate':
					// Sets the value for strTax3Rate (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax3Rate = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax4Rate':
					// Sets the value for strTax4Rate (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax4Rate = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax5Rate':
					// Sets the value for strTax5Rate (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax5Rate = QType::Cast($mixValue, QType::String));
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

			
		
		// Related Objects' Methods for CartAsFk
		//-------------------------------------------------------------------

		/**
		 * Gets all associated CartsAsFk as an array of Cart objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/ 
		public function GetCartAsFkArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Cart::LoadArrayByFkTaxCodeId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated CartsAsFk
		 * @return int
		*/ 
		public function CountCartsAsFk() {
			if ((is_null($this->intRowid)))
				return 0;

			return Cart::CountByFkTaxCodeId($this->intRowid);
		}

		/**
		 * Associates a CartAsFk
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function AssociateCartAsFk(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartAsFk on this unsaved TaxCode.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartAsFk on this TaxCode with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`fk_tax_code_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . '
			');
		}

		/**
		 * Unassociates a CartAsFk
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function UnassociateCartAsFk(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartAsFk on this unsaved TaxCode.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartAsFk on this TaxCode with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`fk_tax_code_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . ' AND
					`fk_tax_code_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all CartsAsFk
		 * @return void
		*/ 
		public function UnassociateAllCartsAsFk() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartAsFk on this unsaved TaxCode.');

			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`fk_tax_code_id` = null
				WHERE
					`fk_tax_code_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated CartAsFk
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function DeleteAssociatedCartAsFk(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartAsFk on this unsaved TaxCode.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartAsFk on this TaxCode with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . ' AND
					`fk_tax_code_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated CartsAsFk
		 * @return void
		*/ 
		public function DeleteAllCartsAsFk() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartAsFk on this unsaved TaxCode.');

			// Get the Database Object for this Class
			$objDatabase = TaxCode::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`fk_tax_code_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="TaxCode"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Code" type="xsd:string"/>';
			$strToReturn .= '<element name="ListOrder" type="xsd:int"/>';
			$strToReturn .= '<element name="Tax1Rate" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax2Rate" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax3Rate" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax4Rate" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax5Rate" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('TaxCode', $strComplexTypeArray)) {
				$strComplexTypeArray['TaxCode'] = TaxCode::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, TaxCode::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new TaxCode();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Code'))
				$objToReturn->strCode = $objSoapObject->Code;
			if (property_exists($objSoapObject, 'ListOrder'))
				$objToReturn->intListOrder = $objSoapObject->ListOrder;
			if (property_exists($objSoapObject, 'Tax1Rate'))
				$objToReturn->strTax1Rate = $objSoapObject->Tax1Rate;
			if (property_exists($objSoapObject, 'Tax2Rate'))
				$objToReturn->strTax2Rate = $objSoapObject->Tax2Rate;
			if (property_exists($objSoapObject, 'Tax3Rate'))
				$objToReturn->strTax3Rate = $objSoapObject->Tax3Rate;
			if (property_exists($objSoapObject, 'Tax4Rate'))
				$objToReturn->strTax4Rate = $objSoapObject->Tax4Rate;
			if (property_exists($objSoapObject, 'Tax5Rate'))
				$objToReturn->strTax5Rate = $objSoapObject->Tax5Rate;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, TaxCode::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeTaxCode extends QQNode {
		protected $strTableName = 'xlsws_tax_code';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'TaxCode';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'ListOrder':
					return new QQNode('list_order', 'ListOrder', 'integer', $this);
				case 'Tax1Rate':
					return new QQNode('tax1_rate', 'Tax1Rate', 'string', $this);
				case 'Tax2Rate':
					return new QQNode('tax2_rate', 'Tax2Rate', 'string', $this);
				case 'Tax3Rate':
					return new QQNode('tax3_rate', 'Tax3Rate', 'string', $this);
				case 'Tax4Rate':
					return new QQNode('tax4_rate', 'Tax4Rate', 'string', $this);
				case 'Tax5Rate':
					return new QQNode('tax5_rate', 'Tax5Rate', 'string', $this);
				case 'CartAsFk':
					return new QQReverseReferenceNodeCart($this, 'cartasfk', 'reverse_reference', 'fk_tax_code_id');

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

	class QQReverseReferenceNodeTaxCode extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_tax_code';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'TaxCode';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'ListOrder':
					return new QQNode('list_order', 'ListOrder', 'integer', $this);
				case 'Tax1Rate':
					return new QQNode('tax1_rate', 'Tax1Rate', 'string', $this);
				case 'Tax2Rate':
					return new QQNode('tax2_rate', 'Tax2Rate', 'string', $this);
				case 'Tax3Rate':
					return new QQNode('tax3_rate', 'Tax3Rate', 'string', $this);
				case 'Tax4Rate':
					return new QQNode('tax4_rate', 'Tax4Rate', 'string', $this);
				case 'Tax5Rate':
					return new QQNode('tax5_rate', 'Tax5Rate', 'string', $this);
				case 'CartAsFk':
					return new QQReverseReferenceNodeCart($this, 'cartasfk', 'reverse_reference', 'fk_tax_code_id');

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