<?php
	require(__DATAGEN_CLASSES__ . '/GoogleCategoriesGen.class.php');

	/**
	 * The GoogleCategories class defined here contains any
	 * customized code for the GoogleCategories class in the
	 * Object Relational Model.  It represents the "xlsws_google_categories" table 
	 * in the database, and extends from the code generated abstract GoogleCategoriesGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package LightSpeed Web Store
	 * @subpackage DataObjects
	 * 
	 */
	class GoogleCategories extends GoogleCategoriesGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objGoogleCategories->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('GoogleCategories Object %s',  $this->intRowid);
		}


		// Override or Create New Load/Count methods
		// (For obvious reasons, these methods are commented out...
		// but feel free to use these as a starting point)
/*
		public static function LoadArrayBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return an array of GoogleCategories objects
			return GoogleCategories::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::GoogleCategories()->Param1, $strParam1),
					QQ::GreaterThan(QQN::GoogleCategories()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function LoadBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return a single GoogleCategories object
			return GoogleCategories::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::GoogleCategories()->Param1, $strParam1),
					QQ::GreaterThan(QQN::GoogleCategories()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function CountBySample($strParam1, $intParam2, $objOptionalClauses = null) {
			// This will return a count of GoogleCategories objects
			return GoogleCategories::QueryCount(
				QQ::AndCondition(
					QQ::Equal(QQN::GoogleCategories()->Param1, $strParam1),
					QQ::Equal(QQN::GoogleCategories()->Param2, $intParam2)
				),
				$objOptionalClauses
			);
		}

		public static function LoadArrayBySample($strParam1, $intParam2, $objOptionalClauses) {
			// Performing the load manually (instead of using Qcodo Query)

			// Get the Database Object for this Class
			$objDatabase = GoogleCategories::GetDatabase();

			// Properly Escape All Input Parameters using Database->SqlVariable()
			$strParam1 = $objDatabase->SqlVariable($strParam1);
			$intParam2 = $objDatabase->SqlVariable($intParam2);

			// Setup the SQL Query
			$strQuery = sprintf('
				SELECT
					`xlsws_google_categories`.*
				FROM
					`xlsws_google_categories` AS `xlsws_google_categories`
				WHERE
					param_1 = %s AND
					param_2 < %s',
				$strParam1, $intParam2);

			// Perform the Query and Instantiate the Result
			$objDbResult = $objDatabase->Query($strQuery);
			return GoogleCategories::InstantiateDbResult($objDbResult);
		}
*/




		// Override or Create New Properties and Variables
		// For performance reasons, these variables and __set and __get override methods
		// are commented out.  But if you wish to implement or override any
		// of the data generated properties, please feel free to uncomment them.
/*
		protected $strSomeNewProperty;

		public function __get($strName) {
			switch ($strName) {
				case 'SomeNewProperty': return $this->strSomeNewProperty;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function __set($strName, $mixValue) {
			switch ($strName) {
				case 'SomeNewProperty':
					try {
						return ($this->strSomeNewProperty = QType::Cast($mixValue, QType::String));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
*/
	}
?>