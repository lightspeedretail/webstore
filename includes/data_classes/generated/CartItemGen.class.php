<?php
	/**
	 * The abstract CartItemGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the CartItem subclass which
	 * extends this CartItemGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the CartItem class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property integer $CartId the value for intCartId (Not Null)
	 * @property integer $CartType the value for intCartType 
	 * @property integer $ProductId the value for intProductId (Not Null)
	 * @property string $Code the value for strCode (Not Null)
	 * @property string $Description the value for strDescription (Not Null)
	 * @property string $Discount the value for strDiscount 
	 * @property double $Qty the value for fltQty (Not Null)
	 * @property string $Sell the value for strSell (Not Null)
	 * @property string $SellBase the value for strSellBase (Not Null)
	 * @property string $SellDiscount the value for strSellDiscount (Not Null)
	 * @property string $SellTotal the value for strSellTotal (Not Null)
	 * @property string $SerialNumbers the value for strSerialNumbers 
	 * @property integer $GiftRegistryItem the value for intGiftRegistryItem 
	 * @property QDateTime $DatetimeAdded the value for dttDatetimeAdded (Not Null)
	 * @property string $DatetimeMod the value for strDatetimeMod (Read-Only Timestamp)
	 * @property Cart $Cart the value for the Cart object referenced by intCartId (Not Null)
	 * @property Product $Product the value for the Product object referenced by intProductId (Not Null)
	 * @property GiftRegistryItems $GiftRegistryItemObject the value for the GiftRegistryItems object referenced by intGiftRegistryItem 
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CartItemGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_cart_item.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.cart_id
		 * @var integer intCartId
		 */
		protected $intCartId;
		const CartIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.cart_type
		 * @var integer intCartType
		 */
		protected $intCartType;
		const CartTypeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.product_id
		 * @var integer intProductId
		 */
		protected $intProductId;
		const ProductIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.code
		 * @var string strCode
		 */
		protected $strCode;
		const CodeMaxLength = 255;
		const CodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.description
		 * @var string strDescription
		 */
		protected $strDescription;
		const DescriptionMaxLength = 255;
		const DescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.discount
		 * @var string strDiscount
		 */
		protected $strDiscount;
		const DiscountMaxLength = 16;
		const DiscountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.qty
		 * @var double fltQty
		 */
		protected $fltQty;
		const QtyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.sell
		 * @var string strSell
		 */
		protected $strSell;
		const SellDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.sell_base
		 * @var string strSellBase
		 */
		protected $strSellBase;
		const SellBaseDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.sell_discount
		 * @var string strSellDiscount
		 */
		protected $strSellDiscount;
		const SellDiscountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.sell_total
		 * @var string strSellTotal
		 */
		protected $strSellTotal;
		const SellTotalDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.serial_numbers
		 * @var string strSerialNumbers
		 */
		protected $strSerialNumbers;
		const SerialNumbersMaxLength = 255;
		const SerialNumbersDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.gift_registry_item
		 * @var integer intGiftRegistryItem
		 */
		protected $intGiftRegistryItem;
		const GiftRegistryItemDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.datetime_added
		 * @var QDateTime dttDatetimeAdded
		 */
		protected $dttDatetimeAdded;
		const DatetimeAddedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart_item.datetime_mod
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
		 * in the database column xlsws_cart_item.cart_id.
		 *
		 * NOTE: Always use the Cart property getter to correctly retrieve this Cart object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Cart objCart
		 */
		protected $objCart;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_cart_item.product_id.
		 *
		 * NOTE: Always use the Product property getter to correctly retrieve this Product object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Product objProduct
		 */
		protected $objProduct;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_cart_item.gift_registry_item.
		 *
		 * NOTE: Always use the GiftRegistryItemObject property getter to correctly retrieve this GiftRegistryItems object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var GiftRegistryItems objGiftRegistryItemObject
		 */
		protected $objGiftRegistryItemObject;





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
		 * Load a CartItem from PK Info
		 * @param integer $intRowid
		 * @return CartItem
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return CartItem::QuerySingle(
				QQ::Equal(QQN::CartItem()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all CartItems
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call CartItem::QueryArray to perform the LoadAll query
			try {
				return CartItem::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all CartItems
		 * @return int
		 */
		public static function CountAll() {
			// Call CartItem::QueryCount to perform the CountAll query
			return CartItem::QueryCount(QQ::All());
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
			$objDatabase = CartItem::GetDatabase();

			// Create/Build out the QueryBuilder object with CartItem-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_cart_item');
			CartItem::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_cart_item');

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
		 * Static Qcodo Query method to query for a single CartItem object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return CartItem the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CartItem::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new CartItem object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return CartItem::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of CartItem objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return CartItem[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CartItem::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return CartItem::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of CartItem objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = CartItem::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = CartItem::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_cart_item_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with CartItem-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				CartItem::GetSelectFields($objQueryBuilder);
				CartItem::GetFromFields($objQueryBuilder);

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
			return CartItem::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this CartItem
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_cart_item';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'cart_id', $strAliasPrefix . 'cart_id');
			$objBuilder->AddSelectItem($strTableName, 'cart_type', $strAliasPrefix . 'cart_type');
			$objBuilder->AddSelectItem($strTableName, 'product_id', $strAliasPrefix . 'product_id');
			$objBuilder->AddSelectItem($strTableName, 'code', $strAliasPrefix . 'code');
			$objBuilder->AddSelectItem($strTableName, 'description', $strAliasPrefix . 'description');
			$objBuilder->AddSelectItem($strTableName, 'discount', $strAliasPrefix . 'discount');
			$objBuilder->AddSelectItem($strTableName, 'qty', $strAliasPrefix . 'qty');
			$objBuilder->AddSelectItem($strTableName, 'sell', $strAliasPrefix . 'sell');
			$objBuilder->AddSelectItem($strTableName, 'sell_base', $strAliasPrefix . 'sell_base');
			$objBuilder->AddSelectItem($strTableName, 'sell_discount', $strAliasPrefix . 'sell_discount');
			$objBuilder->AddSelectItem($strTableName, 'sell_total', $strAliasPrefix . 'sell_total');
			$objBuilder->AddSelectItem($strTableName, 'serial_numbers', $strAliasPrefix . 'serial_numbers');
			$objBuilder->AddSelectItem($strTableName, 'gift_registry_item', $strAliasPrefix . 'gift_registry_item');
			$objBuilder->AddSelectItem($strTableName, 'datetime_added', $strAliasPrefix . 'datetime_added');
			$objBuilder->AddSelectItem($strTableName, 'datetime_mod', $strAliasPrefix . 'datetime_mod');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a CartItem from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this CartItem::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return CartItem
		*/
		public static function InstantiateDbRow($objDbRow, $strAliasPrefix = null, $strExpandAsArrayNodes = null, $objPreviousItem = null, $strColumnAliasArray = array()) {
			// If blank row, return null
			if (!$objDbRow)
				return null;


			// Create a new instance of the CartItem object
			$objToReturn = new CartItem();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'cart_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'cart_id'] : $strAliasPrefix . 'cart_id';
			$objToReturn->intCartId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'cart_type', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'cart_type'] : $strAliasPrefix . 'cart_type';
			$objToReturn->intCartType = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_id'] : $strAliasPrefix . 'product_id';
			$objToReturn->intProductId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code'] : $strAliasPrefix . 'code';
			$objToReturn->strCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'description'] : $strAliasPrefix . 'description';
			$objToReturn->strDescription = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'discount', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'discount'] : $strAliasPrefix . 'discount';
			$objToReturn->strDiscount = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'qty', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'qty'] : $strAliasPrefix . 'qty';
			$objToReturn->fltQty = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell'] : $strAliasPrefix . 'sell';
			$objToReturn->strSell = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell_base', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell_base'] : $strAliasPrefix . 'sell_base';
			$objToReturn->strSellBase = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell_discount', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell_discount'] : $strAliasPrefix . 'sell_discount';
			$objToReturn->strSellDiscount = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell_total', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell_total'] : $strAliasPrefix . 'sell_total';
			$objToReturn->strSellTotal = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'serial_numbers', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'serial_numbers'] : $strAliasPrefix . 'serial_numbers';
			$objToReturn->strSerialNumbers = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'gift_registry_item', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'gift_registry_item'] : $strAliasPrefix . 'gift_registry_item';
			$objToReturn->intGiftRegistryItem = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'datetime_added', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'datetime_added'] : $strAliasPrefix . 'datetime_added';
			$objToReturn->dttDatetimeAdded = $objDbRow->GetColumn($strAliasName, 'DateTime');
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
				$strAliasPrefix = 'xlsws_cart_item__';

			// Check for Cart Early Binding
			$strAlias = $strAliasPrefix . 'cart_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objCart = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for Product Early Binding
			$strAlias = $strAliasPrefix . 'product_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objProduct = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'product_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for GiftRegistryItemObject Early Binding
			$strAlias = $strAliasPrefix . 'gift_registry_item__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objGiftRegistryItemObject = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'gift_registry_item__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			return $objToReturn;
		}

		/**
		 * Instantiate an array of CartItems from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return CartItem[]
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
					$objItem = CartItem::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = CartItem::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single CartItem object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return CartItem
		*/
		public static function LoadByRowid($intRowid) {
			return CartItem::QuerySingle(
				QQ::Equal(QQN::CartItem()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load an array of CartItem objects,
		 * by CartId Index(es)
		 * @param integer $intCartId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		*/
		public static function LoadArrayByCartId($intCartId, $objOptionalClauses = null) {
			// Call CartItem::QueryArray to perform the LoadArrayByCartId query
			try {
				return CartItem::QueryArray(
					QQ::Equal(QQN::CartItem()->CartId, $intCartId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count CartItems
		 * by CartId Index(es)
		 * @param integer $intCartId
		 * @return int
		*/
		public static function CountByCartId($intCartId) {
			// Call CartItem::QueryCount to perform the CountByCartId query
			return CartItem::QueryCount(
				QQ::Equal(QQN::CartItem()->CartId, $intCartId)
			);
		}
			
		/**
		 * Load an array of CartItem objects,
		 * by Code Index(es)
		 * @param string $strCode
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		*/
		public static function LoadArrayByCode($strCode, $objOptionalClauses = null) {
			// Call CartItem::QueryArray to perform the LoadArrayByCode query
			try {
				return CartItem::QueryArray(
					QQ::Equal(QQN::CartItem()->Code, $strCode),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count CartItems
		 * by Code Index(es)
		 * @param string $strCode
		 * @return int
		*/
		public static function CountByCode($strCode) {
			// Call CartItem::QueryCount to perform the CountByCode query
			return CartItem::QueryCount(
				QQ::Equal(QQN::CartItem()->Code, $strCode)
			);
		}
			
		/**
		 * Load an array of CartItem objects,
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		*/
		public static function LoadArrayByProductId($intProductId, $objOptionalClauses = null) {
			// Call CartItem::QueryArray to perform the LoadArrayByProductId query
			try {
				return CartItem::QueryArray(
					QQ::Equal(QQN::CartItem()->ProductId, $intProductId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count CartItems
		 * by ProductId Index(es)
		 * @param integer $intProductId
		 * @return int
		*/
		public static function CountByProductId($intProductId) {
			// Call CartItem::QueryCount to perform the CountByProductId query
			return CartItem::QueryCount(
				QQ::Equal(QQN::CartItem()->ProductId, $intProductId)
			);
		}
			
		/**
		 * Load an array of CartItem objects,
		 * by GiftRegistryItem Index(es)
		 * @param integer $intGiftRegistryItem
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		*/
		public static function LoadArrayByGiftRegistryItem($intGiftRegistryItem, $objOptionalClauses = null) {
			// Call CartItem::QueryArray to perform the LoadArrayByGiftRegistryItem query
			try {
				return CartItem::QueryArray(
					QQ::Equal(QQN::CartItem()->GiftRegistryItem, $intGiftRegistryItem),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count CartItems
		 * by GiftRegistryItem Index(es)
		 * @param integer $intGiftRegistryItem
		 * @return int
		*/
		public static function CountByGiftRegistryItem($intGiftRegistryItem) {
			// Call CartItem::QueryCount to perform the CountByGiftRegistryItem query
			return CartItem::QueryCount(
				QQ::Equal(QQN::CartItem()->GiftRegistryItem, $intGiftRegistryItem)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this CartItem
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = CartItem::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_cart_item` (
							`cart_id`,
							`cart_type`,
							`product_id`,
							`code`,
							`description`,
							`discount`,
							`qty`,
							`sell`,
							`sell_base`,
							`sell_discount`,
							`sell_total`,
							`serial_numbers`,
							`gift_registry_item`,
							`datetime_added`
						) VALUES (
							' . $objDatabase->SqlVariable($this->intCartId) . ',
							' . $objDatabase->SqlVariable($this->intCartType) . ',
							' . $objDatabase->SqlVariable($this->intProductId) . ',
							' . $objDatabase->SqlVariable($this->strCode) . ',
							' . $objDatabase->SqlVariable($this->strDescription) . ',
							' . $objDatabase->SqlVariable($this->strDiscount) . ',
							' . $objDatabase->SqlVariable($this->fltQty) . ',
							' . $objDatabase->SqlVariable($this->strSell) . ',
							' . $objDatabase->SqlVariable($this->strSellBase) . ',
							' . $objDatabase->SqlVariable($this->strSellDiscount) . ',
							' . $objDatabase->SqlVariable($this->strSellTotal) . ',
							' . $objDatabase->SqlVariable($this->strSerialNumbers) . ',
							' . $objDatabase->SqlVariable($this->intGiftRegistryItem) . ',
							' . $objDatabase->SqlVariable($this->dttDatetimeAdded) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_cart_item', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`datetime_mod`
							FROM
								`xlsws_cart_item`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strDatetimeMod)
							throw new QOptimisticLockingException('CartItem');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_cart_item`
						SET
							`cart_id` = ' . $objDatabase->SqlVariable($this->intCartId) . ',
							`cart_type` = ' . $objDatabase->SqlVariable($this->intCartType) . ',
							`product_id` = ' . $objDatabase->SqlVariable($this->intProductId) . ',
							`code` = ' . $objDatabase->SqlVariable($this->strCode) . ',
							`description` = ' . $objDatabase->SqlVariable($this->strDescription) . ',
							`discount` = ' . $objDatabase->SqlVariable($this->strDiscount) . ',
							`qty` = ' . $objDatabase->SqlVariable($this->fltQty) . ',
							`sell` = ' . $objDatabase->SqlVariable($this->strSell) . ',
							`sell_base` = ' . $objDatabase->SqlVariable($this->strSellBase) . ',
							`sell_discount` = ' . $objDatabase->SqlVariable($this->strSellDiscount) . ',
							`sell_total` = ' . $objDatabase->SqlVariable($this->strSellTotal) . ',
							`serial_numbers` = ' . $objDatabase->SqlVariable($this->strSerialNumbers) . ',
							`gift_registry_item` = ' . $objDatabase->SqlVariable($this->intGiftRegistryItem) . ',
							`datetime_added` = ' . $objDatabase->SqlVariable($this->dttDatetimeAdded) . '
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
					`xlsws_cart_item`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strDatetimeMod = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this CartItem
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this CartItem with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = CartItem::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all CartItems
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = CartItem::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`');
		}

		/**
		 * Truncate xlsws_cart_item table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = CartItem::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_cart_item`');
		}

		/**
		 * Reload this CartItem from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved CartItem object.');

			// Reload the Object
			$objReloaded = CartItem::Load($this->intRowid);

			// Update $this's local variables to match
			$this->CartId = $objReloaded->CartId;
			$this->intCartType = $objReloaded->intCartType;
			$this->ProductId = $objReloaded->ProductId;
			$this->strCode = $objReloaded->strCode;
			$this->strDescription = $objReloaded->strDescription;
			$this->strDiscount = $objReloaded->strDiscount;
			$this->fltQty = $objReloaded->fltQty;
			$this->strSell = $objReloaded->strSell;
			$this->strSellBase = $objReloaded->strSellBase;
			$this->strSellDiscount = $objReloaded->strSellDiscount;
			$this->strSellTotal = $objReloaded->strSellTotal;
			$this->strSerialNumbers = $objReloaded->strSerialNumbers;
			$this->GiftRegistryItem = $objReloaded->GiftRegistryItem;
			$this->dttDatetimeAdded = $objReloaded->dttDatetimeAdded;
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

				case 'CartId':
					// Gets the value for intCartId (Not Null)
					// @return integer
					return $this->intCartId;

				case 'CartType':
					// Gets the value for intCartType 
					// @return integer
					return $this->intCartType;

				case 'ProductId':
					// Gets the value for intProductId (Not Null)
					// @return integer
					return $this->intProductId;

				case 'Code':
					// Gets the value for strCode (Not Null)
					// @return string
					return $this->strCode;

				case 'Description':
					// Gets the value for strDescription (Not Null)
					// @return string
					return $this->strDescription;

				case 'Discount':
					// Gets the value for strDiscount 
					// @return string
					return $this->strDiscount;

				case 'Qty':
					// Gets the value for fltQty (Not Null)
					// @return double
					return $this->fltQty;

				case 'Sell':
					// Gets the value for strSell (Not Null)
					// @return string
					return $this->strSell;

				case 'SellBase':
					// Gets the value for strSellBase (Not Null)
					// @return string
					return $this->strSellBase;

				case 'SellDiscount':
					// Gets the value for strSellDiscount (Not Null)
					// @return string
					return $this->strSellDiscount;

				case 'SellTotal':
					// Gets the value for strSellTotal (Not Null)
					// @return string
					return $this->strSellTotal;

				case 'SerialNumbers':
					// Gets the value for strSerialNumbers 
					// @return string
					return $this->strSerialNumbers;

				case 'GiftRegistryItem':
					// Gets the value for intGiftRegistryItem 
					// @return integer
					return $this->intGiftRegistryItem;

				case 'DatetimeAdded':
					// Gets the value for dttDatetimeAdded (Not Null)
					// @return QDateTime
					return $this->dttDatetimeAdded;

				case 'DatetimeMod':
					// Gets the value for strDatetimeMod (Read-Only Timestamp)
					// @return string
					return $this->strDatetimeMod;


				///////////////////
				// Member Objects
				///////////////////
				case 'Cart':
					// Gets the value for the Cart object referenced by intCartId (Not Null)
					// @return Cart
					try {
						if ((!$this->objCart) && (!is_null($this->intCartId)))
							$this->objCart = Cart::Load($this->intCartId);
						return $this->objCart;
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

				case 'GiftRegistryItemObject':
					// Gets the value for the GiftRegistryItems object referenced by intGiftRegistryItem 
					// @return GiftRegistryItems
					try {
						if ((!$this->objGiftRegistryItemObject) && (!is_null($this->intGiftRegistryItem)))
							$this->objGiftRegistryItemObject = GiftRegistryItems::Load($this->intGiftRegistryItem);
						return $this->objGiftRegistryItemObject;
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
				case 'CartId':
					// Sets the value for intCartId (Not Null)
					// @param integer $mixValue
					// @return integer
					try {
						$this->objCart = null;
						return ($this->intCartId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CartType':
					// Sets the value for intCartType 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intCartType = QType::Cast($mixValue, QType::Integer));
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

				case 'Code':
					// Sets the value for strCode (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCode = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Description':
					// Sets the value for strDescription (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Discount':
					// Sets the value for strDiscount 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strDiscount = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Qty':
					// Sets the value for fltQty (Not Null)
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltQty = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Sell':
					// Sets the value for strSell (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSell = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SellBase':
					// Sets the value for strSellBase (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSellBase = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SellDiscount':
					// Sets the value for strSellDiscount (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSellDiscount = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SellTotal':
					// Sets the value for strSellTotal (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSellTotal = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SerialNumbers':
					// Sets the value for strSerialNumbers 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSerialNumbers = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'GiftRegistryItem':
					// Sets the value for intGiftRegistryItem 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objGiftRegistryItemObject = null;
						return ($this->intGiftRegistryItem = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DatetimeAdded':
					// Sets the value for dttDatetimeAdded (Not Null)
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttDatetimeAdded = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'Cart':
					// Sets the value for the Cart object referenced by intCartId (Not Null)
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
							throw new QCallerException('Unable to set an unsaved Cart for this CartItem');

						// Update Local Member Variables
						$this->objCart = $mixValue;
						$this->intCartId = $mixValue->Rowid;

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
							throw new QCallerException('Unable to set an unsaved Product for this CartItem');

						// Update Local Member Variables
						$this->objProduct = $mixValue;
						$this->intProductId = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				case 'GiftRegistryItemObject':
					// Sets the value for the GiftRegistryItems object referenced by intGiftRegistryItem 
					// @param GiftRegistryItems $mixValue
					// @return GiftRegistryItems
					if (is_null($mixValue)) {
						$this->intGiftRegistryItem = null;
						$this->objGiftRegistryItemObject = null;
						return null;
					} else {
						// Make sure $mixValue actually is a GiftRegistryItems object
						try {
							$mixValue = QType::Cast($mixValue, 'GiftRegistryItems');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED GiftRegistryItems object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved GiftRegistryItemObject for this CartItem');

						// Update Local Member Variables
						$this->objGiftRegistryItemObject = $mixValue;
						$this->intGiftRegistryItem = $mixValue->Rowid;

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
			$strToReturn = '<complexType name="CartItem"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Cart" type="xsd1:Cart"/>';
			$strToReturn .= '<element name="CartType" type="xsd:int"/>';
			$strToReturn .= '<element name="Product" type="xsd1:Product"/>';
			$strToReturn .= '<element name="Code" type="xsd:string"/>';
			$strToReturn .= '<element name="Description" type="xsd:string"/>';
			$strToReturn .= '<element name="Discount" type="xsd:string"/>';
			$strToReturn .= '<element name="Qty" type="xsd:float"/>';
			$strToReturn .= '<element name="Sell" type="xsd:string"/>';
			$strToReturn .= '<element name="SellBase" type="xsd:string"/>';
			$strToReturn .= '<element name="SellDiscount" type="xsd:string"/>';
			$strToReturn .= '<element name="SellTotal" type="xsd:string"/>';
			$strToReturn .= '<element name="SerialNumbers" type="xsd:string"/>';
			$strToReturn .= '<element name="GiftRegistryItemObject" type="xsd1:GiftRegistryItems"/>';
			$strToReturn .= '<element name="DatetimeAdded" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="DatetimeMod" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('CartItem', $strComplexTypeArray)) {
				$strComplexTypeArray['CartItem'] = CartItem::GetSoapComplexTypeXml();
				Cart::AlterSoapComplexTypeArray($strComplexTypeArray);
				Product::AlterSoapComplexTypeArray($strComplexTypeArray);
				GiftRegistryItems::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, CartItem::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new CartItem();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if ((property_exists($objSoapObject, 'Cart')) &&
				($objSoapObject->Cart))
				$objToReturn->Cart = Cart::GetObjectFromSoapObject($objSoapObject->Cart);
			if (property_exists($objSoapObject, 'CartType'))
				$objToReturn->intCartType = $objSoapObject->CartType;
			if ((property_exists($objSoapObject, 'Product')) &&
				($objSoapObject->Product))
				$objToReturn->Product = Product::GetObjectFromSoapObject($objSoapObject->Product);
			if (property_exists($objSoapObject, 'Code'))
				$objToReturn->strCode = $objSoapObject->Code;
			if (property_exists($objSoapObject, 'Description'))
				$objToReturn->strDescription = $objSoapObject->Description;
			if (property_exists($objSoapObject, 'Discount'))
				$objToReturn->strDiscount = $objSoapObject->Discount;
			if (property_exists($objSoapObject, 'Qty'))
				$objToReturn->fltQty = $objSoapObject->Qty;
			if (property_exists($objSoapObject, 'Sell'))
				$objToReturn->strSell = $objSoapObject->Sell;
			if (property_exists($objSoapObject, 'SellBase'))
				$objToReturn->strSellBase = $objSoapObject->SellBase;
			if (property_exists($objSoapObject, 'SellDiscount'))
				$objToReturn->strSellDiscount = $objSoapObject->SellDiscount;
			if (property_exists($objSoapObject, 'SellTotal'))
				$objToReturn->strSellTotal = $objSoapObject->SellTotal;
			if (property_exists($objSoapObject, 'SerialNumbers'))
				$objToReturn->strSerialNumbers = $objSoapObject->SerialNumbers;
			if ((property_exists($objSoapObject, 'GiftRegistryItemObject')) &&
				($objSoapObject->GiftRegistryItemObject))
				$objToReturn->GiftRegistryItemObject = GiftRegistryItems::GetObjectFromSoapObject($objSoapObject->GiftRegistryItemObject);
			if (property_exists($objSoapObject, 'DatetimeAdded'))
				$objToReturn->dttDatetimeAdded = new QDateTime($objSoapObject->DatetimeAdded);
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
				array_push($objArrayToReturn, CartItem::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objCart)
				$objObject->objCart = Cart::GetSoapObjectFromObject($objObject->objCart, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intCartId = null;
			if ($objObject->objProduct)
				$objObject->objProduct = Product::GetSoapObjectFromObject($objObject->objProduct, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intProductId = null;
			if ($objObject->objGiftRegistryItemObject)
				$objObject->objGiftRegistryItemObject = GiftRegistryItems::GetSoapObjectFromObject($objObject->objGiftRegistryItemObject, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intGiftRegistryItem = null;
			if ($objObject->dttDatetimeAdded)
				$objObject->dttDatetimeAdded = $objObject->dttDatetimeAdded->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeCartItem extends QQNode {
		protected $strTableName = 'xlsws_cart_item';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'CartItem';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'CartId':
					return new QQNode('cart_id', 'CartId', 'integer', $this);
				case 'Cart':
					return new QQNodeCart('cart_id', 'Cart', 'integer', $this);
				case 'CartType':
					return new QQNode('cart_type', 'CartType', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'Discount':
					return new QQNode('discount', 'Discount', 'string', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'double', $this);
				case 'Sell':
					return new QQNode('sell', 'Sell', 'string', $this);
				case 'SellBase':
					return new QQNode('sell_base', 'SellBase', 'string', $this);
				case 'SellDiscount':
					return new QQNode('sell_discount', 'SellDiscount', 'string', $this);
				case 'SellTotal':
					return new QQNode('sell_total', 'SellTotal', 'string', $this);
				case 'SerialNumbers':
					return new QQNode('serial_numbers', 'SerialNumbers', 'string', $this);
				case 'GiftRegistryItem':
					return new QQNode('gift_registry_item', 'GiftRegistryItem', 'integer', $this);
				case 'GiftRegistryItemObject':
					return new QQNodeGiftRegistryItems('gift_registry_item', 'GiftRegistryItemObject', 'integer', $this);
				case 'DatetimeAdded':
					return new QQNode('datetime_added', 'DatetimeAdded', 'QDateTime', $this);
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

	class QQReverseReferenceNodeCartItem extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_cart_item';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'CartItem';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'CartId':
					return new QQNode('cart_id', 'CartId', 'integer', $this);
				case 'Cart':
					return new QQNodeCart('cart_id', 'Cart', 'integer', $this);
				case 'CartType':
					return new QQNode('cart_type', 'CartType', 'integer', $this);
				case 'ProductId':
					return new QQNode('product_id', 'ProductId', 'integer', $this);
				case 'Product':
					return new QQNodeProduct('product_id', 'Product', 'integer', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'Discount':
					return new QQNode('discount', 'Discount', 'string', $this);
				case 'Qty':
					return new QQNode('qty', 'Qty', 'double', $this);
				case 'Sell':
					return new QQNode('sell', 'Sell', 'string', $this);
				case 'SellBase':
					return new QQNode('sell_base', 'SellBase', 'string', $this);
				case 'SellDiscount':
					return new QQNode('sell_discount', 'SellDiscount', 'string', $this);
				case 'SellTotal':
					return new QQNode('sell_total', 'SellTotal', 'string', $this);
				case 'SerialNumbers':
					return new QQNode('serial_numbers', 'SerialNumbers', 'string', $this);
				case 'GiftRegistryItem':
					return new QQNode('gift_registry_item', 'GiftRegistryItem', 'integer', $this);
				case 'GiftRegistryItemObject':
					return new QQNodeGiftRegistryItems('gift_registry_item', 'GiftRegistryItemObject', 'integer', $this);
				case 'DatetimeAdded':
					return new QQNode('datetime_added', 'DatetimeAdded', 'QDateTime', $this);
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