<?php
	/**
	 * Used by the Qcodo Code Generator to describe a database Table
	 */
	class QTable extends QBaseClass {

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
		 * Pluralized Name as a collection of objects of this PHP Class
		 * @var string ClassNamePlural;
		 */
		protected $strClassNamePlural;

		/**
		 * Array of Column objects (as indexed by Column name)
		 * @var Column[] ColumnArray
		 */
		protected $objColumnArray;

		/**
		 * Array of ReverseReverence objects (indexed numerically)
		 * @var ReverseReference[] ReverseReferenceArray
		 */
		protected $objReverseReferenceArray;

		/**
		 * Array of ManyToManyReference objects (indexed numerically)
		 * @var ManyToManyReference[] ManyToManyReferenceArray
		 */
		protected $objManyToManyReferenceArray;

		/**
		 * Array of Index objects (indexed numerically)
		 * @var Index[] IndexArray
		 */
		protected $objIndexArray;



		/////////////////////
		// Public Constructor
		/////////////////////

		/**
		 * Default Constructor.  Simply sets up the TableName and ensures that ReverseReferenceArray is a blank array.
		 *
		 * @param string strName Name of the Table
		 * @return TypeTable
		 */
		public function __construct($strName) {
			$this->strName = $strName;
			$this->objReverseReferenceArray = array();
			$this->objManyToManyReferenceArray = array();
			$this->objColumnArray = array();
			$this->objIndexArray = array();
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
				case 'ClassNamePlural':
					return $this->strClassNamePlural;
				case 'ClassName':
					return $this->strClassName;
				case 'ColumnArray':
					return (array) $this->objColumnArray;
				case 'PrimaryKeyColumnArray':
					if ($this->objColumnArray) {
						$objToReturn = array();
						foreach ($this->objColumnArray as $objColumn)
							if ($objColumn->PrimaryKey)
								array_push($objToReturn, $objColumn);
						return $objToReturn;
					} else
						return null;
				case 'ReverseReferenceArray':
					return (array) $this->objReverseReferenceArray;
				case 'ManyToManyReferenceArray':
					return (array) $this->objManyToManyReferenceArray;
				case 'IndexArray':
					return (array) $this->objIndexArray;
				case 'ReferenceCount':
					$intCount = count($this->objManyToManyReferenceArray);
					foreach ($this->objColumnArray as $objColumn)
						if ($objColumn->Reference)
							$intCount++;
					return $intCount;
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
						return $this->strClassName = QType::Cast($mixValue, QType::String);
					case 'ClassNamePlural':
						return $this->strClassNamePlural = QType::Cast($mixValue, QType::String);
					case 'ColumnArray':
						return $this->objColumnArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ReverseReferenceArray':
						return $this->objReverseReferenceArray = QType::Cast($mixValue, QType::ArrayType);
					case 'ManyToManyReferenceArray':
						return $this->objManyToManyReferenceArray = QType::Cast($mixValue, QType::ArrayType);
					case 'IndexArray':
						return $this->objIndexArray = QType::Cast($mixValue, QType::ArrayType);
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