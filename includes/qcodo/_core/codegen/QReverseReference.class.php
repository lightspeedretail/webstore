<?php
	/**
	 * Used by the Qcodo Code Generator to describe a column reference from 
	 * the table's perspective (aka a Foreign Key from the referenced Table's point of view)
	 */
	class QReverseReference extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * Name of the foreign key object itself, as defined in the database or create script
		 * @var string KeyName
		 */
		protected $strKeyName;

		/**
		 * Name of the referencing table (the table that owns the column that is the foreign key)
		 * @var string Table
		 */
		protected $strTable;

		/**
		 * Name of the referencing column (the column that owns the foreign key)
		 * @var string Column
		 */
		protected $strColumn;

		/**
		 * Specifies whether the referencing column is specified as "NOT NULL"
		 * @var bool NotNull
		 */
		protected $blnNotNull;

		/**
		 * Specifies whether the referencing column is unique
		 * @var bool Unique
		 */
		protected $blnUnique;

		/**
		 * Name of the reverse-referenced object as an function parameter.
		 * So if this is a reverse reference to "person" via "report.person_id",
		 * the VariableName would be "objReport"
		 * @var string VariableName
		 */
		protected $strVariableName;

		/**
		 * Type of the reverse-referenced object as a class.
		 * So if this is a reverse reference to "person" via "report.person_id",
		 * the VariableName would be "Report"
		 * @var string VariableType
		 */
		protected $strVariableType;

		/**
		 * Property Name of the referencing column (the column that owns the foreign key)
		 * in the associated Class.  So if this is a reverse reference to the "person" table 
		 * via the table/column "report.owner_person_id", the PropertyName would be "OwnerPersonId"
		 * @var string PropertyName
		 */
		protected $strPropertyName;

		/**
		 * Singular object description used in the function names for the
		 * reverse reference.  See documentation for more details.
		 * @var string ObjectDescription
		 */
		protected $strObjectDescription;

		/**
		 * Plural object description used in the function names for the
		 * reverse reference.  See documentation for more details.
		 * @var string ObjectDescriptionPlural
		 */
		protected $strObjectDescriptionPlural;

		/**
		 * A member variable name to be used by classes that contain the local member variable
		 * for this unique reverse reference.  Only aggregated when blnUnique is true.
		 * @var string ObjectMemberVariable
		 */
		protected $strObjectMemberVariable;

		/**
		 * A property name to be used by classes that contain the property
		 * for this unique reverse reference.  Only aggregated when blnUnique is true.
		 * @var string ObjectPropertyName
		 */
		protected $strObjectPropertyName;




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
				case 'KeyName':
					return $this->strKeyName;
				case 'Table':
					return $this->strTable;
				case 'Column':
					return $this->strColumn;
				case 'NotNull':
					return $this->blnNotNull;
				case 'Unique':
					return $this->blnUnique;
				case 'VariableName':
					return $this->strVariableName;
				case 'VariableType':
					return $this->strVariableType;
				case 'PropertyName':
					return $this->strPropertyName;
				case 'ObjectDescription':
					return $this->strObjectDescription;
				case 'ObjectDescriptionPlural':
					return $this->strObjectDescriptionPlural;
				case 'ObjectMemberVariable':
					return $this->strObjectMemberVariable;
				case 'ObjectPropertyName':
					return $this->strObjectPropertyName;
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
					case 'KeyName':
						return $this->strKeyName = QType::Cast($mixValue, QType::String);
					case 'Table':
						return $this->strTable = QType::Cast($mixValue, QType::String);
					case 'Column':
						return $this->strColumn = QType::Cast($mixValue, QType::String);
					case 'NotNull':
						return $this->blnNotNull = QType::Cast($mixValue, QType::Boolean);
					case 'Unique':
						return $this->blnUnique = QType::Cast($mixValue, QType::Boolean);
					case 'VariableName':
						return $this->strVariableName = QType::Cast($mixValue, QType::String);
					case 'VariableType':
						return $this->strVariableType = QType::Cast($mixValue, QType::String);
					case 'PropertyName':
						return $this->strPropertyName = QType::Cast($mixValue, QType::String);
					case 'ObjectDescription':
						return $this->strObjectDescription = QType::Cast($mixValue, QType::String);
					case 'ObjectDescriptionPlural':
						return $this->strObjectDescriptionPlural = QType::Cast($mixValue, QType::String);
					case 'ObjectMemberVariable':
						return $this->strObjectMemberVariable = QType::Cast($mixValue, QType::String);
					case 'ObjectPropertyName':
						return $this->strObjectPropertyName = QType::Cast($mixValue, QType::String);
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