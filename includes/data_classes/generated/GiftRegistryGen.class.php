<?php
	/**
	 * The abstract GiftRegistryGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the GiftRegistry subclass which
	 * extends this GiftRegistryGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the GiftRegistry class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $RegistryName the value for strRegistryName (Not Null)
	 * @property string $RegistryPassword the value for strRegistryPassword (Not Null)
	 * @property string $RegistryDescription the value for strRegistryDescription 
	 * @property QDateTime $EventDate the value for dttEventDate (Not Null)
	 * @property string $HtmlContent the value for strHtmlContent (Not Null)
	 * @property string $ShipOption the value for strShipOption 
	 * @property integer $CustomerId the value for intCustomerId (Not Null)
	 * @property string $GiftCode the value for strGiftCode (Unique)
	 * @property QDateTime $Created the value for dttCreated (Not Null)
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Customer $Customer the value for the Customer object referenced by intCustomerId (Not Null)
	 * @property Cart $_Cart the value for the private _objCart (Read-Only) if set due to an expansion on the xlsws_cart.gift_registry reverse relationship
	 * @property Cart[] $_CartArray the value for the private _objCartArray (Read-Only) if set due to an ExpandAsArray on the xlsws_cart.gift_registry reverse relationship
	 * @property GiftRegistryItems $_GiftRegistryItemsAsRegistry the value for the private _objGiftRegistryItemsAsRegistry (Read-Only) if set due to an expansion on the xlsws_gift_registry_items.registry_id reverse relationship
	 * @property GiftRegistryItems[] $_GiftRegistryItemsAsRegistryArray the value for the private _objGiftRegistryItemsAsRegistryArray (Read-Only) if set due to an ExpandAsArray on the xlsws_gift_registry_items.registry_id reverse relationship
	 * @property GiftRegistryReceipents $_GiftRegistryReceipentsAsRegistry the value for the private _objGiftRegistryReceipentsAsRegistry (Read-Only) if set due to an expansion on the xlsws_gift_registry_receipents.registry_id reverse relationship
	 * @property GiftRegistryReceipents[] $_GiftRegistryReceipentsAsRegistryArray the value for the private _objGiftRegistryReceipentsAsRegistryArray (Read-Only) if set due to an ExpandAsArray on the xlsws_gift_registry_receipents.registry_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class GiftRegistryGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_gift_registry.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.registry_name
		 * @var string strRegistryName
		 */
		protected $strRegistryName;
		const RegistryNameMaxLength = 100;
		const RegistryNameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.registry_password
		 * @var string strRegistryPassword
		 */
		protected $strRegistryPassword;
		const RegistryPasswordMaxLength = 100;
		const RegistryPasswordDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.registry_description
		 * @var string strRegistryDescription
		 */
		protected $strRegistryDescription;
		const RegistryDescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.event_date
		 * @var QDateTime dttEventDate
		 */
		protected $dttEventDate;
		const EventDateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.html_content
		 * @var string strHtmlContent
		 */
		protected $strHtmlContent;
		const HtmlContentDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.ship_option
		 * @var string strShipOption
		 */
		protected $strShipOption;
		const ShipOptionMaxLength = 100;
		const ShipOptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.customer_id
		 * @var integer intCustomerId
		 */
		protected $intCustomerId;
		const CustomerIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.gift_code
		 * @var string strGiftCode
		 */
		protected $strGiftCode;
		const GiftCodeMaxLength = 100;
		const GiftCodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single Cart object
		 * (of type Cart), if this GiftRegistry object was restored with
		 * an expansion on the xlsws_cart association table.
		 * @var Cart _objCart;
		 */
		private $_objCart;

		/**
		 * Private member variable that stores a reference to an array of Cart objects
		 * (of type Cart[]), if this GiftRegistry object was restored with
		 * an ExpandAsArray on the xlsws_cart association table.
		 * @var Cart[] _objCartArray;
		 */
		private $_objCartArray = array();

		/**
		 * Private member variable that stores a reference to a single GiftRegistryItemsAsRegistry object
		 * (of type GiftRegistryItems), if this GiftRegistry object was restored with
		 * an expansion on the xlsws_gift_registry_items association table.
		 * @var GiftRegistryItems _objGiftRegistryItemsAsRegistry;
		 */
		private $_objGiftRegistryItemsAsRegistry;

		/**
		 * Private member variable that stores a reference to an array of GiftRegistryItemsAsRegistry objects
		 * (of type GiftRegistryItems[]), if this GiftRegistry object was restored with
		 * an ExpandAsArray on the xlsws_gift_registry_items association table.
		 * @var GiftRegistryItems[] _objGiftRegistryItemsAsRegistryArray;
		 */
		private $_objGiftRegistryItemsAsRegistryArray = array();

		/**
		 * Private member variable that stores a reference to a single GiftRegistryReceipentsAsRegistry object
		 * (of type GiftRegistryReceipents), if this GiftRegistry object was restored with
		 * an expansion on the xlsws_gift_registry_receipents association table.
		 * @var GiftRegistryReceipents _objGiftRegistryReceipentsAsRegistry;
		 */
		private $_objGiftRegistryReceipentsAsRegistry;

		/**
		 * Private member variable that stores a reference to an array of GiftRegistryReceipentsAsRegistry objects
		 * (of type GiftRegistryReceipents[]), if this GiftRegistry object was restored with
		 * an ExpandAsArray on the xlsws_gift_registry_receipents association table.
		 * @var GiftRegistryReceipents[] _objGiftRegistryReceipentsAsRegistryArray;
		 */
		private $_objGiftRegistryReceipentsAsRegistryArray = array();

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
		 * in the database column xlsws_gift_registry.customer_id.
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
		 * Load a GiftRegistry from PK Info
		 * @param integer $intRowid
		 * @return GiftRegistry
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return GiftRegistry::QuerySingle(
				QQ::Equal(QQN::GiftRegistry()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all GiftRegistries
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistry[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call GiftRegistry::QueryArray to perform the LoadAll query
			try {
				return GiftRegistry::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all GiftRegistries
		 * @return int
		 */
		public static function CountAll() {
			// Call GiftRegistry::QueryCount to perform the CountAll query
			return GiftRegistry::QueryCount(QQ::All());
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
			$objDatabase = GiftRegistry::GetDatabase();

			// Create/Build out the QueryBuilder object with GiftRegistry-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_gift_registry');
			GiftRegistry::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_gift_registry');

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
		 * Static Qcodo Query method to query for a single GiftRegistry object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return GiftRegistry the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistry::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new GiftRegistry object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return GiftRegistry::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of GiftRegistry objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return GiftRegistry[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistry::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return GiftRegistry::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of GiftRegistry objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistry::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = GiftRegistry::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_gift_registry_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with GiftRegistry-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				GiftRegistry::GetSelectFields($objQueryBuilder);
				GiftRegistry::GetFromFields($objQueryBuilder);

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
			return GiftRegistry::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this GiftRegistry
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_gift_registry';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'registry_name', $strAliasPrefix . 'registry_name');
			$objBuilder->AddSelectItem($strTableName, 'registry_password', $strAliasPrefix . 'registry_password');
			$objBuilder->AddSelectItem($strTableName, 'registry_description', $strAliasPrefix . 'registry_description');
			$objBuilder->AddSelectItem($strTableName, 'event_date', $strAliasPrefix . 'event_date');
			$objBuilder->AddSelectItem($strTableName, 'html_content', $strAliasPrefix . 'html_content');
			$objBuilder->AddSelectItem($strTableName, 'ship_option', $strAliasPrefix . 'ship_option');
			$objBuilder->AddSelectItem($strTableName, 'customer_id', $strAliasPrefix . 'customer_id');
			$objBuilder->AddSelectItem($strTableName, 'gift_code', $strAliasPrefix . 'gift_code');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a GiftRegistry from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this GiftRegistry::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return GiftRegistry
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
					$strAliasPrefix = 'xlsws_gift_registry__';


				$strAlias = $strAliasPrefix . 'cart__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objCartArray)) {
						$objPreviousChildItem = $objPreviousItem->_objCartArray[$intPreviousChildItemCount - 1];
						$objChildItem = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objCartArray[] = $objChildItem;
					} else
						$objPreviousItem->_objCartArray[] = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'giftregistryitemsasregistry__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objGiftRegistryItemsAsRegistryArray)) {
						$objPreviousChildItem = $objPreviousItem->_objGiftRegistryItemsAsRegistryArray[$intPreviousChildItemCount - 1];
						$objChildItem = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitemsasregistry__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objGiftRegistryItemsAsRegistryArray[] = $objChildItem;
					} else
						$objPreviousItem->_objGiftRegistryItemsAsRegistryArray[] = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitemsasregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'giftregistryreceipentsasregistry__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objGiftRegistryReceipentsAsRegistryArray)) {
						$objPreviousChildItem = $objPreviousItem->_objGiftRegistryReceipentsAsRegistryArray[$intPreviousChildItemCount - 1];
						$objChildItem = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipentsasregistry__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objGiftRegistryReceipentsAsRegistryArray[] = $objChildItem;
					} else
						$objPreviousItem->_objGiftRegistryReceipentsAsRegistryArray[] = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipentsasregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_gift_registry__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the GiftRegistry object
			$objToReturn = new GiftRegistry();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'registry_name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'registry_name'] : $strAliasPrefix . 'registry_name';
			$objToReturn->strRegistryName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'registry_password', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'registry_password'] : $strAliasPrefix . 'registry_password';
			$objToReturn->strRegistryPassword = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'registry_description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'registry_description'] : $strAliasPrefix . 'registry_description';
			$objToReturn->strRegistryDescription = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'event_date', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'event_date'] : $strAliasPrefix . 'event_date';
			$objToReturn->dttEventDate = $objDbRow->GetColumn($strAliasName, 'Date');
			$strAliasName = array_key_exists($strAliasPrefix . 'html_content', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'html_content'] : $strAliasPrefix . 'html_content';
			$objToReturn->strHtmlContent = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_option', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_option'] : $strAliasPrefix . 'ship_option';
			$objToReturn->strShipOption = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'customer_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'customer_id'] : $strAliasPrefix . 'customer_id';
			$objToReturn->intCustomerId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'gift_code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'gift_code'] : $strAliasPrefix . 'gift_code';
			$objToReturn->strGiftCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
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
				$strAliasPrefix = 'xlsws_gift_registry__';

			// Check for Customer Early Binding
			$strAlias = $strAliasPrefix . 'customer_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objCustomer = Customer::InstantiateDbRow($objDbRow, $strAliasPrefix . 'customer_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			// Check for Cart Virtual Binding
			$strAlias = $strAliasPrefix . 'cart__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCartArray[] = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCart = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for GiftRegistryItemsAsRegistry Virtual Binding
			$strAlias = $strAliasPrefix . 'giftregistryitemsasregistry__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objGiftRegistryItemsAsRegistryArray[] = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitemsasregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objGiftRegistryItemsAsRegistry = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitemsasregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for GiftRegistryReceipentsAsRegistry Virtual Binding
			$strAlias = $strAliasPrefix . 'giftregistryreceipentsasregistry__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objGiftRegistryReceipentsAsRegistryArray[] = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipentsasregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objGiftRegistryReceipentsAsRegistry = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipentsasregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of GiftRegistries from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return GiftRegistry[]
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
					$objItem = GiftRegistry::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = GiftRegistry::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single GiftRegistry object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return GiftRegistry
		*/
		public static function LoadByRowid($intRowid) {
			return GiftRegistry::QuerySingle(
				QQ::Equal(QQN::GiftRegistry()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single GiftRegistry object,
		 * by GiftCode Index(es)
		 * @param string $strGiftCode
		 * @return GiftRegistry
		*/
		public static function LoadByGiftCode($strGiftCode) {
			return GiftRegistry::QuerySingle(
				QQ::Equal(QQN::GiftRegistry()->GiftCode, $strGiftCode)
			);
		}
			
		/**
		 * Load an array of GiftRegistry objects,
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistry[]
		*/
		public static function LoadArrayByCustomerId($intCustomerId, $objOptionalClauses = null) {
			// Call GiftRegistry::QueryArray to perform the LoadArrayByCustomerId query
			try {
				return GiftRegistry::QueryArray(
					QQ::Equal(QQN::GiftRegistry()->CustomerId, $intCustomerId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count GiftRegistries
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @return int
		*/
		public static function CountByCustomerId($intCustomerId) {
			// Call GiftRegistry::QueryCount to perform the CountByCustomerId query
			return GiftRegistry::QueryCount(
				QQ::Equal(QQN::GiftRegistry()->CustomerId, $intCustomerId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this GiftRegistry
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_gift_registry` (
							`registry_name`,
							`registry_password`,
							`registry_description`,
							`event_date`,
							`html_content`,
							`ship_option`,
							`customer_id`,
							`gift_code`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strRegistryName) . ',
							' . $objDatabase->SqlVariable($this->strRegistryPassword) . ',
							' . $objDatabase->SqlVariable($this->strRegistryDescription) . ',
							' . $objDatabase->SqlVariable($this->dttEventDate) . ',
							' . $objDatabase->SqlVariable($this->strHtmlContent) . ',
							' . $objDatabase->SqlVariable($this->strShipOption) . ',
							' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							' . $objDatabase->SqlVariable($this->strGiftCode) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_gift_registry', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_gift_registry`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('GiftRegistry');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_gift_registry`
						SET
							`registry_name` = ' . $objDatabase->SqlVariable($this->strRegistryName) . ',
							`registry_password` = ' . $objDatabase->SqlVariable($this->strRegistryPassword) . ',
							`registry_description` = ' . $objDatabase->SqlVariable($this->strRegistryDescription) . ',
							`event_date` = ' . $objDatabase->SqlVariable($this->dttEventDate) . ',
							`html_content` = ' . $objDatabase->SqlVariable($this->strHtmlContent) . ',
							`ship_option` = ' . $objDatabase->SqlVariable($this->strShipOption) . ',
							`customer_id` = ' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							`gift_code` = ' . $objDatabase->SqlVariable($this->strGiftCode) . ',
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
					`xlsws_gift_registry`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this GiftRegistry
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this GiftRegistry with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all GiftRegistries
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry`');
		}

		/**
		 * Truncate xlsws_gift_registry table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_gift_registry`');
		}

		/**
		 * Reload this GiftRegistry from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved GiftRegistry object.');

			// Reload the Object
			$objReloaded = GiftRegistry::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strRegistryName = $objReloaded->strRegistryName;
			$this->strRegistryPassword = $objReloaded->strRegistryPassword;
			$this->strRegistryDescription = $objReloaded->strRegistryDescription;
			$this->dttEventDate = $objReloaded->dttEventDate;
			$this->strHtmlContent = $objReloaded->strHtmlContent;
			$this->strShipOption = $objReloaded->strShipOption;
			$this->CustomerId = $objReloaded->CustomerId;
			$this->strGiftCode = $objReloaded->strGiftCode;
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

				case 'RegistryName':
					// Gets the value for strRegistryName (Not Null)
					// @return string
					return $this->strRegistryName;

				case 'RegistryPassword':
					// Gets the value for strRegistryPassword (Not Null)
					// @return string
					return $this->strRegistryPassword;

				case 'RegistryDescription':
					// Gets the value for strRegistryDescription 
					// @return string
					return $this->strRegistryDescription;

				case 'EventDate':
					// Gets the value for dttEventDate (Not Null)
					// @return QDateTime
					return $this->dttEventDate;

				case 'HtmlContent':
					// Gets the value for strHtmlContent (Not Null)
					// @return string
					return $this->strHtmlContent;

				case 'ShipOption':
					// Gets the value for strShipOption 
					// @return string
					return $this->strShipOption;

				case 'CustomerId':
					// Gets the value for intCustomerId (Not Null)
					// @return integer
					return $this->intCustomerId;

				case 'GiftCode':
					// Gets the value for strGiftCode (Unique)
					// @return string
					return $this->strGiftCode;

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
				case 'Customer':
					// Gets the value for the Customer object referenced by intCustomerId (Not Null)
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

				case '_Cart':
					// Gets the value for the private _objCart (Read-Only)
					// if set due to an expansion on the xlsws_cart.gift_registry reverse relationship
					// @return Cart
					return $this->_objCart;

				case '_CartArray':
					// Gets the value for the private _objCartArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_cart.gift_registry reverse relationship
					// @return Cart[]
					return (array) $this->_objCartArray;

				case '_GiftRegistryItemsAsRegistry':
					// Gets the value for the private _objGiftRegistryItemsAsRegistry (Read-Only)
					// if set due to an expansion on the xlsws_gift_registry_items.registry_id reverse relationship
					// @return GiftRegistryItems
					return $this->_objGiftRegistryItemsAsRegistry;

				case '_GiftRegistryItemsAsRegistryArray':
					// Gets the value for the private _objGiftRegistryItemsAsRegistryArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_gift_registry_items.registry_id reverse relationship
					// @return GiftRegistryItems[]
					return (array) $this->_objGiftRegistryItemsAsRegistryArray;

				case '_GiftRegistryReceipentsAsRegistry':
					// Gets the value for the private _objGiftRegistryReceipentsAsRegistry (Read-Only)
					// if set due to an expansion on the xlsws_gift_registry_receipents.registry_id reverse relationship
					// @return GiftRegistryReceipents
					return $this->_objGiftRegistryReceipentsAsRegistry;

				case '_GiftRegistryReceipentsAsRegistryArray':
					// Gets the value for the private _objGiftRegistryReceipentsAsRegistryArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_gift_registry_receipents.registry_id reverse relationship
					// @return GiftRegistryReceipents[]
					return (array) $this->_objGiftRegistryReceipentsAsRegistryArray;


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
				case 'RegistryName':
					// Sets the value for strRegistryName (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strRegistryName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RegistryPassword':
					// Sets the value for strRegistryPassword (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strRegistryPassword = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RegistryDescription':
					// Sets the value for strRegistryDescription 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strRegistryDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'EventDate':
					// Sets the value for dttEventDate (Not Null)
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttEventDate = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HtmlContent':
					// Sets the value for strHtmlContent (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strHtmlContent = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipOption':
					// Sets the value for strShipOption 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipOption = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CustomerId':
					// Sets the value for intCustomerId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						$this->objCustomer = null;
						return ($this->intCustomerId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'GiftCode':
					// Sets the value for strGiftCode (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strGiftCode = QType::Cast($mixValue, QType::String));
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
				case 'Customer':
					// Sets the value for the Customer object referenced by intCustomerId (Not Null)
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
							throw new QCallerException('Unable to set an unsaved Customer for this GiftRegistry');

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

			
		
		// Related Objects' Methods for Cart
		//-------------------------------------------------------------------

		/**
		 * Gets all associated Carts as an array of Cart objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/ 
		public function GetCartArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Cart::LoadArrayByGiftRegistry($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated Carts
		 * @return int
		*/ 
		public function CountCarts() {
			if ((is_null($this->intRowid)))
				return 0;

			return Cart::CountByGiftRegistry($this->intRowid);
		}

		/**
		 * Associates a Cart
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function AssociateCart(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCart on this unsaved GiftRegistry.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCart on this GiftRegistry with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`gift_registry` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . '
			');
		}

		/**
		 * Unassociates a Cart
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function UnassociateCart(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved GiftRegistry.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this GiftRegistry with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`gift_registry` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . ' AND
					`gift_registry` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all Carts
		 * @return void
		*/ 
		public function UnassociateAllCarts() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`gift_registry` = null
				WHERE
					`gift_registry` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated Cart
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function DeleteAssociatedCart(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved GiftRegistry.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this GiftRegistry with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . ' AND
					`gift_registry` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated Carts
		 * @return void
		*/ 
		public function DeleteAllCarts() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`gift_registry` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for GiftRegistryItemsAsRegistry
		//-------------------------------------------------------------------

		/**
		 * Gets all associated GiftRegistryItemsesAsRegistry as an array of GiftRegistryItems objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryItems[]
		*/ 
		public function GetGiftRegistryItemsAsRegistryArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return GiftRegistryItems::LoadArrayByRegistryId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated GiftRegistryItemsesAsRegistry
		 * @return int
		*/ 
		public function CountGiftRegistryItemsesAsRegistry() {
			if ((is_null($this->intRowid)))
				return 0;

			return GiftRegistryItems::CountByRegistryId($this->intRowid);
		}

		/**
		 * Associates a GiftRegistryItemsAsRegistry
		 * @param GiftRegistryItems $objGiftRegistryItems
		 * @return void
		*/ 
		public function AssociateGiftRegistryItemsAsRegistry(GiftRegistryItems $objGiftRegistryItems) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryItemsAsRegistry on this unsaved GiftRegistry.');
			if ((is_null($objGiftRegistryItems->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryItemsAsRegistry on this GiftRegistry with an unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_items`
				SET
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryItems->Rowid) . '
			');
		}

		/**
		 * Unassociates a GiftRegistryItemsAsRegistry
		 * @param GiftRegistryItems $objGiftRegistryItems
		 * @return void
		*/ 
		public function UnassociateGiftRegistryItemsAsRegistry(GiftRegistryItems $objGiftRegistryItems) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItemsAsRegistry on this unsaved GiftRegistry.');
			if ((is_null($objGiftRegistryItems->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItemsAsRegistry on this GiftRegistry with an unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_items`
				SET
					`registry_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryItems->Rowid) . ' AND
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all GiftRegistryItemsesAsRegistry
		 * @return void
		*/ 
		public function UnassociateAllGiftRegistryItemsesAsRegistry() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItemsAsRegistry on this unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_items`
				SET
					`registry_id` = null
				WHERE
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated GiftRegistryItemsAsRegistry
		 * @param GiftRegistryItems $objGiftRegistryItems
		 * @return void
		*/ 
		public function DeleteAssociatedGiftRegistryItemsAsRegistry(GiftRegistryItems $objGiftRegistryItems) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItemsAsRegistry on this unsaved GiftRegistry.');
			if ((is_null($objGiftRegistryItems->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItemsAsRegistry on this GiftRegistry with an unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_items`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryItems->Rowid) . ' AND
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated GiftRegistryItemsesAsRegistry
		 * @return void
		*/ 
		public function DeleteAllGiftRegistryItemsesAsRegistry() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItemsAsRegistry on this unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_items`
				WHERE
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for GiftRegistryReceipentsAsRegistry
		//-------------------------------------------------------------------

		/**
		 * Gets all associated GiftRegistryReceipentsesAsRegistry as an array of GiftRegistryReceipents objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryReceipents[]
		*/ 
		public function GetGiftRegistryReceipentsAsRegistryArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return GiftRegistryReceipents::LoadArrayByRegistryId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated GiftRegistryReceipentsesAsRegistry
		 * @return int
		*/ 
		public function CountGiftRegistryReceipentsesAsRegistry() {
			if ((is_null($this->intRowid)))
				return 0;

			return GiftRegistryReceipents::CountByRegistryId($this->intRowid);
		}

		/**
		 * Associates a GiftRegistryReceipentsAsRegistry
		 * @param GiftRegistryReceipents $objGiftRegistryReceipents
		 * @return void
		*/ 
		public function AssociateGiftRegistryReceipentsAsRegistry(GiftRegistryReceipents $objGiftRegistryReceipents) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryReceipentsAsRegistry on this unsaved GiftRegistry.');
			if ((is_null($objGiftRegistryReceipents->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryReceipentsAsRegistry on this GiftRegistry with an unsaved GiftRegistryReceipents.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_receipents`
				SET
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryReceipents->Rowid) . '
			');
		}

		/**
		 * Unassociates a GiftRegistryReceipentsAsRegistry
		 * @param GiftRegistryReceipents $objGiftRegistryReceipents
		 * @return void
		*/ 
		public function UnassociateGiftRegistryReceipentsAsRegistry(GiftRegistryReceipents $objGiftRegistryReceipents) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipentsAsRegistry on this unsaved GiftRegistry.');
			if ((is_null($objGiftRegistryReceipents->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipentsAsRegistry on this GiftRegistry with an unsaved GiftRegistryReceipents.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_receipents`
				SET
					`registry_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryReceipents->Rowid) . ' AND
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all GiftRegistryReceipentsesAsRegistry
		 * @return void
		*/ 
		public function UnassociateAllGiftRegistryReceipentsesAsRegistry() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipentsAsRegistry on this unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_receipents`
				SET
					`registry_id` = null
				WHERE
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated GiftRegistryReceipentsAsRegistry
		 * @param GiftRegistryReceipents $objGiftRegistryReceipents
		 * @return void
		*/ 
		public function DeleteAssociatedGiftRegistryReceipentsAsRegistry(GiftRegistryReceipents $objGiftRegistryReceipents) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipentsAsRegistry on this unsaved GiftRegistry.');
			if ((is_null($objGiftRegistryReceipents->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipentsAsRegistry on this GiftRegistry with an unsaved GiftRegistryReceipents.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_receipents`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryReceipents->Rowid) . ' AND
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated GiftRegistryReceipentsesAsRegistry
		 * @return void
		*/ 
		public function DeleteAllGiftRegistryReceipentsesAsRegistry() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipentsAsRegistry on this unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistry::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_receipents`
				WHERE
					`registry_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="GiftRegistry"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="RegistryName" type="xsd:string"/>';
			$strToReturn .= '<element name="RegistryPassword" type="xsd:string"/>';
			$strToReturn .= '<element name="RegistryDescription" type="xsd:string"/>';
			$strToReturn .= '<element name="EventDate" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="HtmlContent" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipOption" type="xsd:string"/>';
			$strToReturn .= '<element name="Customer" type="xsd1:Customer"/>';
			$strToReturn .= '<element name="GiftCode" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('GiftRegistry', $strComplexTypeArray)) {
				$strComplexTypeArray['GiftRegistry'] = GiftRegistry::GetSoapComplexTypeXml();
				Customer::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, GiftRegistry::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new GiftRegistry();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'RegistryName'))
				$objToReturn->strRegistryName = $objSoapObject->RegistryName;
			if (property_exists($objSoapObject, 'RegistryPassword'))
				$objToReturn->strRegistryPassword = $objSoapObject->RegistryPassword;
			if (property_exists($objSoapObject, 'RegistryDescription'))
				$objToReturn->strRegistryDescription = $objSoapObject->RegistryDescription;
			if (property_exists($objSoapObject, 'EventDate'))
				$objToReturn->dttEventDate = new QDateTime($objSoapObject->EventDate);
			if (property_exists($objSoapObject, 'HtmlContent'))
				$objToReturn->strHtmlContent = $objSoapObject->HtmlContent;
			if (property_exists($objSoapObject, 'ShipOption'))
				$objToReturn->strShipOption = $objSoapObject->ShipOption;
			if ((property_exists($objSoapObject, 'Customer')) &&
				($objSoapObject->Customer))
				$objToReturn->Customer = Customer::GetObjectFromSoapObject($objSoapObject->Customer);
			if (property_exists($objSoapObject, 'GiftCode'))
				$objToReturn->strGiftCode = $objSoapObject->GiftCode;
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
				array_push($objArrayToReturn, GiftRegistry::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->dttEventDate)
				$objObject->dttEventDate = $objObject->dttEventDate->__toString(QDateTime::FormatSoap);
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

	class QQNodeGiftRegistry extends QQNode {
		protected $strTableName = 'xlsws_gift_registry';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'GiftRegistry';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'RegistryName':
					return new QQNode('registry_name', 'RegistryName', 'string', $this);
				case 'RegistryPassword':
					return new QQNode('registry_password', 'RegistryPassword', 'string', $this);
				case 'RegistryDescription':
					return new QQNode('registry_description', 'RegistryDescription', 'string', $this);
				case 'EventDate':
					return new QQNode('event_date', 'EventDate', 'QDateTime', $this);
				case 'HtmlContent':
					return new QQNode('html_content', 'HtmlContent', 'string', $this);
				case 'ShipOption':
					return new QQNode('ship_option', 'ShipOption', 'string', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'GiftCode':
					return new QQNode('gift_code', 'GiftCode', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Cart':
					return new QQReverseReferenceNodeCart($this, 'cart', 'reverse_reference', 'gift_registry');
				case 'GiftRegistryItemsAsRegistry':
					return new QQReverseReferenceNodeGiftRegistryItems($this, 'giftregistryitemsasregistry', 'reverse_reference', 'registry_id');
				case 'GiftRegistryReceipentsAsRegistry':
					return new QQReverseReferenceNodeGiftRegistryReceipents($this, 'giftregistryreceipentsasregistry', 'reverse_reference', 'registry_id');

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

	class QQReverseReferenceNodeGiftRegistry extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_gift_registry';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'GiftRegistry';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'RegistryName':
					return new QQNode('registry_name', 'RegistryName', 'string', $this);
				case 'RegistryPassword':
					return new QQNode('registry_password', 'RegistryPassword', 'string', $this);
				case 'RegistryDescription':
					return new QQNode('registry_description', 'RegistryDescription', 'string', $this);
				case 'EventDate':
					return new QQNode('event_date', 'EventDate', 'QDateTime', $this);
				case 'HtmlContent':
					return new QQNode('html_content', 'HtmlContent', 'string', $this);
				case 'ShipOption':
					return new QQNode('ship_option', 'ShipOption', 'string', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'GiftCode':
					return new QQNode('gift_code', 'GiftCode', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Cart':
					return new QQReverseReferenceNodeCart($this, 'cart', 'reverse_reference', 'gift_registry');
				case 'GiftRegistryItemsAsRegistry':
					return new QQReverseReferenceNodeGiftRegistryItems($this, 'giftregistryitemsasregistry', 'reverse_reference', 'registry_id');
				case 'GiftRegistryReceipentsAsRegistry':
					return new QQReverseReferenceNodeGiftRegistryReceipents($this, 'giftregistryreceipentsasregistry', 'reverse_reference', 'registry_id');

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