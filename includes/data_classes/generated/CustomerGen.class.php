<?php
	/**
	 * The abstract CustomerGen class defined here is
	 * code-generated and contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 *
	 * To use, you should use the Customer subclass which
	 * extends this CustomerGen class.
	 *
	 * Because subsequent re-code generations will overwrite any changes to this
	 * file, you should leave this file unaltered to prevent yourself from losing
	 * any information or code changes.  All customizations should be done by
	 * overriding existing or implementing new methods, properties and variables
	 * in the Customer class.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage GeneratedDataObjects
	 * @property integer $Rowid the value for intRowid (Read-Only PK)
	 * @property string $Address11 the value for strAddress11 
	 * @property string $Address12 the value for strAddress12 
	 * @property string $Address21 the value for strAddress21 
	 * @property string $Address22 the value for strAddress22 
	 * @property string $City1 the value for strCity1 
	 * @property string $City2 the value for strCity2 
	 * @property string $Company the value for strCompany 
	 * @property string $Country1 the value for strCountry1 
	 * @property string $Country2 the value for strCountry2 
	 * @property string $Currency the value for strCurrency 
	 * @property string $Email the value for strEmail (Unique)
	 * @property string $Firstname the value for strFirstname 
	 * @property integer $PricingLevel the value for intPricingLevel 
	 * @property string $Homepage the value for strHomepage 
	 * @property string $IdCustomer the value for strIdCustomer 
	 * @property string $Language the value for strLanguage 
	 * @property string $Lastname the value for strLastname 
	 * @property string $Mainname the value for strMainname 
	 * @property string $Mainphone the value for strMainphone 
	 * @property string $Mainephonetype the value for strMainephonetype 
	 * @property string $Phone1 the value for strPhone1 
	 * @property string $Phonetype1 the value for strPhonetype1 
	 * @property string $Phone2 the value for strPhone2 
	 * @property string $Phonetype2 the value for strPhonetype2 
	 * @property string $Phone3 the value for strPhone3 
	 * @property string $Phonetype3 the value for strPhonetype3 
	 * @property string $Phone4 the value for strPhone4 
	 * @property string $Phonetype4 the value for strPhonetype4 
	 * @property string $State1 the value for strState1 
	 * @property string $State2 the value for strState2 
	 * @property string $Type the value for strType 
	 * @property string $User the value for strUser 
	 * @property string $Zip1 the value for strZip1 
	 * @property string $Zip2 the value for strZip2 
	 * @property boolean $NewsletterSubscribe the value for blnNewsletterSubscribe 
	 * @property boolean $HtmlEmail the value for blnHtmlEmail 
	 * @property string $Password the value for strPassword 
	 * @property string $TempPassword the value for strTempPassword 
	 * @property boolean $AllowLogin the value for blnAllowLogin 
	 * @property QDateTime $Created the value for dttCreated (Not Null)
	 * @property string $Modified the value for strModified (Read-Only Timestamp)
	 * @property Cart $_Cart the value for the private _objCart (Read-Only) if set due to an expansion on the xlsws_cart.customer_id reverse relationship
	 * @property Cart[] $_CartArray the value for the private _objCartArray (Read-Only) if set due to an ExpandAsArray on the xlsws_cart.customer_id reverse relationship
	 * @property GiftRegistry $_GiftRegistry the value for the private _objGiftRegistry (Read-Only) if set due to an expansion on the xlsws_gift_registry.customer_id reverse relationship
	 * @property GiftRegistry[] $_GiftRegistryArray the value for the private _objGiftRegistryArray (Read-Only) if set due to an ExpandAsArray on the xlsws_gift_registry.customer_id reverse relationship
	 * @property GiftRegistryReceipents $_GiftRegistryReceipents the value for the private _objGiftRegistryReceipents (Read-Only) if set due to an expansion on the xlsws_gift_registry_receipents.customer_id reverse relationship
	 * @property GiftRegistryReceipents[] $_GiftRegistryReceipentsArray the value for the private _objGiftRegistryReceipentsArray (Read-Only) if set due to an ExpandAsArray on the xlsws_gift_registry_receipents.customer_id reverse relationship
	 * @property Visitor $_Visitor the value for the private _objVisitor (Read-Only) if set due to an expansion on the xlsws_visitor.customer_id reverse relationship
	 * @property Visitor[] $_VisitorArray the value for the private _objVisitorArray (Read-Only) if set due to an ExpandAsArray on the xlsws_visitor.customer_id reverse relationship
	 * @property boolean $__Restored whether or not this object was restored from the database (as opposed to created new)
	 */
	class CustomerGen extends QBaseClass {

		///////////////////////////////////////////////////////////////////////
		// PROTECTED MEMBER VARIABLES and TEXT FIELD MAXLENGTHS (if applicable)
		///////////////////////////////////////////////////////////////////////
		
		/**
		 * Protected member variable that maps to the database PK Identity column xlsws_customer.rowid
		 * @var integer intRowid
		 */
		protected $intRowid;
		const RowidDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.address1_1
		 * @var string strAddress11
		 */
		protected $strAddress11;
		const Address11MaxLength = 255;
		const Address11Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.address1_2
		 * @var string strAddress12
		 */
		protected $strAddress12;
		const Address12MaxLength = 255;
		const Address12Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.address2_1
		 * @var string strAddress21
		 */
		protected $strAddress21;
		const Address21MaxLength = 255;
		const Address21Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.address_2_2
		 * @var string strAddress22
		 */
		protected $strAddress22;
		const Address22MaxLength = 255;
		const Address22Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.city1
		 * @var string strCity1
		 */
		protected $strCity1;
		const City1MaxLength = 64;
		const City1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.city2
		 * @var string strCity2
		 */
		protected $strCity2;
		const City2MaxLength = 64;
		const City2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.company
		 * @var string strCompany
		 */
		protected $strCompany;
		const CompanyMaxLength = 255;
		const CompanyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.country1
		 * @var string strCountry1
		 */
		protected $strCountry1;
		const Country1MaxLength = 32;
		const Country1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.country2
		 * @var string strCountry2
		 */
		protected $strCountry2;
		const Country2MaxLength = 32;
		const Country2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.currency
		 * @var string strCurrency
		 */
		protected $strCurrency;
		const CurrencyMaxLength = 3;
		const CurrencyDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.email
		 * @var string strEmail
		 */
		protected $strEmail;
		const EmailMaxLength = 255;
		const EmailDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.firstname
		 * @var string strFirstname
		 */
		protected $strFirstname;
		const FirstnameMaxLength = 64;
		const FirstnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.pricing_level
		 * @var integer intPricingLevel
		 */
		protected $intPricingLevel;
		const PricingLevelDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.homepage
		 * @var string strHomepage
		 */
		protected $strHomepage;
		const HomepageMaxLength = 255;
		const HomepageDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.id_customer
		 * @var string strIdCustomer
		 */
		protected $strIdCustomer;
		const IdCustomerMaxLength = 32;
		const IdCustomerDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.language
		 * @var string strLanguage
		 */
		protected $strLanguage;
		const LanguageMaxLength = 8;
		const LanguageDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.lastname
		 * @var string strLastname
		 */
		protected $strLastname;
		const LastnameMaxLength = 64;
		const LastnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.mainname
		 * @var string strMainname
		 */
		protected $strMainname;
		const MainnameMaxLength = 255;
		const MainnameDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.mainphone
		 * @var string strMainphone
		 */
		protected $strMainphone;
		const MainphoneMaxLength = 32;
		const MainphoneDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.mainephonetype
		 * @var string strMainephonetype
		 */
		protected $strMainephonetype;
		const MainephonetypeMaxLength = 8;
		const MainephonetypeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phone1
		 * @var string strPhone1
		 */
		protected $strPhone1;
		const Phone1MaxLength = 32;
		const Phone1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phonetype1
		 * @var string strPhonetype1
		 */
		protected $strPhonetype1;
		const Phonetype1MaxLength = 8;
		const Phonetype1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phone2
		 * @var string strPhone2
		 */
		protected $strPhone2;
		const Phone2MaxLength = 32;
		const Phone2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phonetype2
		 * @var string strPhonetype2
		 */
		protected $strPhonetype2;
		const Phonetype2MaxLength = 8;
		const Phonetype2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phone3
		 * @var string strPhone3
		 */
		protected $strPhone3;
		const Phone3MaxLength = 32;
		const Phone3Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phonetype3
		 * @var string strPhonetype3
		 */
		protected $strPhonetype3;
		const Phonetype3MaxLength = 8;
		const Phonetype3Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phone4
		 * @var string strPhone4
		 */
		protected $strPhone4;
		const Phone4MaxLength = 32;
		const Phone4Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.phonetype4
		 * @var string strPhonetype4
		 */
		protected $strPhonetype4;
		const Phonetype4MaxLength = 8;
		const Phonetype4Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.state1
		 * @var string strState1
		 */
		protected $strState1;
		const State1MaxLength = 32;
		const State1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.state2
		 * @var string strState2
		 */
		protected $strState2;
		const State2MaxLength = 32;
		const State2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.type
		 * @var string strType
		 */
		protected $strType;
		const TypeMaxLength = 1;
		const TypeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.user
		 * @var string strUser
		 */
		protected $strUser;
		const UserMaxLength = 32;
		const UserDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.zip1
		 * @var string strZip1
		 */
		protected $strZip1;
		const Zip1MaxLength = 16;
		const Zip1Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.zip2
		 * @var string strZip2
		 */
		protected $strZip2;
		const Zip2MaxLength = 16;
		const Zip2Default = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.newsletter_subscribe
		 * @var boolean blnNewsletterSubscribe
		 */
		protected $blnNewsletterSubscribe;
		const NewsletterSubscribeDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.html_email
		 * @var boolean blnHtmlEmail
		 */
		protected $blnHtmlEmail;
		const HtmlEmailDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.password
		 * @var string strPassword
		 */
		protected $strPassword;
		const PasswordMaxLength = 32;
		const PasswordDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.temp_password
		 * @var string strTempPassword
		 */
		protected $strTempPassword;
		const TempPasswordMaxLength = 32;
		const TempPasswordDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.allow_login
		 * @var boolean blnAllowLogin
		 */
		protected $blnAllowLogin;
		const AllowLoginDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.created
		 * @var QDateTime dttCreated
		 */
		protected $dttCreated;
		const CreatedDefault = null;


		/**
		 * Protected member variable that maps to the database column xlsws_customer.modified
		 * @var string strModified
		 */
		protected $strModified;
		const ModifiedDefault = null;


		/**
		 * Private member variable that stores a reference to a single Cart object
		 * (of type Cart), if this Customer object was restored with
		 * an expansion on the xlsws_cart association table.
		 * @var Cart _objCart;
		 */
		private $_objCart;

		/**
		 * Private member variable that stores a reference to an array of Cart objects
		 * (of type Cart[]), if this Customer object was restored with
		 * an ExpandAsArray on the xlsws_cart association table.
		 * @var Cart[] _objCartArray;
		 */
		private $_objCartArray = array();

		/**
		 * Private member variable that stores a reference to a single GiftRegistry object
		 * (of type GiftRegistry), if this Customer object was restored with
		 * an expansion on the xlsws_gift_registry association table.
		 * @var GiftRegistry _objGiftRegistry;
		 */
		private $_objGiftRegistry;

		/**
		 * Private member variable that stores a reference to an array of GiftRegistry objects
		 * (of type GiftRegistry[]), if this Customer object was restored with
		 * an ExpandAsArray on the xlsws_gift_registry association table.
		 * @var GiftRegistry[] _objGiftRegistryArray;
		 */
		private $_objGiftRegistryArray = array();

		/**
		 * Private member variable that stores a reference to a single GiftRegistryReceipents object
		 * (of type GiftRegistryReceipents), if this Customer object was restored with
		 * an expansion on the xlsws_gift_registry_receipents association table.
		 * @var GiftRegistryReceipents _objGiftRegistryReceipents;
		 */
		private $_objGiftRegistryReceipents;

		/**
		 * Private member variable that stores a reference to an array of GiftRegistryReceipents objects
		 * (of type GiftRegistryReceipents[]), if this Customer object was restored with
		 * an ExpandAsArray on the xlsws_gift_registry_receipents association table.
		 * @var GiftRegistryReceipents[] _objGiftRegistryReceipentsArray;
		 */
		private $_objGiftRegistryReceipentsArray = array();

		/**
		 * Private member variable that stores a reference to a single Visitor object
		 * (of type Visitor), if this Customer object was restored with
		 * an expansion on the xlsws_visitor association table.
		 * @var Visitor _objVisitor;
		 */
		private $_objVisitor;

		/**
		 * Private member variable that stores a reference to an array of Visitor objects
		 * (of type Visitor[]), if this Customer object was restored with
		 * an ExpandAsArray on the xlsws_visitor association table.
		 * @var Visitor[] _objVisitorArray;
		 */
		private $_objVisitorArray = array();

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
		 * Load a Customer from PK Info
		 * @param integer $intRowid
		 * @return Customer
		 */
		public static function Load($intRowid) {
			// Use QuerySingle to Perform the Query
			return Customer::QuerySingle(
				QQ::Equal(QQN::Customer()->Rowid, $intRowid)
			);
		}

		/**
		 * Load all Customers
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Customer[]
		 */
		public static function LoadAll($objOptionalClauses = null) {
			// Call Customer::QueryArray to perform the LoadAll query
			try {
				return Customer::QueryArray(QQ::All(), $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Count all Customers
		 * @return int
		 */
		public static function CountAll() {
			// Call Customer::QueryCount to perform the CountAll query
			return Customer::QueryCount(QQ::All());
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
			$objDatabase = Customer::GetDatabase();

			// Create/Build out the QueryBuilder object with Customer-specific SELET and FROM fields
			$objQueryBuilder = new QQueryBuilder($objDatabase, 'xlsws_customer');
			Customer::GetSelectFields($objQueryBuilder);
			$objQueryBuilder->AddFromItem('xlsws_customer');

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
		 * Static Qcodo Query method to query for a single Customer object.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Customer the queried object
		 */
		public static function QuerySingle(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Customer::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query, Get the First Row, and Instantiate a new Customer object
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Customer::InstantiateDbRow($objDbResult->GetNextRow(), null, null, null, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for an array of Customer objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return Customer[] the queried objects as an array
		 */
		public static function QueryArray(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Customer::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, false);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Perform the Query and Instantiate the Array Result
			$objDbResult = $objQueryBuilder->Database->Query($strQuery);
			return Customer::InstantiateDbResult($objDbResult, $objQueryBuilder->ExpandAsArrayNodes, $objQueryBuilder->ColumnAliasArray);
		}

		/**
		 * Static Qcodo Query method to query for a count of Customer objects.
		 * Uses BuildQueryStatment to perform most of the work.
		 * @param QQCondition $objConditions any conditions on the query, itself
		 * @param QQClause[] $objOptionalClausees additional optional QQClause objects for this query
		 * @param mixed[] $mixParameterArray a array of name-value pairs to perform PrepareStatement with
		 * @return integer the count of queried objects as an integer
		 */
		public static function QueryCount(QQCondition $objConditions, $objOptionalClauses = null, $mixParameterArray = null) {
			// Get the Query Statement
			try {
				$strQuery = Customer::BuildQueryStatement($objQueryBuilder, $objConditions, $objOptionalClauses, $mixParameterArray, true);
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
			$objDatabase = Customer::GetDatabase();

			// Lookup the QCache for This Query Statement
			$objCache = new QCache('query', 'xlsws_customer_' . serialize($strConditions));
			if (!($strQuery = $objCache->GetData())) {
				// Not Found -- Go ahead and Create/Build out a new QueryBuilder object with Customer-specific fields
				$objQueryBuilder = new QQueryBuilder($objDatabase);
				Customer::GetSelectFields($objQueryBuilder);
				Customer::GetFromFields($objQueryBuilder);

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
			return Customer::InstantiateDbResult($objDbResult);
		}*/

		/**
		 * Updates a QQueryBuilder with the SELECT fields for this Customer
		 * @param QQueryBuilder $objBuilder the Query Builder object to update
		 * @param string $strPrefix optional prefix to add to the SELECT fields
		 */
		public static function GetSelectFields(QQueryBuilder $objBuilder, $strPrefix = null) {
			if ($strPrefix) {
				$strTableName = $strPrefix;
				$strAliasPrefix = $strPrefix . '__';
			} else {
				$strTableName = 'xlsws_customer';
				$strAliasPrefix = '';
			}

			$objBuilder->AddSelectItem($strTableName, 'rowid', $strAliasPrefix . 'rowid');
			$objBuilder->AddSelectItem($strTableName, 'address1_1', $strAliasPrefix . 'address1_1');
			$objBuilder->AddSelectItem($strTableName, 'address1_2', $strAliasPrefix . 'address1_2');
			$objBuilder->AddSelectItem($strTableName, 'address2_1', $strAliasPrefix . 'address2_1');
			$objBuilder->AddSelectItem($strTableName, 'address_2_2', $strAliasPrefix . 'address_2_2');
			$objBuilder->AddSelectItem($strTableName, 'city1', $strAliasPrefix . 'city1');
			$objBuilder->AddSelectItem($strTableName, 'city2', $strAliasPrefix . 'city2');
			$objBuilder->AddSelectItem($strTableName, 'company', $strAliasPrefix . 'company');
			$objBuilder->AddSelectItem($strTableName, 'country1', $strAliasPrefix . 'country1');
			$objBuilder->AddSelectItem($strTableName, 'country2', $strAliasPrefix . 'country2');
			$objBuilder->AddSelectItem($strTableName, 'currency', $strAliasPrefix . 'currency');
			$objBuilder->AddSelectItem($strTableName, 'email', $strAliasPrefix . 'email');
			$objBuilder->AddSelectItem($strTableName, 'firstname', $strAliasPrefix . 'firstname');
			$objBuilder->AddSelectItem($strTableName, 'pricing_level', $strAliasPrefix . 'pricing_level');
			$objBuilder->AddSelectItem($strTableName, 'homepage', $strAliasPrefix . 'homepage');
			$objBuilder->AddSelectItem($strTableName, 'id_customer', $strAliasPrefix . 'id_customer');
			$objBuilder->AddSelectItem($strTableName, 'language', $strAliasPrefix . 'language');
			$objBuilder->AddSelectItem($strTableName, 'lastname', $strAliasPrefix . 'lastname');
			$objBuilder->AddSelectItem($strTableName, 'mainname', $strAliasPrefix . 'mainname');
			$objBuilder->AddSelectItem($strTableName, 'mainphone', $strAliasPrefix . 'mainphone');
			$objBuilder->AddSelectItem($strTableName, 'mainephonetype', $strAliasPrefix . 'mainephonetype');
			$objBuilder->AddSelectItem($strTableName, 'phone1', $strAliasPrefix . 'phone1');
			$objBuilder->AddSelectItem($strTableName, 'phonetype1', $strAliasPrefix . 'phonetype1');
			$objBuilder->AddSelectItem($strTableName, 'phone2', $strAliasPrefix . 'phone2');
			$objBuilder->AddSelectItem($strTableName, 'phonetype2', $strAliasPrefix . 'phonetype2');
			$objBuilder->AddSelectItem($strTableName, 'phone3', $strAliasPrefix . 'phone3');
			$objBuilder->AddSelectItem($strTableName, 'phonetype3', $strAliasPrefix . 'phonetype3');
			$objBuilder->AddSelectItem($strTableName, 'phone4', $strAliasPrefix . 'phone4');
			$objBuilder->AddSelectItem($strTableName, 'phonetype4', $strAliasPrefix . 'phonetype4');
			$objBuilder->AddSelectItem($strTableName, 'state1', $strAliasPrefix . 'state1');
			$objBuilder->AddSelectItem($strTableName, 'state2', $strAliasPrefix . 'state2');
			$objBuilder->AddSelectItem($strTableName, 'type', $strAliasPrefix . 'type');
			$objBuilder->AddSelectItem($strTableName, 'user', $strAliasPrefix . 'user');
			$objBuilder->AddSelectItem($strTableName, 'zip1', $strAliasPrefix . 'zip1');
			$objBuilder->AddSelectItem($strTableName, 'zip2', $strAliasPrefix . 'zip2');
			$objBuilder->AddSelectItem($strTableName, 'newsletter_subscribe', $strAliasPrefix . 'newsletter_subscribe');
			$objBuilder->AddSelectItem($strTableName, 'html_email', $strAliasPrefix . 'html_email');
			$objBuilder->AddSelectItem($strTableName, 'password', $strAliasPrefix . 'password');
			$objBuilder->AddSelectItem($strTableName, 'temp_password', $strAliasPrefix . 'temp_password');
			$objBuilder->AddSelectItem($strTableName, 'allow_login', $strAliasPrefix . 'allow_login');
			$objBuilder->AddSelectItem($strTableName, 'created', $strAliasPrefix . 'created');
			$objBuilder->AddSelectItem($strTableName, 'modified', $strAliasPrefix . 'modified');
		}




		///////////////////////////////
		// INSTANTIATION-RELATED METHODS
		///////////////////////////////

		/**
		 * Instantiate a Customer from a Database Row.
		 * Takes in an optional strAliasPrefix, used in case another Object::InstantiateDbRow
		 * is calling this Customer::InstantiateDbRow in order to perform
		 * early binding on referenced objects.
		 * @param DatabaseRowBase $objDbRow
		 * @param string $strAliasPrefix
		 * @param string $strExpandAsArrayNodes
		 * @param QBaseClass $objPreviousItem
		 * @param string[] $strColumnAliasArray
		 * @return Customer
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
					$strAliasPrefix = 'xlsws_customer__';


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

				$strAlias = $strAliasPrefix . 'giftregistry__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objGiftRegistryArray)) {
						$objPreviousChildItem = $objPreviousItem->_objGiftRegistryArray[$intPreviousChildItemCount - 1];
						$objChildItem = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistry__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objGiftRegistryArray[] = $objChildItem;
					} else
						$objPreviousItem->_objGiftRegistryArray[] = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'giftregistryreceipents__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objGiftRegistryReceipentsArray)) {
						$objPreviousChildItem = $objPreviousItem->_objGiftRegistryReceipentsArray[$intPreviousChildItemCount - 1];
						$objChildItem = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipents__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objGiftRegistryReceipentsArray[] = $objChildItem;
					} else
						$objPreviousItem->_objGiftRegistryReceipentsArray[] = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipents__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				$strAlias = $strAliasPrefix . 'visitor__rowid';
				$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
				if ((array_key_exists($strAlias, $strExpandAsArrayNodes)) &&
					(!is_null($objDbRow->GetColumn($strAliasName)))) {
					if ($intPreviousChildItemCount = count($objPreviousItem->_objVisitorArray)) {
						$objPreviousChildItem = $objPreviousItem->_objVisitorArray[$intPreviousChildItemCount - 1];
						$objChildItem = Visitor::InstantiateDbRow($objDbRow, $strAliasPrefix . 'visitor__', $strExpandAsArrayNodes, $objPreviousChildItem, $strColumnAliasArray);
						if ($objChildItem)
							$objPreviousItem->_objVisitorArray[] = $objChildItem;
					} else
						$objPreviousItem->_objVisitorArray[] = Visitor::InstantiateDbRow($objDbRow, $strAliasPrefix . 'visitor__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
					$blnExpandedViaArray = true;
				}

				// Either return false to signal array expansion, or check-to-reset the Alias prefix and move on
				if ($blnExpandedViaArray)
					return false;
				else if ($strAliasPrefix == 'xlsws_customer__')
					$strAliasPrefix = null;
			}

			// Create a new instance of the Customer object
			$objToReturn = new Customer();
			$objToReturn->__blnRestored = true;

			$strAliasName = array_key_exists($strAliasPrefix . 'rowid', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'rowid'] : $strAliasPrefix . 'rowid';
			$objToReturn->intRowid = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'address1_1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'address1_1'] : $strAliasPrefix . 'address1_1';
			$objToReturn->strAddress11 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'address1_2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'address1_2'] : $strAliasPrefix . 'address1_2';
			$objToReturn->strAddress12 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'address2_1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'address2_1'] : $strAliasPrefix . 'address2_1';
			$objToReturn->strAddress21 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'address_2_2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'address_2_2'] : $strAliasPrefix . 'address_2_2';
			$objToReturn->strAddress22 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'city1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'city1'] : $strAliasPrefix . 'city1';
			$objToReturn->strCity1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'city2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'city2'] : $strAliasPrefix . 'city2';
			$objToReturn->strCity2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'company', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'company'] : $strAliasPrefix . 'company';
			$objToReturn->strCompany = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'country1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'country1'] : $strAliasPrefix . 'country1';
			$objToReturn->strCountry1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'country2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'country2'] : $strAliasPrefix . 'country2';
			$objToReturn->strCountry2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'currency', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'currency'] : $strAliasPrefix . 'currency';
			$objToReturn->strCurrency = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'email', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'email'] : $strAliasPrefix . 'email';
			$objToReturn->strEmail = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'firstname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'firstname'] : $strAliasPrefix . 'firstname';
			$objToReturn->strFirstname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'pricing_level', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'pricing_level'] : $strAliasPrefix . 'pricing_level';
			$objToReturn->intPricingLevel = $objDbRow->GetColumn($strAliasName, 'Integer');
			$strAliasName = array_key_exists($strAliasPrefix . 'homepage', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'homepage'] : $strAliasPrefix . 'homepage';
			$objToReturn->strHomepage = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'id_customer', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'id_customer'] : $strAliasPrefix . 'id_customer';
			$objToReturn->strIdCustomer = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'language', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'language'] : $strAliasPrefix . 'language';
			$objToReturn->strLanguage = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'lastname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'lastname'] : $strAliasPrefix . 'lastname';
			$objToReturn->strLastname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'mainname', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'mainname'] : $strAliasPrefix . 'mainname';
			$objToReturn->strMainname = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'mainphone', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'mainphone'] : $strAliasPrefix . 'mainphone';
			$objToReturn->strMainphone = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'mainephonetype', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'mainephonetype'] : $strAliasPrefix . 'mainephonetype';
			$objToReturn->strMainephonetype = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phone1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phone1'] : $strAliasPrefix . 'phone1';
			$objToReturn->strPhone1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phonetype1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phonetype1'] : $strAliasPrefix . 'phonetype1';
			$objToReturn->strPhonetype1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phone2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phone2'] : $strAliasPrefix . 'phone2';
			$objToReturn->strPhone2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phonetype2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phonetype2'] : $strAliasPrefix . 'phonetype2';
			$objToReturn->strPhonetype2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phone3', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phone3'] : $strAliasPrefix . 'phone3';
			$objToReturn->strPhone3 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phonetype3', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phonetype3'] : $strAliasPrefix . 'phonetype3';
			$objToReturn->strPhonetype3 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phone4', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phone4'] : $strAliasPrefix . 'phone4';
			$objToReturn->strPhone4 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'phonetype4', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'phonetype4'] : $strAliasPrefix . 'phonetype4';
			$objToReturn->strPhonetype4 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'state1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'state1'] : $strAliasPrefix . 'state1';
			$objToReturn->strState1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'state2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'state2'] : $strAliasPrefix . 'state2';
			$objToReturn->strState2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'type', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'type'] : $strAliasPrefix . 'type';
			$objToReturn->strType = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'user', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'user'] : $strAliasPrefix . 'user';
			$objToReturn->strUser = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zip1', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zip1'] : $strAliasPrefix . 'zip1';
			$objToReturn->strZip1 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'zip2', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'zip2'] : $strAliasPrefix . 'zip2';
			$objToReturn->strZip2 = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'newsletter_subscribe', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'newsletter_subscribe'] : $strAliasPrefix . 'newsletter_subscribe';
			$objToReturn->blnNewsletterSubscribe = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'html_email', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'html_email'] : $strAliasPrefix . 'html_email';
			$objToReturn->blnHtmlEmail = $objDbRow->GetColumn($strAliasName, 'Bit');
			$strAliasName = array_key_exists($strAliasPrefix . 'password', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'password'] : $strAliasPrefix . 'password';
			$objToReturn->strPassword = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'temp_password', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'temp_password'] : $strAliasPrefix . 'temp_password';
			$objToReturn->strTempPassword = $objDbRow->GetColumn($strAliasName, 'VarChar');
			$strAliasName = array_key_exists($strAliasPrefix . 'allow_login', $strColumnAliasArray) ? $strColumnAliasArray[$strAliasPrefix . 'allow_login'] : $strAliasPrefix . 'allow_login';
			$objToReturn->blnAllowLogin = $objDbRow->GetColumn($strAliasName, 'Bit');
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
				$strAliasPrefix = 'xlsws_customer__';




			// Check for Cart Virtual Binding
			$strAlias = $strAliasPrefix . 'cart__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objCartArray[] = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objCart = Cart::InstantiateDbRow($objDbRow, $strAliasPrefix . 'cart__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for GiftRegistry Virtual Binding
			$strAlias = $strAliasPrefix . 'giftregistry__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objGiftRegistryArray[] = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objGiftRegistry = GiftRegistry::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistry__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for GiftRegistryReceipents Virtual Binding
			$strAlias = $strAliasPrefix . 'giftregistryreceipents__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objGiftRegistryReceipentsArray[] = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipents__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objGiftRegistryReceipents = GiftRegistryReceipents::InstantiateDbRow($objDbRow, $strAliasPrefix . 'giftregistryreceipents__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			// Check for Visitor Virtual Binding
			$strAlias = $strAliasPrefix . 'visitor__rowid';
			$strAliasName = array_key_exists($strAlias, $strColumnAliasArray) ? $strColumnAliasArray[$strAlias] : $strAlias;
			if (!is_null($objDbRow->GetColumn($strAliasName))) {
				if (($strExpandAsArrayNodes) && (array_key_exists($strAlias, $strExpandAsArrayNodes)))
					$objToReturn->_objVisitorArray[] = Visitor::InstantiateDbRow($objDbRow, $strAliasPrefix . 'visitor__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
				else
					$objToReturn->_objVisitor = Visitor::InstantiateDbRow($objDbRow, $strAliasPrefix . 'visitor__', $strExpandAsArrayNodes, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}

		/**
		 * Instantiate an array of Customers from a Database Result
		 * @param DatabaseResultBase $objDbResult
		 * @param string $strExpandAsArrayNodes
		 * @param string[] $strColumnAliasArray
		 * @return Customer[]
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
					$objItem = Customer::InstantiateDbRow($objDbRow, null, $strExpandAsArrayNodes, $objLastRowItem, $strColumnAliasArray);
					if ($objItem) {
						$objToReturn[] = $objItem;
						$objLastRowItem = $objItem;
					}
				}
			} else {
				while ($objDbRow = $objDbResult->GetNextRow())
					$objToReturn[] = Customer::InstantiateDbRow($objDbRow, null, null, null, $strColumnAliasArray);
			}

			return $objToReturn;
		}




		///////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Single Load and Array)
		///////////////////////////////////////////////////
			
		/**
		 * Load a single Customer object,
		 * by Rowid Index(es)
		 * @param integer $intRowid
		 * @return Customer
		*/
		public static function LoadByRowid($intRowid) {
			return Customer::QuerySingle(
				QQ::Equal(QQN::Customer()->Rowid, $intRowid)
			);
		}
			
		/**
		 * Load a single Customer object,
		 * by Email Index(es)
		 * @param string $strEmail
		 * @return Customer
		*/
		public static function LoadByEmail($strEmail) {
			return Customer::QuerySingle(
				QQ::Equal(QQN::Customer()->Email, $strEmail)
			);
		}



		////////////////////////////////////////////////////
		// INDEX-BASED LOAD METHODS (Array via Many to Many)
		////////////////////////////////////////////////////




		//////////////////////////
		// SAVE, DELETE AND RELOAD
		//////////////////////////

		/**
		 * Save this Customer
		 * @param bool $blnForceInsert
		 * @param bool $blnForceUpdate
		 * @return int
		 */
		public function Save($blnForceInsert = false, $blnForceUpdate = false) {
			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			$mixToReturn = null;

			try {
				if ((!$this->__blnRestored) || ($blnForceInsert)) {
					// Perform an INSERT query
					$objDatabase->NonQuery('
						INSERT INTO `xlsws_customer` (
							`address1_1`,
							`address1_2`,
							`address2_1`,
							`address_2_2`,
							`city1`,
							`city2`,
							`company`,
							`country1`,
							`country2`,
							`currency`,
							`email`,
							`firstname`,
							`pricing_level`,
							`homepage`,
							`id_customer`,
							`language`,
							`lastname`,
							`mainname`,
							`mainphone`,
							`mainephonetype`,
							`phone1`,
							`phonetype1`,
							`phone2`,
							`phonetype2`,
							`phone3`,
							`phonetype3`,
							`phone4`,
							`phonetype4`,
							`state1`,
							`state2`,
							`type`,
							`user`,
							`zip1`,
							`zip2`,
							`newsletter_subscribe`,
							`html_email`,
							`password`,
							`temp_password`,
							`allow_login`,
							`created`
						) VALUES (
							' . $objDatabase->SqlVariable($this->strAddress11) . ',
							' . $objDatabase->SqlVariable($this->strAddress12) . ',
							' . $objDatabase->SqlVariable($this->strAddress21) . ',
							' . $objDatabase->SqlVariable($this->strAddress22) . ',
							' . $objDatabase->SqlVariable($this->strCity1) . ',
							' . $objDatabase->SqlVariable($this->strCity2) . ',
							' . $objDatabase->SqlVariable($this->strCompany) . ',
							' . $objDatabase->SqlVariable($this->strCountry1) . ',
							' . $objDatabase->SqlVariable($this->strCountry2) . ',
							' . $objDatabase->SqlVariable($this->strCurrency) . ',
							' . $objDatabase->SqlVariable($this->strEmail) . ',
							' . $objDatabase->SqlVariable($this->strFirstname) . ',
							' . $objDatabase->SqlVariable($this->intPricingLevel) . ',
							' . $objDatabase->SqlVariable($this->strHomepage) . ',
							' . $objDatabase->SqlVariable($this->strIdCustomer) . ',
							' . $objDatabase->SqlVariable($this->strLanguage) . ',
							' . $objDatabase->SqlVariable($this->strLastname) . ',
							' . $objDatabase->SqlVariable($this->strMainname) . ',
							' . $objDatabase->SqlVariable($this->strMainphone) . ',
							' . $objDatabase->SqlVariable($this->strMainephonetype) . ',
							' . $objDatabase->SqlVariable($this->strPhone1) . ',
							' . $objDatabase->SqlVariable($this->strPhonetype1) . ',
							' . $objDatabase->SqlVariable($this->strPhone2) . ',
							' . $objDatabase->SqlVariable($this->strPhonetype2) . ',
							' . $objDatabase->SqlVariable($this->strPhone3) . ',
							' . $objDatabase->SqlVariable($this->strPhonetype3) . ',
							' . $objDatabase->SqlVariable($this->strPhone4) . ',
							' . $objDatabase->SqlVariable($this->strPhonetype4) . ',
							' . $objDatabase->SqlVariable($this->strState1) . ',
							' . $objDatabase->SqlVariable($this->strState2) . ',
							' . $objDatabase->SqlVariable($this->strType) . ',
							' . $objDatabase->SqlVariable($this->strUser) . ',
							' . $objDatabase->SqlVariable($this->strZip1) . ',
							' . $objDatabase->SqlVariable($this->strZip2) . ',
							' . $objDatabase->SqlVariable($this->blnNewsletterSubscribe) . ',
							' . $objDatabase->SqlVariable($this->blnHtmlEmail) . ',
							' . $objDatabase->SqlVariable($this->strPassword) . ',
							' . $objDatabase->SqlVariable($this->strTempPassword) . ',
							' . $objDatabase->SqlVariable($this->blnAllowLogin) . ',
							' . $objDatabase->SqlVariable($this->dttCreated) . '
						)
					');

					// Update Identity column and return its value
					$mixToReturn = $this->intRowid = $objDatabase->InsertId('xlsws_customer', 'rowid');
				} else {
					// Perform an UPDATE query

					// First checking for Optimistic Locking constraints (if applicable)
					if (!$blnForceUpdate) {
						// Perform the Optimistic Locking check
						$objResult = $objDatabase->Query('
							SELECT
								`modified`
							FROM
								`xlsws_customer`
							WHERE
								`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
						');
						
						$objRow = $objResult->FetchArray();
						if ($objRow[0] != $this->strModified)
							throw new QOptimisticLockingException('Customer');
					}

					// Perform the UPDATE query
					$objDatabase->NonQuery('
						UPDATE
							`xlsws_customer`
						SET
							`address1_1` = ' . $objDatabase->SqlVariable($this->strAddress11) . ',
							`address1_2` = ' . $objDatabase->SqlVariable($this->strAddress12) . ',
							`address2_1` = ' . $objDatabase->SqlVariable($this->strAddress21) . ',
							`address_2_2` = ' . $objDatabase->SqlVariable($this->strAddress22) . ',
							`city1` = ' . $objDatabase->SqlVariable($this->strCity1) . ',
							`city2` = ' . $objDatabase->SqlVariable($this->strCity2) . ',
							`company` = ' . $objDatabase->SqlVariable($this->strCompany) . ',
							`country1` = ' . $objDatabase->SqlVariable($this->strCountry1) . ',
							`country2` = ' . $objDatabase->SqlVariable($this->strCountry2) . ',
							`currency` = ' . $objDatabase->SqlVariable($this->strCurrency) . ',
							`email` = ' . $objDatabase->SqlVariable($this->strEmail) . ',
							`firstname` = ' . $objDatabase->SqlVariable($this->strFirstname) . ',
							`pricing_level` = ' . $objDatabase->SqlVariable($this->intPricingLevel) . ',
							`homepage` = ' . $objDatabase->SqlVariable($this->strHomepage) . ',
							`id_customer` = ' . $objDatabase->SqlVariable($this->strIdCustomer) . ',
							`language` = ' . $objDatabase->SqlVariable($this->strLanguage) . ',
							`lastname` = ' . $objDatabase->SqlVariable($this->strLastname) . ',
							`mainname` = ' . $objDatabase->SqlVariable($this->strMainname) . ',
							`mainphone` = ' . $objDatabase->SqlVariable($this->strMainphone) . ',
							`mainephonetype` = ' . $objDatabase->SqlVariable($this->strMainephonetype) . ',
							`phone1` = ' . $objDatabase->SqlVariable($this->strPhone1) . ',
							`phonetype1` = ' . $objDatabase->SqlVariable($this->strPhonetype1) . ',
							`phone2` = ' . $objDatabase->SqlVariable($this->strPhone2) . ',
							`phonetype2` = ' . $objDatabase->SqlVariable($this->strPhonetype2) . ',
							`phone3` = ' . $objDatabase->SqlVariable($this->strPhone3) . ',
							`phonetype3` = ' . $objDatabase->SqlVariable($this->strPhonetype3) . ',
							`phone4` = ' . $objDatabase->SqlVariable($this->strPhone4) . ',
							`phonetype4` = ' . $objDatabase->SqlVariable($this->strPhonetype4) . ',
							`state1` = ' . $objDatabase->SqlVariable($this->strState1) . ',
							`state2` = ' . $objDatabase->SqlVariable($this->strState2) . ',
							`type` = ' . $objDatabase->SqlVariable($this->strType) . ',
							`user` = ' . $objDatabase->SqlVariable($this->strUser) . ',
							`zip1` = ' . $objDatabase->SqlVariable($this->strZip1) . ',
							`zip2` = ' . $objDatabase->SqlVariable($this->strZip2) . ',
							`newsletter_subscribe` = ' . $objDatabase->SqlVariable($this->blnNewsletterSubscribe) . ',
							`html_email` = ' . $objDatabase->SqlVariable($this->blnHtmlEmail) . ',
							`password` = ' . $objDatabase->SqlVariable($this->strPassword) . ',
							`temp_password` = ' . $objDatabase->SqlVariable($this->strTempPassword) . ',
							`allow_login` = ' . $objDatabase->SqlVariable($this->blnAllowLogin) . ',
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
					`xlsws_customer`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
						
			$objRow = $objResult->FetchArray();
			$this->strModified = $objRow[0];

			// Return 
			return $mixToReturn;
		}

		/**
		 * Delete this Customer
		 * @return void
		 */
		public function Delete() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Cannot delete this Customer with an unset primary key.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();


			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_customer`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($this->intRowid) . '');
		}

		/**
		 * Delete all Customers
		 * @return void
		 */
		public static function DeleteAll() {
			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_customer`');
		}

		/**
		 * Truncate xlsws_customer table
		 * @return void
		 */
		public static function Truncate() {
			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the Query
			$objDatabase->NonQuery('
				TRUNCATE `xlsws_customer`');
		}

		/**
		 * Reload this Customer from the database.
		 * @return void
		 */
		public function Reload() {
			// Make sure we are actually Restored from the database
			if (!$this->__blnRestored)
				throw new QCallerException('Cannot call Reload() on a new, unsaved Customer object.');

			// Reload the Object
			$objReloaded = Customer::Load($this->intRowid);

			// Update $this's local variables to match
			$this->strAddress11 = $objReloaded->strAddress11;
			$this->strAddress12 = $objReloaded->strAddress12;
			$this->strAddress21 = $objReloaded->strAddress21;
			$this->strAddress22 = $objReloaded->strAddress22;
			$this->strCity1 = $objReloaded->strCity1;
			$this->strCity2 = $objReloaded->strCity2;
			$this->strCompany = $objReloaded->strCompany;
			$this->strCountry1 = $objReloaded->strCountry1;
			$this->strCountry2 = $objReloaded->strCountry2;
			$this->strCurrency = $objReloaded->strCurrency;
			$this->strEmail = $objReloaded->strEmail;
			$this->strFirstname = $objReloaded->strFirstname;
			$this->intPricingLevel = $objReloaded->intPricingLevel;
			$this->strHomepage = $objReloaded->strHomepage;
			$this->strIdCustomer = $objReloaded->strIdCustomer;
			$this->strLanguage = $objReloaded->strLanguage;
			$this->strLastname = $objReloaded->strLastname;
			$this->strMainname = $objReloaded->strMainname;
			$this->strMainphone = $objReloaded->strMainphone;
			$this->strMainephonetype = $objReloaded->strMainephonetype;
			$this->strPhone1 = $objReloaded->strPhone1;
			$this->strPhonetype1 = $objReloaded->strPhonetype1;
			$this->strPhone2 = $objReloaded->strPhone2;
			$this->strPhonetype2 = $objReloaded->strPhonetype2;
			$this->strPhone3 = $objReloaded->strPhone3;
			$this->strPhonetype3 = $objReloaded->strPhonetype3;
			$this->strPhone4 = $objReloaded->strPhone4;
			$this->strPhonetype4 = $objReloaded->strPhonetype4;
			$this->strState1 = $objReloaded->strState1;
			$this->strState2 = $objReloaded->strState2;
			$this->strType = $objReloaded->strType;
			$this->strUser = $objReloaded->strUser;
			$this->strZip1 = $objReloaded->strZip1;
			$this->strZip2 = $objReloaded->strZip2;
			$this->blnNewsletterSubscribe = $objReloaded->blnNewsletterSubscribe;
			$this->blnHtmlEmail = $objReloaded->blnHtmlEmail;
			$this->strPassword = $objReloaded->strPassword;
			$this->strTempPassword = $objReloaded->strTempPassword;
			$this->blnAllowLogin = $objReloaded->blnAllowLogin;
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

				case 'Address11':
					// Gets the value for strAddress11 
					// @return string
					return $this->strAddress11;

				case 'Address12':
					// Gets the value for strAddress12 
					// @return string
					return $this->strAddress12;

				case 'Address21':
					// Gets the value for strAddress21 
					// @return string
					return $this->strAddress21;

				case 'Address22':
					// Gets the value for strAddress22 
					// @return string
					return $this->strAddress22;

				case 'City1':
					// Gets the value for strCity1 
					// @return string
					return $this->strCity1;

				case 'City2':
					// Gets the value for strCity2 
					// @return string
					return $this->strCity2;

				case 'Company':
					// Gets the value for strCompany 
					// @return string
					return $this->strCompany;

				case 'Country1':
					// Gets the value for strCountry1 
					// @return string
					return $this->strCountry1;

				case 'Country2':
					// Gets the value for strCountry2 
					// @return string
					return $this->strCountry2;

				case 'Currency':
					// Gets the value for strCurrency 
					// @return string
					return $this->strCurrency;

				case 'Email':
					// Gets the value for strEmail (Unique)
					// @return string
					return $this->strEmail;

				case 'Firstname':
					// Gets the value for strFirstname 
					// @return string
					return $this->strFirstname;

				case 'PricingLevel':
					// Gets the value for intPricingLevel 
					// @return integer
					return $this->intPricingLevel;

				case 'Homepage':
					// Gets the value for strHomepage 
					// @return string
					return $this->strHomepage;

				case 'IdCustomer':
					// Gets the value for strIdCustomer 
					// @return string
					return $this->strIdCustomer;

				case 'Language':
					// Gets the value for strLanguage 
					// @return string
					return $this->strLanguage;

				case 'Lastname':
					// Gets the value for strLastname 
					// @return string
					return $this->strLastname;

				case 'Mainname':
					// Gets the value for strMainname 
					// @return string
					return $this->strMainname;

				case 'Mainphone':
					// Gets the value for strMainphone 
					// @return string
					return $this->strMainphone;

				case 'Mainephonetype':
					// Gets the value for strMainephonetype 
					// @return string
					return $this->strMainephonetype;

				case 'Phone1':
					// Gets the value for strPhone1 
					// @return string
					return $this->strPhone1;

				case 'Phonetype1':
					// Gets the value for strPhonetype1 
					// @return string
					return $this->strPhonetype1;

				case 'Phone2':
					// Gets the value for strPhone2 
					// @return string
					return $this->strPhone2;

				case 'Phonetype2':
					// Gets the value for strPhonetype2 
					// @return string
					return $this->strPhonetype2;

				case 'Phone3':
					// Gets the value for strPhone3 
					// @return string
					return $this->strPhone3;

				case 'Phonetype3':
					// Gets the value for strPhonetype3 
					// @return string
					return $this->strPhonetype3;

				case 'Phone4':
					// Gets the value for strPhone4 
					// @return string
					return $this->strPhone4;

				case 'Phonetype4':
					// Gets the value for strPhonetype4 
					// @return string
					return $this->strPhonetype4;

				case 'State1':
					// Gets the value for strState1 
					// @return string
					return $this->strState1;

				case 'State2':
					// Gets the value for strState2 
					// @return string
					return $this->strState2;

				case 'Type':
					// Gets the value for strType 
					// @return string
					return $this->strType;

				case 'User':
					// Gets the value for strUser 
					// @return string
					return $this->strUser;

				case 'Zip1':
					// Gets the value for strZip1 
					// @return string
					return $this->strZip1;

				case 'Zip2':
					// Gets the value for strZip2 
					// @return string
					return $this->strZip2;

				case 'NewsletterSubscribe':
					// Gets the value for blnNewsletterSubscribe 
					// @return boolean
					return $this->blnNewsletterSubscribe;

				case 'HtmlEmail':
					// Gets the value for blnHtmlEmail 
					// @return boolean
					return $this->blnHtmlEmail;

				case 'Password':
					// Gets the value for strPassword 
					// @return string
					return $this->strPassword;

				case 'TempPassword':
					// Gets the value for strTempPassword 
					// @return string
					return $this->strTempPassword;

				case 'AllowLogin':
					// Gets the value for blnAllowLogin 
					// @return boolean
					return $this->blnAllowLogin;

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

				////////////////////////////
				// Virtual Object References (Many to Many and Reverse References)
				// (If restored via a "Many-to" expansion)
				////////////////////////////

				case '_Cart':
					// Gets the value for the private _objCart (Read-Only)
					// if set due to an expansion on the xlsws_cart.customer_id reverse relationship
					// @return Cart
					return $this->_objCart;

				case '_CartArray':
					// Gets the value for the private _objCartArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_cart.customer_id reverse relationship
					// @return Cart[]
					return (array) $this->_objCartArray;

				case '_GiftRegistry':
					// Gets the value for the private _objGiftRegistry (Read-Only)
					// if set due to an expansion on the xlsws_gift_registry.customer_id reverse relationship
					// @return GiftRegistry
					return $this->_objGiftRegistry;

				case '_GiftRegistryArray':
					// Gets the value for the private _objGiftRegistryArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_gift_registry.customer_id reverse relationship
					// @return GiftRegistry[]
					return (array) $this->_objGiftRegistryArray;

				case '_GiftRegistryReceipents':
					// Gets the value for the private _objGiftRegistryReceipents (Read-Only)
					// if set due to an expansion on the xlsws_gift_registry_receipents.customer_id reverse relationship
					// @return GiftRegistryReceipents
					return $this->_objGiftRegistryReceipents;

				case '_GiftRegistryReceipentsArray':
					// Gets the value for the private _objGiftRegistryReceipentsArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_gift_registry_receipents.customer_id reverse relationship
					// @return GiftRegistryReceipents[]
					return (array) $this->_objGiftRegistryReceipentsArray;

				case '_Visitor':
					// Gets the value for the private _objVisitor (Read-Only)
					// if set due to an expansion on the xlsws_visitor.customer_id reverse relationship
					// @return Visitor
					return $this->_objVisitor;

				case '_VisitorArray':
					// Gets the value for the private _objVisitorArray (Read-Only)
					// if set due to an ExpandAsArray on the xlsws_visitor.customer_id reverse relationship
					// @return Visitor[]
					return (array) $this->_objVisitorArray;


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
				case 'Address11':
					// Sets the value for strAddress11 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAddress11 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Address12':
					// Sets the value for strAddress12 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAddress12 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Address21':
					// Sets the value for strAddress21 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAddress21 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Address22':
					// Sets the value for strAddress22 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strAddress22 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'City1':
					// Sets the value for strCity1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCity1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'City2':
					// Sets the value for strCity2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCity2 = QType::Cast($mixValue, QType::String));
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

				case 'Country1':
					// Sets the value for strCountry1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCountry1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Country2':
					// Sets the value for strCountry2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strCountry2 = QType::Cast($mixValue, QType::String));
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

				case 'Email':
					// Sets the value for strEmail (Unique)
					// @param string $mixValue
					// @return string
					try {
						return ($this->strEmail = QType::Cast($mixValue, QType::String));
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

				case 'PricingLevel':
					// Sets the value for intPricingLevel 
					// @param integer $mixValue
					// @return integer
					try {
						return ($this->intPricingLevel = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Homepage':
					// Sets the value for strHomepage 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strHomepage = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'IdCustomer':
					// Sets the value for strIdCustomer 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strIdCustomer = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Language':
					// Sets the value for strLanguage 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strLanguage = QType::Cast($mixValue, QType::String));
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

				case 'Mainname':
					// Sets the value for strMainname 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMainname = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Mainphone':
					// Sets the value for strMainphone 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMainphone = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Mainephonetype':
					// Sets the value for strMainephonetype 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strMainephonetype = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phone1':
					// Sets the value for strPhone1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhone1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phonetype1':
					// Sets the value for strPhonetype1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhonetype1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phone2':
					// Sets the value for strPhone2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhone2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phonetype2':
					// Sets the value for strPhonetype2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhonetype2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phone3':
					// Sets the value for strPhone3 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhone3 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phonetype3':
					// Sets the value for strPhonetype3 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhonetype3 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phone4':
					// Sets the value for strPhone4 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhone4 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Phonetype4':
					// Sets the value for strPhonetype4 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPhonetype4 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'State1':
					// Sets the value for strState1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strState1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'State2':
					// Sets the value for strState2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strState2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Type':
					// Sets the value for strType 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strType = QType::Cast($mixValue, QType::String));
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

				case 'Zip1':
					// Sets the value for strZip1 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strZip1 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Zip2':
					// Sets the value for strZip2 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strZip2 = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'NewsletterSubscribe':
					// Sets the value for blnNewsletterSubscribe 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnNewsletterSubscribe = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'HtmlEmail':
					// Sets the value for blnHtmlEmail 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnHtmlEmail = QType::Cast($mixValue, QType::Boolean));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'Password':
					// Sets the value for strPassword 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strPassword = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'TempPassword':
					// Sets the value for strTempPassword 
					// @param string $mixValue
					// @return string
					try {
						return ($this->strTempPassword = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case 'AllowLogin':
					// Sets the value for blnAllowLogin 
					// @param boolean $mixValue
					// @return boolean
					try {
						return ($this->blnAllowLogin = QType::Cast($mixValue, QType::Boolean));
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
				return Cart::LoadArrayByCustomerId($this->intRowid, $objOptionalClauses);
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

			return Cart::CountByCustomerId($this->intRowid);
		}

		/**
		 * Associates a Cart
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function AssociateCart(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCart on this unsaved Customer.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateCart on this Customer with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
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
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved Customer.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this Customer with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`customer_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all Carts
		 * @return void
		*/ 
		public function UnassociateAllCarts() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_cart`
				SET
					`customer_id` = null
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated Cart
		 * @param Cart $objCart
		 * @return void
		*/ 
		public function DeleteAssociatedCart(Cart $objCart) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved Customer.');
			if ((is_null($objCart->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this Customer with an unsaved Cart.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objCart->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated Carts
		 * @return void
		*/ 
		public function DeleteAllCarts() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateCart on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_cart`
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for GiftRegistry
		//-------------------------------------------------------------------

		/**
		 * Gets all associated GiftRegistries as an array of GiftRegistry objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistry[]
		*/ 
		public function GetGiftRegistryArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return GiftRegistry::LoadArrayByCustomerId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated GiftRegistries
		 * @return int
		*/ 
		public function CountGiftRegistries() {
			if ((is_null($this->intRowid)))
				return 0;

			return GiftRegistry::CountByCustomerId($this->intRowid);
		}

		/**
		 * Associates a GiftRegistry
		 * @param GiftRegistry $objGiftRegistry
		 * @return void
		*/ 
		public function AssociateGiftRegistry(GiftRegistry $objGiftRegistry) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistry on this unsaved Customer.');
			if ((is_null($objGiftRegistry->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistry on this Customer with an unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry`
				SET
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistry->Rowid) . '
			');
		}

		/**
		 * Unassociates a GiftRegistry
		 * @param GiftRegistry $objGiftRegistry
		 * @return void
		*/ 
		public function UnassociateGiftRegistry(GiftRegistry $objGiftRegistry) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistry on this unsaved Customer.');
			if ((is_null($objGiftRegistry->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistry on this Customer with an unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry`
				SET
					`customer_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistry->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all GiftRegistries
		 * @return void
		*/ 
		public function UnassociateAllGiftRegistries() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistry on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry`
				SET
					`customer_id` = null
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated GiftRegistry
		 * @param GiftRegistry $objGiftRegistry
		 * @return void
		*/ 
		public function DeleteAssociatedGiftRegistry(GiftRegistry $objGiftRegistry) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistry on this unsaved Customer.');
			if ((is_null($objGiftRegistry->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistry on this Customer with an unsaved GiftRegistry.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistry->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated GiftRegistries
		 * @return void
		*/ 
		public function DeleteAllGiftRegistries() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistry on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry`
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for GiftRegistryReceipents
		//-------------------------------------------------------------------

		/**
		 * Gets all associated GiftRegistryReceipentses as an array of GiftRegistryReceipents objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return GiftRegistryReceipents[]
		*/ 
		public function GetGiftRegistryReceipentsArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return GiftRegistryReceipents::LoadArrayByCustomerId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated GiftRegistryReceipentses
		 * @return int
		*/ 
		public function CountGiftRegistryReceipentses() {
			if ((is_null($this->intRowid)))
				return 0;

			return GiftRegistryReceipents::CountByCustomerId($this->intRowid);
		}

		/**
		 * Associates a GiftRegistryReceipents
		 * @param GiftRegistryReceipents $objGiftRegistryReceipents
		 * @return void
		*/ 
		public function AssociateGiftRegistryReceipents(GiftRegistryReceipents $objGiftRegistryReceipents) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryReceipents on this unsaved Customer.');
			if ((is_null($objGiftRegistryReceipents->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateGiftRegistryReceipents on this Customer with an unsaved GiftRegistryReceipents.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_receipents`
				SET
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryReceipents->Rowid) . '
			');
		}

		/**
		 * Unassociates a GiftRegistryReceipents
		 * @param GiftRegistryReceipents $objGiftRegistryReceipents
		 * @return void
		*/ 
		public function UnassociateGiftRegistryReceipents(GiftRegistryReceipents $objGiftRegistryReceipents) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipents on this unsaved Customer.');
			if ((is_null($objGiftRegistryReceipents->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipents on this Customer with an unsaved GiftRegistryReceipents.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_receipents`
				SET
					`customer_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryReceipents->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all GiftRegistryReceipentses
		 * @return void
		*/ 
		public function UnassociateAllGiftRegistryReceipentses() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipents on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_gift_registry_receipents`
				SET
					`customer_id` = null
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated GiftRegistryReceipents
		 * @param GiftRegistryReceipents $objGiftRegistryReceipents
		 * @return void
		*/ 
		public function DeleteAssociatedGiftRegistryReceipents(GiftRegistryReceipents $objGiftRegistryReceipents) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipents on this unsaved Customer.');
			if ((is_null($objGiftRegistryReceipents->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipents on this Customer with an unsaved GiftRegistryReceipents.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_receipents`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objGiftRegistryReceipents->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated GiftRegistryReceipentses
		 * @return void
		*/ 
		public function DeleteAllGiftRegistryReceipentses() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateGiftRegistryReceipents on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_gift_registry_receipents`
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

			
		
		// Related Objects' Methods for Visitor
		//-------------------------------------------------------------------

		/**
		 * Gets all associated Visitors as an array of Visitor objects
		 * @param QQClause[] $objOptionalClauses additional optional QQClause objects for this query
		 * @return Visitor[]
		*/ 
		public function GetVisitorArray($objOptionalClauses = null) {
			if ((is_null($this->intRowid)))
				return array();

			try {
				return Visitor::LoadArrayByCustomerId($this->intRowid, $objOptionalClauses);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		/**
		 * Counts all associated Visitors
		 * @return int
		*/ 
		public function CountVisitors() {
			if ((is_null($this->intRowid)))
				return 0;

			return Visitor::CountByCustomerId($this->intRowid);
		}

		/**
		 * Associates a Visitor
		 * @param Visitor $objVisitor
		 * @return void
		*/ 
		public function AssociateVisitor(Visitor $objVisitor) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateVisitor on this unsaved Customer.');
			if ((is_null($objVisitor->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call AssociateVisitor on this Customer with an unsaved Visitor.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_visitor`
				SET
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objVisitor->Rowid) . '
			');
		}

		/**
		 * Unassociates a Visitor
		 * @param Visitor $objVisitor
		 * @return void
		*/ 
		public function UnassociateVisitor(Visitor $objVisitor) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateVisitor on this unsaved Customer.');
			if ((is_null($objVisitor->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateVisitor on this Customer with an unsaved Visitor.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_visitor`
				SET
					`customer_id` = null
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objVisitor->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Unassociates all Visitors
		 * @return void
		*/ 
		public function UnassociateAllVisitors() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateVisitor on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				UPDATE
					`xlsws_visitor`
				SET
					`customer_id` = null
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes an associated Visitor
		 * @param Visitor $objVisitor
		 * @return void
		*/ 
		public function DeleteAssociatedVisitor(Visitor $objVisitor) {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateVisitor on this unsaved Customer.');
			if ((is_null($objVisitor->Rowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateVisitor on this Customer with an unsaved Visitor.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_visitor`
				WHERE
					`rowid` = ' . $objDatabase->SqlVariable($objVisitor->Rowid) . ' AND
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}

		/**
		 * Deletes all associated Visitors
		 * @return void
		*/ 
		public function DeleteAllVisitors() {
			if ((is_null($this->intRowid)))
				throw new QUndefinedPrimaryKeyException('Unable to call UnassociateVisitor on this unsaved Customer.');

			// Get the Database Object for this Class
			$objDatabase = Customer::GetDatabase();

			// Perform the SQL Query
			$objDatabase->NonQuery('
				DELETE FROM
					`xlsws_visitor`
				WHERE
					`customer_id` = ' . $objDatabase->SqlVariable($this->intRowid) . '
			');
		}





		////////////////////////////////////////
		// METHODS for SOAP-BASED WEB SERVICES
		////////////////////////////////////////

		public static function GetSoapComplexTypeXml() {
			$strToReturn = '<complexType name="Customer"><sequence>';
			$strToReturn .= '<element name="Rowid" type="xsd:int"/>';
			$strToReturn .= '<element name="Address11" type="xsd:string"/>';
			$strToReturn .= '<element name="Address12" type="xsd:string"/>';
			$strToReturn .= '<element name="Address21" type="xsd:string"/>';
			$strToReturn .= '<element name="Address22" type="xsd:string"/>';
			$strToReturn .= '<element name="City1" type="xsd:string"/>';
			$strToReturn .= '<element name="City2" type="xsd:string"/>';
			$strToReturn .= '<element name="Company" type="xsd:string"/>';
			$strToReturn .= '<element name="Country1" type="xsd:string"/>';
			$strToReturn .= '<element name="Country2" type="xsd:string"/>';
			$strToReturn .= '<element name="Currency" type="xsd:string"/>';
			$strToReturn .= '<element name="Email" type="xsd:string"/>';
			$strToReturn .= '<element name="Firstname" type="xsd:string"/>';
			$strToReturn .= '<element name="PricingLevel" type="xsd:int"/>';
			$strToReturn .= '<element name="Homepage" type="xsd:string"/>';
			$strToReturn .= '<element name="IdCustomer" type="xsd:string"/>';
			$strToReturn .= '<element name="Language" type="xsd:string"/>';
			$strToReturn .= '<element name="Lastname" type="xsd:string"/>';
			$strToReturn .= '<element name="Mainname" type="xsd:string"/>';
			$strToReturn .= '<element name="Mainphone" type="xsd:string"/>';
			$strToReturn .= '<element name="Mainephonetype" type="xsd:string"/>';
			$strToReturn .= '<element name="Phone1" type="xsd:string"/>';
			$strToReturn .= '<element name="Phonetype1" type="xsd:string"/>';
			$strToReturn .= '<element name="Phone2" type="xsd:string"/>';
			$strToReturn .= '<element name="Phonetype2" type="xsd:string"/>';
			$strToReturn .= '<element name="Phone3" type="xsd:string"/>';
			$strToReturn .= '<element name="Phonetype3" type="xsd:string"/>';
			$strToReturn .= '<element name="Phone4" type="xsd:string"/>';
			$strToReturn .= '<element name="Phonetype4" type="xsd:string"/>';
			$strToReturn .= '<element name="State1" type="xsd:string"/>';
			$strToReturn .= '<element name="State2" type="xsd:string"/>';
			$strToReturn .= '<element name="Type" type="xsd:string"/>';
			$strToReturn .= '<element name="User" type="xsd:string"/>';
			$strToReturn .= '<element name="Zip1" type="xsd:string"/>';
			$strToReturn .= '<element name="Zip2" type="xsd:string"/>';
			$strToReturn .= '<element name="NewsletterSubscribe" type="xsd:boolean"/>';
			$strToReturn .= '<element name="HtmlEmail" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Password" type="xsd:string"/>';
			$strToReturn .= '<element name="TempPassword" type="xsd:string"/>';
			$strToReturn .= '<element name="AllowLogin" type="xsd:boolean"/>';
			$strToReturn .= '<element name="Created" type="xsd:dateTime"/>';
			$strToReturn .= '<element name="Modified" type="xsd:string"/>';
			$strToReturn .= '<element name="__blnRestored" type="xsd:boolean"/>';
			$strToReturn .= '</sequence></complexType>';
			return $strToReturn;
		}

		public static function AlterSoapComplexTypeArray(&$strComplexTypeArray) {
			if (!array_key_exists('Customer', $strComplexTypeArray)) {
				$strComplexTypeArray['Customer'] = Customer::GetSoapComplexTypeXml();
			}
		}

		public static function GetArrayFromSoapArray($objSoapArray) {
			$objArrayToReturn = array();

			foreach ($objSoapArray as $objSoapObject)
				array_push($objArrayToReturn, Customer::GetObjectFromSoapObject($objSoapObject));

			return $objArrayToReturn;
		}

		public static function GetObjectFromSoapObject($objSoapObject) {
			$objToReturn = new Customer();
			if (property_exists($objSoapObject, 'Rowid'))
				$objToReturn->intRowid = $objSoapObject->Rowid;
			if (property_exists($objSoapObject, 'Address11'))
				$objToReturn->strAddress11 = $objSoapObject->Address11;
			if (property_exists($objSoapObject, 'Address12'))
				$objToReturn->strAddress12 = $objSoapObject->Address12;
			if (property_exists($objSoapObject, 'Address21'))
				$objToReturn->strAddress21 = $objSoapObject->Address21;
			if (property_exists($objSoapObject, 'Address22'))
				$objToReturn->strAddress22 = $objSoapObject->Address22;
			if (property_exists($objSoapObject, 'City1'))
				$objToReturn->strCity1 = $objSoapObject->City1;
			if (property_exists($objSoapObject, 'City2'))
				$objToReturn->strCity2 = $objSoapObject->City2;
			if (property_exists($objSoapObject, 'Company'))
				$objToReturn->strCompany = $objSoapObject->Company;
			if (property_exists($objSoapObject, 'Country1'))
				$objToReturn->strCountry1 = $objSoapObject->Country1;
			if (property_exists($objSoapObject, 'Country2'))
				$objToReturn->strCountry2 = $objSoapObject->Country2;
			if (property_exists($objSoapObject, 'Currency'))
				$objToReturn->strCurrency = $objSoapObject->Currency;
			if (property_exists($objSoapObject, 'Email'))
				$objToReturn->strEmail = $objSoapObject->Email;
			if (property_exists($objSoapObject, 'Firstname'))
				$objToReturn->strFirstname = $objSoapObject->Firstname;
			if (property_exists($objSoapObject, 'PricingLevel'))
				$objToReturn->intPricingLevel = $objSoapObject->PricingLevel;
			if (property_exists($objSoapObject, 'Homepage'))
				$objToReturn->strHomepage = $objSoapObject->Homepage;
			if (property_exists($objSoapObject, 'IdCustomer'))
				$objToReturn->strIdCustomer = $objSoapObject->IdCustomer;
			if (property_exists($objSoapObject, 'Language'))
				$objToReturn->strLanguage = $objSoapObject->Language;
			if (property_exists($objSoapObject, 'Lastname'))
				$objToReturn->strLastname = $objSoapObject->Lastname;
			if (property_exists($objSoapObject, 'Mainname'))
				$objToReturn->strMainname = $objSoapObject->Mainname;
			if (property_exists($objSoapObject, 'Mainphone'))
				$objToReturn->strMainphone = $objSoapObject->Mainphone;
			if (property_exists($objSoapObject, 'Mainephonetype'))
				$objToReturn->strMainephonetype = $objSoapObject->Mainephonetype;
			if (property_exists($objSoapObject, 'Phone1'))
				$objToReturn->strPhone1 = $objSoapObject->Phone1;
			if (property_exists($objSoapObject, 'Phonetype1'))
				$objToReturn->strPhonetype1 = $objSoapObject->Phonetype1;
			if (property_exists($objSoapObject, 'Phone2'))
				$objToReturn->strPhone2 = $objSoapObject->Phone2;
			if (property_exists($objSoapObject, 'Phonetype2'))
				$objToReturn->strPhonetype2 = $objSoapObject->Phonetype2;
			if (property_exists($objSoapObject, 'Phone3'))
				$objToReturn->strPhone3 = $objSoapObject->Phone3;
			if (property_exists($objSoapObject, 'Phonetype3'))
				$objToReturn->strPhonetype3 = $objSoapObject->Phonetype3;
			if (property_exists($objSoapObject, 'Phone4'))
				$objToReturn->strPhone4 = $objSoapObject->Phone4;
			if (property_exists($objSoapObject, 'Phonetype4'))
				$objToReturn->strPhonetype4 = $objSoapObject->Phonetype4;
			if (property_exists($objSoapObject, 'State1'))
				$objToReturn->strState1 = $objSoapObject->State1;
			if (property_exists($objSoapObject, 'State2'))
				$objToReturn->strState2 = $objSoapObject->State2;
			if (property_exists($objSoapObject, 'Type'))
				$objToReturn->strType = $objSoapObject->Type;
			if (property_exists($objSoapObject, 'User'))
				$objToReturn->strUser = $objSoapObject->User;
			if (property_exists($objSoapObject, 'Zip1'))
				$objToReturn->strZip1 = $objSoapObject->Zip1;
			if (property_exists($objSoapObject, 'Zip2'))
				$objToReturn->strZip2 = $objSoapObject->Zip2;
			if (property_exists($objSoapObject, 'NewsletterSubscribe'))
				$objToReturn->blnNewsletterSubscribe = $objSoapObject->NewsletterSubscribe;
			if (property_exists($objSoapObject, 'HtmlEmail'))
				$objToReturn->blnHtmlEmail = $objSoapObject->HtmlEmail;
			if (property_exists($objSoapObject, 'Password'))
				$objToReturn->strPassword = $objSoapObject->Password;
			if (property_exists($objSoapObject, 'TempPassword'))
				$objToReturn->strTempPassword = $objSoapObject->TempPassword;
			if (property_exists($objSoapObject, 'AllowLogin'))
				$objToReturn->blnAllowLogin = $objSoapObject->AllowLogin;
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
				array_push($objArrayToReturn, Customer::GetSoapObjectFromObject($objObject, true));

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

	class QQNodeCustomer extends QQNode {
		protected $strTableName = 'xlsws_customer';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Customer';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Address11':
					return new QQNode('address1_1', 'Address11', 'string', $this);
				case 'Address12':
					return new QQNode('address1_2', 'Address12', 'string', $this);
				case 'Address21':
					return new QQNode('address2_1', 'Address21', 'string', $this);
				case 'Address22':
					return new QQNode('address_2_2', 'Address22', 'string', $this);
				case 'City1':
					return new QQNode('city1', 'City1', 'string', $this);
				case 'City2':
					return new QQNode('city2', 'City2', 'string', $this);
				case 'Company':
					return new QQNode('company', 'Company', 'string', $this);
				case 'Country1':
					return new QQNode('country1', 'Country1', 'string', $this);
				case 'Country2':
					return new QQNode('country2', 'Country2', 'string', $this);
				case 'Currency':
					return new QQNode('currency', 'Currency', 'string', $this);
				case 'Email':
					return new QQNode('email', 'Email', 'string', $this);
				case 'Firstname':
					return new QQNode('firstname', 'Firstname', 'string', $this);
				case 'PricingLevel':
					return new QQNode('pricing_level', 'PricingLevel', 'integer', $this);
				case 'Homepage':
					return new QQNode('homepage', 'Homepage', 'string', $this);
				case 'IdCustomer':
					return new QQNode('id_customer', 'IdCustomer', 'string', $this);
				case 'Language':
					return new QQNode('language', 'Language', 'string', $this);
				case 'Lastname':
					return new QQNode('lastname', 'Lastname', 'string', $this);
				case 'Mainname':
					return new QQNode('mainname', 'Mainname', 'string', $this);
				case 'Mainphone':
					return new QQNode('mainphone', 'Mainphone', 'string', $this);
				case 'Mainephonetype':
					return new QQNode('mainephonetype', 'Mainephonetype', 'string', $this);
				case 'Phone1':
					return new QQNode('phone1', 'Phone1', 'string', $this);
				case 'Phonetype1':
					return new QQNode('phonetype1', 'Phonetype1', 'string', $this);
				case 'Phone2':
					return new QQNode('phone2', 'Phone2', 'string', $this);
				case 'Phonetype2':
					return new QQNode('phonetype2', 'Phonetype2', 'string', $this);
				case 'Phone3':
					return new QQNode('phone3', 'Phone3', 'string', $this);
				case 'Phonetype3':
					return new QQNode('phonetype3', 'Phonetype3', 'string', $this);
				case 'Phone4':
					return new QQNode('phone4', 'Phone4', 'string', $this);
				case 'Phonetype4':
					return new QQNode('phonetype4', 'Phonetype4', 'string', $this);
				case 'State1':
					return new QQNode('state1', 'State1', 'string', $this);
				case 'State2':
					return new QQNode('state2', 'State2', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'string', $this);
				case 'User':
					return new QQNode('user', 'User', 'string', $this);
				case 'Zip1':
					return new QQNode('zip1', 'Zip1', 'string', $this);
				case 'Zip2':
					return new QQNode('zip2', 'Zip2', 'string', $this);
				case 'NewsletterSubscribe':
					return new QQNode('newsletter_subscribe', 'NewsletterSubscribe', 'boolean', $this);
				case 'HtmlEmail':
					return new QQNode('html_email', 'HtmlEmail', 'boolean', $this);
				case 'Password':
					return new QQNode('password', 'Password', 'string', $this);
				case 'TempPassword':
					return new QQNode('temp_password', 'TempPassword', 'string', $this);
				case 'AllowLogin':
					return new QQNode('allow_login', 'AllowLogin', 'boolean', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Cart':
					return new QQReverseReferenceNodeCart($this, 'cart', 'reverse_reference', 'customer_id');
				case 'GiftRegistry':
					return new QQReverseReferenceNodeGiftRegistry($this, 'giftregistry', 'reverse_reference', 'customer_id');
				case 'GiftRegistryReceipents':
					return new QQReverseReferenceNodeGiftRegistryReceipents($this, 'giftregistryreceipents', 'reverse_reference', 'customer_id');
				case 'Visitor':
					return new QQReverseReferenceNodeVisitor($this, 'visitor', 'reverse_reference', 'customer_id');

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

	class QQReverseReferenceNodeCustomer extends QQReverseReferenceNode {
		protected $strTableName = 'xlsws_customer';
		protected $strPrimaryKey = 'rowid';
		protected $strClassName = 'Customer';
		public function __get($strName) {
			switch ($strName) {
				case 'Rowid':
					return new QQNode('rowid', 'Rowid', 'integer', $this);
				case 'Address11':
					return new QQNode('address1_1', 'Address11', 'string', $this);
				case 'Address12':
					return new QQNode('address1_2', 'Address12', 'string', $this);
				case 'Address21':
					return new QQNode('address2_1', 'Address21', 'string', $this);
				case 'Address22':
					return new QQNode('address_2_2', 'Address22', 'string', $this);
				case 'City1':
					return new QQNode('city1', 'City1', 'string', $this);
				case 'City2':
					return new QQNode('city2', 'City2', 'string', $this);
				case 'Company':
					return new QQNode('company', 'Company', 'string', $this);
				case 'Country1':
					return new QQNode('country1', 'Country1', 'string', $this);
				case 'Country2':
					return new QQNode('country2', 'Country2', 'string', $this);
				case 'Currency':
					return new QQNode('currency', 'Currency', 'string', $this);
				case 'Email':
					return new QQNode('email', 'Email', 'string', $this);
				case 'Firstname':
					return new QQNode('firstname', 'Firstname', 'string', $this);
				case 'PricingLevel':
					return new QQNode('pricing_level', 'PricingLevel', 'integer', $this);
				case 'Homepage':
					return new QQNode('homepage', 'Homepage', 'string', $this);
				case 'IdCustomer':
					return new QQNode('id_customer', 'IdCustomer', 'string', $this);
				case 'Language':
					return new QQNode('language', 'Language', 'string', $this);
				case 'Lastname':
					return new QQNode('lastname', 'Lastname', 'string', $this);
				case 'Mainname':
					return new QQNode('mainname', 'Mainname', 'string', $this);
				case 'Mainphone':
					return new QQNode('mainphone', 'Mainphone', 'string', $this);
				case 'Mainephonetype':
					return new QQNode('mainephonetype', 'Mainephonetype', 'string', $this);
				case 'Phone1':
					return new QQNode('phone1', 'Phone1', 'string', $this);
				case 'Phonetype1':
					return new QQNode('phonetype1', 'Phonetype1', 'string', $this);
				case 'Phone2':
					return new QQNode('phone2', 'Phone2', 'string', $this);
				case 'Phonetype2':
					return new QQNode('phonetype2', 'Phonetype2', 'string', $this);
				case 'Phone3':
					return new QQNode('phone3', 'Phone3', 'string', $this);
				case 'Phonetype3':
					return new QQNode('phonetype3', 'Phonetype3', 'string', $this);
				case 'Phone4':
					return new QQNode('phone4', 'Phone4', 'string', $this);
				case 'Phonetype4':
					return new QQNode('phonetype4', 'Phonetype4', 'string', $this);
				case 'State1':
					return new QQNode('state1', 'State1', 'string', $this);
				case 'State2':
					return new QQNode('state2', 'State2', 'string', $this);
				case 'Type':
					return new QQNode('type', 'Type', 'string', $this);
				case 'User':
					return new QQNode('user', 'User', 'string', $this);
				case 'Zip1':
					return new QQNode('zip1', 'Zip1', 'string', $this);
				case 'Zip2':
					return new QQNode('zip2', 'Zip2', 'string', $this);
				case 'NewsletterSubscribe':
					return new QQNode('newsletter_subscribe', 'NewsletterSubscribe', 'boolean', $this);
				case 'HtmlEmail':
					return new QQNode('html_email', 'HtmlEmail', 'boolean', $this);
				case 'Password':
					return new QQNode('password', 'Password', 'string', $this);
				case 'TempPassword':
					return new QQNode('temp_password', 'TempPassword', 'string', $this);
				case 'AllowLogin':
					return new QQNode('allow_login', 'AllowLogin', 'boolean', $this);
				case 'Created':
					return new QQNode('created', 'Created', 'QDateTime', $this);
				case 'Modified':
					return new QQNode('modified', 'Modified', 'string', $this);
				case 'Cart':
					return new QQReverseReferenceNodeCart($this, 'cart', 'reverse_reference', 'customer_id');
				case 'GiftRegistry':
					return new QQReverseReferenceNodeGiftRegistry($this, 'giftregistry', 'reverse_reference', 'customer_id');
				case 'GiftRegistryReceipents':
					return new QQReverseReferenceNodeGiftRegistryReceipents($this, 'giftregistryreceipents', 'reverse_reference', 'customer_id');
				case 'Visitor':
					return new QQReverseReferenceNodeVisitor($this, 'visitor', 'reverse_reference', 'customer_id');

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