<?php
	/**
	 * The abstract GiftRegistryItemsGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the GiftRegistryItems subclass which
	 * extends this GiftRegistryItemsGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the GiftRegistryItems class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $RegistryId the value for intRegistryId (Not Null)
	 * @property integer $ProductId the value for intProductId (Not Null)
	 * @property string $Qty the value for strQty (Not Null)
	 * @property string $RegistryStatus the value for strRegistryStatus 
	 * @property integer $PurchaseStatus the value for intPurchaseStatus 
	 * @property string $PurchasedBy the value for strPurchasedBy 
	 * @property QDateTime $Created the value for dttCreated (Not Null)
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property GiftRegistry $Registry the value for the GiftRegistry object referenced by intRegistryId (Not Null)
	 * @property Product $Product the value for the Product object referenced by intProductId (Not Null)
	 * @property CartItem $_CartItemAsGiftRegistryItem the value for the private _objCartItemAsGiftRegistryItem (Read-Only) if set due to an expansion on the xlsws_cart_item.gift_registry_item reverse relationship
	 * @property CartItem[] $_CartItemAsGiftRegistryItemArray the value for the private _objCartItemAsGiftRegistryItemArray (Read-Only) if set due to an ExpandAsArray on the xlsws_cart_item.gift_registry_item reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class GiftRegistryItemsGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_gift_registry_items.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.registry_id
		 * @var integer intRegistryId
		 */
		protected $intRegistryId;
		const RegistryIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.product_id
		 * @var integer intProductId
		 */
		protected $intProductId;
		const ProductIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.qty
		 * @var string strQty
		 */
		protected $strQty;
		const QtyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.registry_status
		 * @var string strRegistryStatus
		 */
		protected $strRegistryStatus;
		const RegistryStatusMaxLength = 50;
		const RegistryStatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.purchase_status
		 * @var integer intPurchaseStatus
		 */
		protected $intPurchaseStatus;
		const PurchaseStatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.purchased_by
		 * @var string strPurchasedBy
		 */
		protected $strPurchasedBy;
		const PurchasedByMaxLength = 100;
		const PurchasedByDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_gift_registry_items.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single CartItemAsGiftRegistryItem object
		 * (of type CartItem), if this GiftRegistryItems object was restored with
		 * an expansion on the xlsws_cart_item association table.
		 * @var CartItem _objCartItemAsGiftRegistryItem;
		 */
		private $_objCartItemAsGiftRegistryItem;

		/**
		 * Private member variable that stores a reference to an array of CartItemAsGiftRegistryItem objects
		 * (of type CartItem[]), if this GiftRegistryItems object was restored with
		 * an ExpandAsArray on the xlsws_cart_item association table.
		 * @var CartItem[] _objCartItemAsGiftRegistryItemArray;
		 */
		private $_objCartItemAsGiftRegistryItemArray = array();

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
		 * in the database column xlsws_gift_registry_items.registry_id.
		 *
		 * NOTE: Always use the Registry property getter to correctly retrieve this GiftRegistry object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var GiftRegistry objRegistry
		 */
		protected $objRegistry;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_gift_registry_items.product_id.
		 *
		 * NOTE: Always use the Product property getter to correctly retrieve this Product object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Product objProduct
		 */
		protected $objProduct;





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
		 * Load a GiftRegistryItems from PK Info
		 * @param integer $intRowid
		 * @return GiftRegistryItems
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return GiftRegistryItems::QuerySingle(
				QQ::Equal(QQN::GiftRegistryItems()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all GiftRegistryItemses
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryItems[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call GiftRegistryItems::QueryArray to perform the LoadAll query
			try {
				return GiftRegistryItems::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all GiftRegistryItemses
		 * @return int
		 */
		public static function CountAll() {
			// Call GiftRegistryItems::QueryCount to perform the CountAll query
			return GiftRegistryItems::QueryCount(QQ::All());
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
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Create/Build out the QueryBuilder object with GiftRegistryItems-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_gift_registry_items');
			GiftRegistryItems::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_gift_registry_items');

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
		 * Static Qcodo Query method to query for a single GiftRegistryItems object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return GiftRegistryItems the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistryItems::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new GiftRegistryItems object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return GiftRegistryItems::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of GiftRegistryItems objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return GiftRegistryItems[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistryItems::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return GiftRegistryItems::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of GiftRegistryItems objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = GiftRegistryItems::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_gift_registry_items_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with GiftRegistryItems-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				GiftRegistryItems::GetSelectFields($objQueryBuilder);
				GiftRegistryItems::GetFromFields($objQueryBuilder);

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
			return GiftRegistryItems::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this GiftRegistryItems
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_gift_registry_items';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'registry_id', $strAliasPrefix . 'registry_id');
			$objBuilder->AddSelectItem($strTableName, 'product_id', $strAliasPrefix . 'product_id');
			$objBuilder->AddSelectItem($strTableName, 'qty', $strAliasPrefix . 'qty');
			$objBuilder->AddSelectItem($strTableName, 'registry_status', $strAliasPrefix . 'registry_status');
			$objBuilder->AddSelectItem($strTableName, 'purchase_status', $strAliasPrefix . 'purchase_status');
			$objBuilder->AddSelectItem($strTableName, 'purchased_by', $strAliasPrefix . 'purchased_by');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a GiftRegistryItems from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this GiftRegistryItems::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return GiftRegistryItems
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
					$strAliasPrefix = 'xlsws_gift_registry_items__';


				$strAlias = $strAliasPrefix . 'cartitemasgiftregistryitem__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objCartItemAsGiftRegistryItemArray)) {
						$objPreviousChildItem = $objPreviousItem->_objCartItemAsGiftRegistryItemArray[$intPreviousChildItemCount - 1];
						$objChildItem = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitemasgiftregistryitem__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objCartItemAsGiftRegistryItemArray[] = $objChildItem;
					} else
						$objPreviousItem->_objCartItemAsGiftRegistryItemArray[] = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitemasgiftregistryitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_gift_registry_items__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the GiftRegistryItems object
			$objToReturn = new GiftRegistryItems();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'registry_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'registry_id'] : $strAliasPrefix . 'registry_id';
			$objToReturn->intRegistryId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_id'] : $strAliasPrefix . 'product_id';
			$objToReturn->intProductId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'qty', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'qty'] : $strAliasPrefix . 'qty';
			$objToReturn->strQty = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'registry_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'registry_status'] : $strAliasPrefix . 'registry_status';
			$objToReturn->strRegistryStatus = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'purchase_status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'purchase_status'] : $strAliasPrefix . 'purchase_status';
			$objToReturn->intPurchaseStatus = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'purchased_by', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'purchased_by'] : $strAliasPrefix . 'purchased_by';
			$objToReturn->strPurchasedBy = $objDbRow->GetColumn($strAliasName, 'VarChar');
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
				$strAliasPrefix = 'xlsws_gift_registry_items__';

			// Check for Registry Early Binding
			$strAlias = $strAliasPrefix . 'registry_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objRegistry = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'registry_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for Product Early Binding
			$strAlias = $strAliasPrefix . 'product_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objProduct = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			// Check for CartItemAsGiftRegistryItem Virtual Binding
			$strAlias = $strAliasPrefix . 'cartitemasgiftregistryitem__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCartItemAsGiftRegistryItemArray[] = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitemasgiftregistryitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCartItemAsGiftRegistryItem = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitemasgiftregistryitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of GiftRegistryItemses from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return GiftRegistryItems[]
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
					$objItem = GiftRegistryItems::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = GiftRegistryItems::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single GiftRegistryItems object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return GiftRegistryItems
		*/
		public static function LoadByRowid($intRowid) {
			return GiftRegistryItems::QuerySingle(
				QQ::Equal(QQN::GiftRegistryItems()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single GiftRegistryItems object,
		 * by Rowid, RegistryId Index(es)
		 * @param integer $intRowid
		 * @param integer $intRegistryId
		 * @return GiftRegistryItems
		*/
		public static function LoadByRowidRegistryId($intRowid, $intRegistryId) {
			return GiftRegistryItems::QuerySingle(
				QQ::AndCondition(
				QQ::Equal(QQN::GiftRegistryItems()->Rowid, $intRowid),
				QQ::Equal(QQN::GiftRegistryItems()->RegistryId, $intRegistryId)
				)
			);
		}
			
		/**
		 * Load an array of GiftRegistryItems objects,
		 * by RegistryId Index(es)
		 * @param integer $intRegistryId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryItems[]
		*/
		public static function LoadArrayByRegistryId($intRegistryId, $objOptionalClauses = null) {
			// Call GiftRegistryItems::QueryArray to perform the LoadArrayByRegistryId query
			try {
				return GiftRegistryItems::QueryArray(
					QQ::Equal(QQN::GiftRegistryItems()->RegistryId, $intRegistryId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count GiftRegistryItemses
		 * by RegistryId Index(es)
		 * @param integer $intRegistryId
		 * @return int
		*/
		public static function CountByRegistryId($intRegistryId) {
			// Call GiftRegistryItems::QueryCount to perform the CountByRegistryId query
			return GiftRegistryItems::QueryCount(
				QQ::Equal(QQN::GiftRegistryItems()->RegistryId, $intRegistryId)
			);
		}
			
		/**
		 * Load an array of GiftRegistryItems objects,
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryItems[]
		*/
		public static function LoadArrayByProductId($intProductId, $objOptionalClauses = null) {
			// Call GiftRegistryItems::QueryArray to perform the LoadArrayByProductId query
			try {
				return GiftRegistryItems::QueryArray(
					QQ::Equal(QQN::GiftRegistryItems()->ProductId, $intProductId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count GiftRegistryItemses
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @return int
		*/
		public static function CountByProductId($intProductId) {
			// Call GiftRegistryItems::QueryCount to perform the CountByProductId query
			return GiftRegistryItems::QueryCount(
				QQ::Equal(QQN::GiftRegistryItems()->ProductId, $intProductId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this GiftRegistryItems
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_gift_registry_items` (
							`registry_id`,
							`product_id`,
							`qty`,
							`registry_status`,
							`purchase_status`,
							`purchased_by`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intRegistryId) . ',
							' . $objDatabase->SqlVariable($this->intProductId) . ',
							' . $objDatabase->SqlVariable($this->strQty) . ',
							' . $objDatabase->SqlVariable($this->strRegistryStatus) . ',
							' . $objDatabase->SqlVariable($this->intPurchaseStatus) . ',
							' . $objDatabase->SqlVariable($this->strPurchasedBy) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_gift_registry_items', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_gift_registry_items`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('GiftRegistryItems');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_gift_registry_items`
						SET
							`registry_id` = ' . $objDatabase->SqlVariable($this->intRegistryId) . ',
							`product_id` = ' . $objDatabase->SqlVariable($this->intProductId) . ',
							`qty` = ' . $objDatabase->SqlVariable($this->strQty) . ',
							`registry_status` = ' . $objDatabase->SqlVariable($this->strRegistryStatus) . ',
							`purchase_status` = ' . $objDatabase->SqlVariable($this->intPurchaseStatus) . ',
							`purchased_by` = ' . $objDatabase->SqlVariable($this->strPurchasedBy) . ',
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
					`xlsws_gift_registry_items`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this GiftRegistryItems
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this GiftRegistryItems with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_items`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all GiftRegistryItemses
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_items`');
		}

		/**
		 * Truncate xlsws_gift_registry_items table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_gift_registry_items`');
		}

		/**
		 * Reload this GiftRegistryItems from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved GiftRegistryItems object.');

			// Reload the Object
			$objReloaded = GiftRegistryItems::Load($this->intRowid);

			// Update $this's local variables to match
			$this->RegistryId = $objReloaded->RegistryId;
			$this->ProductId = $objReloaded->ProductId;
			$this->strQty = $objReloaded->strQty;
			$this->strRegistryStatus = $objReloaded->strRegistryStatus;
			$this->intPurchaseStatus = $objReloaded->intPurchaseStatus;
			$this->strPurchasedBy = $objReloaded->strPurchasedBy;
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

				case 'ProductId':
					// Gets the value for intProductId (Not Null)
					// @return integer
					return $this->intProductId;

				case 'Qty':
					// Gets the value for strQty (Not Null)
					// @return string
					return $this->strQty;

				case 'RegistryStatus':
					// Gets the value for strRegistryStatus 
					// @return string
					return $this->strRegistryStatus;

				case 'PurchaseStatus':
					// Gets the value for intPurchaseStatus 
					// @return integer
					return $this->intPurchaseStatus;

				case 'PurchasedBy':
					// Gets the value for strPurchasedBy 
					// @return string
					return $this->strPurchasedBy;

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

				case 'Product':
					// Gets the value for the Product object referenced by intProductId (Not Null)
					// @return Product
					try {
						if ((!$this->objProduct) && (!is_null($this->intProductId)))
							$this->objProduct = Product::Load($this->intProductId);
						return $this->objProduct;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_CartItemAsGiftRegistryItem':
					// Gets the value for the private _objCartItemAsGiftRegistryItem (Read-Only)
					// if set due to an expansion on the xlsws_cart_item.gift_registry_item reverse relationship
					// @return CartItem
					return $this->_objCartItemAsGiftRegistryItem;

				case '_CartItemAsGiftRegistryItemArray':
					// Gets the value for the private _objCartItemAsGiftRegistryItemArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_cart_item.gift_registry_item reverse relationship
					// @return CartItem[]
					return (array) $this->_objCartItemAsGiftRegistryItemArray;


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

				case 'ProductId':
					// Sets the value for intProductId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						$this->objProduct = null;
						return ($this->intProductId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Qty':
					// Sets the value for strQty (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strQty = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'RegistryStatus':
					// Sets the value for strRegistryStatus 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strRegistryStatus = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PurchaseStatus':
					// Sets the value for intPurchaseStatus 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intPurchaseStatus = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PurchasedBy':
					// Sets the value for strPurchasedBy 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPurchasedBy = QType::Cast($mixValue, QType::String));
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
							throw new QCallerException('Unable to set an unsaved Registry for this GiftRegistryItems');

						// Update Local Member Variables
						$this->objRegistry = $mixValue;
						$this->intRegistryId = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				case 'Product':
					// Sets the value for the Product object referenced by intProductId (Not Null)
					// @param Product $mixValue
					// @return Product
					if (is_null($mixValue)) {
						$this->intProductId = null;
						$this->objProduct = null;
						return null;
					} else {
						// Make sure $mixValue actually is a Product object
						try {
							$mixValue = QType::Cast($mixValue, 'Product');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED Product object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved Product for this GiftRegistryItems');

						// Update Local Member Variables
						$this->objProduct = $mixValue;
						$this->intProductId = $mixValue->Rowid;

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

			
		
		// Related Objects' Methods for CartItemAsGiftRegistryItem
		//-------------------------------------------------------------------

		/**
		 * Gets all associated CartItemsAsGiftRegistryItem as an array of CartItem objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		*/ 
		public function GetCartItemAsGiftRegistryItemArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return CartItem::LoadArrayByGiftRegistryItem($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated CartItemsAsGiftRegistryItem
		 * @return int
		*/ 
		public function CountCartItemsAsGiftRegistryItem() {
			if ((is_null($this->intRowid)))
				return 0;

			return CartItem::CountByGiftRegistryItem($this->intRowid);
		}

		/**
		 * Associates a CartItemAsGiftRegistryItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function AssociateCartItemAsGiftRegistryItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartItemAsGiftRegistryItem on this unsaved GiftRegistryItems.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartItemAsGiftRegistryItem on this GiftRegistryItems with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`gift_registry_item` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . '
			');
		}

		/**
		 * Unassociates a CartItemAsGiftRegistryItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function UnassociateCartItemAsGiftRegistryItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItemAsGiftRegistryItem on this unsaved GiftRegistryItems.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItemAsGiftRegistryItem on this GiftRegistryItems with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`gift_registry_item` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . ' AND
					`gift_registry_item` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all CartItemsAsGiftRegistryItem
		 * @return void
		*/ 
		public function UnassociateAllCartItemsAsGiftRegistryItem() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItemAsGiftRegistryItem on this unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`gift_registry_item` = null
				WHERE
					`gift_registry_item` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated CartItemAsGiftRegistryItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function DeleteAssociatedCartItemAsGiftRegistryItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItemAsGiftRegistryItem on this unsaved GiftRegistryItems.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItemAsGiftRegistryItem on this GiftRegistryItems with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . ' AND
					`gift_registry_item` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated CartItemsAsGiftRegistryItem
		 * @return void
		*/ 
		public function DeleteAllCartItemsAsGiftRegistryItem() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItemAsGiftRegistryItem on this unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = GiftRegistryItems::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`gift_registry_item` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="GiftRegistryItems"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Registry" type="xsd1:GiftRegistry"/>';
			$strToReturn .= '<element name="Product" type="xsd1:Product"/>';
			$strToReturn .= '<element name="Qty" type="xsd:string"/>';
			$strToReturn .= '<element name="RegistryStatus" type="xsd:string"/>';
			$strToReturn .= '<element name="PurchaseStatus" type="xsd:int"/>';
			$strToReturn .= '<element name="PurchasedBy" type="xsd:string"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('GiftRegistryItems', $strComplexTypeArray)) {
				$strComplexTypeArray['GiftRegistryItems'] = GiftRegistryItems::GetSoapComplexTypeXml();
				GiftRegistry::AlterSoapComplexTypeArray($strComplexTypeArray);
				Product::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, GiftRegistryItems::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new GiftRegistryItems();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Registry')) &&
				($objSoapObject->Registry))
				$objToReturn->Registry = GiftRegistry::GetObjectFromSoapObject($objSoapObject->Registry);
			if ((property_exists($objSoapObject, 'Product')) &&
				($objSoapObject->Product))
				$objToReturn->Product = Product::GetObjectFromSoapObject($objSoapObject->Product);
			if (property_exists($objSoapObject, 'Qty'))
				$objToReturn->strQty = $objSoapObject->Qty;
			if (property_exists($objSoapObject, 'RegistryStatus'))
				$objToReturn->strRegistryStatus = $objSoapObject->RegistryStatus;
			if (property_exists($objSoapObject, 'PurchaseStatus'))
				$objToReturn->intPurchaseStatus = $objSoapObject->PurchaseStatus;
			if (property_exists($objSoapObject, 'PurchasedBy'))
				$objToReturn->strPurchasedBy = $objSoapObject->PurchasedBy;
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
				array_push($objArrayToReturn, GiftRegistryItems::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objRegistry)
				$objObject->objRegistry = GiftRegistry::GetSoapObjectFromObject($objObject->objRegistry, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intRegistryId = null;
			if ($objObject->objProduct)
				$objObject->objProduct = Product::GetSoapObjectFromObject($objObject->objProduct, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intProductId = null;
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeGiftRegistryItems extends QQNode {
		protected $strTableName = 'xlsws_gift_registry_items';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'GiftRegistryItems';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'RegistryId':
					return new QQNode('registry_id', 'RegistryId', 'integer', $this);
				case 'Registry':
					return new QQNodeGiftRegistry('registry_id', 'Registry', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'string', $this);
				case 'RegistryStatus':
					return new QQNode('registry_status', 'RegistryStatus', 'string', $this);
				case 'PurchaseStatus':
					return new QQNode('purchase_status', 'PurchaseStatus', 'integer', $this);
				case 'PurchasedBy':
					return new QQNode('purchased_by', 'PurchasedBy', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'CartItemAsGiftRegistryItem':
					return new QQReverseReferenceNodeCartItem($this, 'cartitemasgiftregistryitem', 'reverse_reference', 'gift_registry_item');

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

	class QQReverseReferenceNodeGiftRegistryItems extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_gift_registry_items';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'GiftRegistryItems';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'RegistryId':
					return new QQNode('registry_id', 'RegistryId', 'integer', $this);
				case 'Registry':
					return new QQNodeGiftRegistry('registry_id', 'Registry', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'string', $this);
				case 'RegistryStatus':
					return new QQNode('registry_status', 'RegistryStatus', 'string', $this);
				case 'PurchaseStatus':
					return new QQNode('purchase_status', 'PurchaseStatus', 'integer', $this);
				case 'PurchasedBy':
					return new QQNode('purchased_by', 'PurchasedBy', 'string', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'CartItemAsGiftRegistryItem':
					return new QQReverseReferenceNodeCartItem($this, 'cartitemasgiftregistryitem', 'reverse_reference', 'gift_registry_item');

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