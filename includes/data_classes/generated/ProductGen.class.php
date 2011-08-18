<?php
	/**
	 * The abstract ProductGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Product subclass which
	 * extends this ProductGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Product class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Name the value for strName (Not Null)
	 * @property integer $ImageId the value for intImageId 
	 * @property string $ClassName the value for strClassName 
	 * @property string $Code the value for strCode (Unique)
	 * @property boolean $Current the value for blnCurrent 
	 * @property string $Description the value for strDescription 
	 * @property string $DescriptionShort the value for strDescriptionShort 
	 * @property string $Family the value for strFamily 
	 * @property boolean $GiftCard the value for blnGiftCard 
	 * @property boolean $Inventoried the value for blnInventoried 
	 * @property double $Inventory the value for fltInventory 
	 * @property double $InventoryTotal the value for fltInventoryTotal 
	 * @property boolean $MasterModel the value for blnMasterModel 
	 * @property integer $FkProductMasterId the value for intFkProductMasterId 
	 * @property string $ProductSize the value for strProductSize 
	 * @property string $ProductColor the value for strProductColor 
	 * @property double $ProductHeight the value for fltProductHeight 
	 * @property double $ProductLength the value for fltProductLength 
	 * @property double $ProductWidth the value for fltProductWidth 
	 * @property double $ProductWeight the value for fltProductWeight 
	 * @property integer $FkTaxStatusId the value for intFkTaxStatusId 
	 * @property double $Sell the value for fltSell 
	 * @property double $SellTaxInclusive the value for fltSellTaxInclusive 
	 * @property double $SellWeb the value for fltSellWeb 
	 * @property string $Upc the value for strUpc 
	 * @property boolean $Web the value for blnWeb 
	 * @property string $WebKeyword1 the value for strWebKeyword1 
	 * @property string $WebKeyword2 the value for strWebKeyword2 
	 * @property string $WebKeyword3 the value for strWebKeyword3 
	 * @property string $MetaDesc the value for strMetaDesc 
	 * @property string $MetaKeyword the value for strMetaKeyword 
	 * @property boolean $Featured the value for blnFeatured (Not Null)
	 * @property QDateTime $Created the value for dttCreated 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Product $FkProductMaster the value for the Product object referenced by intFkProductMasterId 
	 * @property TaxStatus $FkTaxStatus the value for the TaxStatus object referenced by intFkTaxStatusId 
	 * @property Category $_Category the value for the private _objCategory (Read-Only) if set due to an expansion on the xlsws_product_category_assn association table
	 * @property Category[] $_CategoryArray the value for the private _objCategoryArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_category_assn association table
	 * @property Images $_ImagesAsImage the value for the private _objImagesAsImage (Read-Only) if set due to an expansion on the xlsws_product_image_assn association table
	 * @property Images[] $_ImagesAsImageArray the value for the private _objImagesAsImageArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_image_assn association table
	 * @property CartItem $_CartItem the value for the private _objCartItem (Read-Only) if set due to an expansion on the xlsws_cart_item.product_id reverse relationship
	 * @property CartItem[] $_CartItemArray the value for the private _objCartItemArray (Read-Only) if set due to an ExpandAsArray on the xlsws_cart_item.product_id reverse relationship
	 * @property GiftRegistryItems $_GiftRegistryItems the value for the private _objGiftRegistryItems (Read-Only) if set due to an expansion on the xlsws_gift_registry_items.product_id reverse relationship
	 * @property GiftRegistryItems[] $_GiftRegistryItemsArray the value for the private _objGiftRegistryItemsArray (Read-Only) if set due to an ExpandAsArray on the xlsws_gift_registry_items.product_id reverse relationship
	 * @property Product $_ProductAsFkMaster the value for the private _objProductAsFkMaster (Read-Only) if set due to an expansion on the xlsws_product.fk_product_master_id reverse relationship
	 * @property Product[] $_ProductAsFkMasterArray the value for the private _objProductAsFkMasterArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product.fk_product_master_id reverse relationship
	 * @property ProductQtyPricing $_ProductQtyPricing the value for the private _objProductQtyPricing (Read-Only) if set due to an expansion on the xlsws_product_qty_pricing.product_id reverse relationship
	 * @property ProductQtyPricing[] $_ProductQtyPricingArray the value for the private _objProductQtyPricingArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_qty_pricing.product_id reverse relationship
	 * @property ProductRelated $_ProductRelated the value for the private _objProductRelated (Read-Only) if set due to an expansion on the xlsws_product_related.product_id reverse relationship
	 * @property ProductRelated[] $_ProductRelatedArray the value for the private _objProductRelatedArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_related.product_id reverse relationship
	 * @property ProductRelated $_ProductRelatedAsRelated the value for the private _objProductRelatedAsRelated (Read-Only) if set due to an expansion on the xlsws_product_related.related_id reverse relationship
	 * @property ProductRelated[] $_ProductRelatedAsRelatedArray the value for the private _objProductRelatedAsRelatedArray (Read-Only) if set due to an ExpandAsArray on the xlsws_product_related.related_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class ProductGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_product.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.name
		 * @var string strName
		 */
		protected $strName;
		const NameMaxLength = 255;
		const NameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.image_id
		 * @var integer intImageId
		 */
		protected $intImageId;
		const ImageIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.class_name
		 * @var string strClassName
		 */
		protected $strClassName;
		const ClassNameMaxLength = 32;
		const ClassNameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.code
		 * @var string strCode
		 */
		protected $strCode;
		const CodeMaxLength = 255;
		const CodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.current
		 * @var boolean blnCurrent
		 */
		protected $blnCurrent;
		const CurrentDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.description
		 * @var string strDescription
		 */
		protected $strDescription;
		const DescriptionDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.description_short
		 * @var string strDescriptionShort
		 */
		protected $strDescriptionShort;
		const DescriptionShortDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.family
		 * @var string strFamily
		 */
		protected $strFamily;
		const FamilyMaxLength = 32;
		const FamilyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.gift_card
		 * @var boolean blnGiftCard
		 */
		protected $blnGiftCard;
		const GiftCardDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.inventoried
		 * @var boolean blnInventoried
		 */
		protected $blnInventoried;
		const InventoriedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.inventory
		 * @var double fltInventory
		 */
		protected $fltInventory;
		const InventoryDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.inventory_total
		 * @var double fltInventoryTotal
		 */
		protected $fltInventoryTotal;
		const InventoryTotalDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.master_model
		 * @var boolean blnMasterModel
		 */
		protected $blnMasterModel;
		const MasterModelDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.fk_product_master_id
		 * @var integer intFkProductMasterId
		 */
		protected $intFkProductMasterId;
		const FkProductMasterIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.product_size
		 * @var string strProductSize
		 */
		protected $strProductSize;
		const ProductSizeMaxLength = 32;
		const ProductSizeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.product_color
		 * @var string strProductColor
		 */
		protected $strProductColor;
		const ProductColorMaxLength = 32;
		const ProductColorDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.product_height
		 * @var double fltProductHeight
		 */
		protected $fltProductHeight;
		const ProductHeightDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.product_length
		 * @var double fltProductLength
		 */
		protected $fltProductLength;
		const ProductLengthDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.product_width
		 * @var double fltProductWidth
		 */
		protected $fltProductWidth;
		const ProductWidthDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.product_weight
		 * @var double fltProductWeight
		 */
		protected $fltProductWeight;
		const ProductWeightDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.fk_tax_status_id
		 * @var integer intFkTaxStatusId
		 */
		protected $intFkTaxStatusId;
		const FkTaxStatusIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.sell
		 * @var double fltSell
		 */
		protected $fltSell;
		const SellDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.sell_tax_inclusive
		 * @var double fltSellTaxInclusive
		 */
		protected $fltSellTaxInclusive;
		const SellTaxInclusiveDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.sell_web
		 * @var double fltSellWeb
		 */
		protected $fltSellWeb;
		const SellWebDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.upc
		 * @var string strUpc
		 */
		protected $strUpc;
		const UpcMaxLength = 12;
		const UpcDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.web
		 * @var boolean blnWeb
		 */
		protected $blnWeb;
		const WebDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.web_keyword1
		 * @var string strWebKeyword1
		 */
		protected $strWebKeyword1;
		const WebKeyword1MaxLength = 255;
		const WebKeyword1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.web_keyword2
		 * @var string strWebKeyword2
		 */
		protected $strWebKeyword2;
		const WebKeyword2MaxLength = 255;
		const WebKeyword2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.web_keyword3
		 * @var string strWebKeyword3
		 */
		protected $strWebKeyword3;
		const WebKeyword3MaxLength = 255;
		const WebKeyword3Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.meta_desc
		 * @var string strMetaDesc
		 */
		protected $strMetaDesc;
		const MetaDescMaxLength = 255;
		const MetaDescDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.meta_keyword
		 * @var string strMetaKeyword
		 */
		protected $strMetaKeyword;
		const MetaKeywordMaxLength = 255;
		const MetaKeywordDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.featured
		 * @var boolean blnFeatured
		 */
		protected $blnFeatured;
		const FeaturedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_product.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single Category object
		 * (of type Category), if this Product object was restored with
		 * an expansion on the xlsws_product_category_assn association table.
		 * @var Category _objCategory;
		 */
		private $_objCategory;

		/**
		 * Private member variable that stores a reference to an array of Category objects
		 * (of type Category[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_product_category_assn association table.
		 * @var Category[] _objCategoryArray;
		 */
		private $_objCategoryArray = array();

		/**
		 * Private member variable that stores a reference to a single ImagesAsImage object
		 * (of type Images), if this Product object was restored with
		 * an expansion on the xlsws_product_image_assn association table.
		 * @var Images _objImagesAsImage;
		 */
		private $_objImagesAsImage;

		/**
		 * Private member variable that stores a reference to an array of ImagesAsImage objects
		 * (of type Images[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_product_image_assn association table.
		 * @var Images[] _objImagesAsImageArray;
		 */
		private $_objImagesAsImageArray = array();

		/**
		 * Private member variable that stores a reference to a single CartItem object
		 * (of type CartItem), if this Product object was restored with
		 * an expansion on the xlsws_cart_item association table.
		 * @var CartItem _objCartItem;
		 */
		private $_objCartItem;

		/**
		 * Private member variable that stores a reference to an array of CartItem objects
		 * (of type CartItem[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_cart_item association table.
		 * @var CartItem[] _objCartItemArray;
		 */
		private $_objCartItemArray = array();

		/**
		 * Private member variable that stores a reference to a single GiftRegistryItems object
		 * (of type GiftRegistryItems), if this Product object was restored with
		 * an expansion on the xlsws_gift_registry_items association table.
		 * @var GiftRegistryItems _objGiftRegistryItems;
		 */
		private $_objGiftRegistryItems;

		/**
		 * Private member variable that stores a reference to an array of GiftRegistryItems objects
		 * (of type GiftRegistryItems[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_gift_registry_items association table.
		 * @var GiftRegistryItems[] _objGiftRegistryItemsArray;
		 */
		private $_objGiftRegistryItemsArray = array();

		/**
		 * Private member variable that stores a reference to a single ProductAsFkMaster object
		 * (of type Product), if this Product object was restored with
		 * an expansion on the xlsws_product association table.
		 * @var Product _objProductAsFkMaster;
		 */
		private $_objProductAsFkMaster;

		/**
		 * Private member variable that stores a reference to an array of ProductAsFkMaster objects
		 * (of type Product[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_product association table.
		 * @var Product[] _objProductAsFkMasterArray;
		 */
		private $_objProductAsFkMasterArray = array();

		/**
		 * Private member variable that stores a reference to a single ProductQtyPricing object
		 * (of type ProductQtyPricing), if this Product object was restored with
		 * an expansion on the xlsws_product_qty_pricing association table.
		 * @var ProductQtyPricing _objProductQtyPricing;
		 */
		private $_objProductQtyPricing;

		/**
		 * Private member variable that stores a reference to an array of ProductQtyPricing objects
		 * (of type ProductQtyPricing[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_product_qty_pricing association table.
		 * @var ProductQtyPricing[] _objProductQtyPricingArray;
		 */
		private $_objProductQtyPricingArray = array();

		/**
		 * Private member variable that stores a reference to a single ProductRelated object
		 * (of type ProductRelated), if this Product object was restored with
		 * an expansion on the xlsws_product_related association table.
		 * @var ProductRelated _objProductRelated;
		 */
		private $_objProductRelated;

		/**
		 * Private member variable that stores a reference to an array of ProductRelated objects
		 * (of type ProductRelated[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_product_related association table.
		 * @var ProductRelated[] _objProductRelatedArray;
		 */
		private $_objProductRelatedArray = array();

		/**
		 * Private member variable that stores a reference to a single ProductRelatedAsRelated object
		 * (of type ProductRelated), if this Product object was restored with
		 * an expansion on the xlsws_product_related association table.
		 * @var ProductRelated _objProductRelatedAsRelated;
		 */
		private $_objProductRelatedAsRelated;

		/**
		 * Private member variable that stores a reference to an array of ProductRelatedAsRelated objects
		 * (of type ProductRelated[]), if this Product object was restored with
		 * an ExpandAsArray on the xlsws_product_related association table.
		 * @var ProductRelated[] _objProductRelatedAsRelatedArray;
		 */
		private $_objProductRelatedAsRelatedArray = array();

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
		 * in the database column xlsws_product.fk_product_master_id.
		 *
		 * NOTE: Always use the FkProductMaster property getter to correctly retrieve this Product object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Product objFkProductMaster
		 */
		protected $objFkProductMaster;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_product.fk_tax_status_id.
		 *
		 * NOTE: Always use the FkTaxStatus property getter to correctly retrieve this TaxStatus object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var TaxStatus objFkTaxStatus
		 */
		protected $objFkTaxStatus;





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
		 * Load a Product from PK Info
		 * @param integer $intRowid
		 * @return Product
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Product::QuerySingle(
				QQ::Equal(QQN::Product()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Products
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadAll query
			try {
				return Product::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Products
		 * @return int
		 */
		public static function CountAll() {
			// Call Product::QueryCount to perform the CountAll query
			return Product::QueryCount(QQ::All());
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
			$objDatabase = Product::GetDatabase();

			// Create/Build out the QueryBuilder object with Product-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_product');
			Product::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_product');

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
		 * Static Qcodo Query method to query for a single Product object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Product the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Product::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Product object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Product::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Product objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Product[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Product::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Product::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Product objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Product::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Product::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_product_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Product-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Product::GetSelectFields($objQueryBuilder);
				Product::GetFromFields($objQueryBuilder);

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
			return Product::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Product
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_product';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
			$objBuilder->AddSelectItem($strTableName, 'image_id', $strAliasPrefix . 'image_id');
			$objBuilder->AddSelectItem($strTableName, 'class_name', $strAliasPrefix . 'class_name');
			$objBuilder->AddSelectItem($strTableName, 'code', $strAliasPrefix . 'code');
			$objBuilder->AddSelectItem($strTableName, 'current', $strAliasPrefix . 'current');
			$objBuilder->AddSelectItem($strTableName, 'description', $strAliasPrefix . 'description');
			$objBuilder->AddSelectItem($strTableName, 'description_short', $strAliasPrefix . 'description_short');
			$objBuilder->AddSelectItem($strTableName, 'family', $strAliasPrefix . 'family');
			$objBuilder->AddSelectItem($strTableName, 'gift_card', $strAliasPrefix . 'gift_card');
			$objBuilder->AddSelectItem($strTableName, 'inventoried', $strAliasPrefix . 'inventoried');
			$objBuilder->AddSelectItem($strTableName, 'inventory', $strAliasPrefix . 'inventory');
			$objBuilder->AddSelectItem($strTableName, 'inventory_total', $strAliasPrefix . 'inventory_total');
			$objBuilder->AddSelectItem($strTableName, 'master_model', $strAliasPrefix . 'master_model');
			$objBuilder->AddSelectItem($strTableName, 'fk_product_master_id', $strAliasPrefix . 'fk_product_master_id');
			$objBuilder->AddSelectItem($strTableName, 'product_size', $strAliasPrefix . 'product_size');
			$objBuilder->AddSelectItem($strTableName, 'product_color', $strAliasPrefix . 'product_color');
			$objBuilder->AddSelectItem($strTableName, 'product_height', $strAliasPrefix . 'product_height');
			$objBuilder->AddSelectItem($strTableName, 'product_length', $strAliasPrefix . 'product_length');
			$objBuilder->AddSelectItem($strTableName, 'product_width', $strAliasPrefix . 'product_width');
			$objBuilder->AddSelectItem($strTableName, 'product_weight', $strAliasPrefix . 'product_weight');
			$objBuilder->AddSelectItem($strTableName, 'fk_tax_status_id', $strAliasPrefix . 'fk_tax_status_id');
			$objBuilder->AddSelectItem($strTableName, 'sell', $strAliasPrefix . 'sell');
			$objBuilder->AddSelectItem($strTableName, 'sell_tax_inclusive', $strAliasPrefix . 'sell_tax_inclusive');
			$objBuilder->AddSelectItem($strTableName, 'sell_web', $strAliasPrefix . 'sell_web');
			$objBuilder->AddSelectItem($strTableName, 'upc', $strAliasPrefix . 'upc');
			$objBuilder->AddSelectItem($strTableName, 'web', $strAliasPrefix . 'web');
			$objBuilder->AddSelectItem($strTableName, 'web_keyword1', $strAliasPrefix . 'web_keyword1');
			$objBuilder->AddSelectItem($strTableName, 'web_keyword2', $strAliasPrefix . 'web_keyword2');
			$objBuilder->AddSelectItem($strTableName, 'web_keyword3', $strAliasPrefix . 'web_keyword3');
			$objBuilder->AddSelectItem($strTableName, 'meta_desc', $strAliasPrefix . 'meta_desc');
			$objBuilder->AddSelectItem($strTableName, 'meta_keyword', $strAliasPrefix . 'meta_keyword');
			$objBuilder->AddSelectItem($strTableName, 'featured', $strAliasPrefix . 'featured');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Product from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Product::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Product
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
					$strAliasPrefix = 'xlsws_product__';

				$strAlias = $strAliasPrefix . 'category__category_id__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objCategoryArray)) {
						$objPreviousChildItem = $objPreviousItem->_objCategoryArray[$intPreviousChildItemCount - 1];
						$objChildItem = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'category__category_id__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objCategoryArray[] = $objChildItem;
					} else
						$objPreviousItem->_objCategoryArray[] = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'category__category_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'imagesasimage__image_id__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objImagesAsImageArray)) {
						$objPreviousChildItem = $objPreviousItem->_objImagesAsImageArray[$intPreviousChildItemCount - 1];
						$objChildItem = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'imagesasimage__image_id__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objImagesAsImageArray[] = $objChildItem;
					} else
						$objPreviousItem->_objImagesAsImageArray[] = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'imagesasimage__image_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}


				$strAlias = $strAliasPrefix . 'cartitem__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objCartItemArray)) {
						$objPreviousChildItem = $objPreviousItem->_objCartItemArray[$intPreviousChildItemCount - 1];
						$objChildItem = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitem__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objCartItemArray[] = $objChildItem;
					} else
						$objPreviousItem->_objCartItemArray[] = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'giftregistryitems__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objGiftRegistryItemsArray)) {
						$objPreviousChildItem = $objPreviousItem->_objGiftRegistryItemsArray[$intPreviousChildItemCount - 1];
						$objChildItem = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitems__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objGiftRegistryItemsArray[] = $objChildItem;
					} else
						$objPreviousItem->_objGiftRegistryItemsArray[] = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitems__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'productasfkmaster__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductAsFkMasterArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductAsFkMasterArray[$intPreviousChildItemCount - 1];
						$objChildItem = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfkmaster__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductAsFkMasterArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductAsFkMasterArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfkmaster__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'productqtypricing__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductQtyPricingArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductQtyPricingArray[$intPreviousChildItemCount - 1];
						$objChildItem = ProductQtyPricing::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productqtypricing__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductQtyPricingArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductQtyPricingArray[] = ProductQtyPricing::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productqtypricing__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'productrelated__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductRelatedArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductRelatedArray[$intPreviousChildItemCount - 1];
						$objChildItem = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelated__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductRelatedArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductRelatedArray[] = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelated__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'productrelatedasrelated__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objProductRelatedAsRelatedArray)) {
						$objPreviousChildItem = $objPreviousItem->_objProductRelatedAsRelatedArray[$intPreviousChildItemCount - 1];
						$objChildItem = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelatedasrelated__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objProductRelatedAsRelatedArray[] = $objChildItem;
					} else
						$objPreviousItem->_objProductRelatedAsRelatedArray[] = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelatedasrelated__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_product__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Product object
			$objToReturn = new Product();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'name'] : $strAliasPrefix . 'name';
			$objToReturn->strName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'image_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'image_id'] : $strAliasPrefix . 'image_id';
			$objToReturn->intImageId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'class_name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'class_name'] : $strAliasPrefix . 'class_name';
			$objToReturn->strClassName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'code', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'code'] : $strAliasPrefix . 'code';
			$objToReturn->strCode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'current', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'current'] : $strAliasPrefix . 'current';
			$objToReturn->blnCurrent = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'description', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'description'] : $strAliasPrefix . 'description';
			$objToReturn->strDescription = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'description_short', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'description_short'] : $strAliasPrefix . 'description_short';
			$objToReturn->strDescriptionShort = $objDbRow->GetColumn($strAliasName, 'Blob');
			$strAliasName = array_key_exists($strAliasPrefix . 'family', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'family'] : $strAliasPrefix . 'family';
			$objToReturn->strFamily = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'gift_card', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'gift_card'] : $strAliasPrefix . 'gift_card';
			$objToReturn->blnGiftCard = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'inventoried', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'inventoried'] : $strAliasPrefix . 'inventoried';
			$objToReturn->blnInventoried = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'inventory', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'inventory'] : $strAliasPrefix . 'inventory';
			$objToReturn->fltInventory = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'inventory_total', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'inventory_total'] : $strAliasPrefix . 'inventory_total';
			$objToReturn->fltInventoryTotal = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'master_model', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'master_model'] : $strAliasPrefix . 'master_model';
			$objToReturn->blnMasterModel = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'fk_product_master_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'fk_product_master_id'] : $strAliasPrefix . 'fk_product_master_id';
			$objToReturn->intFkProductMasterId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_size', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_size'] : $strAliasPrefix . 'product_size';
			$objToReturn->strProductSize = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_color', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_color'] : $strAliasPrefix . 'product_color';
			$objToReturn->strProductColor = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_height', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_height'] : $strAliasPrefix . 'product_height';
			$objToReturn->fltProductHeight = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_length', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_length'] : $strAliasPrefix . 'product_length';
			$objToReturn->fltProductLength = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_width', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_width'] : $strAliasPrefix . 'product_width';
			$objToReturn->fltProductWidth = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'product_weight', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'product_weight'] : $strAliasPrefix . 'product_weight';
			$objToReturn->fltProductWeight = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'fk_tax_status_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'fk_tax_status_id'] : $strAliasPrefix . 'fk_tax_status_id';
			$objToReturn->intFkTaxStatusId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell'] : $strAliasPrefix . 'sell';
			$objToReturn->fltSell = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell_tax_inclusive', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell_tax_inclusive'] : $strAliasPrefix . 'sell_tax_inclusive';
			$objToReturn->fltSellTaxInclusive = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell_web', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell_web'] : $strAliasPrefix . 'sell_web';
			$objToReturn->fltSellWeb = $objDbRow->GetColumn($strAliasName, 'Float');
			$strAliasName = array_key_exists($strAliasPrefix . 'upc', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'upc'] : $strAliasPrefix . 'upc';
			$objToReturn->strUpc = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'web', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'web'] : $strAliasPrefix . 'web';
			$objToReturn->blnWeb = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'web_keyword1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'web_keyword1'] : $strAliasPrefix . 'web_keyword1';
			$objToReturn->strWebKeyword1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'web_keyword2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'web_keyword2'] : $strAliasPrefix . 'web_keyword2';
			$objToReturn->strWebKeyword2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'web_keyword3', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'web_keyword3'] : $strAliasPrefix . 'web_keyword3';
			$objToReturn->strWebKeyword3 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'meta_desc', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'meta_desc'] : $strAliasPrefix . 'meta_desc';
			$objToReturn->strMetaDesc = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'meta_keyword', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'meta_keyword'] : $strAliasPrefix . 'meta_keyword';
			$objToReturn->strMetaKeyword = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'featured', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'featured'] : $strAliasPrefix . 'featured';
			$objToReturn->blnFeatured = $objDbRow->GetColumn($strAliasName, 'Bit');
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
				$strAliasPrefix = 'xlsws_product__';

			// Check for FkProductMaster Early Binding
			$strAlias = $strAliasPrefix . 'fk_product_master_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objFkProductMaster = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'fk_product_master_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for FkTaxStatus Early Binding
			$strAlias = $strAliasPrefix . 'fk_tax_status_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objFkTaxStatus = TaxStatus::InstantiateDbRow($objDbRow, $strAliasPrefix . 'fk_tax_status_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);



			// Check for Category Virtual Binding
			$strAlias = $strAliasPrefix . 'category__category_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCategoryArray[] = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'category__category_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCategory = Category::InstantiateDbRow($objDbRow, $strAliasPrefix . 'category__category_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ImagesAsImage Virtual Binding
			$strAlias = $strAliasPrefix . 'imagesasimage__image_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objImagesAsImageArray[] = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'imagesasimage__image_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objImagesAsImage = Images::InstantiateDbRow($objDbRow, $strAliasPrefix . 'imagesasimage__image_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}


			// Check for CartItem Virtual Binding
			$strAlias = $strAliasPrefix . 'cartitem__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCartItemArray[] = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCartItem = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for GiftRegistryItems Virtual Binding
			$strAlias = $strAliasPrefix . 'giftregistryitems__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objGiftRegistryItemsArray[] = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitems__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objGiftRegistryItems = GiftRegistryItems::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryitems__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ProductAsFkMaster Virtual Binding
			$strAlias = $strAliasPrefix . 'productasfkmaster__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductAsFkMasterArray[] = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfkmaster__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProductAsFkMaster = Product::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productasfkmaster__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ProductQtyPricing Virtual Binding
			$strAlias = $strAliasPrefix . 'productqtypricing__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductQtyPricingArray[] = ProductQtyPricing::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productqtypricing__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProductQtyPricing = ProductQtyPricing::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productqtypricing__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ProductRelated Virtual Binding
			$strAlias = $strAliasPrefix . 'productrelated__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductRelatedArray[] = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelated__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProductRelated = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelated__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for ProductRelatedAsRelated Virtual Binding
			$strAlias = $strAliasPrefix . 'productrelatedasrelated__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objProductRelatedAsRelatedArray[] = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelatedasrelated__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objProductRelatedAsRelated = ProductRelated::InstantiateDbRow($objDbRow, $strAliasPrefix . 'productrelatedasrelated__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Products from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Product[]
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
					$objItem = Product::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Product::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Product object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Product
		*/
		public static function LoadByRowid($intRowid) {
			return Product::QuerySingle(
				QQ::Equal(QQN::Product()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Product object,
		 * by Code Index(es)
		 * @param string $strCode
		 * @return Product
		*/
		public static function LoadByCode($strCode) {
			return Product::QuerySingle(
				QQ::Equal(QQN::Product()->Code, $strCode)
			);
		}
			
		/**
		 * Load an array of Product objects,
		 * by Web Index(es)
		 * @param boolean $blnWeb
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByWeb($blnWeb, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByWeb query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->Web, $blnWeb),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products
		 * by Web Index(es)
		 * @param boolean $blnWeb
		 * @return int
		*/
		public static function CountByWeb($blnWeb) {
			// Call Product::QueryCount to perform the CountByWeb query
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->Web, $blnWeb)
			);
		}
			
		/**
		 * Load an array of Product objects,
		 * by Name Index(es)
		 * @param string $strName
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByName($strName, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByName query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->Name, $strName),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products
		 * by Name Index(es)
		 * @param string $strName
		 * @return int
		*/
		public static function CountByName($strName) {
			// Call Product::QueryCount to perform the CountByName query
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->Name, $strName)
			);
		}
			
		/**
		 * Load an array of Product objects,
		 * by FkProductMasterId Index(es)
		 * @param integer $intFkProductMasterId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByFkProductMasterId($intFkProductMasterId, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByFkProductMasterId query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->FkProductMasterId, $intFkProductMasterId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products
		 * by FkProductMasterId Index(es)
		 * @param integer $intFkProductMasterId
		 * @return int
		*/
		public static function CountByFkProductMasterId($intFkProductMasterId) {
			// Call Product::QueryCount to perform the CountByFkProductMasterId query
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->FkProductMasterId, $intFkProductMasterId)
			);
		}
			
		/**
		 * Load an array of Product objects,
		 * by MasterModel Index(es)
		 * @param boolean $blnMasterModel
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByMasterModel($blnMasterModel, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByMasterModel query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->MasterModel, $blnMasterModel),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products
		 * by MasterModel Index(es)
		 * @param boolean $blnMasterModel
		 * @return int
		*/
		public static function CountByMasterModel($blnMasterModel) {
			// Call Product::QueryCount to perform the CountByMasterModel query
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->MasterModel, $blnMasterModel)
			);
		}
			
		/**
		 * Load an array of Product objects,
		 * by FkTaxStatusId Index(es)
		 * @param integer $intFkTaxStatusId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByFkTaxStatusId($intFkTaxStatusId, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByFkTaxStatusId query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->FkTaxStatusId, $intFkTaxStatusId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products
		 * by FkTaxStatusId Index(es)
		 * @param integer $intFkTaxStatusId
		 * @return int
		*/
		public static function CountByFkTaxStatusId($intFkTaxStatusId) {
			// Call Product::QueryCount to perform the CountByFkTaxStatusId query
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->FkTaxStatusId, $intFkTaxStatusId)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////
			/**
		 * Load an array of Category objects for a given Category
		 * via the xlsws_product_category_assn table
		 * @param integer $intCategoryId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByCategory($intCategoryId, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByCategory query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->Category->CategoryId, $intCategoryId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products for a given Category
		 * via the xlsws_product_category_assn table
		 * @param integer $intCategoryId
		 * @return int
		*/
		public static function CountByCategory($intCategoryId) {
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->Category->CategoryId, $intCategoryId)
			);
		}
			/**
		 * Load an array of Images objects for a given ImagesAsImage
		 * via the xlsws_product_image_assn table
		 * @param integer $intImageId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/
		public static function LoadArrayByImagesAsImage($intImageId, $objOptionalClauses = null) {
			// Call Product::QueryArray to perform the LoadArrayByImagesAsImage query
			try {
				return Product::QueryArray(
					QQ::Equal(QQN::Product()->ImagesAsImage->ImageId, $intImageId),
					$objOptionalClauses
				);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Products for a given ImagesAsImage
		 * via the xlsws_product_image_assn table
		 * @param integer $intImageId
		 * @return int
		*/
		public static function CountByImagesAsImage($intImageId) {
			return Product::QueryCount(
				QQ::Equal(QQN::Product()->ImagesAsImage->ImageId, $intImageId)
			);
		}




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Product
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_product` (
							`name`,
							`image_id`,
							`class_name`,
							`code`,
							`current`,
							`description`,
							`description_short`,
							`family`,
							`gift_card`,
							`inventoried`,
							`inventory`,
							`inventory_total`,
							`master_model`,
							`fk_product_master_id`,
							`product_size`,
							`product_color`,
							`product_height`,
							`product_length`,
							`product_width`,
							`product_weight`,
							`fk_tax_status_id`,
							`sell`,
							`sell_tax_inclusive`,
							`sell_web`,
							`upc`,
							`web`,
							`web_keyword1`,
							`web_keyword2`,
							`web_keyword3`,
							`meta_desc`,
							`meta_keyword`,
							`featured`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strName) . ',
							' . $objDatabase->SqlVariable($this->intImageId) . ',
							' . $objDatabase->SqlVariable($this->strClassName) . ',
							' . $objDatabase->SqlVariable($this->strCode) . ',
							' . $objDatabase->SqlVariable($this->blnCurrent) . ',
							' . $objDatabase->SqlVariable($this->strDescription) . ',
							' . $objDatabase->SqlVariable($this->strDescriptionShort) . ',
							' . $objDatabase->SqlVariable($this->strFamily) . ',
							' . $objDatabase->SqlVariable($this->blnGiftCard) . ',
							' . $objDatabase->SqlVariable($this->blnInventoried) . ',
							' . $objDatabase->SqlVariable($this->fltInventory) . ',
							' . $objDatabase->SqlVariable($this->fltInventoryTotal) . ',
							' . $objDatabase->SqlVariable($this->blnMasterModel) . ',
							' . $objDatabase->SqlVariable($this->intFkProductMasterId) . ',
							' . $objDatabase->SqlVariable($this->strProductSize) . ',
							' . $objDatabase->SqlVariable($this->strProductColor) . ',
							' . $objDatabase->SqlVariable($this->fltProductHeight) . ',
							' . $objDatabase->SqlVariable($this->fltProductLength) . ',
							' . $objDatabase->SqlVariable($this->fltProductWidth) . ',
							' . $objDatabase->SqlVariable($this->fltProductWeight) . ',
							' . $objDatabase->SqlVariable($this->intFkTaxStatusId) . ',
							' . $objDatabase->SqlVariable($this->fltSell) . ',
							' . $objDatabase->SqlVariable($this->fltSellTaxInclusive) . ',
							' . $objDatabase->SqlVariable($this->fltSellWeb) . ',
							' . $objDatabase->SqlVariable($this->strUpc) . ',
							' . $objDatabase->SqlVariable($this->blnWeb) . ',
							' . $objDatabase->SqlVariable($this->strWebKeyword1) . ',
							' . $objDatabase->SqlVariable($this->strWebKeyword2) . ',
							' . $objDatabase->SqlVariable($this->strWebKeyword3) . ',
							' . $objDatabase->SqlVariable($this->strMetaDesc) . ',
							' . $objDatabase->SqlVariable($this->strMetaKeyword) . ',
							' . $objDatabase->SqlVariable($this->blnFeatured) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_product', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_product`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Product');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_product`
						SET
							`name` = ' . $objDatabase->SqlVariable($this->strName) . ',
							`image_id` = ' . $objDatabase->SqlVariable($this->intImageId) . ',
							`class_name` = ' . $objDatabase->SqlVariable($this->strClassName) . ',
							`code` = ' . $objDatabase->SqlVariable($this->strCode) . ',
							`current` = ' . $objDatabase->SqlVariable($this->blnCurrent) . ',
							`description` = ' . $objDatabase->SqlVariable($this->strDescription) . ',
							`description_short` = ' . $objDatabase->SqlVariable($this->strDescriptionShort) . ',
							`family` = ' . $objDatabase->SqlVariable($this->strFamily) . ',
							`gift_card` = ' . $objDatabase->SqlVariable($this->blnGiftCard) . ',
							`inventoried` = ' . $objDatabase->SqlVariable($this->blnInventoried) . ',
							`inventory` = ' . $objDatabase->SqlVariable($this->fltInventory) . ',
							`inventory_total` = ' . $objDatabase->SqlVariable($this->fltInventoryTotal) . ',
							`master_model` = ' . $objDatabase->SqlVariable($this->blnMasterModel) . ',
							`fk_product_master_id` = ' . $objDatabase->SqlVariable($this->intFkProductMasterId) . ',
							`product_size` = ' . $objDatabase->SqlVariable($this->strProductSize) . ',
							`product_color` = ' . $objDatabase->SqlVariable($this->strProductColor) . ',
							`product_height` = ' . $objDatabase->SqlVariable($this->fltProductHeight) . ',
							`product_length` = ' . $objDatabase->SqlVariable($this->fltProductLength) . ',
							`product_width` = ' . $objDatabase->SqlVariable($this->fltProductWidth) . ',
							`product_weight` = ' . $objDatabase->SqlVariable($this->fltProductWeight) . ',
							`fk_tax_status_id` = ' . $objDatabase->SqlVariable($this->intFkTaxStatusId) . ',
							`sell` = ' . $objDatabase->SqlVariable($this->fltSell) . ',
							`sell_tax_inclusive` = ' . $objDatabase->SqlVariable($this->fltSellTaxInclusive) . ',
							`sell_web` = ' . $objDatabase->SqlVariable($this->fltSellWeb) . ',
							`upc` = ' . $objDatabase->SqlVariable($this->strUpc) . ',
							`web` = ' . $objDatabase->SqlVariable($this->blnWeb) . ',
							`web_keyword1` = ' . $objDatabase->SqlVariable($this->strWebKeyword1) . ',
							`web_keyword2` = ' . $objDatabase->SqlVariable($this->strWebKeyword2) . ',
							`web_keyword3` = ' . $objDatabase->SqlVariable($this->strWebKeyword3) . ',
							`meta_desc` = ' . $objDatabase->SqlVariable($this->strMetaDesc) . ',
							`meta_keyword` = ' . $objDatabase->SqlVariable($this->strMetaKeyword) . ',
							`featured` = ' . $objDatabase->SqlVariable($this->blnFeatured) . ',
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
					`xlsws_product`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Product
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Product with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Products
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product`');
		}

		/**
		 * Truncate xlsws_product table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_product`');
		}

		/**
		 * Reload this Product from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Product object.');

			// Reload the Object
			$objReloaded = Product::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strName = $objReloaded->strName;
			$this->intImageId = $objReloaded->intImageId;
			$this->strClassName = $objReloaded->strClassName;
			$this->strCode = $objReloaded->strCode;
			$this->blnCurrent = $objReloaded->blnCurrent;
			$this->strDescription = $objReloaded->strDescription;
			$this->strDescriptionShort = $objReloaded->strDescriptionShort;
			$this->strFamily = $objReloaded->strFamily;
			$this->blnGiftCard = $objReloaded->blnGiftCard;
			$this->blnInventoried = $objReloaded->blnInventoried;
			$this->fltInventory = $objReloaded->fltInventory;
			$this->fltInventoryTotal = $objReloaded->fltInventoryTotal;
			$this->blnMasterModel = $objReloaded->blnMasterModel;
			$this->FkProductMasterId = $objReloaded->FkProductMasterId;
			$this->strProductSize = $objReloaded->strProductSize;
			$this->strProductColor = $objReloaded->strProductColor;
			$this->fltProductHeight = $objReloaded->fltProductHeight;
			$this->fltProductLength = $objReloaded->fltProductLength;
			$this->fltProductWidth = $objReloaded->fltProductWidth;
			$this->fltProductWeight = $objReloaded->fltProductWeight;
			$this->FkTaxStatusId = $objReloaded->FkTaxStatusId;
			$this->fltSell = $objReloaded->fltSell;
			$this->fltSellTaxInclusive = $objReloaded->fltSellTaxInclusive;
			$this->fltSellWeb = $objReloaded->fltSellWeb;
			$this->strUpc = $objReloaded->strUpc;
			$this->blnWeb = $objReloaded->blnWeb;
			$this->strWebKeyword1 = $objReloaded->strWebKeyword1;
			$this->strWebKeyword2 = $objReloaded->strWebKeyword2;
			$this->strWebKeyword3 = $objReloaded->strWebKeyword3;
			$this->strMetaDesc = $objReloaded->strMetaDesc;
			$this->strMetaKeyword = $objReloaded->strMetaKeyword;
			$this->blnFeatured = $objReloaded->blnFeatured;
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

				case 'Name':
					// Gets the value for strName (Not Null)
					// @return string
					return $this->strName;

				case 'ImageId':
					// Gets the value for intImageId 
					// @return integer
					return $this->intImageId;

				case 'ClassName':
					// Gets the value for strClassName 
					// @return string
					return $this->strClassName;

				case 'Code':
					// Gets the value for strCode (Unique)
					// @return string
					return $this->strCode;

				case 'Current':
					// Gets the value for blnCurrent 
					// @return boolean
					return $this->blnCurrent;

				case 'Description':
					// Gets the value for strDescription 
					// @return string
					return $this->strDescription;

				case 'DescriptionShort':
					// Gets the value for strDescriptionShort 
					// @return string
					return $this->strDescriptionShort;

				case 'Family':
					// Gets the value for strFamily 
					// @return string
					return $this->strFamily;

				case 'GiftCard':
					// Gets the value for blnGiftCard 
					// @return boolean
					return $this->blnGiftCard;

				case 'Inventoried':
					// Gets the value for blnInventoried 
					// @return boolean
					return $this->blnInventoried;

				case 'Inventory':
					// Gets the value for fltInventory 
					// @return double
					return $this->fltInventory;

				case 'InventoryTotal':
					// Gets the value for fltInventoryTotal 
					// @return double
					return $this->fltInventoryTotal;

				case 'MasterModel':
					// Gets the value for blnMasterModel 
					// @return boolean
					return $this->blnMasterModel;

				case 'FkProductMasterId':
					// Gets the value for intFkProductMasterId 
					// @return integer
					return $this->intFkProductMasterId;

				case 'ProductSize':
					// Gets the value for strProductSize 
					// @return string
					return $this->strProductSize;

				case 'ProductColor':
					// Gets the value for strProductColor 
					// @return string
					return $this->strProductColor;

				case 'ProductHeight':
					// Gets the value for fltProductHeight 
					// @return double
					return $this->fltProductHeight;

				case 'ProductLength':
					// Gets the value for fltProductLength 
					// @return double
					return $this->fltProductLength;

				case 'ProductWidth':
					// Gets the value for fltProductWidth 
					// @return double
					return $this->fltProductWidth;

				case 'ProductWeight':
					// Gets the value for fltProductWeight 
					// @return double
					return $this->fltProductWeight;

				case 'FkTaxStatusId':
					// Gets the value for intFkTaxStatusId 
					// @return integer
					return $this->intFkTaxStatusId;

				case 'Sell':
					// Gets the value for fltSell 
					// @return double
					return $this->fltSell;

				case 'SellTaxInclusive':
					// Gets the value for fltSellTaxInclusive 
					// @return double
					return $this->fltSellTaxInclusive;

				case 'SellWeb':
					// Gets the value for fltSellWeb 
					// @return double
					return $this->fltSellWeb;

				case 'Upc':
					// Gets the value for strUpc 
					// @return string
					return $this->strUpc;

				case 'Web':
					// Gets the value for blnWeb 
					// @return boolean
					return $this->blnWeb;

				case 'WebKeyword1':
					// Gets the value for strWebKeyword1 
					// @return string
					return $this->strWebKeyword1;

				case 'WebKeyword2':
					// Gets the value for strWebKeyword2 
					// @return string
					return $this->strWebKeyword2;

				case 'WebKeyword3':
					// Gets the value for strWebKeyword3 
					// @return string
					return $this->strWebKeyword3;

				case 'MetaDesc':
					// Gets the value for strMetaDesc 
					// @return string
					return $this->strMetaDesc;

				case 'MetaKeyword':
					// Gets the value for strMetaKeyword 
					// @return string
					return $this->strMetaKeyword;

				case 'Featured':
					// Gets the value for blnFeatured (Not Null)
					// @return boolean
					return $this->blnFeatured;

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
				case 'FkProductMaster':
					// Gets the value for the Product object referenced by intFkProductMasterId 
					// @return Product
					try {
						if ((!$this->objFkProductMaster) && (!is_null($this->intFkProductMasterId)))
							$this->objFkProductMaster = Product::Load($this->intFkProductMasterId);
						return $this->objFkProductMaster;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FkTaxStatus':
					// Gets the value for the TaxStatus object referenced by intFkTaxStatusId 
					// @return TaxStatus
					try {
						if ((!$this->objFkTaxStatus) && (!is_null($this->intFkTaxStatusId)))
							$this->objFkTaxStatus = TaxStatus::Load($this->intFkTaxStatusId);
						return $this->objFkTaxStatus;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_Category':
					// Gets the value for the private _objCategory (Read-Only)
					// if set due to an expansion on the xlsws_product_category_assn association table
					// @return Category
					return $this->_objCategory;

				case '_CategoryArray':
					// Gets the value for the private _objCategoryArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_category_assn association table
					// @return Category[]
					return (array) $this->_objCategoryArray;

				case '_ImagesAsImage':
					// Gets the value for the private _objImagesAsImage (Read-Only)
					// if set due to an expansion on the xlsws_product_image_assn association table
					// @return Images
					return $this->_objImagesAsImage;

				case '_ImagesAsImageArray':
					// Gets the value for the private _objImagesAsImageArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_image_assn association table
					// @return Images[]
					return (array) $this->_objImagesAsImageArray;

				case '_CartItem':
					// Gets the value for the private _objCartItem (Read-Only)
					// if set due to an expansion on the xlsws_cart_item.product_id reverse relationship
					// @return CartItem
					return $this->_objCartItem;

				case '_CartItemArray':
					// Gets the value for the private _objCartItemArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_cart_item.product_id reverse relationship
					// @return CartItem[]
					return (array) $this->_objCartItemArray;

				case '_GiftRegistryItems':
					// Gets the value for the private _objGiftRegistryItems (Read-Only)
					// if set due to an expansion on the xlsws_gift_registry_items.product_id reverse relationship
					// @return GiftRegistryItems
					return $this->_objGiftRegistryItems;

				case '_GiftRegistryItemsArray':
					// Gets the value for the private _objGiftRegistryItemsArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_gift_registry_items.product_id reverse relationship
					// @return GiftRegistryItems[]
					return (array) $this->_objGiftRegistryItemsArray;

				case '_ProductAsFkMaster':
					// Gets the value for the private _objProductAsFkMaster (Read-Only)
					// if set due to an expansion on the xlsws_product.fk_product_master_id reverse relationship
					// @return Product
					return $this->_objProductAsFkMaster;

				case '_ProductAsFkMasterArray':
					// Gets the value for the private _objProductAsFkMasterArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product.fk_product_master_id reverse relationship
					// @return Product[]
					return (array) $this->_objProductAsFkMasterArray;

				case '_ProductQtyPricing':
					// Gets the value for the private _objProductQtyPricing (Read-Only)
					// if set due to an expansion on the xlsws_product_qty_pricing.product_id reverse relationship
					// @return ProductQtyPricing
					return $this->_objProductQtyPricing;

				case '_ProductQtyPricingArray':
					// Gets the value for the private _objProductQtyPricingArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_qty_pricing.product_id reverse relationship
					// @return ProductQtyPricing[]
					return (array) $this->_objProductQtyPricingArray;

				case '_ProductRelated':
					// Gets the value for the private _objProductRelated (Read-Only)
					// if set due to an expansion on the xlsws_product_related.product_id reverse relationship
					// @return ProductRelated
					return $this->_objProductRelated;

				case '_ProductRelatedArray':
					// Gets the value for the private _objProductRelatedArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_related.product_id reverse relationship
					// @return ProductRelated[]
					return (array) $this->_objProductRelatedArray;

				case '_ProductRelatedAsRelated':
					// Gets the value for the private _objProductRelatedAsRelated (Read-Only)
					// if set due to an expansion on the xlsws_product_related.related_id reverse relationship
					// @return ProductRelated
					return $this->_objProductRelatedAsRelated;

				case '_ProductRelatedAsRelatedArray':
					// Gets the value for the private _objProductRelatedAsRelatedArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_product_related.related_id reverse relationship
					// @return ProductRelated[]
					return (array) $this->_objProductRelatedAsRelatedArray;


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
				case 'Name':
					// Sets the value for strName (Not Null)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ImageId':
					// Sets the value for intImageId 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intImageId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ClassName':
					// Sets the value for strClassName 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strClassName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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

				case 'Current':
					// Sets the value for blnCurrent 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnCurrent = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Description':
					// Sets the value for strDescription 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strDescription = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DescriptionShort':
					// Sets the value for strDescriptionShort 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strDescriptionShort = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Family':
					// Sets the value for strFamily 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strFamily = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'GiftCard':
					// Sets the value for blnGiftCard 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnGiftCard = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Inventoried':
					// Sets the value for blnInventoried 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnInventoried = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Inventory':
					// Sets the value for fltInventory 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltInventory = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'InventoryTotal':
					// Sets the value for fltInventoryTotal 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltInventoryTotal = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MasterModel':
					// Sets the value for blnMasterModel 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnMasterModel = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FkProductMasterId':
					// Sets the value for intFkProductMasterId 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objFkProductMaster = null;
						return ($this->intFkProductMasterId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProductSize':
					// Sets the value for strProductSize 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strProductSize = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProductColor':
					// Sets the value for strProductColor 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strProductColor = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProductHeight':
					// Sets the value for fltProductHeight 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltProductHeight = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProductLength':
					// Sets the value for fltProductLength 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltProductLength = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProductWidth':
					// Sets the value for fltProductWidth 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltProductWidth = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ProductWeight':
					// Sets the value for fltProductWeight 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltProductWeight = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FkTaxStatusId':
					// Sets the value for intFkTaxStatusId 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objFkTaxStatus = null;
						return ($this->intFkTaxStatusId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Sell':
					// Sets the value for fltSell 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltSell = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SellTaxInclusive':
					// Sets the value for fltSellTaxInclusive 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltSellTaxInclusive = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SellWeb':
					// Sets the value for fltSellWeb 
					// @param double $mixValue
					// @return double
					try {
						return ($this->fltSellWeb = QType::Cast($mixValue, QType::Float));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Upc':
					// Sets the value for strUpc 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strUpc = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Web':
					// Sets the value for blnWeb 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnWeb = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WebKeyword1':
					// Sets the value for strWebKeyword1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strWebKeyword1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WebKeyword2':
					// Sets the value for strWebKeyword2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strWebKeyword2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'WebKeyword3':
					// Sets the value for strWebKeyword3 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strWebKeyword3 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MetaDesc':
					// Sets the value for strMetaDesc 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMetaDesc = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'MetaKeyword':
					// Sets the value for strMetaKeyword 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMetaKeyword = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Featured':
					// Sets the value for blnFeatured (Not Null)
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnFeatured = QType::Cast($mixValue, QType::Boolean));
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
				case 'FkProductMaster':
					// Sets the value for the Product object referenced by intFkProductMasterId 
					// @param Product $mixValue
					// @return Product
					if (is_null($mixValue)) {
						$this->intFkProductMasterId = null;
						$this->objFkProductMaster = null;
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
							throw new QCallerException('Unable to set an unsaved FkProductMaster for this Product');

						// Update Local Member Variables
						$this->objFkProductMaster = $mixValue;
						$this->intFkProductMasterId = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				case 'FkTaxStatus':
					// Sets the value for the TaxStatus object referenced by intFkTaxStatusId 
					// @param TaxStatus $mixValue
					// @return TaxStatus
					if (is_null($mixValue)) {
						$this->intFkTaxStatusId = null;
						$this->objFkTaxStatus = null;
						return null;
					} else {
						// Make sure $mixValue actually is a TaxStatus object
						try {
							$mixValue = QType::Cast($mixValue, 'TaxStatus');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED TaxStatus object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved FkTaxStatus for this Product');

						// Update Local Member Variables
						$this->objFkTaxStatus = $mixValue;
						$this->intFkTaxStatusId = $mixValue->Rowid;

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

			
		
		// Related Objects' Methods for CartItem
		//-------------------------------------------------------------------

		/**
		 * Gets all associated CartItems as an array of CartItem objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return CartItem[]
		*/ 
		public function GetCartItemArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return CartItem::LoadArrayByProductId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated CartItems
		 * @return int
		*/ 
		public function CountCartItems() {
			if ((is_null($this->intRowid)))
				return 0;

			return CartItem::CountByProductId($this->intRowid);
		}

		/**
		 * Associates a CartItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function AssociateCartItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartItem on this unsaved Product.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartItem on this Product with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . '
			');
		}

		/**
		 * Unassociates a CartItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function UnassociateCartItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Product.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this Product with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`product_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all CartItems
		 * @return void
		*/ 
		public function UnassociateAllCartItems() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`product_id` = null
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated CartItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function DeleteAssociatedCartItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Product.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this Product with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated CartItems
		 * @return void
		*/ 
		public function DeleteAllCartItems() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for GiftRegistryItems
		//-------------------------------------------------------------------

		/**
		 * Gets all associated GiftRegistryItemses as an array of GiftRegistryItems objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryItems[]
		*/ 
		public function GetGiftRegistryItemsArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return GiftRegistryItems::LoadArrayByProductId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated GiftRegistryItemses
		 * @return int
		*/ 
		public function CountGiftRegistryItemses() {
			if ((is_null($this->intRowid)))
				return 0;

			return GiftRegistryItems::CountByProductId($this->intRowid);
		}

		/**
		 * Associates a GiftRegistryItems
		 * @param GiftRegistryItems $objGiftRegistryItems
		 * @return void
		*/ 
		public function AssociateGiftRegistryItems(GiftRegistryItems $objGiftRegistryItems) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryItems on this unsaved Product.');
			if ((is_null($objGiftRegistryItems->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryItems on this Product with an unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_items`
				SET
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryItems->Rowid) . '
			');
		}

		/**
		 * Unassociates a GiftRegistryItems
		 * @param GiftRegistryItems $objGiftRegistryItems
		 * @return void
		*/ 
		public function UnassociateGiftRegistryItems(GiftRegistryItems $objGiftRegistryItems) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItems on this unsaved Product.');
			if ((is_null($objGiftRegistryItems->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItems on this Product with an unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_items`
				SET
					`product_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryItems->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all GiftRegistryItemses
		 * @return void
		*/ 
		public function UnassociateAllGiftRegistryItemses() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItems on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_items`
				SET
					`product_id` = null
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated GiftRegistryItems
		 * @param GiftRegistryItems $objGiftRegistryItems
		 * @return void
		*/ 
		public function DeleteAssociatedGiftRegistryItems(GiftRegistryItems $objGiftRegistryItems) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItems on this unsaved Product.');
			if ((is_null($objGiftRegistryItems->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItems on this Product with an unsaved GiftRegistryItems.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_items`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryItems->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated GiftRegistryItemses
		 * @return void
		*/ 
		public function DeleteAllGiftRegistryItemses() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryItems on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_items`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for ProductAsFkMaster
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ProductsAsFkMaster as an array of Product objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Product[]
		*/ 
		public function GetProductAsFkMasterArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Product::LoadArrayByFkProductMasterId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ProductsAsFkMaster
		 * @return int
		*/ 
		public function CountProductsAsFkMaster() {
			if ((is_null($this->intRowid)))
				return 0;

			return Product::CountByFkProductMasterId($this->intRowid);
		}

		/**
		 * Associates a ProductAsFkMaster
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function AssociateProductAsFkMaster(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductAsFkMaster on this unsaved Product.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductAsFkMaster on this Product with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product`
				SET
					`fk_product_master_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . '
			');
		}

		/**
		 * Unassociates a ProductAsFkMaster
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function UnassociateProductAsFkMaster(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFkMaster on this unsaved Product.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFkMaster on this Product with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product`
				SET
					`fk_product_master_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . ' AND
					`fk_product_master_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ProductsAsFkMaster
		 * @return void
		*/ 
		public function UnassociateAllProductsAsFkMaster() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFkMaster on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product`
				SET
					`fk_product_master_id` = null
				WHERE
					`fk_product_master_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ProductAsFkMaster
		 * @param Product $objProduct
		 * @return void
		*/ 
		public function DeleteAssociatedProductAsFkMaster(Product $objProduct) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFkMaster on this unsaved Product.');
			if ((is_null($objProduct->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFkMaster on this Product with an unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProduct->Rowid) . ' AND
					`fk_product_master_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ProductsAsFkMaster
		 * @return void
		*/ 
		public function DeleteAllProductsAsFkMaster() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductAsFkMaster on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product`
				WHERE
					`fk_product_master_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for ProductQtyPricing
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ProductQtyPricings as an array of ProductQtyPricing objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductQtyPricing[]
		*/ 
		public function GetProductQtyPricingArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return ProductQtyPricing::LoadArrayByProductId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ProductQtyPricings
		 * @return int
		*/ 
		public function CountProductQtyPricings() {
			if ((is_null($this->intRowid)))
				return 0;

			return ProductQtyPricing::CountByProductId($this->intRowid);
		}

		/**
		 * Associates a ProductQtyPricing
		 * @param ProductQtyPricing $objProductQtyPricing
		 * @return void
		*/ 
		public function AssociateProductQtyPricing(ProductQtyPricing $objProductQtyPricing) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductQtyPricing on this unsaved Product.');
			if ((is_null($objProductQtyPricing->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductQtyPricing on this Product with an unsaved ProductQtyPricing.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_qty_pricing`
				SET
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductQtyPricing->Rowid) . '
			');
		}

		/**
		 * Unassociates a ProductQtyPricing
		 * @param ProductQtyPricing $objProductQtyPricing
		 * @return void
		*/ 
		public function UnassociateProductQtyPricing(ProductQtyPricing $objProductQtyPricing) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductQtyPricing on this unsaved Product.');
			if ((is_null($objProductQtyPricing->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductQtyPricing on this Product with an unsaved ProductQtyPricing.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_qty_pricing`
				SET
					`product_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductQtyPricing->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ProductQtyPricings
		 * @return void
		*/ 
		public function UnassociateAllProductQtyPricings() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductQtyPricing on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_qty_pricing`
				SET
					`product_id` = null
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ProductQtyPricing
		 * @param ProductQtyPricing $objProductQtyPricing
		 * @return void
		*/ 
		public function DeleteAssociatedProductQtyPricing(ProductQtyPricing $objProductQtyPricing) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductQtyPricing on this unsaved Product.');
			if ((is_null($objProductQtyPricing->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductQtyPricing on this Product with an unsaved ProductQtyPricing.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_qty_pricing`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductQtyPricing->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ProductQtyPricings
		 * @return void
		*/ 
		public function DeleteAllProductQtyPricings() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductQtyPricing on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_qty_pricing`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for ProductRelated
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ProductRelateds as an array of ProductRelated objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductRelated[]
		*/ 
		public function GetProductRelatedArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return ProductRelated::LoadArrayByProductId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ProductRelateds
		 * @return int
		*/ 
		public function CountProductRelateds() {
			if ((is_null($this->intRowid)))
				return 0;

			return ProductRelated::CountByProductId($this->intRowid);
		}

		/**
		 * Associates a ProductRelated
		 * @param ProductRelated $objProductRelated
		 * @return void
		*/ 
		public function AssociateProductRelated(ProductRelated $objProductRelated) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductRelated on this unsaved Product.');
			if ((is_null($objProductRelated->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductRelated on this Product with an unsaved ProductRelated.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_related`
				SET
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductRelated->Rowid) . '
			');
		}

		/**
		 * Unassociates a ProductRelated
		 * @param ProductRelated $objProductRelated
		 * @return void
		*/ 
		public function UnassociateProductRelated(ProductRelated $objProductRelated) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelated on this unsaved Product.');
			if ((is_null($objProductRelated->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelated on this Product with an unsaved ProductRelated.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_related`
				SET
					`product_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductRelated->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ProductRelateds
		 * @return void
		*/ 
		public function UnassociateAllProductRelateds() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelated on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_related`
				SET
					`product_id` = null
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ProductRelated
		 * @param ProductRelated $objProductRelated
		 * @return void
		*/ 
		public function DeleteAssociatedProductRelated(ProductRelated $objProductRelated) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelated on this unsaved Product.');
			if ((is_null($objProductRelated->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelated on this Product with an unsaved ProductRelated.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_related`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductRelated->Rowid) . ' AND
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ProductRelateds
		 * @return void
		*/ 
		public function DeleteAllProductRelateds() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelated on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_related`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for ProductRelatedAsRelated
		//-------------------------------------------------------------------

		/**
		 * Gets all associated ProductRelatedsAsRelated as an array of ProductRelated objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return ProductRelated[]
		*/ 
		public function GetProductRelatedAsRelatedArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return ProductRelated::LoadArrayByRelatedId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated ProductRelatedsAsRelated
		 * @return int
		*/ 
		public function CountProductRelatedsAsRelated() {
			if ((is_null($this->intRowid)))
				return 0;

			return ProductRelated::CountByRelatedId($this->intRowid);
		}

		/**
		 * Associates a ProductRelatedAsRelated
		 * @param ProductRelated $objProductRelated
		 * @return void
		*/ 
		public function AssociateProductRelatedAsRelated(ProductRelated $objProductRelated) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductRelatedAsRelated on this unsaved Product.');
			if ((is_null($objProductRelated->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateProductRelatedAsRelated on this Product with an unsaved ProductRelated.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_related`
				SET
					`related_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductRelated->Rowid) . '
			');
		}

		/**
		 * Unassociates a ProductRelatedAsRelated
		 * @param ProductRelated $objProductRelated
		 * @return void
		*/ 
		public function UnassociateProductRelatedAsRelated(ProductRelated $objProductRelated) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelatedAsRelated on this unsaved Product.');
			if ((is_null($objProductRelated->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelatedAsRelated on this Product with an unsaved ProductRelated.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_related`
				SET
					`related_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductRelated->Rowid) . ' AND
					`related_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all ProductRelatedsAsRelated
		 * @return void
		*/ 
		public function UnassociateAllProductRelatedsAsRelated() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelatedAsRelated on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_product_related`
				SET
					`related_id` = null
				WHERE
					`related_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated ProductRelatedAsRelated
		 * @param ProductRelated $objProductRelated
		 * @return void
		*/ 
		public function DeleteAssociatedProductRelatedAsRelated(ProductRelated $objProductRelated) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelatedAsRelated on this unsaved Product.');
			if ((is_null($objProductRelated->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelatedAsRelated on this Product with an unsaved ProductRelated.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_related`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objProductRelated->Rowid) . ' AND
					`related_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated ProductRelatedsAsRelated
		 * @return void
		*/ 
		public function DeleteAllProductRelatedsAsRelated() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateProductRelatedAsRelated on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_related`
				WHERE
					`related_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		// Related Many-to-Many Objects' Methods for Category
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated Categories as an array of Category objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Category[]
		*/ 
		public function GetCategoryArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Category::LoadArrayByProduct($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated Categories
		 * @return int
		*/ 
		public function CountCategories() {
			if ((is_null($this->intRowid)))
				return 0;

			return Category::CountByProduct($this->intRowid);
		}

		/**
		 * Checks to see if an association exists with a specific Category
		 * @param Category $objCategory
		 * @return bool
		*/
		public function IsCategoryAssociated(Category $objCategory) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsCategoryAssociated on this unsaved Product.');
			if ((is_null($objCategory->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsCategoryAssociated on this Product with an unsaved Category.');

			$intRowCount = Product::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Product()->Rowid, $this->intRowid),
					QQ::Equal(QQN::Product()->Category->CategoryId, $objCategory->Rowid)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a Category
		 * @param Category $objCategory
		 * @return void
		*/ 
		public function AssociateCategory(Category $objCategory) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCategory on this unsaved Product.');
			if ((is_null($objCategory->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCategory on this Product with an unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `xlsws_product_category_assn` (
					`product_id`,
					`category_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intRowid) . ',
					' . $objDatabase->SqlVariable($objCategory->Rowid) . '
				)
			');
		}

		/**
		 * Unassociates a Category
		 * @param Category $objCategory
		 * @return void
		*/ 
		public function UnassociateCategory(Category $objCategory) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCategory on this unsaved Product.');
			if ((is_null($objCategory->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCategory on this Product with an unsaved Category.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_category_assn`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . ' AND
					`category_id` = ' . $objDatabase->SqlVariable($objCategory->Rowid) . '
			');
		}

		/**
		 * Unassociates all Categories
		 * @return void
		*/ 
		public function UnassociateAllCategories() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllCategoryArray on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_category_assn`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}
			
		// Related Many-to-Many Objects' Methods for ImagesAsImage
		//-------------------------------------------------------------------

		/**
		 * Gets all many-to-many associated ImagesesAsImage as an array of Images objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Images[]
		*/ 
		public function GetImagesAsImageArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Images::LoadArrayByProductAsImage($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all many-to-many associated ImagesesAsImage
		 * @return int
		*/ 
		public function CountImagesesAsImage() {
			if ((is_null($this->intRowid)))
				return 0;

			return Images::CountByProductAsImage($this->intRowid);
		}

		/**
		 * Checks to see if an association exists with a specific ImagesAsImage
		 * @param Images $objImages
		 * @return bool
		*/
		public function IsImagesAsImageAssociated(Images $objImages) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsImagesAsImageAssociated on this unsaved Product.');
			if ((is_null($objImages->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call IsImagesAsImageAssociated on this Product with an unsaved Images.');

			$intRowCount = Product::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::Product()->Rowid, $this->intRowid),
					QQ::Equal(QQN::Product()->ImagesAsImage->ImageId, $objImages->Rowid)
				)
			);

			return ($intRowCount > 0);
		}

		/**
		 * Associates a ImagesAsImage
		 * @param Images $objImages
		 * @return void
		*/ 
		public function AssociateImagesAsImage(Images $objImages) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateImagesAsImage on this unsaved Product.');
			if ((is_null($objImages->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateImagesAsImage on this Product with an unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				INSERT INTO `xlsws_product_image_assn` (
					`product_id`,
					`image_id`
				) VALUES (
					' . $objDatabase->SqlVariable($this->intRowid) . ',
					' . $objDatabase->SqlVariable($objImages->Rowid) . '
				)
			');
		}

		/**
		 * Unassociates a ImagesAsImage
		 * @param Images $objImages
		 * @return void
		*/ 
		public function UnassociateImagesAsImage(Images $objImages) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateImagesAsImage on this unsaved Product.');
			if ((is_null($objImages->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateImagesAsImage on this Product with an unsaved Images.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_image_assn`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . ' AND
					`image_id` = ' . $objDatabase->SqlVariable($objImages->Rowid) . '
			');
		}

		/**
		 * Unassociates all ImagesesAsImage
		 * @return void
		*/ 
		public function UnassociateAllImagesesAsImage() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateAllImagesAsImageArray on this unsaved Product.');

			// Get the Database Object for this Class
			$objDatabase = Product::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_product_image_assn`
				WHERE
					`product_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}




		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Product"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Name" type="xsd:string"/>';
			$strToReturn .= '<element name="ImageId" type="xsd:int"/>';
			$strToReturn .= '<element name="ClassName" type="xsd:string"/>';
			$strToReturn .= '<element name="Code" type="xsd:string"/>';
			$strToReturn .= '<element name="Current" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Description" type="xsd:string"/>';
			$strToReturn .= '<element name="DescriptionShort" type="xsd:string"/>';
			$strToReturn .= '<element name="Family" type="xsd:string"/>';
			$strToReturn .= '<element name="GiftCard" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Inventoried" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Inventory" type="xsd:float"/>';
			$strToReturn .= '<element name="InventoryTotal" type="xsd:float"/>';
			$strToReturn .= '<element name="MasterModel" type="xsd:boolean"/>';
			$strToReturn .= '<element name="FkProductMaster" type="xsd1:Product"/>';
			$strToReturn .= '<element name="ProductSize" type="xsd:string"/>';
			$strToReturn .= '<element name="ProductColor" type="xsd:string"/>';
			$strToReturn .= '<element name="ProductHeight" type="xsd:float"/>';
			$strToReturn .= '<element name="ProductLength" type="xsd:float"/>';
			$strToReturn .= '<element name="ProductWidth" type="xsd:float"/>';
			$strToReturn .= '<element name="ProductWeight" type="xsd:float"/>';
			$strToReturn .= '<element name="FkTaxStatus" type="xsd1:TaxStatus"/>';
			$strToReturn .= '<element name="Sell" type="xsd:float"/>';
			$strToReturn .= '<element name="SellTaxInclusive" type="xsd:float"/>';
			$strToReturn .= '<element name="SellWeb" type="xsd:float"/>';
			$strToReturn .= '<element name="Upc" type="xsd:string"/>';
			$strToReturn .= '<element name="Web" type="xsd:boolean"/>';
			$strToReturn .= '<element name="WebKeyword1" type="xsd:string"/>';
			$strToReturn .= '<element name="WebKeyword2" type="xsd:string"/>';
			$strToReturn .= '<element name="WebKeyword3" type="xsd:string"/>';
			$strToReturn .= '<element name="MetaDesc" type="xsd:string"/>';
			$strToReturn .= '<element name="MetaKeyword" type="xsd:string"/>';
			$strToReturn .= '<element name="Featured" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Product', $strComplexTypeArray)) {
				$strComplexTypeArray['Product'] = Product::GetSoapComplexTypeXml();
				Product::AlterSoapComplexTypeArray($strComplexTypeArray);
				TaxStatus::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Product::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Product();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Name'))
				$objToReturn->strName = $objSoapObject->Name;
			if (property_exists($objSoapObject, 'ImageId'))
				$objToReturn->intImageId = $objSoapObject->ImageId;
			if (property_exists($objSoapObject, 'ClassName'))
				$objToReturn->strClassName = $objSoapObject->ClassName;
			if (property_exists($objSoapObject, 'Code'))
				$objToReturn->strCode = $objSoapObject->Code;
			if (property_exists($objSoapObject, 'Current'))
				$objToReturn->blnCurrent = $objSoapObject->Current;
			if (property_exists($objSoapObject, 'Description'))
				$objToReturn->strDescription = $objSoapObject->Description;
			if (property_exists($objSoapObject, 'DescriptionShort'))
				$objToReturn->strDescriptionShort = $objSoapObject->DescriptionShort;
			if (property_exists($objSoapObject, 'Family'))
				$objToReturn->strFamily = $objSoapObject->Family;
			if (property_exists($objSoapObject, 'GiftCard'))
				$objToReturn->blnGiftCard = $objSoapObject->GiftCard;
			if (property_exists($objSoapObject, 'Inventoried'))
				$objToReturn->blnInventoried = $objSoapObject->Inventoried;
			if (property_exists($objSoapObject, 'Inventory'))
				$objToReturn->fltInventory = $objSoapObject->Inventory;
			if (property_exists($objSoapObject, 'InventoryTotal'))
				$objToReturn->fltInventoryTotal = $objSoapObject->InventoryTotal;
			if (property_exists($objSoapObject, 'MasterModel'))
				$objToReturn->blnMasterModel = $objSoapObject->MasterModel;
			if ((property_exists($objSoapObject, 'FkProductMaster')) &&
				($objSoapObject->FkProductMaster))
				$objToReturn->FkProductMaster = Product::GetObjectFromSoapObject($objSoapObject->FkProductMaster);
			if (property_exists($objSoapObject, 'ProductSize'))
				$objToReturn->strProductSize = $objSoapObject->ProductSize;
			if (property_exists($objSoapObject, 'ProductColor'))
				$objToReturn->strProductColor = $objSoapObject->ProductColor;
			if (property_exists($objSoapObject, 'ProductHeight'))
				$objToReturn->fltProductHeight = $objSoapObject->ProductHeight;
			if (property_exists($objSoapObject, 'ProductLength'))
				$objToReturn->fltProductLength = $objSoapObject->ProductLength;
			if (property_exists($objSoapObject, 'ProductWidth'))
				$objToReturn->fltProductWidth = $objSoapObject->ProductWidth;
			if (property_exists($objSoapObject, 'ProductWeight'))
				$objToReturn->fltProductWeight = $objSoapObject->ProductWeight;
			if ((property_exists($objSoapObject, 'FkTaxStatus')) &&
				($objSoapObject->FkTaxStatus))
				$objToReturn->FkTaxStatus = TaxStatus::GetObjectFromSoapObject($objSoapObject->FkTaxStatus);
			if (property_exists($objSoapObject, 'Sell'))
				$objToReturn->fltSell = $objSoapObject->Sell;
			if (property_exists($objSoapObject, 'SellTaxInclusive'))
				$objToReturn->fltSellTaxInclusive = $objSoapObject->SellTaxInclusive;
			if (property_exists($objSoapObject, 'SellWeb'))
				$objToReturn->fltSellWeb = $objSoapObject->SellWeb;
			if (property_exists($objSoapObject, 'Upc'))
				$objToReturn->strUpc = $objSoapObject->Upc;
			if (property_exists($objSoapObject, 'Web'))
				$objToReturn->blnWeb = $objSoapObject->Web;
			if (property_exists($objSoapObject, 'WebKeyword1'))
				$objToReturn->strWebKeyword1 = $objSoapObject->WebKeyword1;
			if (property_exists($objSoapObject, 'WebKeyword2'))
				$objToReturn->strWebKeyword2 = $objSoapObject->WebKeyword2;
			if (property_exists($objSoapObject, 'WebKeyword3'))
				$objToReturn->strWebKeyword3 = $objSoapObject->WebKeyword3;
			if (property_exists($objSoapObject, 'MetaDesc'))
				$objToReturn->strMetaDesc = $objSoapObject->MetaDesc;
			if (property_exists($objSoapObject, 'MetaKeyword'))
				$objToReturn->strMetaKeyword = $objSoapObject->MetaKeyword;
			if (property_exists($objSoapObject, 'Featured'))
				$objToReturn->blnFeatured = $objSoapObject->Featured;
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
				array_push($objArrayToReturn, Product::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->objFkProductMaster)
				$objObject->objFkProductMaster = Product::GetSoapObjectFromObject($objObject->objFkProductMaster, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intFkProductMasterId = null;
			if ($objObject->objFkTaxStatus)
				$objObject->objFkTaxStatus = TaxStatus::GetSoapObjectFromObject($objObject->objFkTaxStatus, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intFkTaxStatusId = null;
			if ($objObject->dttCreated)
				$objObject->dttCreated = $objObject->dttCreated->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeProductCategory extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'category';

		protected $strTableName = 'xlsws_product_category_assn';
		protected $strPrimaryKey = 'product_id';
		protected $strClassName = 'Category';

		public function __get($strName) {
			switch ($strName) {
				case 'CategoryId':
					return new QQNode('category_id', 'CategoryId', 'integer', $this);
				case 'Category':
					return new QQNodeCategory('category_id', 'CategoryId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodeCategory('category_id', 'CategoryId', 'integer', $this);
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

	class QQNodeProductImagesAsImage extends QQAssociationNode {
		protected $strType = 'association';
		protected $strName = 'imagesasimage';

		protected $strTableName = 'xlsws_product_image_assn';
		protected $strPrimaryKey = 'product_id';
		protected $strClassName = 'Images';

		public function __get($strName) {
			switch ($strName) {
				case 'ImageId':
					return new QQNode('image_id', 'ImageId', 'integer', $this);
				case 'Images':
					return new QQNodeImages('image_id', 'ImageId', 'integer', $this);
				case '_ChildTableNode':
					return new QQNodeImages('image_id', 'ImageId', 'integer', $this);
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

	class QQNodeProduct extends QQNode {
		protected $strTableName = 'xlsws_product';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Product';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'ImageId':
					return new QQNode('image_id', 'ImageId', 'integer', $this);
				case 'ClassName':
					return new QQNode('class_name', 'ClassName', 'string', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Current':
					return new QQNode('current', 'Current', 'boolean', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'DescriptionShort':
					return new QQNode('description_short', 'DescriptionShort', 'string', $this);
				case 'Family':
					return new QQNode('family', 'Family', 'string', $this);
				case 'GiftCard':
					return new QQNode('gift_card', 'GiftCard', 'boolean', $this);
				case 'Inventoried':
					return new QQNode('inventoried', 'Inventoried', 'boolean', $this);
				case 'Inventory':
					return new QQNode('inventory', 'Inventory', 'double', $this);
				case 'InventoryTotal':
					return new QQNode('inventory_total', 'InventoryTotal', 'double', $this);
				case 'MasterModel':
					return new QQNode('master_model', 'MasterModel', 'boolean', $this);
				case 'FkProductMasterId':
					return new QQNode('fk_product_master_id', 'FkProductMasterId', 'integer', $this);
				case 'FkProductMaster':
					return new QQNodeProduct('fk_product_master_id', 'FkProductMaster', 'integer', $this);
				case 'ProductSize':
					return new QQNode('product_size', 'ProductSize', 'string', $this);
				case 'ProductColor':
					return new QQNode('product_color', 'ProductColor', 'string', $this);
				case 'ProductHeight':
					return new QQNode('product_height', 'ProductHeight', 'double', $this);
				case 'ProductLength':
					return new QQNode('product_length', 'ProductLength', 'double', $this);
				case 'ProductWidth':
					return new QQNode('product_width', 'ProductWidth', 'double', $this);
				case 'ProductWeight':
					return new QQNode('product_weight', 'ProductWeight', 'double', $this);
				case 'FkTaxStatusId':
					return new QQNode('fk_tax_status_id', 'FkTaxStatusId', 'integer', $this);
				case 'FkTaxStatus':
					return new QQNodeTaxStatus('fk_tax_status_id', 'FkTaxStatus', 'integer', $this);
				case 'Sell':
					return new QQNode('sell', 'Sell', 'double', $this);
				case 'SellTaxInclusive':
					return new QQNode('sell_tax_inclusive', 'SellTaxInclusive', 'double', $this);
				case 'SellWeb':
					return new QQNode('sell_web', 'SellWeb', 'double', $this);
				case 'Upc':
					return new QQNode('upc', 'Upc', 'string', $this);
				case 'Web':
					return new QQNode('web', 'Web', 'boolean', $this);
				case 'WebKeyword1':
					return new QQNode('web_keyword1', 'WebKeyword1', 'string', $this);
				case 'WebKeyword2':
					return new QQNode('web_keyword2', 'WebKeyword2', 'string', $this);
				case 'WebKeyword3':
					return new QQNode('web_keyword3', 'WebKeyword3', 'string', $this);
				case 'MetaDesc':
					return new QQNode('meta_desc', 'MetaDesc', 'string', $this);
				case 'MetaKeyword':
					return new QQNode('meta_keyword', 'MetaKeyword', 'string', $this);
				case 'Featured':
					return new QQNode('featured', 'Featured', 'boolean', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Category':
					return new QQNodeProductCategory($this);
				case 'ImagesAsImage':
					return new QQNodeProductImagesAsImage($this);
				case 'CartItem':
					return new QQReverseReferenceNodeCartItem($this, 'cartitem', 'reverse_reference', 'product_id');
				case 'GiftRegistryItems':
					return new QQReverseReferenceNodeGiftRegistryItems($this, 'giftregistryitems', 'reverse_reference', 'product_id');
				case 'ProductAsFkMaster':
					return new QQReverseReferenceNodeProduct($this, 'productasfkmaster', 'reverse_reference', 'fk_product_master_id');
				case 'ProductQtyPricing':
					return new QQReverseReferenceNodeProductQtyPricing($this, 'productqtypricing', 'reverse_reference', 'product_id');
				case 'ProductRelated':
					return new QQReverseReferenceNodeProductRelated($this, 'productrelated', 'reverse_reference', 'product_id');
				case 'ProductRelatedAsRelated':
					return new QQReverseReferenceNodeProductRelated($this, 'productrelatedasrelated', 'reverse_reference', 'related_id');

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

	class QQReverseReferenceNodeProduct extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_product';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Product';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'ImageId':
					return new QQNode('image_id', 'ImageId', 'integer', $this);
				case 'ClassName':
					return new QQNode('class_name', 'ClassName', 'string', $this);
				case 'Code':
					return new QQNode('code', 'Code', 'string', $this);
				case 'Current':
					return new QQNode('current', 'Current', 'boolean', $this);
				case 'Description':
					return new QQNode('description', 'Description', 'string', $this);
				case 'DescriptionShort':
					return new QQNode('description_short', 'DescriptionShort', 'string', $this);
				case 'Family':
					return new QQNode('family', 'Family', 'string', $this);
				case 'GiftCard':
					return new QQNode('gift_card', 'GiftCard', 'boolean', $this);
				case 'Inventoried':
					return new QQNode('inventoried', 'Inventoried', 'boolean', $this);
				case 'Inventory':
					return new QQNode('inventory', 'Inventory', 'double', $this);
				case 'InventoryTotal':
					return new QQNode('inventory_total', 'InventoryTotal', 'double', $this);
				case 'MasterModel':
					return new QQNode('master_model', 'MasterModel', 'boolean', $this);
				case 'FkProductMasterId':
					return new QQNode('fk_product_master_id', 'FkProductMasterId', 'integer', $this);
				case 'FkProductMaster':
					return new QQNodeProduct('fk_product_master_id', 'FkProductMaster', 'integer', $this);
				case 'ProductSize':
					return new QQNode('product_size', 'ProductSize', 'string', $this);
				case 'ProductColor':
					return new QQNode('product_color', 'ProductColor', 'string', $this);
				case 'ProductHeight':
					return new QQNode('product_height', 'ProductHeight', 'double', $this);
				case 'ProductLength':
					return new QQNode('product_length', 'ProductLength', 'double', $this);
				case 'ProductWidth':
					return new QQNode('product_width', 'ProductWidth', 'double', $this);
				case 'ProductWeight':
					return new QQNode('product_weight', 'ProductWeight', 'double', $this);
				case 'FkTaxStatusId':
					return new QQNode('fk_tax_status_id', 'FkTaxStatusId', 'integer', $this);
				case 'FkTaxStatus':
					return new QQNodeTaxStatus('fk_tax_status_id', 'FkTaxStatus', 'integer', $this);
				case 'Sell':
					return new QQNode('sell', 'Sell', 'double', $this);
				case 'SellTaxInclusive':
					return new QQNode('sell_tax_inclusive', 'SellTaxInclusive', 'double', $this);
				case 'SellWeb':
					return new QQNode('sell_web', 'SellWeb', 'double', $this);
				case 'Upc':
					return new QQNode('upc', 'Upc', 'string', $this);
				case 'Web':
					return new QQNode('web', 'Web', 'boolean', $this);
				case 'WebKeyword1':
					return new QQNode('web_keyword1', 'WebKeyword1', 'string', $this);
				case 'WebKeyword2':
					return new QQNode('web_keyword2', 'WebKeyword2', 'string', $this);
				case 'WebKeyword3':
					return new QQNode('web_keyword3', 'WebKeyword3', 'string', $this);
				case 'MetaDesc':
					return new QQNode('meta_desc', 'MetaDesc', 'string', $this);
				case 'MetaKeyword':
					return new QQNode('meta_keyword', 'MetaKeyword', 'string', $this);
				case 'Featured':
					return new QQNode('featured', 'Featured', 'boolean', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Category':
					return new QQNodeProductCategory($this);
				case 'ImagesAsImage':
					return new QQNodeProductImagesAsImage($this);
				case 'CartItem':
					return new QQReverseReferenceNodeCartItem($this, 'cartitem', 'reverse_reference', 'product_id');
				case 'GiftRegistryItems':
					return new QQReverseReferenceNodeGiftRegistryItems($this, 'giftregistryitems', 'reverse_reference', 'product_id');
				case 'ProductAsFkMaster':
					return new QQReverseReferenceNodeProduct($this, 'productasfkmaster', 'reverse_reference', 'fk_product_master_id');
				case 'ProductQtyPricing':
					return new QQReverseReferenceNodeProductQtyPricing($this, 'productqtypricing', 'reverse_reference', 'product_id');
				case 'ProductRelated':
					return new QQReverseReferenceNodeProductRelated($this, 'productrelated', 'reverse_reference', 'product_id');
				case 'ProductRelatedAsRelated':
					return new QQReverseReferenceNodeProductRelated($this, 'productrelatedasrelated', 'reverse_reference', 'related_id');

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