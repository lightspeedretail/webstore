<?php
	/**
	 * Used by the Qcodo Code Generator to describe a table's column
	 */
	class QColumn extends QBaseClass {

		/////////////////////////////
		// Protected Member Variables
		/////////////////////////////

		/**
		 * Specifies whether or not the column is a Primary Key
		 * @var bool PrimaryKey
		 */
		protected $blnPrimaryKey;

		/**
		 * Name of the column as defined in the database
		 * So for example, "first_name"
		 * @var string Name
		 */
		protected $strName;

		/**
		 * Name of the column as an object Property
		 * So for "first_name", it would be FirstName
		 * @var string PropertyName
		 */
		protected $strPropertyName;

		/**
		 * Name of the column as an object protected Member Variable
		 * So for "first_name VARCHAR(50)", it would be strFirstName
		 * @var string VariableName
		 */
		protected $strVariableName;

		/**
		 * The type of the protected member variable (uses one of the string constants from the Type class)
		 * @var string VariableType
		 */
		protected $strVariableType;

		/**
		 * The type of the protected member variable (uses the actual constant from the Type class)
		 * @var string VariableType
		 */
		protected $strVariableTypeAsConstant;

		/**
		 * The actual type of the column in the database (uses one of the string constants from the DatabaseType class)
		 * @var string DbType
		 */
		protected $strDbType;

		/**
		 * Length of the column as defined in the database
		 * @var int Length
		 */
		protected $intLength;

		/**
		 * The default value for the column as defined in the database
		 * @var mixed Default
		 */
		protected $mixDefault;

		/**
		 * Specifies whether or not the column is specified as "NOT NULL"
		 * @var bool NotNull
		 */
		protected $blnNotNull;

		/**
		 * Specifies whether or not the column is an identiy column (like auto_increment)
		 * @var bool Identity
		 */
		protected $blnIdentity;

		/**
		 * Specifies whether or not the column is a single-column Index
		 * @var bool Indexed
		 */
		protected $blnIndexed;

		/**
		 * Specifies whether or not the column is a unique
		 * @var bool Unique
		 */
		protected $blnUnique;

		/**
		 * Specifies whether or not the column is a system-updated "timestamp" column
		 * @var bool Timestamp
		 */
		protected $blnTimestamp;

		/**
		 * If the table column is foreign keyed off another column, then this
		 * Column instance would be a reference to another object
		 * @var Reference Reference
		 */
		protected $objReference;




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
				case 'PrimaryKey':
					return $this->blnPrimaryKey;
				case 'Name':
					return $this->strName;
				case 'PropertyName':
					return $this->strPropertyName;
				case 'VariableName':
					return $this->strVariableName;
				case 'VariableType':
					return $this->strVariableType;
				case 'VariableTypeAsConstant':
					return $this->strVariableTypeAsConstant;
				case 'DbType':
					return $this->strDbType;
				case 'Length':
					return $this->intLength;
				case 'Default':
					return $this->mixDefault;
				case 'NotNull':
					return $this->blnNotNull;
				case 'Identity':
					return $this->blnIdentity;
				case 'Indexed':
					return $this->blnIndexed;
				case 'Unique':
					return $this->blnUnique;
				case 'Timestamp':
					return $this->blnTimestamp;
				case 'Reference':
					return $this->objReference;
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
					case 'PrimaryKey':
						return $this->blnPrimaryKey = QType::Cast($mixValue, QType::Boolean);
					case 'Name':
						return $this->strName = QType::Cast($mixValue, QType::String);
					case 'PropertyName':
						return $this->strPropertyName = QType::Cast($mixValue, QType::String);
					case 'VariableName':
						return $this->strVariableName = QType::Cast($mixValue, QType::String);
					case 'VariableType':
						return $this->strVariableType = QType::Cast($mixValue, QType::String);
					case 'VariableTypeAsConstant':
						return $this->strVariableTypeAsConstant = QType::Cast($mixValue, QType::String);
					case 'DbType':
						return $this->strDbType = QType::Cast($mixValue, QType::String);
					case 'Length':
						return $this->intLength = QType::Cast($mixValue, QType::Integer);
					case 'Default':
						if ($mixValue === null || (($mixValue == '' || $mixValue == '0000-00-00 00:00:00' || $mixValue == '0000-00-00') && !$this->blnNotNull))
							return $this->mixDefault = null;
						else if (is_int($mixValue))
							return $this->mixDefault = QType::Cast($mixValue, QType::Integer);
						else if (is_numeric($mixValue))
							return $this->mixDefault = QType::Cast($mixValue, QType::Float);
						else
							return $this->mixDefault = QType::Cast($mixValue, QType::String);
					case 'NotNull':
						return $this->blnNotNull = QType::Cast($mixValue, QType::Boolean);
					case 'Identity':
						return $this->blnIdentity = QType::Cast($mixValue, QType::Boolean);
					case 'Indexed':
						return $this->blnIndexed = QType::Cast($mixValue, QType::Boolean);
					case 'Unique':
						return $this->blnUnique = QType::Cast($mixValue, QType::Boolean);
					case 'Timestamp':
						return $this->blnTimestamp = QType::Cast($mixValue, QType::Boolean);
					case 'Reference':
						return $this->objReference = QType::Cast($mixValue, 'QReference');
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