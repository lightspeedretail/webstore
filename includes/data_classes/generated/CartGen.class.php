<?php
	/**
	 * The abstract CartGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Cart subclass which
	 * extends this CartGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Cart class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $IdStr the value for strIdStr (Unique)
	 * @property string $AddressBill the value for strAddressBill 
	 * @property string $AddressShip the value for strAddressShip 
	 * @property string $ShipFirstname the value for strShipFirstname 
	 * @property string $ShipLastname the value for strShipLastname 
	 * @property string $ShipCompany the value for strShipCompany 
	 * @property string $ShipAddress1 the value for strShipAddress1 
	 * @property string $ShipAddress2 the value for strShipAddress2 
	 * @property string $ShipCity the value for strShipCity 
	 * @property string $ShipZip the value for strShipZip 
	 * @property string $ShipState the value for strShipState 
	 * @property string $ShipCountry the value for strShipCountry 
	 * @property string $ShipPhone the value for strShipPhone 
	 * @property string $Zipcode the value for strZipcode 
	 * @property string $Contact the value for strContact 
	 * @property string $Discount the value for strDiscount 
	 * @property string $Firstname the value for strFirstname 
	 * @property string $Lastname the value for strLastname 
	 * @property string $Company the value for strCompany 
	 * @property string $Name the value for strName 
	 * @property string $Phone the value for strPhone 
	 * @property string $Po the value for strPo 
	 * @property integer $Type the value for intType 
	 * @property string $Status the value for strStatus 
	 * @property string $CostTotal the value for strCostTotal 
	 * @property string $Currency the value for strCurrency 
	 * @property string $CurrencyRate the value for strCurrencyRate 
	 * @property QDateTime $DatetimeCre the value for dttDatetimeCre 
	 * @property QDateTime $DatetimeDue the value for dttDatetimeDue 
	 * @property QDateTime $DatetimePosted the value for dttDatetimePosted 
	 * @property string $Email the value for strEmail 
	 * @property string $SellTotal the value for strSellTotal 
	 * @property string $PrintedNotes the value for strPrintedNotes 
	 * @property string $ShippingMethod the value for strShippingMethod 
	 * @property string $ShippingModule the value for strShippingModule 
	 * @property string $ShippingData the value for strShippingData 
	 * @property string $ShippingCost the value for strShippingCost 
	 * @property string $ShippingSell the value for strShippingSell 
	 * @property string $PaymentMethod the value for strPaymentMethod 
	 * @property string $PaymentModule the value for strPaymentModule 
	 * @property string $PaymentData the value for strPaymentData 
	 * @property string $PaymentAmount the value for strPaymentAmount 
	 * @property integer $FkTaxCodeId the value for intFkTaxCodeId 
	 * @property boolean $TaxInclusive the value for blnTaxInclusive 
	 * @property string $Subtotal the value for strSubtotal 
	 * @property string $Tax1 the value for strTax1 
	 * @property string $Tax2 the value for strTax2 
	 * @property string $Tax3 the value for strTax3 
	 * @property string $Tax4 the value for strTax4 
	 * @property string $Tax5 the value for strTax5 
	 * @property string $Total the value for strTotal 
	 * @property integer $Count the value for intCount 
	 * @property boolean $Downloaded the value for blnDownloaded 
	 * @property string $User the value for strUser 
	 * @property string $IpHost the value for strIpHost 
	 * @property integer $CustomerId the value for intCustomerId 
	 * @property integer $GiftRegistry the value for intGiftRegistry 
	 * @property string $SendTo the value for strSendTo 
	 * @property QDateTime $Submitted the value for dttSubmitted 
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property string $Linkid the value for strLinkid 
	 * @property integer $FkPromoId the value for intFkPromoId 
	 * @property TaxCode $FkTaxCode the value for the TaxCode object referenced by intFkTaxCodeId 
	 * @property Customer $Customer the value for the Customer object referenced by intCustomerId 
	 * @property GiftRegistry $GiftRegistryObject the value for the GiftRegistry object referenced by intGiftRegistry 
	 * @property CartItem $_CartItem the value for the private _objCartItem (Read-Only) if set due to an expansion on the xlsws_cart_item.cart_id reverse relationship
	 * @property CartItem[] $_CartItemArray the value for the private _objCartItemArray (Read-Only) if set due to an ExpandAsArray on the xlsws_cart_item.cart_id reverse relationship
	 * @property Sro $_Sro the value for the private _objSro (Read-Only) if set due to an expansion on the xlsws_sro.cart_id reverse relationship
	 * @property Sro[] $_SroArray the value for the private _objSroArray (Read-Only) if set due to an ExpandAsArray on the xlsws_sro.cart_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CartGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_cart.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.id_str
		 * @var string strIdStr
		 */
		protected $strIdStr;
		const IdStrMaxLength = 64;
		const IdStrDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.address_bill
		 * @var string strAddressBill
		 */
		protected $strAddressBill;
		const AddressBillMaxLength = 255;
		const AddressBillDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.address_ship
		 * @var string strAddressShip
		 */
		protected $strAddressShip;
		const AddressShipMaxLength = 255;
		const AddressShipDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_firstname
		 * @var string strShipFirstname
		 */
		protected $strShipFirstname;
		const ShipFirstnameMaxLength = 64;
		const ShipFirstnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_lastname
		 * @var string strShipLastname
		 */
		protected $strShipLastname;
		const ShipLastnameMaxLength = 64;
		const ShipLastnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_company
		 * @var string strShipCompany
		 */
		protected $strShipCompany;
		const ShipCompanyMaxLength = 255;
		const ShipCompanyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_address1
		 * @var string strShipAddress1
		 */
		protected $strShipAddress1;
		const ShipAddress1MaxLength = 255;
		const ShipAddress1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_address2
		 * @var string strShipAddress2
		 */
		protected $strShipAddress2;
		const ShipAddress2MaxLength = 255;
		const ShipAddress2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_city
		 * @var string strShipCity
		 */
		protected $strShipCity;
		const ShipCityMaxLength = 64;
		const ShipCityDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_zip
		 * @var string strShipZip
		 */
		protected $strShipZip;
		const ShipZipMaxLength = 10;
		const ShipZipDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_state
		 * @var string strShipState
		 */
		protected $strShipState;
		const ShipStateMaxLength = 16;
		const ShipStateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_country
		 * @var string strShipCountry
		 */
		protected $strShipCountry;
		const ShipCountryMaxLength = 16;
		const ShipCountryDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ship_phone
		 * @var string strShipPhone
		 */
		protected $strShipPhone;
		const ShipPhoneMaxLength = 32;
		const ShipPhoneDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.zipcode
		 * @var string strZipcode
		 */
		protected $strZipcode;
		const ZipcodeMaxLength = 10;
		const ZipcodeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.contact
		 * @var string strContact
		 */
		protected $strContact;
		const ContactMaxLength = 255;
		const ContactDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.discount
		 * @var string strDiscount
		 */
		protected $strDiscount;
		const DiscountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.firstname
		 * @var string strFirstname
		 */
		protected $strFirstname;
		const FirstnameMaxLength = 64;
		const FirstnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.lastname
		 * @var string strLastname
		 */
		protected $strLastname;
		const LastnameMaxLength = 64;
		const LastnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.company
		 * @var string strCompany
		 */
		protected $strCompany;
		const CompanyMaxLength = 255;
		const CompanyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.name
		 * @var string strName
		 */
		protected $strName;
		const NameMaxLength = 255;
		const NameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.phone
		 * @var string strPhone
		 */
		protected $strPhone;
		const PhoneMaxLength = 64;
		const PhoneDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.po
		 * @var string strPo
		 */
		protected $strPo;
		const PoMaxLength = 64;
		const PoDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.type
		 * @var integer intType
		 */
		protected $intType;
		const TypeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.status
		 * @var string strStatus
		 */
		protected $strStatus;
		const StatusMaxLength = 32;
		const StatusDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.cost_total
		 * @var string strCostTotal
		 */
		protected $strCostTotal;
		const CostTotalDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.currency
		 * @var string strCurrency
		 */
		protected $strCurrency;
		const CurrencyMaxLength = 3;
		const CurrencyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.currency_rate
		 * @var string strCurrencyRate
		 */
		protected $strCurrencyRate;
		const CurrencyRateDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.datetime_cre
		 * @var QDateTime dttDatetimeCre
		 */
		protected $dttDatetimeCre;
		const DatetimeCreDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.datetime_due
		 * @var QDateTime dttDatetimeDue
		 */
		protected $dttDatetimeDue;
		const DatetimeDueDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.datetime_posted
		 * @var QDateTime dttDatetimePosted
		 */
		protected $dttDatetimePosted;
		const DatetimePostedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.email
		 * @var string strEmail
		 */
		protected $strEmail;
		const EmailMaxLength = 255;
		const EmailDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.sell_total
		 * @var string strSellTotal
		 */
		protected $strSellTotal;
		const SellTotalDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.printed_notes
		 * @var string strPrintedNotes
		 */
		protected $strPrintedNotes;
		const PrintedNotesMaxLength = 255;
		const PrintedNotesDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.shipping_method
		 * @var string strShippingMethod
		 */
		protected $strShippingMethod;
		const ShippingMethodMaxLength = 255;
		const ShippingMethodDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.shipping_module
		 * @var string strShippingModule
		 */
		protected $strShippingModule;
		const ShippingModuleMaxLength = 64;
		const ShippingModuleDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.shipping_data
		 * @var string strShippingData
		 */
		protected $strShippingData;
		const ShippingDataMaxLength = 255;
		const ShippingDataDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.shipping_cost
		 * @var string strShippingCost
		 */
		protected $strShippingCost;
		const ShippingCostDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.shipping_sell
		 * @var string strShippingSell
		 */
		protected $strShippingSell;
		const ShippingSellDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.payment_method
		 * @var string strPaymentMethod
		 */
		protected $strPaymentMethod;
		const PaymentMethodMaxLength = 255;
		const PaymentMethodDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.payment_module
		 * @var string strPaymentModule
		 */
		protected $strPaymentModule;
		const PaymentModuleMaxLength = 64;
		const PaymentModuleDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.payment_data
		 * @var string strPaymentData
		 */
		protected $strPaymentData;
		const PaymentDataMaxLength = 255;
		const PaymentDataDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.payment_amount
		 * @var string strPaymentAmount
		 */
		protected $strPaymentAmount;
		const PaymentAmountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.fk_tax_code_id
		 * @var integer intFkTaxCodeId
		 */
		protected $intFkTaxCodeId;
		const FkTaxCodeIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.tax_inclusive
		 * @var boolean blnTaxInclusive
		 */
		protected $blnTaxInclusive;
		const TaxInclusiveDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.subtotal
		 * @var string strSubtotal
		 */
		protected $strSubtotal;
		const SubtotalDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.tax1
		 * @var string strTax1
		 */
		protected $strTax1;
		const Tax1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.tax2
		 * @var string strTax2
		 */
		protected $strTax2;
		const Tax2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.tax3
		 * @var string strTax3
		 */
		protected $strTax3;
		const Tax3Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.tax4
		 * @var string strTax4
		 */
		protected $strTax4;
		const Tax4Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.tax5
		 * @var string strTax5
		 */
		protected $strTax5;
		const Tax5Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.total
		 * @var string strTotal
		 */
		protected $strTotal;
		const TotalDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.count
		 * @var integer intCount
		 */
		protected $intCount;
		const CountDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.downloaded
		 * @var boolean blnDownloaded
		 */
		protected $blnDownloaded;
		const DownloadedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.user
		 * @var string strUser
		 */
		protected $strUser;
		const UserMaxLength = 32;
		const UserDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.ip_host
		 * @var string strIpHost
		 */
		protected $strIpHost;
		const IpHostMaxLength = 255;
		const IpHostDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.customer_id
		 * @var integer intCustomerId
		 */
		protected $intCustomerId;
		const CustomerIdDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.gift_registry
		 * @var integer intGiftRegistry
		 */
		protected $intGiftRegistry;
		const GiftRegistryDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.send_to
		 * @var string strSendTo
		 */
		protected $strSendTo;
		const SendToMaxLength = 255;
		const SendToDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.submitted
		 * @var QDateTime dttSubmitted
		 */
		protected $dttSubmitted;
		const SubmittedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.linkid
		 * @var string strLinkid
		 */
		protected $strLinkid;
		const LinkidMaxLength = 32;
		const LinkidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_cart.fk_promo_id
		 * @var integer intFkPromoId
		 */
		protected $intFkPromoId;
		const FkPromoIdDefault = null;


		/**
		 * Private member variable that stores a reference to a single CartItem object
		 * (of type CartItem), if this Cart object was restored with
		 * an expansion on the xlsws_cart_item association table.
		 * @var CartItem _objCartItem;
		 */
		private $_objCartItem;

		/**
		 * Private member variable that stores a reference to an array of CartItem objects
		 * (of type CartItem[]), if this Cart object was restored with
		 * an ExpandAsArray on the xlsws_cart_item association table.
		 * @var CartItem[] _objCartItemArray;
		 */
		private $_objCartItemArray = array();

		/**
		 * Private member variable that stores a reference to a single Sro object
		 * (of type Sro), if this Cart object was restored with
		 * an expansion on the xlsws_sro association table.
		 * @var Sro _objSro;
		 */
		private $_objSro;

		/**
		 * Private member variable that stores a reference to an array of Sro objects
		 * (of type Sro[]), if this Cart object was restored with
		 * an ExpandAsArray on the xlsws_sro association table.
		 * @var Sro[] _objSroArray;
		 */
		private $_objSroArray = array();

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
		 * in the database column xlsws_cart.fk_tax_code_id.
		 *
		 * NOTE: Always use the FkTaxCode property getter to correctly retrieve this TaxCode object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var TaxCode objFkTaxCode
		 */
		protected $objFkTaxCode;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_cart.customer_id.
		 *
		 * NOTE: Always use the Customer property getter to correctly retrieve this Customer object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var Customer objCustomer
		 */
		protected $objCustomer;

		/**
		 * Protected member variable that contains the object pointed by the reference
		 * in the database column xlsws_cart.gift_registry.
		 *
		 * NOTE: Always use the GiftRegistryObject property getter to correctly retrieve this GiftRegistry object.
		 * (Because this class implements late binding, this variable reference MAY be null.)
		 * @var GiftRegistry objGiftRegistryObject
		 */
		protected $objGiftRegistryObject;





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
		 * Load a Cart from PK Info
		 * @param integer $intRowid
		 * @return Cart
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Cart::QuerySingle(
				QQ::Equal(QQN::Cart()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Carts
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadAll query
			try {
				return Cart::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Carts
		 * @return int
		 */
		public static function CountAll() {
			// Call Cart::QueryCount to perform the CountAll query
			return Cart::QueryCount(QQ::All());
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
			$objDatabase = Cart::GetDatabase();

			// Create/Build out the QueryBuilder object with Cart-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_cart');
			Cart::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_cart');

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
		 * Static Qcodo Query method to query for a single Cart object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Cart the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Cart::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Cart object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Cart::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Cart objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Cart[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Cart::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Cart::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Cart objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Cart::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Cart::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_cart_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Cart-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Cart::GetSelectFields($objQueryBuilder);
				Cart::GetFromFields($objQueryBuilder);

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
			return Cart::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Cart
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_cart';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'id_str', $strAliasPrefix . 'id_str');
			$objBuilder->AddSelectItem($strTableName, 'address_bill', $strAliasPrefix . 'address_bill');
			$objBuilder->AddSelectItem($strTableName, 'address_ship', $strAliasPrefix . 'address_ship');
			$objBuilder->AddSelectItem($strTableName, 'ship_firstname', $strAliasPrefix . 'ship_firstname');
			$objBuilder->AddSelectItem($strTableName, 'ship_lastname', $strAliasPrefix . 'ship_lastname');
			$objBuilder->AddSelectItem($strTableName, 'ship_company', $strAliasPrefix . 'ship_company');
			$objBuilder->AddSelectItem($strTableName, 'ship_address1', $strAliasPrefix . 'ship_address1');
			$objBuilder->AddSelectItem($strTableName, 'ship_address2', $strAliasPrefix . 'ship_address2');
			$objBuilder->AddSelectItem($strTableName, 'ship_city', $strAliasPrefix . 'ship_city');
			$objBuilder->AddSelectItem($strTableName, 'ship_zip', $strAliasPrefix . 'ship_zip');
			$objBuilder->AddSelectItem($strTableName, 'ship_state', $strAliasPrefix . 'ship_state');
			$objBuilder->AddSelectItem($strTableName, 'ship_country', $strAliasPrefix . 'ship_country');
			$objBuilder->AddSelectItem($strTableName, 'ship_phone', $strAliasPrefix . 'ship_phone');
			$objBuilder->AddSelectItem($strTableName, 'zipcode', $strAliasPrefix . 'zipcode');
			$objBuilder->AddSelectItem($strTableName, 'contact', $strAliasPrefix . 'contact');
			$objBuilder->AddSelectItem($strTableName, 'discount', $strAliasPrefix . 'discount');
			$objBuilder->AddSelectItem($strTableName, 'firstname', $strAliasPrefix . 'firstname');
			$objBuilder->AddSelectItem($strTableName, 'lastname', $strAliasPrefix . 'lastname');
			$objBuilder->AddSelectItem($strTableName, 'company', $strAliasPrefix . 'company');
			$objBuilder->AddSelectItem($strTableName, 'name', $strAliasPrefix . 'name');
			$objBuilder->AddSelectItem($strTableName, 'phone', $strAliasPrefix . 'phone');
			$objBuilder->AddSelectItem($strTableName, 'po', $strAliasPrefix . 'po');
			$objBuilder->AddSelectItem($strTableName, 'type', $strAliasPrefix . 'type');
			$objBuilder->AddSelectItem($strTableName, 'status', $strAliasPrefix . 'status');
			$objBuilder->AddSelectItem($strTableName, 'cost_total', $strAliasPrefix . 'cost_total');
			$objBuilder->AddSelectItem($strTableName, 'currency', $strAliasPrefix . 'currency');
			$objBuilder->AddSelectItem($strTableName, 'currency_rate', $strAliasPrefix . 'currency_rate');
			$objBuilder->AddSelectItem($strTableName, 'datetime_cre', $strAliasPrefix . 'datetime_cre');
			$objBuilder->AddSelectItem($strTableName, 'datetime_due', $strAliasPrefix . 'datetime_due');
			$objBuilder->AddSelectItem($strTableName, 'datetime_posted', $strAliasPrefix . 'datetime_posted');
			$objBuilder->AddSelectItem($strTableName, 'email', $strAliasPrefix . 'email');
			$objBuilder->AddSelectItem($strTableName, 'sell_total', $strAliasPrefix . 'sell_total');
			$objBuilder->AddSelectItem($strTableName, 'printed_notes', $strAliasPrefix . 'printed_notes');
			$objBuilder->AddSelectItem($strTableName, 'shipping_method', $strAliasPrefix . 'shipping_method');
			$objBuilder->AddSelectItem($strTableName, 'shipping_module', $strAliasPrefix . 'shipping_module');
			$objBuilder->AddSelectItem($strTableName, 'shipping_data', $strAliasPrefix . 'shipping_data');
			$objBuilder->AddSelectItem($strTableName, 'shipping_cost', $strAliasPrefix . 'shipping_cost');
			$objBuilder->AddSelectItem($strTableName, 'shipping_sell', $strAliasPrefix . 'shipping_sell');
			$objBuilder->AddSelectItem($strTableName, 'payment_method', $strAliasPrefix . 'payment_method');
			$objBuilder->AddSelectItem($strTableName, 'payment_module', $strAliasPrefix . 'payment_module');
			$objBuilder->AddSelectItem($strTableName, 'payment_data', $strAliasPrefix . 'payment_data');
			$objBuilder->AddSelectItem($strTableName, 'payment_amount', $strAliasPrefix . 'payment_amount');
			$objBuilder->AddSelectItem($strTableName, 'fk_tax_code_id', $strAliasPrefix . 'fk_tax_code_id');
			$objBuilder->AddSelectItem($strTableName, 'tax_inclusive', $strAliasPrefix . 'tax_inclusive');
			$objBuilder->AddSelectItem($strTableName, 'subtotal', $strAliasPrefix . 'subtotal');
			$objBuilder->AddSelectItem($strTableName, 'tax1', $strAliasPrefix . 'tax1');
			$objBuilder->AddSelectItem($strTableName, 'tax2', $strAliasPrefix . 'tax2');
			$objBuilder->AddSelectItem($strTableName, 'tax3', $strAliasPrefix . 'tax3');
			$objBuilder->AddSelectItem($strTableName, 'tax4', $strAliasPrefix . 'tax4');
			$objBuilder->AddSelectItem($strTableName, 'tax5', $strAliasPrefix . 'tax5');
			$objBuilder->AddSelectItem($strTableName, 'total', $strAliasPrefix . 'total');
			$objBuilder->AddSelectItem($strTableName, 'count', $strAliasPrefix . 'count');
			$objBuilder->AddSelectItem($strTableName, 'downloaded', $strAliasPrefix . 'downloaded');
			$objBuilder->AddSelectItem($strTableName, 'user', $strAliasPrefix . 'user');
			$objBuilder->AddSelectItem($strTableName, 'ip_host', $strAliasPrefix . 'ip_host');
			$objBuilder->AddSelectItem($strTableName, 'customer_id', $strAliasPrefix . 'customer_id');
			$objBuilder->AddSelectItem($strTableName, 'gift_registry', $strAliasPrefix . 'gift_registry');
			$objBuilder->AddSelectItem($strTableName, 'send_to', $strAliasPrefix . 'send_to');
			$objBuilder->AddSelectItem($strTableName, 'submitted', $strAliasPrefix . 'submitted');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
			$objBuilder->AddSelectItem($strTableName, 'linkid', $strAliasPrefix . 'linkid');
			$objBuilder->AddSelectItem($strTableName, 'fk_promo_id', $strAliasPrefix . 'fk_promo_id');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Cart from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Cart::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Cart
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
					$strAliasPrefix = 'xlsws_cart__';


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

				$strAlias = $strAliasPrefix . 'sro__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objSroArray)) {
						$objPreviousChildItem = $objPreviousItem->_objSroArray[$intPreviousChildItemCount - 1];
						$objChildItem = Sro::InstantiateDbRow($objDbRow, $strAliasPrefix . 'sro__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objSroArray[] = $objChildItem;
					} else
						$objPreviousItem->_objSroArray[] = Sro::InstantiateDbRow($objDbRow, $strAliasPrefix . 'sro__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_cart__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Cart object
			$objToReturn = new Cart();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'id_str', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'id_str'] : $strAliasPrefix . 'id_str';
			$objToReturn->strIdStr = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'address_bill', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'address_bill'] : $strAliasPrefix . 'address_bill';
			$objToReturn->strAddressBill = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'address_ship', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'address_ship'] : $strAliasPrefix . 'address_ship';
			$objToReturn->strAddressShip = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_firstname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_firstname'] : $strAliasPrefix . 'ship_firstname';
			$objToReturn->strShipFirstname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_lastname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_lastname'] : $strAliasPrefix . 'ship_lastname';
			$objToReturn->strShipLastname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_company', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_company'] : $strAliasPrefix . 'ship_company';
			$objToReturn->strShipCompany = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_address1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_address1'] : $strAliasPrefix . 'ship_address1';
			$objToReturn->strShipAddress1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_address2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_address2'] : $strAliasPrefix . 'ship_address2';
			$objToReturn->strShipAddress2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_city', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_city'] : $strAliasPrefix . 'ship_city';
			$objToReturn->strShipCity = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_zip', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_zip'] : $strAliasPrefix . 'ship_zip';
			$objToReturn->strShipZip = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_state', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_state'] : $strAliasPrefix . 'ship_state';
			$objToReturn->strShipState = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_country', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_country'] : $strAliasPrefix . 'ship_country';
			$objToReturn->strShipCountry = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ship_phone', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ship_phone'] : $strAliasPrefix . 'ship_phone';
			$objToReturn->strShipPhone = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zipcode', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zipcode'] : $strAliasPrefix . 'zipcode';
			$objToReturn->strZipcode = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'contact', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'contact'] : $strAliasPrefix . 'contact';
			$objToReturn->strContact = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'discount', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'discount'] : $strAliasPrefix . 'discount';
			$objToReturn->strDiscount = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'firstname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'firstname'] : $strAliasPrefix . 'firstname';
			$objToReturn->strFirstname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'lastname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'lastname'] : $strAliasPrefix . 'lastname';
			$objToReturn->strLastname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'company', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'company'] : $strAliasPrefix . 'company';
			$objToReturn->strCompany = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'name', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'name'] : $strAliasPrefix . 'name';
			$objToReturn->strName = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phone', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phone'] : $strAliasPrefix . 'phone';
			$objToReturn->strPhone = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'po', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'po'] : $strAliasPrefix . 'po';
			$objToReturn->strPo = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'type', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'type'] : $strAliasPrefix . 'type';
			$objToReturn->intType = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'status', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'status'] : $strAliasPrefix . 'status';
			$objToReturn->strStatus = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'cost_total', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'cost_total'] : $strAliasPrefix . 'cost_total';
			$objToReturn->strCostTotal = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'currency', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'currency'] : $strAliasPrefix . 'currency';
			$objToReturn->strCurrency = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'currency_rate', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'currency_rate'] : $strAliasPrefix . 'currency_rate';
			$objToReturn->strCurrencyRate = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'datetime_cre', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'datetime_cre'] : $strAliasPrefix . 'datetime_cre';
			$objToReturn->dttDatetimeCre = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'datetime_due', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'datetime_due'] : $strAliasPrefix . 'datetime_due';
			$objToReturn->dttDatetimeDue = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'datetime_posted', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'datetime_posted'] : $strAliasPrefix . 'datetime_posted';
			$objToReturn->dttDatetimePosted = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'email', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'email'] : $strAliasPrefix . 'email';
			$objToReturn->strEmail = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'sell_total', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'sell_total'] : $strAliasPrefix . 'sell_total';
			$objToReturn->strSellTotal = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'printed_notes', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'printed_notes'] : $strAliasPrefix . 'printed_notes';
			$objToReturn->strPrintedNotes = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'shipping_method', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'shipping_method'] : $strAliasPrefix . 'shipping_method';
			$objToReturn->strShippingMethod = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'shipping_module', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'shipping_module'] : $strAliasPrefix . 'shipping_module';
			$objToReturn->strShippingModule = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'shipping_data', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'shipping_data'] : $strAliasPrefix . 'shipping_data';
			$objToReturn->strShippingData = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'shipping_cost', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'shipping_cost'] : $strAliasPrefix . 'shipping_cost';
			$objToReturn->strShippingCost = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'shipping_sell', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'shipping_sell'] : $strAliasPrefix . 'shipping_sell';
			$objToReturn->strShippingSell = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'payment_method', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'payment_method'] : $strAliasPrefix . 'payment_method';
			$objToReturn->strPaymentMethod = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'payment_module', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'payment_module'] : $strAliasPrefix . 'payment_module';
			$objToReturn->strPaymentModule = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'payment_data', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'payment_data'] : $strAliasPrefix . 'payment_data';
			$objToReturn->strPaymentData = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'payment_amount', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'payment_amount'] : $strAliasPrefix . 'payment_amount';
			$objToReturn->strPaymentAmount = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'fk_tax_code_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'fk_tax_code_id'] : $strAliasPrefix . 'fk_tax_code_id';
			$objToReturn->intFkTaxCodeId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax_inclusive', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax_inclusive'] : $strAliasPrefix . 'tax_inclusive';
			$objToReturn->blnTaxInclusive = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'subtotal', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'subtotal'] : $strAliasPrefix . 'subtotal';
			$objToReturn->strSubtotal = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax1'] : $strAliasPrefix . 'tax1';
			$objToReturn->strTax1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax2'] : $strAliasPrefix . 'tax2';
			$objToReturn->strTax2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax3', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax3'] : $strAliasPrefix . 'tax3';
			$objToReturn->strTax3 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax4', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax4'] : $strAliasPrefix . 'tax4';
			$objToReturn->strTax4 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'tax5', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'tax5'] : $strAliasPrefix . 'tax5';
			$objToReturn->strTax5 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'total', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'total'] : $strAliasPrefix . 'total';
			$objToReturn->strTotal = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'count', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'count'] : $strAliasPrefix . 'count';
			$objToReturn->intCount = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'downloaded', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'downloaded'] : $strAliasPrefix . 'downloaded';
			$objToReturn->blnDownloaded = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'user', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'user'] : $strAliasPrefix . 'user';
			$objToReturn->strUser = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'ip_host', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'ip_host'] : $strAliasPrefix . 'ip_host';
			$objToReturn->strIpHost = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'customer_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'customer_id'] : $strAliasPrefix . 'customer_id';
			$objToReturn->intCustomerId = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'gift_registry', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'gift_registry'] : $strAliasPrefix . 'gift_registry';
			$objToReturn->intGiftRegistry = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'send_to', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'send_to'] : $strAliasPrefix . 'send_to';
			$objToReturn->strSendTo = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'submitted', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'submitted'] : $strAliasPrefix . 'submitted';
			$objToReturn->dttSubmitted = $objDbRow->GetColumn($strAliasName, 'DateTime');
			$strAliasName = array_key_exists($strAliasPrefix . 'modified', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'modified'] : $strAliasPrefix . 'modified';
			$objToReturn->strModified = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'linkid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'linkid'] : $strAliasPrefix . 'linkid';
			$objToReturn->strLinkid = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'fk_promo_id', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'fk_promo_id'] : $strAliasPrefix . 'fk_promo_id';
			$objToReturn->intFkPromoId = $objDbRow->GetColumn($strAliasName, 'Integer');

			// Instantiate Virtual Attributes
			foreach ($objDbRow->GetColumnNameArray() as $strColumnName => $mixValue) {
				$strVirtualPrefix = $strAliasPrefix . '__';
				$strVirtualPrefixLength = strlen($strVirtualPrefix);
				if (substr($strColumnName, 0, $strVirtualPrefixLength) == $strVirtualPrefix)
					$objToReturn->__strVirtualAttributeArray[substr($strColumnName, $strVirtualPrefixLength)] = $mixValue;
			}

			// Prepare to Check for Early/Virtual Binding
			if (!$strAliasPrefix)
				$strAliasPrefix = 'xlsws_cart__';

			// Check for FkTaxCode Early Binding
			$strAlias = $strAliasPrefix . 'fk_tax_code_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objFkTaxCode = TaxCode::InstantiateDbRow($objDbRow, $strAliasPrefix . 'fk_tax_code_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for Customer Early Binding
			$strAlias = $strAliasPrefix . 'customer_id__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objCustomer = Customer::InstantiateDbRow($objDbRow, $strAliasPrefix . 'customer_id__', $strExpandAsArrayNodes, null, $strColumnAliasArray);

			// Check for GiftRegistryObject Early Binding
			$strAlias = $strAliasPrefix . 'gift_registry__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName)))
				$objToReturn->objGiftRegistryObject = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'gift_registry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);




			// Check for CartItem Virtual Binding
			$strAlias = $strAliasPrefix . 'cartitem__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCartItemArray[] = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCartItem = CartItem::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cartitem__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for Sro Virtual Binding
			$strAlias = $strAliasPrefix . 'sro__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objSroArray[] = Sro::InstantiateDbRow($objDbRow, $strAliasPrefix . 'sro__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objSro = Sro::InstantiateDbRow($objDbRow, $strAliasPrefix . 'sro__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Carts from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Cart[]
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
					$objItem = Cart::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Cart::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Cart object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Cart
		*/
		public static function LoadByRowid($intRowid) {
			return Cart::QuerySingle(
				QQ::Equal(QQN::Cart()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Cart object,
		 * by IdStr Index(es)
		 * @param string $strIdStr
		 * @return Cart
		*/
		public static function LoadByIdStr($strIdStr) {
			return Cart::QuerySingle(
				QQ::Equal(QQN::Cart()->IdStr, $strIdStr)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayByCustomerId($intCustomerId, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayByCustomerId query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->CustomerId, $intCustomerId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by CustomerId Index(es)
		 * @param integer $intCustomerId
		 * @return int
		*/
		public static function CountByCustomerId($intCustomerId) {
			// Call Cart::QueryCount to perform the CountByCustomerId query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->CustomerId, $intCustomerId)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by Type Index(es)
		 * @param integer $intType
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayByType($intType, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayByType query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->Type, $intType),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by Type Index(es)
		 * @param integer $intType
		 * @return int
		*/
		public static function CountByType($intType) {
			// Call Cart::QueryCount to perform the CountByType query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->Type, $intType)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by Linkid Index(es)
		 * @param string $strLinkid
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayByLinkid($strLinkid, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayByLinkid query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->Linkid, $strLinkid),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by Linkid Index(es)
		 * @param string $strLinkid
		 * @return int
		*/
		public static function CountByLinkid($strLinkid) {
			// Call Cart::QueryCount to perform the CountByLinkid query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->Linkid, $strLinkid)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by FkTaxCodeId Index(es)
		 * @param integer $intFkTaxCodeId
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayByFkTaxCodeId($intFkTaxCodeId, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayByFkTaxCodeId query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->FkTaxCodeId, $intFkTaxCodeId),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by FkTaxCodeId Index(es)
		 * @param integer $intFkTaxCodeId
		 * @return int
		*/
		public static function CountByFkTaxCodeId($intFkTaxCodeId) {
			// Call Cart::QueryCount to perform the CountByFkTaxCodeId query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->FkTaxCodeId, $intFkTaxCodeId)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by Submitted Index(es)
		 * @param QDateTime $dttSubmitted
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayBySubmitted($dttSubmitted, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayBySubmitted query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->Submitted, $dttSubmitted),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by Submitted Index(es)
		 * @param QDateTime $dttSubmitted
		 * @return int
		*/
		public static function CountBySubmitted($dttSubmitted) {
			// Call Cart::QueryCount to perform the CountBySubmitted query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->Submitted, $dttSubmitted)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by GiftRegistry Index(es)
		 * @param integer $intGiftRegistry
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayByGiftRegistry($intGiftRegistry, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayByGiftRegistry query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->GiftRegistry, $intGiftRegistry),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by GiftRegistry Index(es)
		 * @param integer $intGiftRegistry
		 * @return int
		*/
		public static function CountByGiftRegistry($intGiftRegistry) {
			// Call Cart::QueryCount to perform the CountByGiftRegistry query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->GiftRegistry, $intGiftRegistry)
			);
		}
			
		/**
		 * Load an array of Cart objects,
		 * by Downloaded Index(es)
		 * @param boolean $blnDownloaded
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Cart[]
		*/
		public static function LoadArrayByDownloaded($blnDownloaded, $objOptionalClauses = null) {
			// Call Cart::QueryArray to perform the LoadArrayByDownloaded query
			try {
				return Cart::QueryArray(
					QQ::Equal(QQN::Cart()->Downloaded, $blnDownloaded),
					$objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count Carts
		 * by Downloaded Index(es)
		 * @param boolean $blnDownloaded
		 * @return int
		*/
		public static function CountByDownloaded($blnDownloaded) {
			// Call Cart::QueryCount to perform the CountByDownloaded query
			return Cart::QueryCount(
				QQ::Equal(QQN::Cart()->Downloaded, $blnDownloaded)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Cart
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_cart` (
							`id_str`,
							`address_bill`,
							`address_ship`,
							`ship_firstname`,
							`ship_lastname`,
							`ship_company`,
							`ship_address1`,
							`ship_address2`,
							`ship_city`,
							`ship_zip`,
							`ship_state`,
							`ship_country`,
							`ship_phone`,
							`zipcode`,
							`contact`,
							`discount`,
							`firstname`,
							`lastname`,
							`company`,
							`name`,
							`phone`,
							`po`,
							`type`,
							`status`,
							`cost_total`,
							`currency`,
							`currency_rate`,
							`datetime_cre`,
							`datetime_due`,
							`datetime_posted`,
							`email`,
							`sell_total`,
							`printed_notes`,
							`shipping_method`,
							`shipping_module`,
							`shipping_data`,
							`shipping_cost`,
							`shipping_sell`,
							`payment_method`,
							`payment_module`,
							`payment_data`,
							`payment_amount`,
							`fk_tax_code_id`,
							`tax_inclusive`,
							`subtotal`,
							`tax1`,
							`tax2`,
							`tax3`,
							`tax4`,
							`tax5`,
							`total`,
							`count`,
							`downloaded`,
							`user`,
							`ip_host`,
							`customer_id`,
							`gift_registry`,
							`send_to`,
							`submitted`,
							`linkid`,
							`fk_promo_id`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strIdStr) . ',
							' . $objDatabase->SqlVariable($this->strAddressBill) . ',
							' . $objDatabase->SqlVariable($this->strAddressShip) . ',
							' . $objDatabase->SqlVariable($this->strShipFirstname) . ',
							' . $objDatabase->SqlVariable($this->strShipLastname) . ',
							' . $objDatabase->SqlVariable($this->strShipCompany) . ',
							' . $objDatabase->SqlVariable($this->strShipAddress1) . ',
							' . $objDatabase->SqlVariable($this->strShipAddress2) . ',
							' . $objDatabase->SqlVariable($this->strShipCity) . ',
							' . $objDatabase->SqlVariable($this->strShipZip) . ',
							' . $objDatabase->SqlVariable($this->strShipState) . ',
							' . $objDatabase->SqlVariable($this->strShipCountry) . ',
							' . $objDatabase->SqlVariable($this->strShipPhone) . ',
							' . $objDatabase->SqlVariable($this->strZipcode) . ',
							' . $objDatabase->SqlVariable($this->strContact) . ',
							' . $objDatabase->SqlVariable($this->strDiscount) . ',
							' . $objDatabase->SqlVariable($this->strFirstname) . ',
							' . $objDatabase->SqlVariable($this->strLastname) . ',
							' . $objDatabase->SqlVariable($this->strCompany) . ',
							' . $objDatabase->SqlVariable($this->strName) . ',
							' . $objDatabase->SqlVariable($this->strPhone) . ',
							' . $objDatabase->SqlVariable($this->strPo) . ',
							' . $objDatabase->SqlVariable($this->intType) . ',
							' . $objDatabase->SqlVariable($this->strStatus) . ',
							' . $objDatabase->SqlVariable($this->strCostTotal) . ',
							' . $objDatabase->SqlVariable($this->strCurrency) . ',
							' . $objDatabase->SqlVariable($this->strCurrencyRate) . ',
							' . $objDatabase->SqlVariable($this->dttDatetimeCre) . ',
							' . $objDatabase->SqlVariable($this->dttDatetimeDue) . ',
							' . $objDatabase->SqlVariable($this->dttDatetimePosted) . ',
							' . $objDatabase->SqlVariable($this->strEmail) . ',
							' . $objDatabase->SqlVariable($this->strSellTotal) . ',
							' . $objDatabase->SqlVariable($this->strPrintedNotes) . ',
							' . $objDatabase->SqlVariable($this->strShippingMethod) . ',
							' . $objDatabase->SqlVariable($this->strShippingModule) . ',
							' . $objDatabase->SqlVariable($this->strShippingData) . ',
							' . $objDatabase->SqlVariable($this->strShippingCost) . ',
							' . $objDatabase->SqlVariable($this->strShippingSell) . ',
							' . $objDatabase->SqlVariable($this->strPaymentMethod) . ',
							' . $objDatabase->SqlVariable($this->strPaymentModule) . ',
							' . $objDatabase->SqlVariable($this->strPaymentData) . ',
							' . $objDatabase->SqlVariable($this->strPaymentAmount) . ',
							' . $objDatabase->SqlVariable($this->intFkTaxCodeId) . ',
							' . $objDatabase->SqlVariable($this->blnTaxInclusive) . ',
							' . $objDatabase->SqlVariable($this->strSubtotal) . ',
							' . $objDatabase->SqlVariable($this->strTax1) . ',
							' . $objDatabase->SqlVariable($this->strTax2) . ',
							' . $objDatabase->SqlVariable($this->strTax3) . ',
							' . $objDatabase->SqlVariable($this->strTax4) . ',
							' . $objDatabase->SqlVariable($this->strTax5) . ',
							' . $objDatabase->SqlVariable($this->strTotal) . ',
							' . $objDatabase->SqlVariable($this->intCount) . ',
							' . $objDatabase->SqlVariable($this->blnDownloaded) . ',
							' . $objDatabase->SqlVariable($this->strUser) . ',
							' . $objDatabase->SqlVariable($this->strIpHost) . ',
							' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							' . $objDatabase->SqlVariable($this->intGiftRegistry) . ',
							' . $objDatabase->SqlVariable($this->strSendTo) . ',
							' . $objDatabase->SqlVariable($this->dttSubmitted) . ',
							' . $objDatabase->SqlVariable($this->strLinkid) . ',
							' . $objDatabase->SqlVariable($this->intFkPromoId) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_cart', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_cart`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Cart');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_cart`
						SET
							`id_str` = ' . $objDatabase->SqlVariable($this->strIdStr) . ',
							`address_bill` = ' . $objDatabase->SqlVariable($this->strAddressBill) . ',
							`address_ship` = ' . $objDatabase->SqlVariable($this->strAddressShip) . ',
							`ship_firstname` = ' . $objDatabase->SqlVariable($this->strShipFirstname) . ',
							`ship_lastname` = ' . $objDatabase->SqlVariable($this->strShipLastname) . ',
							`ship_company` = ' . $objDatabase->SqlVariable($this->strShipCompany) . ',
							`ship_address1` = ' . $objDatabase->SqlVariable($this->strShipAddress1) . ',
							`ship_address2` = ' . $objDatabase->SqlVariable($this->strShipAddress2) . ',
							`ship_city` = ' . $objDatabase->SqlVariable($this->strShipCity) . ',
							`ship_zip` = ' . $objDatabase->SqlVariable($this->strShipZip) . ',
							`ship_state` = ' . $objDatabase->SqlVariable($this->strShipState) . ',
							`ship_country` = ' . $objDatabase->SqlVariable($this->strShipCountry) . ',
							`ship_phone` = ' . $objDatabase->SqlVariable($this->strShipPhone) . ',
							`zipcode` = ' . $objDatabase->SqlVariable($this->strZipcode) . ',
							`contact` = ' . $objDatabase->SqlVariable($this->strContact) . ',
							`discount` = ' . $objDatabase->SqlVariable($this->strDiscount) . ',
							`firstname` = ' . $objDatabase->SqlVariable($this->strFirstname) . ',
							`lastname` = ' . $objDatabase->SqlVariable($this->strLastname) . ',
							`company` = ' . $objDatabase->SqlVariable($this->strCompany) . ',
							`name` = ' . $objDatabase->SqlVariable($this->strName) . ',
							`phone` = ' . $objDatabase->SqlVariable($this->strPhone) . ',
							`po` = ' . $objDatabase->SqlVariable($this->strPo) . ',
							`type` = ' . $objDatabase->SqlVariable($this->intType) . ',
							`status` = ' . $objDatabase->SqlVariable($this->strStatus) . ',
							`cost_total` = ' . $objDatabase->SqlVariable($this->strCostTotal) . ',
							`currency` = ' . $objDatabase->SqlVariable($this->strCurrency) . ',
							`currency_rate` = ' . $objDatabase->SqlVariable($this->strCurrencyRate) . ',
							`datetime_cre` = ' . $objDatabase->SqlVariable($this->dttDatetimeCre) . ',
							`datetime_due` = ' . $objDatabase->SqlVariable($this->dttDatetimeDue) . ',
							`datetime_posted` = ' . $objDatabase->SqlVariable($this->dttDatetimePosted) . ',
							`email` = ' . $objDatabase->SqlVariable($this->strEmail) . ',
							`sell_total` = ' . $objDatabase->SqlVariable($this->strSellTotal) . ',
							`printed_notes` = ' . $objDatabase->SqlVariable($this->strPrintedNotes) . ',
							`shipping_method` = ' . $objDatabase->SqlVariable($this->strShippingMethod) . ',
							`shipping_module` = ' . $objDatabase->SqlVariable($this->strShippingModule) . ',
							`shipping_data` = ' . $objDatabase->SqlVariable($this->strShippingData) . ',
							`shipping_cost` = ' . $objDatabase->SqlVariable($this->strShippingCost) . ',
							`shipping_sell` = ' . $objDatabase->SqlVariable($this->strShippingSell) . ',
							`payment_method` = ' . $objDatabase->SqlVariable($this->strPaymentMethod) . ',
							`payment_module` = ' . $objDatabase->SqlVariable($this->strPaymentModule) . ',
							`payment_data` = ' . $objDatabase->SqlVariable($this->strPaymentData) . ',
							`payment_amount` = ' . $objDatabase->SqlVariable($this->strPaymentAmount) . ',
							`fk_tax_code_id` = ' . $objDatabase->SqlVariable($this->intFkTaxCodeId) . ',
							`tax_inclusive` = ' . $objDatabase->SqlVariable($this->blnTaxInclusive) . ',
							`subtotal` = ' . $objDatabase->SqlVariable($this->strSubtotal) . ',
							`tax1` = ' . $objDatabase->SqlVariable($this->strTax1) . ',
							`tax2` = ' . $objDatabase->SqlVariable($this->strTax2) . ',
							`tax3` = ' . $objDatabase->SqlVariable($this->strTax3) . ',
							`tax4` = ' . $objDatabase->SqlVariable($this->strTax4) . ',
							`tax5` = ' . $objDatabase->SqlVariable($this->strTax5) . ',
							`total` = ' . $objDatabase->SqlVariable($this->strTotal) . ',
							`count` = ' . $objDatabase->SqlVariable($this->intCount) . ',
							`downloaded` = ' . $objDatabase->SqlVariable($this->blnDownloaded) . ',
							`user` = ' . $objDatabase->SqlVariable($this->strUser) . ',
							`ip_host` = ' . $objDatabase->SqlVariable($this->strIpHost) . ',
							`customer_id` = ' . $objDatabase->SqlVariable($this->intCustomerId) . ',
							`gift_registry` = ' . $objDatabase->SqlVariable($this->intGiftRegistry) . ',
							`send_to` = ' . $objDatabase->SqlVariable($this->strSendTo) . ',
							`submitted` = ' . $objDatabase->SqlVariable($this->dttSubmitted) . ',
							`linkid` = ' . $objDatabase->SqlVariable($this->strLinkid) . ',
							`fk_promo_id` = ' . $objDatabase->SqlVariable($this->intFkPromoId) . '
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
					`xlsws_cart`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Cart
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Cart with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Carts
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`');
		}

		/**
		 * Truncate xlsws_cart table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_cart`');
		}

		/**
		 * Reload this Cart from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Cart object.');

			// Reload the Object
			$objReloaded = Cart::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strIdStr = $objReloaded->strIdStr;
			$this->strAddressBill = $objReloaded->strAddressBill;
			$this->strAddressShip = $objReloaded->strAddressShip;
			$this->strShipFirstname = $objReloaded->strShipFirstname;
			$this->strShipLastname = $objReloaded->strShipLastname;
			$this->strShipCompany = $objReloaded->strShipCompany;
			$this->strShipAddress1 = $objReloaded->strShipAddress1;
			$this->strShipAddress2 = $objReloaded->strShipAddress2;
			$this->strShipCity = $objReloaded->strShipCity;
			$this->strShipZip = $objReloaded->strShipZip;
			$this->strShipState = $objReloaded->strShipState;
			$this->strShipCountry = $objReloaded->strShipCountry;
			$this->strShipPhone = $objReloaded->strShipPhone;
			$this->strZipcode = $objReloaded->strZipcode;
			$this->strContact = $objReloaded->strContact;
			$this->strDiscount = $objReloaded->strDiscount;
			$this->strFirstname = $objReloaded->strFirstname;
			$this->strLastname = $objReloaded->strLastname;
			$this->strCompany = $objReloaded->strCompany;
			$this->strName = $objReloaded->strName;
			$this->strPhone = $objReloaded->strPhone;
			$this->strPo = $objReloaded->strPo;
			$this->Type = $objReloaded->Type;
			$this->strStatus = $objReloaded->strStatus;
			$this->strCostTotal = $objReloaded->strCostTotal;
			$this->strCurrency = $objReloaded->strCurrency;
			$this->strCurrencyRate = $objReloaded->strCurrencyRate;
			$this->dttDatetimeCre = $objReloaded->dttDatetimeCre;
			$this->dttDatetimeDue = $objReloaded->dttDatetimeDue;
			$this->dttDatetimePosted = $objReloaded->dttDatetimePosted;
			$this->strEmail = $objReloaded->strEmail;
			$this->strSellTotal = $objReloaded->strSellTotal;
			$this->strPrintedNotes = $objReloaded->strPrintedNotes;
			$this->strShippingMethod = $objReloaded->strShippingMethod;
			$this->strShippingModule = $objReloaded->strShippingModule;
			$this->strShippingData = $objReloaded->strShippingData;
			$this->strShippingCost = $objReloaded->strShippingCost;
			$this->strShippingSell = $objReloaded->strShippingSell;
			$this->strPaymentMethod = $objReloaded->strPaymentMethod;
			$this->strPaymentModule = $objReloaded->strPaymentModule;
			$this->strPaymentData = $objReloaded->strPaymentData;
			$this->strPaymentAmount = $objReloaded->strPaymentAmount;
			$this->FkTaxCodeId = $objReloaded->FkTaxCodeId;
			$this->blnTaxInclusive = $objReloaded->blnTaxInclusive;
			$this->strSubtotal = $objReloaded->strSubtotal;
			$this->strTax1 = $objReloaded->strTax1;
			$this->strTax2 = $objReloaded->strTax2;
			$this->strTax3 = $objReloaded->strTax3;
			$this->strTax4 = $objReloaded->strTax4;
			$this->strTax5 = $objReloaded->strTax5;
			$this->strTotal = $objReloaded->strTotal;
			$this->intCount = $objReloaded->intCount;
			$this->blnDownloaded = $objReloaded->blnDownloaded;
			$this->strUser = $objReloaded->strUser;
			$this->strIpHost = $objReloaded->strIpHost;
			$this->CustomerId = $objReloaded->CustomerId;
			$this->GiftRegistry = $objReloaded->GiftRegistry;
			$this->strSendTo = $objReloaded->strSendTo;
			$this->dttSubmitted = $objReloaded->dttSubmitted;
			$this->strModified = $objReloaded->strModified;
			$this->strLinkid = $objReloaded->strLinkid;
			$this->intFkPromoId = $objReloaded->intFkPromoId;
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

				case 'IdStr':
					// Gets the value for strIdStr (Unique)
					// @return string
					return $this->strIdStr;

				case 'AddressBill':
					// Gets the value for strAddressBill 
					// @return string
					return $this->strAddressBill;

				case 'AddressShip':
					// Gets the value for strAddressShip 
					// @return string
					return $this->strAddressShip;

				case 'ShipFirstname':
					// Gets the value for strShipFirstname 
					// @return string
					return $this->strShipFirstname;

				case 'ShipLastname':
					// Gets the value for strShipLastname 
					// @return string
					return $this->strShipLastname;

				case 'ShipCompany':
					// Gets the value for strShipCompany 
					// @return string
					return $this->strShipCompany;

				case 'ShipAddress1':
					// Gets the value for strShipAddress1 
					// @return string
					return $this->strShipAddress1;

				case 'ShipAddress2':
					// Gets the value for strShipAddress2 
					// @return string
					return $this->strShipAddress2;

				case 'ShipCity':
					// Gets the value for strShipCity 
					// @return string
					return $this->strShipCity;

				case 'ShipZip':
					// Gets the value for strShipZip 
					// @return string
					return $this->strShipZip;

				case 'ShipState':
					// Gets the value for strShipState 
					// @return string
					return $this->strShipState;

				case 'ShipCountry':
					// Gets the value for strShipCountry 
					// @return string
					return $this->strShipCountry;

				case 'ShipPhone':
					// Gets the value for strShipPhone 
					// @return string
					return $this->strShipPhone;

				case 'Zipcode':
					// Gets the value for strZipcode 
					// @return string
					return $this->strZipcode;

				case 'Contact':
					// Gets the value for strContact 
					// @return string
					return $this->strContact;

				case 'Discount':
					// Gets the value for strDiscount 
					// @return string
					return $this->strDiscount;

				case 'Firstname':
					// Gets the value for strFirstname 
					// @return string
					return $this->strFirstname;

				case 'Lastname':
					// Gets the value for strLastname 
					// @return string
					return $this->strLastname;

				case 'Company':
					// Gets the value for strCompany 
					// @return string
					return $this->strCompany;

				case 'Name':
					// Gets the value for strName 
					// @return string
					return $this->strName;

				case 'Phone':
					// Gets the value for strPhone 
					// @return string
					return $this->strPhone;

				case 'Po':
					// Gets the value for strPo 
					// @return string
					return $this->strPo;

				case 'Type':
					// Gets the value for intType 
					// @return integer
					return $this->intType;

				case 'Status':
					// Gets the value for strStatus 
					// @return string
					return $this->strStatus;

				case 'CostTotal':
					// Gets the value for strCostTotal 
					// @return string
					return $this->strCostTotal;

				case 'Currency':
					// Gets the value for strCurrency 
					// @return string
					return $this->strCurrency;

				case 'CurrencyRate':
					// Gets the value for strCurrencyRate 
					// @return string
					return $this->strCurrencyRate;

				case 'DatetimeCre':
					// Gets the value for dttDatetimeCre 
					// @return QDateTime
					return $this->dttDatetimeCre;

				case 'DatetimeDue':
					// Gets the value for dttDatetimeDue 
					// @return QDateTime
					return $this->dttDatetimeDue;

				case 'DatetimePosted':
					// Gets the value for dttDatetimePosted 
					// @return QDateTime
					return $this->dttDatetimePosted;

				case 'Email':
					// Gets the value for strEmail 
					// @return string
					return $this->strEmail;

				case 'SellTotal':
					// Gets the value for strSellTotal 
					// @return string
					return $this->strSellTotal;

				case 'PrintedNotes':
					// Gets the value for strPrintedNotes 
					// @return string
					return $this->strPrintedNotes;

				case 'ShippingMethod':
					// Gets the value for strShippingMethod 
					// @return string
					return $this->strShippingMethod;

				case 'ShippingModule':
					// Gets the value for strShippingModule 
					// @return string
					return $this->strShippingModule;

				case 'ShippingData':
					// Gets the value for strShippingData 
					// @return string
					return $this->strShippingData;

				case 'ShippingCost':
					// Gets the value for strShippingCost 
					// @return string
					return $this->strShippingCost;

				case 'ShippingSell':
					// Gets the value for strShippingSell 
					// @return string
					return $this->strShippingSell;

				case 'PaymentMethod':
					// Gets the value for strPaymentMethod 
					// @return string
					return $this->strPaymentMethod;

				case 'PaymentModule':
					// Gets the value for strPaymentModule 
					// @return string
					return $this->strPaymentModule;

				case 'PaymentData':
					// Gets the value for strPaymentData 
					// @return string
					return $this->strPaymentData;

				case 'PaymentAmount':
					// Gets the value for strPaymentAmount 
					// @return string
					return $this->strPaymentAmount;

				case 'FkTaxCodeId':
					// Gets the value for intFkTaxCodeId 
					// @return integer
					return $this->intFkTaxCodeId;

				case 'TaxInclusive':
					// Gets the value for blnTaxInclusive 
					// @return boolean
					return $this->blnTaxInclusive;

				case 'Subtotal':
					// Gets the value for strSubtotal 
					// @return string
					return $this->strSubtotal;

				case 'Tax1':
					// Gets the value for strTax1 
					// @return string
					return $this->strTax1;

				case 'Tax2':
					// Gets the value for strTax2 
					// @return string
					return $this->strTax2;

				case 'Tax3':
					// Gets the value for strTax3 
					// @return string
					return $this->strTax3;

				case 'Tax4':
					// Gets the value for strTax4 
					// @return string
					return $this->strTax4;

				case 'Tax5':
					// Gets the value for strTax5 
					// @return string
					return $this->strTax5;

				case 'Total':
					// Gets the value for strTotal 
					// @return string
					return $this->strTotal;

				case 'Count':
					// Gets the value for intCount 
					// @return integer
					return $this->intCount;

				case 'Downloaded':
					// Gets the value for blnDownloaded 
					// @return boolean
					return $this->blnDownloaded;

				case 'User':
					// Gets the value for strUser 
					// @return string
					return $this->strUser;

				case 'IpHost':
					// Gets the value for strIpHost 
					// @return string
					return $this->strIpHost;

				case 'CustomerId':
					// Gets the value for intCustomerId 
					// @return integer
					return $this->intCustomerId;

				case 'GiftRegistry':
					// Gets the value for intGiftRegistry 
					// @return integer
					return $this->intGiftRegistry;

				case 'SendTo':
					// Gets the value for strSendTo 
					// @return string
					return $this->strSendTo;

				case 'Submitted':
					// Gets the value for dttSubmitted 
					// @return QDateTime
					return $this->dttSubmitted;

				case 'Modified':
					// Gets the value for strModified (Read-Only Timestamp)
					// @return string
					return $this->strModified;

				case 'Linkid':
					// Gets the value for strLinkid 
					// @return string
					return $this->strLinkid;

				case 'FkPromoId':
					// Gets the value for intFkPromoId 
					// @return integer
					return $this->intFkPromoId;


				///////////////////
				// Member Objects
				///////////////////
				case 'FkTaxCode':
					// Gets the value for the TaxCode object referenced by intFkTaxCodeId 
					// @return TaxCode
					try {
						if ((!$this->objFkTaxCode) && (!is_null($this->intFkTaxCodeId)))
							$this->objFkTaxCode = TaxCode::Load($this->intFkTaxCodeId);
						return $this->objFkTaxCode;
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

				case 'GiftRegistryObject':
					// Gets the value for the GiftRegistry object referenced by intGiftRegistry 
					// @return GiftRegistry
					try {
						if ((!$this->objGiftRegistryObject) && (!is_null($this->intGiftRegistry)))
							$this->objGiftRegistryObject = GiftRegistry::Load($this->intGiftRegistry);
						return $this->objGiftRegistryObject;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_CartItem':
					// Gets the value for the private _objCartItem (Read-Only)
					// if set due to an expansion on the xlsws_cart_item.cart_id reverse relationship
					// @return CartItem
					return $this->_objCartItem;

				case '_CartItemArray':
					// Gets the value for the private _objCartItemArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_cart_item.cart_id reverse relationship
					// @return CartItem[]
					return (array) $this->_objCartItemArray;

				case '_Sro':
					// Gets the value for the private _objSro (Read-Only)
					// if set due to an expansion on the xlsws_sro.cart_id reverse relationship
					// @return Sro
					return $this->_objSro;

				case '_SroArray':
					// Gets the value for the private _objSroArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_sro.cart_id reverse relationship
					// @return Sro[]
					return (array) $this->_objSroArray;


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
				case 'IdStr':
					// Sets the value for strIdStr (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strIdStr = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AddressBill':
					// Sets the value for strAddressBill 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAddressBill = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AddressShip':
					// Sets the value for strAddressShip 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAddressShip = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipFirstname':
					// Sets the value for strShipFirstname 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipFirstname = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipLastname':
					// Sets the value for strShipLastname 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipLastname = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipCompany':
					// Sets the value for strShipCompany 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipCompany = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipAddress1':
					// Sets the value for strShipAddress1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipAddress1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipAddress2':
					// Sets the value for strShipAddress2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipAddress2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipCity':
					// Sets the value for strShipCity 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipCity = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipZip':
					// Sets the value for strShipZip 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipZip = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipState':
					// Sets the value for strShipState 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipState = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipCountry':
					// Sets the value for strShipCountry 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipCountry = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShipPhone':
					// Sets the value for strShipPhone 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShipPhone = QType::Cast($mixValue, QType::String));
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

				case 'Contact':
					// Sets the value for strContact 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strContact = QType::Cast($mixValue, QType::String));
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

				case 'Firstname':
					// Sets the value for strFirstname 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strFirstname = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Lastname':
					// Sets the value for strLastname 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strLastname = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Company':
					// Sets the value for strCompany 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCompany = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Name':
					// Sets the value for strName 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strName = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phone':
					// Sets the value for strPhone 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhone = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Po':
					// Sets the value for strPo 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPo = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Type':
					// Sets the value for intType 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intType = QType::Cast($mixValue, QType::Integer));
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

				case 'CostTotal':
					// Sets the value for strCostTotal 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCostTotal = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Currency':
					// Sets the value for strCurrency 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCurrency = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'CurrencyRate':
					// Sets the value for strCurrencyRate 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCurrencyRate = QType::Cast($mixValue, QType::String));
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

				case 'DatetimeDue':
					// Sets the value for dttDatetimeDue 
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttDatetimeDue = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'DatetimePosted':
					// Sets the value for dttDatetimePosted 
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttDatetimePosted = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Email':
					// Sets the value for strEmail 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strEmail = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SellTotal':
					// Sets the value for strSellTotal 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSellTotal = QType::Cast($mixValue, QType::String));
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

				case 'ShippingMethod':
					// Sets the value for strShippingMethod 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShippingMethod = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShippingModule':
					// Sets the value for strShippingModule 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShippingModule = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShippingData':
					// Sets the value for strShippingData 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShippingData = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShippingCost':
					// Sets the value for strShippingCost 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShippingCost = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'ShippingSell':
					// Sets the value for strShippingSell 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strShippingSell = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PaymentMethod':
					// Sets the value for strPaymentMethod 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPaymentMethod = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PaymentModule':
					// Sets the value for strPaymentModule 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPaymentModule = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PaymentData':
					// Sets the value for strPaymentData 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPaymentData = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'PaymentAmount':
					// Sets the value for strPaymentAmount 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPaymentAmount = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FkTaxCodeId':
					// Sets the value for intFkTaxCodeId 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objFkTaxCode = null;
						return ($this->intFkTaxCodeId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'TaxInclusive':
					// Sets the value for blnTaxInclusive 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnTaxInclusive = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Subtotal':
					// Sets the value for strSubtotal 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSubtotal = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax1':
					// Sets the value for strTax1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax2':
					// Sets the value for strTax2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax3':
					// Sets the value for strTax3 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax3 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax4':
					// Sets the value for strTax4 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax4 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Tax5':
					// Sets the value for strTax5 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTax5 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Total':
					// Sets the value for strTotal 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTotal = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Count':
					// Sets the value for intCount 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intCount = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Downloaded':
					// Sets the value for blnDownloaded 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnDownloaded = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'User':
					// Sets the value for strUser 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strUser = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'IpHost':
					// Sets the value for strIpHost 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strIpHost = QType::Cast($mixValue, QType::String));
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

				case 'GiftRegistry':
					// Sets the value for intGiftRegistry 
					// @param integer $mixValue
					// @return integer
					try {
						$this->objGiftRegistryObject = null;
						return ($this->intGiftRegistry = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'SendTo':
					// Sets the value for strSendTo 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strSendTo = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Submitted':
					// Sets the value for dttSubmitted 
					// @param QDateTime $mixValue
					// @return QDateTime
					try {
						return ($this->dttSubmitted = QType::Cast($mixValue, QType::DateTime));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Linkid':
					// Sets the value for strLinkid 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strLinkid = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'FkPromoId':
					// Sets the value for intFkPromoId 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intFkPromoId = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				///////////////////
				// Member Objects
				///////////////////
				case 'FkTaxCode':
					// Sets the value for the TaxCode object referenced by intFkTaxCodeId 
					// @param TaxCode $mixValue
					// @return TaxCode
					if (is_null($mixValue)) {
						$this->intFkTaxCodeId = null;
						$this->objFkTaxCode = null;
						return null;
					} else {
						// Make sure $mixValue actually is a TaxCode object
						try {
							$mixValue = QType::Cast($mixValue, 'TaxCode');
						} catch (QInvalidCastException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						} 

						// Make sure $mixValue is a SAVED TaxCode object
						if (is_null($mixValue->Rowid))
							throw new QCallerException('Unable to set an unsaved FkTaxCode for this Cart');

						// Update Local Member Variables
						$this->objFkTaxCode = $mixValue;
						$this->intFkTaxCodeId = $mixValue->Rowid;

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
							throw new QCallerException('Unable to set an unsaved Customer for this Cart');

						// Update Local Member Variables
						$this->objCustomer = $mixValue;
						$this->intCustomerId = $mixValue->Rowid;

						// Return $mixValue
						return $mixValue;
					}
					break;

				case 'GiftRegistryObject':
					// Sets the value for the GiftRegistry object referenced by intGiftRegistry 
					// @param GiftRegistry $mixValue
					// @return GiftRegistry
					if (is_null($mixValue)) {
						$this->intGiftRegistry = null;
						$this->objGiftRegistryObject = null;
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
							throw new QCallerException('Unable to set an unsaved GiftRegistryObject for this Cart');

						// Update Local Member Variables
						$this->objGiftRegistryObject = $mixValue;
						$this->intGiftRegistry = $mixValue->Rowid;

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
				return CartItem::LoadArrayByCartId($this->intRowid, $objOptionalClauses);
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

			return CartItem::CountByCartId($this->intRowid);
		}

		/**
		 * Associates a CartItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function AssociateCartItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartItem on this unsaved Cart.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCartItem on this Cart with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
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
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Cart.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this Cart with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`cart_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . ' AND
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all CartItems
		 * @return void
		*/ 
		public function UnassociateAllCartItems() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart_item`
				SET
					`cart_id` = null
				WHERE
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated CartItem
		 * @param CartItem $objCartItem
		 * @return void
		*/ 
		public function DeleteAssociatedCartItem(CartItem $objCartItem) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Cart.');
			if ((is_null($objCartItem->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this Cart with an unsaved CartItem.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCartItem->Rowid) . ' AND
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated CartItems
		 * @return void
		*/ 
		public function DeleteAllCartItems() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCartItem on this unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart_item`
				WHERE
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for Sro
		//-------------------------------------------------------------------

		/**
		 * Gets all associated Sros as an array of Sro objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Sro[]
		*/ 
		public function GetSroArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Sro::LoadArrayByCartId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated Sros
		 * @return int
		*/ 
		public function CountSros() {
			if ((is_null($this->intRowid)))
				return 0;

			return Sro::CountByCartId($this->intRowid);
		}

		/**
		 * Associates a Sro
		 * @param Sro $objSro
		 * @return void
		*/ 
		public function AssociateSro(Sro $objSro) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateSro on this unsaved Cart.');
			if ((is_null($objSro->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateSro on this Cart with an unsaved Sro.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_sro`
				SET
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objSro->Rowid) . '
			');
		}

		/**
		 * Unassociates a Sro
		 * @param Sro $objSro
		 * @return void
		*/ 
		public function UnassociateSro(Sro $objSro) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSro on this unsaved Cart.');
			if ((is_null($objSro->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSro on this Cart with an unsaved Sro.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_sro`
				SET
					`cart_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objSro->Rowid) . ' AND
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all Sros
		 * @return void
		*/ 
		public function UnassociateAllSros() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSro on this unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_sro`
				SET
					`cart_id` = null
				WHERE
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated Sro
		 * @param Sro $objSro
		 * @return void
		*/ 
		public function DeleteAssociatedSro(Sro $objSro) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSro on this unsaved Cart.');
			if ((is_null($objSro->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSro on this Cart with an unsaved Sro.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objSro->Rowid) . ' AND
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated Sros
		 * @return void
		*/ 
		public function DeleteAllSros() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateSro on this unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Cart::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_sro`
				WHERE
					`cart_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Cart"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="IdStr" type="xsd:string"/>';
			$strToReturn .= '<element name="AddressBill" type="xsd:string"/>';
			$strToReturn .= '<element name="AddressShip" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipFirstname" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipLastname" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipCompany" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipAddress1" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipAddress2" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipCity" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipZip" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipState" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipCountry" type="xsd:string"/>';
			$strToReturn .= '<element name="ShipPhone" type="xsd:string"/>';
			$strToReturn .= '<element name="Zipcode" type="xsd:string"/>';
			$strToReturn .= '<element name="Contact" type="xsd:string"/>';
			$strToReturn .= '<element name="Discount" type="xsd:string"/>';
			$strToReturn .= '<element name="Firstname" type="xsd:string"/>';
			$strToReturn .= '<element name="Lastname" type="xsd:string"/>';
			$strToReturn .= '<element name="Company" type="xsd:string"/>';
			$strToReturn .= '<element name="Name" type="xsd:string"/>';
			$strToReturn .= '<element name="Phone" type="xsd:string"/>';
			$strToReturn .= '<element name="Po" type="xsd:string"/>';
			$strToReturn .= '<element name="Type" type="xsd:int"/>';
			$strToReturn .= '<element name="Status" type="xsd:string"/>';
			$strToReturn .= '<element name="CostTotal" type="xsd:string"/>';
			$strToReturn .= '<element name="Currency" type="xsd:string"/>';
			$strToReturn .= '<element name="CurrencyRate" type="xsd:string"/>';
			$strToReturn .= '<element name="DatetimeCre" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="DatetimeDue" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="DatetimePosted" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Email" type="xsd:string"/>';
			$strToReturn .= '<element name="SellTotal" type="xsd:string"/>';
			$strToReturn .= '<element name="PrintedNotes" type="xsd:string"/>';
			$strToReturn .= '<element name="ShippingMethod" type="xsd:string"/>';
			$strToReturn .= '<element name="ShippingModule" type="xsd:string"/>';
			$strToReturn .= '<element name="ShippingData" type="xsd:string"/>';
			$strToReturn .= '<element name="ShippingCost" type="xsd:string"/>';
			$strToReturn .= '<element name="ShippingSell" type="xsd:string"/>';
			$strToReturn .= '<element name="PaymentMethod" type="xsd:string"/>';
			$strToReturn .= '<element name="PaymentModule" type="xsd:string"/>';
			$strToReturn .= '<element name="PaymentData" type="xsd:string"/>';
			$strToReturn .= '<element name="PaymentAmount" type="xsd:string"/>';
			$strToReturn .= '<element name="FkTaxCode" type="xsd1:TaxCode"/>';
			$strToReturn .= '<element name="TaxInclusive" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Subtotal" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax1" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax2" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax3" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax4" type="xsd:string"/>';
			$strToReturn .= '<element name="Tax5" type="xsd:string"/>';
			$strToReturn .= '<element name="Total" type="xsd:string"/>';
			$strToReturn .= '<element name="Count" type="xsd:int"/>';
			$strToReturn .= '<element name="Downloaded" type="xsd:boolean"/>';
			$strToReturn .= '<element name="User" type="xsd:string"/>';
			$strToReturn .= '<element name="IpHost" type="xsd:string"/>';
			$strToReturn .= '<element name="Customer" type="xsd1:Customer"/>';
			$strToReturn .= '<element name="GiftRegistryObject" type="xsd1:GiftRegistry"/>';
			$strToReturn .= '<element name="SendTo" type="xsd:string"/>';
			$strToReturn .= '<element name="Submitted" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="Linkid" type="xsd:string"/>';
			$strToReturn .= '<element name="FkPromoId" type="xsd:int"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Cart', $strComplexTypeArray)) {
				$strComplexTypeArray['Cart'] = Cart::GetSoapComplexTypeXml();
				TaxCode::AlterSoapComplexTypeArray($strComplexTypeArray);
				Customer::AlterSoapComplexTypeArray($strComplexTypeArray);
				GiftRegistry::AlterSoapComplexTypeArray($strComplexTypeArray);
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Cart::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Cart();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'IdStr'))
				$objToReturn->strIdStr = $objSoapObject->IdStr;
			if (property_exists($objSoapObject, 'AddressBill'))
				$objToReturn->strAddressBill = $objSoapObject->AddressBill;
			if (property_exists($objSoapObject, 'AddressShip'))
				$objToReturn->strAddressShip = $objSoapObject->AddressShip;
			if (property_exists($objSoapObject, 'ShipFirstname'))
				$objToReturn->strShipFirstname = $objSoapObject->ShipFirstname;
			if (property_exists($objSoapObject, 'ShipLastname'))
				$objToReturn->strShipLastname = $objSoapObject->ShipLastname;
			if (property_exists($objSoapObject, 'ShipCompany'))
				$objToReturn->strShipCompany = $objSoapObject->ShipCompany;
			if (property_exists($objSoapObject, 'ShipAddress1'))
				$objToReturn->strShipAddress1 = $objSoapObject->ShipAddress1;
			if (property_exists($objSoapObject, 'ShipAddress2'))
				$objToReturn->strShipAddress2 = $objSoapObject->ShipAddress2;
			if (property_exists($objSoapObject, 'ShipCity'))
				$objToReturn->strShipCity = $objSoapObject->ShipCity;
			if (property_exists($objSoapObject, 'ShipZip'))
				$objToReturn->strShipZip = $objSoapObject->ShipZip;
			if (property_exists($objSoapObject, 'ShipState'))
				$objToReturn->strShipState = $objSoapObject->ShipState;
			if (property_exists($objSoapObject, 'ShipCountry'))
				$objToReturn->strShipCountry = $objSoapObject->ShipCountry;
			if (property_exists($objSoapObject, 'ShipPhone'))
				$objToReturn->strShipPhone = $objSoapObject->ShipPhone;
			if (property_exists($objSoapObject, 'Zipcode'))
				$objToReturn->strZipcode = $objSoapObject->Zipcode;
			if (property_exists($objSoapObject, 'Contact'))
				$objToReturn->strContact = $objSoapObject->Contact;
			if (property_exists($objSoapObject, 'Discount'))
				$objToReturn->strDiscount = $objSoapObject->Discount;
			if (property_exists($objSoapObject, 'Firstname'))
				$objToReturn->strFirstname = $objSoapObject->Firstname;
			if (property_exists($objSoapObject, 'Lastname'))
				$objToReturn->strLastname = $objSoapObject->Lastname;
			if (property_exists($objSoapObject, 'Company'))
				$objToReturn->strCompany = $objSoapObject->Company;
			if (property_exists($objSoapObject, 'Name'))
				$objToReturn->strName = $objSoapObject->Name;
			if (property_exists($objSoapObject, 'Phone'))
				$objToReturn->strPhone = $objSoapObject->Phone;
			if (property_exists($objSoapObject, 'Po'))
				$objToReturn->strPo = $objSoapObject->Po;
			if (property_exists($objSoapObject, 'Type'))
				$objToReturn->intType = $objSoapObject->Type;
			if (property_exists($objSoapObject, 'Status'))
				$objToReturn->strStatus = $objSoapObject->Status;
			if (property_exists($objSoapObject, 'CostTotal'))
				$objToReturn->strCostTotal = $objSoapObject->CostTotal;
			if (property_exists($objSoapObject, 'Currency'))
				$objToReturn->strCurrency = $objSoapObject->Currency;
			if (property_exists($objSoapObject, 'CurrencyRate'))
				$objToReturn->strCurrencyRate = $objSoapObject->CurrencyRate;
			if (property_exists($objSoapObject, 'DatetimeCre'))
				$objToReturn->dttDatetimeCre = new QDateTime($objSoapObject->DatetimeCre);
			if (property_exists($objSoapObject, 'DatetimeDue'))
				$objToReturn->dttDatetimeDue = new QDateTime($objSoapObject->DatetimeDue);
			if (property_exists($objSoapObject, 'DatetimePosted'))
				$objToReturn->dttDatetimePosted = new QDateTime($objSoapObject->DatetimePosted);
			if (property_exists($objSoapObject, 'Email'))
				$objToReturn->strEmail = $objSoapObject->Email;
			if (property_exists($objSoapObject, 'SellTotal'))
				$objToReturn->strSellTotal = $objSoapObject->SellTotal;
			if (property_exists($objSoapObject, 'PrintedNotes'))
				$objToReturn->strPrintedNotes = $objSoapObject->PrintedNotes;
			if (property_exists($objSoapObject, 'ShippingMethod'))
				$objToReturn->strShippingMethod = $objSoapObject->ShippingMethod;
			if (property_exists($objSoapObject, 'ShippingModule'))
				$objToReturn->strShippingModule = $objSoapObject->ShippingModule;
			if (property_exists($objSoapObject, 'ShippingData'))
				$objToReturn->strShippingData = $objSoapObject->ShippingData;
			if (property_exists($objSoapObject, 'ShippingCost'))
				$objToReturn->strShippingCost = $objSoapObject->ShippingCost;
			if (property_exists($objSoapObject, 'ShippingSell'))
				$objToReturn->strShippingSell = $objSoapObject->ShippingSell;
			if (property_exists($objSoapObject, 'PaymentMethod'))
				$objToReturn->strPaymentMethod = $objSoapObject->PaymentMethod;
			if (property_exists($objSoapObject, 'PaymentModule'))
				$objToReturn->strPaymentModule = $objSoapObject->PaymentModule;
			if (property_exists($objSoapObject, 'PaymentData'))
				$objToReturn->strPaymentData = $objSoapObject->PaymentData;
			if (property_exists($objSoapObject, 'PaymentAmount'))
				$objToReturn->strPaymentAmount = $objSoapObject->PaymentAmount;
			if ((property_exists($objSoapObject, 'FkTaxCode')) &&
				($objSoapObject->FkTaxCode))
				$objToReturn->FkTaxCode = TaxCode::GetObjectFromSoapObject($objSoapObject->FkTaxCode);
			if (property_exists($objSoapObject, 'TaxInclusive'))
				$objToReturn->blnTaxInclusive = $objSoapObject->TaxInclusive;
			if (property_exists($objSoapObject, 'Subtotal'))
				$objToReturn->strSubtotal = $objSoapObject->Subtotal;
			if (property_exists($objSoapObject, 'Tax1'))
				$objToReturn->strTax1 = $objSoapObject->Tax1;
			if (property_exists($objSoapObject, 'Tax2'))
				$objToReturn->strTax2 = $objSoapObject->Tax2;
			if (property_exists($objSoapObject, 'Tax3'))
				$objToReturn->strTax3 = $objSoapObject->Tax3;
			if (property_exists($objSoapObject, 'Tax4'))
				$objToReturn->strTax4 = $objSoapObject->Tax4;
			if (property_exists($objSoapObject, 'Tax5'))
				$objToReturn->strTax5 = $objSoapObject->Tax5;
			if (property_exists($objSoapObject, 'Total'))
				$objToReturn->strTotal = $objSoapObject->Total;
			if (property_exists($objSoapObject, 'Count'))
				$objToReturn->intCount = $objSoapObject->Count;
			if (property_exists($objSoapObject, 'Downloaded'))
				$objToReturn->blnDownloaded = $objSoapObject->Downloaded;
			if (property_exists($objSoapObject, 'User'))
				$objToReturn->strUser = $objSoapObject->User;
			if (property_exists($objSoapObject, 'IpHost'))
				$objToReturn->strIpHost = $objSoapObject->IpHost;
			if ((property_exists($objSoapObject, 'Customer')) &&
				($objSoapObject->Customer))
				$objToReturn->Customer = Customer::GetObjectFromSoapObject($objSoapObject->Customer);
			if ((property_exists($objSoapObject, 'GiftRegistryObject')) &&
				($objSoapObject->GiftRegistryObject))
				$objToReturn->GiftRegistryObject = GiftRegistry::GetObjectFromSoapObject($objSoapObject->GiftRegistryObject);
			if (property_exists($objSoapObject, 'SendTo'))
				$objToReturn->strSendTo = $objSoapObject->SendTo;
			if (property_exists($objSoapObject, 'Submitted'))
				$objToReturn->dttSubmitted = new QDateTime($objSoapObject->Submitted);
			if (property_exists($objSoapObject, 'Modified'))
				$objToReturn->strModified = $objSoapObject->Modified;
			if (property_exists($objSoapObject, 'Linkid'))
				$objToReturn->strLinkid = $objSoapObject->Linkid;
			if (property_exists($objSoapObject, 'FkPromoId'))
				$objToReturn->intFkPromoId = $objSoapObject->FkPromoId;
			if (property_exists($objSoapObject, '__blnRestored'))
				$objToReturn->__blnRestored = $objSoapObject->__blnRestored;
			return $objToReturn;
		}

		public static function GetSoapArrayFromArray($objArray) {
			if (!$objArray)
				return null;

			$objArrayToReturn = array();

			foreach ($objArray as $objObject)
				array_push($objArrayToReturn, Cart::GetSoapObjectFromObject($objObject, true));

			return unserialize(serialize($objArrayToReturn));
		}

		public static function GetSoapObjectFromObject($objObject, $blnBindRelatedObjects) {
			if ($objObject->dttDatetimeCre)
				$objObject->dttDatetimeCre = $objObject->dttDatetimeCre->__toString(QDateTime::FormatSoap);
			if ($objObject->dttDatetimeDue)
				$objObject->dttDatetimeDue = $objObject->dttDatetimeDue->__toString(QDateTime::FormatSoap);
			if ($objObject->dttDatetimePosted)
				$objObject->dttDatetimePosted = $objObject->dttDatetimePosted->__toString(QDateTime::FormatSoap);
			if ($objObject->objFkTaxCode)
				$objObject->objFkTaxCode = TaxCode::GetSoapObjectFromObject($objObject->objFkTaxCode, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intFkTaxCodeId = null;
			if ($objObject->objCustomer)
				$objObject->objCustomer = Customer::GetSoapObjectFromObject($objObject->objCustomer, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intCustomerId = null;
			if ($objObject->objGiftRegistryObject)
				$objObject->objGiftRegistryObject = GiftRegistry::GetSoapObjectFromObject($objObject->objGiftRegistryObject, false);
			else if (!$blnBindRelatedObjects)
				$objObject->intGiftRegistry = null;
			if ($objObject->dttSubmitted)
				$objObject->dttSubmitted = $objObject->dttSubmitted->__toString(QDateTime::FormatSoap);
			return $objObject;
		}




	}



	/////////////////////////////////////
	// ADDITIONAL CLASSES for QCODO QUERY
	/////////////////////////////////////

	class QQNodeCart extends QQNode {
		protected $strTableName = 'xlsws_cart';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Cart';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'IdStr':
					return new QQNode('id_str', 'IdStr', 'string', $this);
				case 'AddressBill':
					return new QQNode('address_bill', 'AddressBill', 'string', $this);
				case 'AddressShip':
					return new QQNode('address_ship', 'AddressShip', 'string', $this);
				case 'ShipFirstname':
					return new QQNode('ship_firstname', 'ShipFirstname', 'string', $this);
				case 'ShipLastname':
					return new QQNode('ship_lastname', 'ShipLastname', 'string', $this);
				case 'ShipCompany':
					return new QQNode('ship_company', 'ShipCompany', 'string', $this);
				case 'ShipAddress1':
					return new QQNode('ship_address1', 'ShipAddress1', 'string', $this);
				case 'ShipAddress2':
					return new QQNode('ship_address2', 'ShipAddress2', 'string', $this);
				case 'ShipCity':
					return new QQNode('ship_city', 'ShipCity', 'string', $this);
				case 'ShipZip':
					return new QQNode('ship_zip', 'ShipZip', 'string', $this);
				case 'ShipState':
					return new QQNode('ship_state', 'ShipState', 'string', $this);
				case 'ShipCountry':
					return new QQNode('ship_country', 'ShipCountry', 'string', $this);
				case 'ShipPhone':
					return new QQNode('ship_phone', 'ShipPhone', 'string', $this);
				case 'Zipcode':
					return new QQNode('zipcode', 'Zipcode', 'string', $this);
				case 'Contact':
					return new QQNode('contact', 'Contact', 'string', $this);
				case 'Discount':
					return new QQNode('discount', 'Discount', 'string', $this);
				case 'Firstname':
					return new QQNode('firstname', 'Firstname', 'string', $this);
				case 'Lastname':
					return new QQNode('lastname', 'Lastname', 'string', $this);
				case 'Company':
					return new QQNode('company', 'Company', 'string', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Phone':
					return new QQNode('phone', 'Phone', 'string', $this);
				case 'Po':
					return new QQNode('po', 'Po', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'integer', $this);
				case 'Status':
					return new QQNode('status', 'Status', 'string', $this);
				case 'CostTotal':
					return new QQNode('cost_total', 'CostTotal', 'string', $this);
				case 'Currency':
					return new QQNode('currency', 'Currency', 'string', $this);
				case 'CurrencyRate':
					return new QQNode('currency_rate', 'CurrencyRate', 'string', $this);
				case 'DatetimeCre':
					return new QQNode('datetime_cre', 'DatetimeCre', 'QDateTime', $this);
				case 'DatetimeDue':
					return new QQNode('datetime_due', 'DatetimeDue', 'QDateTime', $this);
				case 'DatetimePosted':
					return new QQNode('datetime_posted', 'DatetimePosted', 'QDateTime', $this);
				case 'Email':
					return new QQNode('email', 'Email', 'string', $this);
				case 'SellTotal':
					return new QQNode('sell_total', 'SellTotal', 'string', $this);
				case 'PrintedNotes':
					return new QQNode('printed_notes', 'PrintedNotes', 'string', $this);
				case 'ShippingMethod':
					return new QQNode('shipping_method', 'ShippingMethod', 'string', $this);
				case 'ShippingModule':
					return new QQNode('shipping_module', 'ShippingModule', 'string', $this);
				case 'ShippingData':
					return new QQNode('shipping_data', 'ShippingData', 'string', $this);
				case 'ShippingCost':
					return new QQNode('shipping_cost', 'ShippingCost', 'string', $this);
				case 'ShippingSell':
					return new QQNode('shipping_sell', 'ShippingSell', 'string', $this);
				case 'PaymentMethod':
					return new QQNode('payment_method', 'PaymentMethod', 'string', $this);
				case 'PaymentModule':
					return new QQNode('payment_module', 'PaymentModule', 'string', $this);
				case 'PaymentData':
					return new QQNode('payment_data', 'PaymentData', 'string', $this);
				case 'PaymentAmount':
					return new QQNode('payment_amount', 'PaymentAmount', 'string', $this);
				case 'FkTaxCodeId':
					return new QQNode('fk_tax_code_id', 'FkTaxCodeId', 'integer', $this);
				case 'FkTaxCode':
					return new QQNodeTaxCode('fk_tax_code_id', 'FkTaxCode', 'integer', $this);
				case 'TaxInclusive':
					return new QQNode('tax_inclusive', 'TaxInclusive', 'boolean', $this);
				case 'Subtotal':
					return new QQNode('subtotal', 'Subtotal', 'string', $this);
				case 'Tax1':
					return new QQNode('tax1', 'Tax1', 'string', $this);
				case 'Tax2':
					return new QQNode('tax2', 'Tax2', 'string', $this);
				case 'Tax3':
					return new QQNode('tax3', 'Tax3', 'string', $this);
				case 'Tax4':
					return new QQNode('tax4', 'Tax4', 'string', $this);
				case 'Tax5':
					return new QQNode('tax5', 'Tax5', 'string', $this);
				case 'Total':
					return new QQNode('total', 'Total', 'string', $this);
				case 'Count':
					return new QQNode('count', 'Count', 'integer', $this);
				case 'Downloaded':
					return new QQNode('downloaded', 'Downloaded', 'boolean', $this);
				case 'User':
					return new QQNode('user', 'User', 'string', $this);
				case 'IpHost':
					return new QQNode('ip_host', 'IpHost', 'string', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'GiftRegistry':
					return new QQNode('gift_registry', 'GiftRegistry', 'integer', $this);
				case 'GiftRegistryObject':
					return new QQNodeGiftRegistry('gift_registry', 'GiftRegistryObject', 'integer', $this);
				case 'SendTo':
					return new QQNode('send_to', 'SendTo', 'string', $this);
				case 'Submitted':
					return new QQNode('submitted', 'Submitted', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Linkid':
					return new QQNode('linkid', 'Linkid', 'string', $this);
				case 'FkPromoId':
					return new QQNode('fk_promo_id', 'FkPromoId', 'integer', $this);
				case 'CartItem':
					return new QQReverseReferenceNodeCartItem($this, 'cartitem', 'reverse_reference', 'cart_id');
				case 'Sro':
					return new QQReverseReferenceNodeSro($this, 'sro', 'reverse_reference', 'cart_id');

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

	class QQReverseReferenceNodeCart extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_cart';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Cart';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'IdStr':
					return new QQNode('id_str', 'IdStr', 'string', $this);
				case 'AddressBill':
					return new QQNode('address_bill', 'AddressBill', 'string', $this);
				case 'AddressShip':
					return new QQNode('address_ship', 'AddressShip', 'string', $this);
				case 'ShipFirstname':
					return new QQNode('ship_firstname', 'ShipFirstname', 'string', $this);
				case 'ShipLastname':
					return new QQNode('ship_lastname', 'ShipLastname', 'string', $this);
				case 'ShipCompany':
					return new QQNode('ship_company', 'ShipCompany', 'string', $this);
				case 'ShipAddress1':
					return new QQNode('ship_address1', 'ShipAddress1', 'string', $this);
				case 'ShipAddress2':
					return new QQNode('ship_address2', 'ShipAddress2', 'string', $this);
				case 'ShipCity':
					return new QQNode('ship_city', 'ShipCity', 'string', $this);
				case 'ShipZip':
					return new QQNode('ship_zip', 'ShipZip', 'string', $this);
				case 'ShipState':
					return new QQNode('ship_state', 'ShipState', 'string', $this);
				case 'ShipCountry':
					return new QQNode('ship_country', 'ShipCountry', 'string', $this);
				case 'ShipPhone':
					return new QQNode('ship_phone', 'ShipPhone', 'string', $this);
				case 'Zipcode':
					return new QQNode('zipcode', 'Zipcode', 'string', $this);
				case 'Contact':
					return new QQNode('contact', 'Contact', 'string', $this);
				case 'Discount':
					return new QQNode('discount', 'Discount', 'string', $this);
				case 'Firstname':
					return new QQNode('firstname', 'Firstname', 'string', $this);
				case 'Lastname':
					return new QQNode('lastname', 'Lastname', 'string', $this);
				case 'Company':
					return new QQNode('company', 'Company', 'string', $this);
				case 'Name':
					return new QQNode('name', 'Name', 'string', $this);
				case 'Phone':
					return new QQNode('phone', 'Phone', 'string', $this);
				case 'Po':
					return new QQNode('po', 'Po', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'integer', $this);
				case 'Status':
					return new QQNode('status', 'Status', 'string', $this);
				case 'CostTotal':
					return new QQNode('cost_total', 'CostTotal', 'string', $this);
				case 'Currency':
					return new QQNode('currency', 'Currency', 'string', $this);
				case 'CurrencyRate':
					return new QQNode('currency_rate', 'CurrencyRate', 'string', $this);
				case 'DatetimeCre':
					return new QQNode('datetime_cre', 'DatetimeCre', 'QDateTime', $this);
				case 'DatetimeDue':
					return new QQNode('datetime_due', 'DatetimeDue', 'QDateTime', $this);
				case 'DatetimePosted':
					return new QQNode('datetime_posted', 'DatetimePosted', 'QDateTime', $this);
				case 'Email':
					return new QQNode('email', 'Email', 'string', $this);
				case 'SellTotal':
					return new QQNode('sell_total', 'SellTotal', 'string', $this);
				case 'PrintedNotes':
					return new QQNode('printed_notes', 'PrintedNotes', 'string', $this);
				case 'ShippingMethod':
					return new QQNode('shipping_method', 'ShippingMethod', 'string', $this);
				case 'ShippingModule':
					return new QQNode('shipping_module', 'ShippingModule', 'string', $this);
				case 'ShippingData':
					return new QQNode('shipping_data', 'ShippingData', 'string', $this);
				case 'ShippingCost':
					return new QQNode('shipping_cost', 'ShippingCost', 'string', $this);
				case 'ShippingSell':
					return new QQNode('shipping_sell', 'ShippingSell', 'string', $this);
				case 'PaymentMethod':
					return new QQNode('payment_method', 'PaymentMethod', 'string', $this);
				case 'PaymentModule':
					return new QQNode('payment_module', 'PaymentModule', 'string', $this);
				case 'PaymentData':
					return new QQNode('payment_data', 'PaymentData', 'string', $this);
				case 'PaymentAmount':
					return new QQNode('payment_amount', 'PaymentAmount', 'string', $this);
				case 'FkTaxCodeId':
					return new QQNode('fk_tax_code_id', 'FkTaxCodeId', 'integer', $this);
				case 'FkTaxCode':
					return new QQNodeTaxCode('fk_tax_code_id', 'FkTaxCode', 'integer', $this);
				case 'TaxInclusive':
					return new QQNode('tax_inclusive', 'TaxInclusive', 'boolean', $this);
				case 'Subtotal':
					return new QQNode('subtotal', 'Subtotal', 'string', $this);
				case 'Tax1':
					return new QQNode('tax1', 'Tax1', 'string', $this);
				case 'Tax2':
					return new QQNode('tax2', 'Tax2', 'string', $this);
				case 'Tax3':
					return new QQNode('tax3', 'Tax3', 'string', $this);
				case 'Tax4':
					return new QQNode('tax4', 'Tax4', 'string', $this);
				case 'Tax5':
					return new QQNode('tax5', 'Tax5', 'string', $this);
				case 'Total':
					return new QQNode('total', 'Total', 'string', $this);
				case 'Count':
					return new QQNode('count', 'Count', 'integer', $this);
				case 'Downloaded':
					return new QQNode('downloaded', 'Downloaded', 'boolean', $this);
				case 'User':
					return new QQNode('user', 'User', 'string', $this);
				case 'IpHost':
					return new QQNode('ip_host', 'IpHost', 'string', $this);
				case 'CustomerId':
					return new QQNode('customer_id', 'CustomerId', 'integer', $this);
				case 'Customer':
					return new QQNodeCustomer('customer_id', 'Customer', 'integer', $this);
				case 'GiftRegistry':
					return new QQNode('gift_registry', 'GiftRegistry', 'integer', $this);
				case 'GiftRegistryObject':
					return new QQNodeGiftRegistry('gift_registry', 'GiftRegistryObject', 'integer', $this);
				case 'SendTo':
					return new QQNode('send_to', 'SendTo', 'string', $this);
				case 'Submitted':
					return new QQNode('submitted', 'Submitted', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Linkid':
					return new QQNode('linkid', 'Linkid', 'string', $this);
				case 'FkPromoId':
					return new QQNode('fk_promo_id', 'FkPromoId', 'integer', $this);
				case 'CartItem':
					return new QQReverseReferenceNodeCartItem($this, 'cartitem', 'reverse_reference', 'cart_id');
				case 'Sro':
					return new QQReverseReferenceNodeSro($this, 'sro', 'reverse_reference', 'cart_id');

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