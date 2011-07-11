<?php
	/**
	 * Used by the Qcodo Code Generator to describe a database Type Table
	 * "Type" tables must be defined with only two columns, the first one being an integer-based primary key,
	 * and the second one being the name of the type.
	 */
	class QTypeTable extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * Name of the table (as defined in the database)
		 * @var string Name
		 */
		protected $strName;

		/**
		 * Name as a PHP Class
		 * @var string ClassName
		 */
		protected $strClassName;

		/**
		 * Array of Type Names (as entered into the rows of this database table)
		 * This is indexed by integer which represents the ID in the database, starting with 1
		 * @var string[] NameArray
		 */
		protected $strNameArray;

        /**
         * Column names for extra properties (beyond the 2 basic ones), if any.
         */
        protected $strExtraFieldNamesArray;

        /**
         * Array of extra properties. This is a double-array - array of arrays. Example:
         *      1 => ['col1' => 'valueA', 'col2 => 'valueB'],
         *      2 => ['col1' => 'valueC', 'col2 => 'valueD'],
         *      3 => ['col1' => 'valueC', 'col2 => 'valueD']
         */
        protected $arrExtraPropertyArray;

		/**
		 * Array of Type Names converted into Tokens (can be used as PHP Constants)
		 * This is indexed by integer which represents the ID in the database, starting with 1
		 * @var string[] TokenArray
		 */
		protected $strTokenArray;



		/////////////////////
		// Public Constructor
		/////////////////////

		/**
		 * Default Constructor.  Simply sets up the TableName.
		 *
		 * @param string strName Name of the Table
		 * @return TypeTable
		 */
		public function __construct($strName) {
			$this->strName = $strName;
		}



		////////////////////
		// Public Overriders
		////////////////////

		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 *
		 * @param string strName Name of the property to get
		 * @return mixed
		 */
		public function __get($strName) {
			switch ($strName) {
				case 'Name':
					return $this->strName;
				case 'ClassName':
					return $this->strClassName;
				case 'NameArray':
					return $this->strNameArray;
				case 'TokenArray':
					return $this->strTokenArray;
				case 'ExtraPropertyArray':
					return $this->arrExtraPropertyArray;
				case 'ExtraFieldNamesArray':
					return $this->strExtraFieldNamesArray;
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
		 * @param string strName Name of the property to set
		 * @param string mixValue New value of the property
		 * @return mixed
		 */
		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Name':
						return $this->strName = QType::Cast($mixValue, QType::String);
					case 'ClassName':
						return $this->strClassName= QType::Cast($mixValue, QType::String);
					case 'NameArray':
						return $this->strNameArray = QType::Cast($mixValue, QType::ArrayType);
					case 'TokenArray':
						return $this->strTokenArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ExtraPropertyArray':
						return $this->arrExtraPropertyArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ExtraFieldNamesArray':
						return $this->strExtraFieldNamesArray = QType::Cast($mixValue, QType::ArrayType);
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}
?>