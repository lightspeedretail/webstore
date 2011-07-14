<?php
	/**
	 * Used by the Qcodo Code Generator to describe a column reference
	 * (aka a Foreign Key)
	 */
	class QReference extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * Name of the foreign key object, as defined in the database or create script
		 * @var string KeyName
		 */
		protected $strKeyName;

		/**
		 * Name of the table that is being referenced
		 * @var string Table
		 */
		protected $strTable;

		/**
		 * Name of the column that is being referenced
		 * @var string Column
		 */
		protected $strColumn;

		/**
		 * Name of the referenced object as an class Property
		 * So if the column that this reference points from is named
		 * "primary_annual_report_id", it would be PrimaryAnnualReport
		 * @var string PropertyName
		 */
		protected $strPropertyName;

		/**
		 * Name of the  referenced object as an class protected Member object
		 * So if the column that this reference poitns from is named
		 * "primary_annual_report_id", it would be objPrimaryAnnualReport
		 * @var string VariableName
		 */
		protected $strVariableName;

		/**
		 * The type of the protected member object (should be based off of $this->strTable)
		 * So if referencing the table "annual_report", it would be AnnualReport
		 * @var string VariableType
		 */
		protected $strVariableType;

		/**
		 * If the table that this reference points to is a type table, then this is true
		 * @var string IsType
		 */
		protected $blnIsType;




		////////////////////
		// Public Overriders
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
				case 'KeyName':
					return $this->strKeyName;
				case 'Table':
					return $this->strTable;
				case 'Column':
					return $this->strColumn;
				case 'PropertyName':
					return $this->strPropertyName;
				case 'VariableName':
					return $this->strVariableName;
				case 'VariableType':
					return $this->strVariableType;
				case 'IsType':
					return $this->blnIsType;
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
			try {
				switch ($strName) {
					case 'KeyName':
						return $this->strKeyName = QType::Cast($mixValue, QType::String);
					case 'Table':
						return $this->strTable = QType::Cast($mixValue, QType::String);
					case 'Column':
						return $this->strColumn = QType::Cast($mixValue, QType::String);
					case 'PropertyName':
						return $this->strPropertyName = QType::Cast($mixValue, QType::String);
					case 'VariableName':
						return $this->strVariableName = QType::Cast($mixValue, QType::String);
					case 'VariableType':
						return $this->strVariableType = QType::Cast($mixValue, QType::String);
					case 'IsType':
						return $this->blnIsType = QType::Cast($mixValue, QType::Boolean);
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