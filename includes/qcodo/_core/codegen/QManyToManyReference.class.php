<?php
	/**
	 * Used by the Qcodo Code Generator to describe a column reference from 
	 * the table's perspective (aka a Foreign Key from the referenced Table's point of view)
	 */
	class QManyToManyReference extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * Name of the foreign key object itself, as defined in the database or create script
		 * @var string KeyName
		 */
		protected $strKeyName;

		/**
		 * Name of the association table, itself (the many-to-many table that maps
		 * the relationshipfor this ManyToManyReference)
		 * @var string Table
		 */
		protected $strTable;

		/**
		 * Name of the referencing column (the column that owns the foreign key to this table)
		 * @var string Column
		 */
		protected $strColumn;

		/**
		 * Name of the opposite column (the column that owns the foreign key to the related table)
		 * @var string Column
		 */
		protected $strOppositeColumn;

		/**
		 * Type of the opposite column (the column that owns the foreign key to the related table)
		 * as a Variable type (for example, to be used to define the input parameter type to a Load function)
		 * @var string OppositeVariableType
		 */
		protected $strOppositeVariableType;

		/**
		 * Name of the opposite column (the column that owns the foreign key to the related table)
		 * as a Variable name (for example, to be used as an input parameter to a Load function)
		 * @var string OppositeVariableName
		 */
		protected $strOppositeVariableName;

		/**
		 * Name of the opposite column (the column that owns the foreign key to the related table)
		 * as a Property name (for example, to be used as a QQAssociationNode parameter name for the
		 * column itself)
		 * @var string OppositePropertyName
		 */
		protected $strOppositePropertyName;

		/**
		 * Name of the opposite column (the column that owns the foreign key to the related table)
		 * as an Object Description (see "ObjectDescription" below)
		 * @var string OppositeObjectDescription
		 */
		protected $strOppositeObjectDescription;

		/**
		 * The name of the associated table (the table that the OTHER
		 * column in the association table points to)
		 * @var string AssociatedTable
		 */
		protected $strAssociatedTable;

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
		 * Singular object description used in the function names for the
		 * reverse reference.  See documentation for more details.
		 * @var string ObjectDescription
		 */
		protected $strObjectDescription;

		/**
		 * Plural object description used in the function names for the
		 * reverse reference.  See documentation for more details.
		 * @var string VariableType
		 */
		protected $strObjectDescriptionPlural;

		/**
		 * Array of non-FK Column objects (as indexed by Column name)
		 * @var Column[] ColumnArray
		 */
		protected $objColumnArray;





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
				case 'OppositeColumn':
					return $this->strOppositeColumn;
				case 'OppositeVariableType':
					return $this->strOppositeVariableType;
				case 'OppositeVariableName':
					return $this->strOppositeVariableName;
				case 'OppositePropertyName':
					return $this->strOppositePropertyName;
				case 'OppositeObjectDescription':
					return $this->strOppositeObjectDescription;
				case 'AssociatedTable':
					return $this->strAssociatedTable;
				case 'VariableName':
					return $this->strVariableName;
				case 'VariableType':
					return $this->strVariableType;
				case 'ObjectDescription':
					return $this->strObjectDescription;
				case 'ObjectDescriptionPlural':
					return $this->strObjectDescriptionPlural;
				case 'ColumnArray':
					return $this->objColumnArray;
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
					case 'OppositeColumn':
						return $this->strOppositeColumn = QType::Cast($mixValue, QType::String);
					case 'OppositeVariableType':
						return $this->strOppositeVariableType = QType::Cast($mixValue, QType::String);
					case 'OppositeVariableName':
						return $this->strOppositeVariableName = QType::Cast($mixValue, QType::String);
					case 'OppositePropertyName':
						return $this->strOppositePropertyName = QType::Cast($mixValue, QType::String);
					case 'OppositeObjectDescription':
						return $this->strOppositeObjectDescription = QType::Cast($mixValue, QType::String);
					case 'AssociatedTable':
						return $this->strAssociatedTable = QType::Cast($mixValue, QType::String);
					case 'VariableName':
						return $this->strVariableName = QType::Cast($mixValue, QType::String);
					case 'VariableType':
						return $this->strVariableType = QType::Cast($mixValue, QType::String);
					case 'ObjectDescription':
						return $this->strObjectDescription = QType::Cast($mixValue, QType::String);
					case 'ObjectDescriptionPlural':
						return $this->strObjectDescriptionPlural = QType::Cast($mixValue, QType::String);
					case 'ColumnArray':
						return $this->objColumnArray = QType::Cast($mixValue, QType::ArrayType);
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