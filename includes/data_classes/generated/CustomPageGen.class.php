<?php
	/**
	 * The abstract CustomPageGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the CustomPage subclass which
	 * extends this CustomPageGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the CustomPage class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Key the value for strKey (Unique)
	 * @property string $Title the value for strTitle (Not Null)
	 * @property string $Page the value for strPage 
	 * @property string $MetaKeywords the value for strMetaKeywords 
	 * @property string $MetaDescription the value for strMetaDescription 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property QDateTime $Created the value for dttCreated 
	 * @property string $ProductTag the value for strProductTag 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CustomPageGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_custom_page.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.key
		 * @var string strKey
		 */
		protected $strKey;
		const KeyMaxLength = 32;
		const KeyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.title
		 * @var string strTitle
		 */
		protected $strTitle;
		const TitleMaxLength = 64;
		const TitleDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.page
		 * @var string strPage
		 */
		protected $strPage;
		const PageDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.meta_keywords
		 * @var string strMetaKeywords
		 */
		protected $strMetaKeywords;
		const MetaKeywordsMaxLength = 255;
		const MetaKeywordsDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.meta_description
		 * @var string strMetaDescription
		 */
		protected $strMetaDescription;
		const MetaDescriptionMaxLength = 255;
		const MetaDescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_custom_page.product_tag
		 * @var string strProductTag
		 */
		protected $strProductTag;
		const ProductTagMaxLength = 255;
		const ProductTagDefault = null;


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
		 * Load a CustomPage from PK Info
		 * @param integer $intRowid
		 * @return CustomPage
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return CustomPage::QuerySingle(
				QQ::Equal(QQN::CustomPage()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all CustomPages
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CustomPage[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call CustomPage::QueryArray to perform the LoadAll query
			try {
				return CustomPage::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all CustomPages
		 * @return int
		 */
		public static function CountAll() {
			// Call CustomPage::QueryCount to perform the CountAll query
			return CustomPage::QueryCount(QQ::All());
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
			$objDatabase = CustomPage::GetDatabase();

			// Create/Build out the QueryBuilder object with CustomPage-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_custom_page');
			CustomPage::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_custom_page');

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
		 * Static Qcodo Query method to query for a single CustomPage object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return CustomPage the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CustomPage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new CustomPage object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return CustomPage::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of CustomPage objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return CustomPage[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CustomPage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return CustomPage::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of CustomPage objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CustomPage::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = CustomPage::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_custom_page_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with CustomPage-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				CustomPage::GetSelectFields($objQueryBuilder);
				CustomPage::GetFromFields($objQueryBuilder);

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
			return CustomPage::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this CustomPage
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_custom_page';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'key', $strAliasPrefix . 'key');
			$objBuilder->AddSelectItem($strTableName, 'title', $strAliasPrefix . 'title');
			$objBuilder->AddSelectItem($strTableName, 'page', $strAliasPrefix . 'page');
			$objBuilder->AddSelectItem($strTableName, 'meta_keywords', $strAliasPrefix . 'meta_keywords');
			$objBuilder->AddSelectItem($strTableName, 'meta_description', $strAliasPrefix . 'meta_description');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'product_tag', $strAliasPrefix . 'product_tag');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a CustomPage from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this CustomPage::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return CustomPage
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the CustomPage object
			$objToReturn = new CustomPage();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'key', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'key'] : $strAliasPrefix . 'key';
			$objToReturn->strKey = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'title', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'title'] : $strAliasPrefix . 'title';
			$objToReturn->strTitle = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'page', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'page'] : $strAliasPrefix . 'page';
			$objToReturn->strPage = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'meta_keywords', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'meta_keywords'] : $strAliasPrefix . 'meta_keywords';
			$objToReturn->strMetaKeywords = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'meta_description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'meta_description'] : $strAliasPrefix . 'meta_description';
			$objToReturn->strMetaDescription = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'modified', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'modified'] : $strAliasPrefix . 'modified';
			$objToReturn->strModified = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'created', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'created'] : $strAliasPrefix . 'created';
			$objToReturn->dttCreated = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_tag', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_tag'] : $strAliasPrefix . 'product_tag';
			$objToReturn->strProductTag = $objDbRow->GetColumn($strAliasName, 'VarChar');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_custom_page__';




			return $objToReturn;
		}

		/**
		 * Instantiate an array of CustomPages from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return CustomPage[]
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
					$objItem = CustomPage::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = CustomPage::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single CustomPage object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return CustomPage
		*/
		public static function LoadByRowid($intRowid) {
			return CustomPage::QuerySingle(
				QQ::Equal(QQN::CustomPage()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single CustomPage object,
		 * by Key Index(es)
		 * @param string $strKey
		 * @return CustomPage
		*/
		public static function LoadByKey($strKey) {
			return CustomPage::QuerySingle(
				QQ::Equal(QQN::CustomPage()->Key, $strKey)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this CustomPage
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = CustomPage::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_custom_page` (
							`key`,
							`title`,
							`page`,
							`meta_keywords`,
							`meta_description`,
							`created`,
							`product_tag`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strKey) . ',
							' . $objDatabase->SqlVariable($this->strTitle) . ',
							' . $objDatabase->SqlVariable($this->strPage) . ',
							' . $objDatabase->SqlVariable($this->strMetaKeywords) . ',
							' . $objDatabase->SqlVariable($this->strMetaDescription) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . ',
							' . $objDatabase->SqlVariable($this->strProductTag) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_custom_page', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_custom_page`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('CustomPage');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_custom_page`
						SET
							`key` = ' . $objDatabase->SqlVariable($this->strKey) . ',
							`title` = ' . $objDatabase->SqlVariable($this->strTitle) . ',
							`page` = ' . $objDatabase->SqlVariable($this->strPage) . ',
							`meta_keywords` = ' . $objDatabase->SqlVariable($this->strMetaKeywords) . ',
							`meta_description` = ' . $objDatabase->SqlVariable($this->strMetaDescription) . ',
							`created` = ' . $objDatabase->SqlVariable($this->dttCreated) . ',
							`product_tag` = ' . $objDatabase->SqlVariable($this->strProductTag) . '
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
					`xlsws_custom_page`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this CustomPage
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this CustomPage with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = CustomPage::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_custom_page`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all CustomPages
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = CustomPage::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_custom_page`');
		}

		/**
		 * Truncate xlsws_custom_page table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = CustomPage::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_custom_page`');
		}

		/**
		 * Reload this CustomPage from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved CustomPage object.');

			// Reload the Object
			$objReloaded = CustomPage::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strKey = $objReloaded->strKey;
			$this->strTitle = $objReloaded->strTitle;
			$this->strPage = $objReloaded->strPage;
			$this->strMetaKeywords = $objReloaded->strMetaKeywords;
			$this->strMetaDescription = $objReloaded->strMetaDescription;
			$this->strModified = $objReloaded->strModified;
			$this->dttCreated = $objReloaded->dttCreated;
			$this->strProductTag = $objReloaded->strProductTag;
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

				case 'Key':
					// Gets the value for strKey (Unique)
					// @return string
					return $this->strKey;

				case 'Title':
					// Gets the value for strTitle (Not Null)
					// @return string
					return $this->strTitle;

				case 'Page':
					// Gets the value for strPage 
					// @return string
					return $this->strPage;

				case 'MetaKeywords':
					// Gets the value for strMetaKeywords 
					// @return string
					return $this->strMetaKeywords;

				case 'MetaDescription':
					// Gets the value for strMetaDescription 
					// @return string
					return $this->strMetaDescription;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;

				case 'Created':
					// Gets the value for dttCreated 
					// @return QDateTime
					return $this->dttCreated;

				case 'ProductTag':
					// Gets the value for strProductTag 
					// @return string
					return $this->strProductTag;


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

				case 'MetaKeywords':
					// Sets the value for strMetaKeywords 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMetaKeywords = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MetaDescription':
					// Sets the value for strMetaDescription 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMetaDescription = QType::Cast($mixValue, QType::String));
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

				case 'ProductTag':
					// Sets the value for strProductTag 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strProductTag = QType::Cast($mixValue, QType::String));
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
			$strToReturn = '<complexType name="CustomPage"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Key" type="xsd:string"/>';
			$strToReturn .= '<element name="Title" type="xsd:string"/>';
			$strToReturn .= '<element name="Page" type="xsd:string"/>';
			$strToReturn .= '<element name="MetaKeywords" type="xsd:string"/>';
			$strToReturn .= '<element name="MetaDescription" type="xsd:string"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="ProductTag" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('CustomPage', $strComplexTypeArray)) {
				$strComplexTypeArray['CustomPage'] = CustomPage::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, CustomPage::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new CustomPage();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Key'))
				$objToReturn->strKey = $objSoapObject->Key;
			if (property_exists($objSoapObject, 'Title'))
				$objToReturn->strTitle = $objSoapObject->Title;
			if (property_exists($objSoapObject, 'Page'))
				$objToReturn->strPage = $objSoapObject->Page;
			if (property_exists($objSoapObject, 'MetaKeywords'))
				$objToReturn->strMetaKeywords = $objSoapObject->MetaKeywords;
			if (property_exists($objSoapObject, 'MetaDescription'))
				$objToReturn->strMetaDescription = $objSoapObject->MetaDescription;
			if (property_exists($objSoapObject, 'Modified'))
				$objToReturn->strModified = $objSoapObject->Modified;
			if (property_exists($objSoapObject, 'Created'))
				$objToReturn->dttCreated = new QDateTime($objSoapObject->Created);
			if (property_exists($objSoapObject, 'ProductTag'))
				$objToReturn->strProductTag = $objSoapObject->ProductTag;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, CustomPage::GetSoapObjectFromObject($objObject, true));

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

	class QQNodeCustomPage extends QQNode {
		protected $strTableName = 'xlsws_custom_page';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'CustomPage';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Key':
					return new QQNode('key', 'Key', 'string', $this);
				case 'Title':
					return new QQNode('title', 'Title', 'string', $this);
				case 'Page':
					return new QQNode('page', 'Page', 'string', $this);
				case 'MetaKeywords':
					return new QQNode('meta_keywords', 'MetaKeywords', 'string', $this);
				case 'MetaDescription':
					return new QQNode('meta_description', 'MetaDescription', 'string', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'ProductTag':
					return new QQNode('product_tag', 'ProductTag', 'string', $this);

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

	class QQReverseReferenceNodeCustomPage extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_custom_page';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'CustomPage';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Key':
					return new QQNode('key', 'Key', 'string', $this);
				case 'Title':
					return new QQNode('title', 'Title', 'string', $this);
				case 'Page':
					return new QQNode('page', 'Page', 'string', $this);
				case 'MetaKeywords':
					return new QQNode('meta_keywords', 'MetaKeywords', 'string', $this);
				case 'MetaDescription':
					return new QQNode('meta_description', 'MetaDescription', 'string', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'ProductTag':
					return new QQNode('product_tag', 'ProductTag', 'string', $this);

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