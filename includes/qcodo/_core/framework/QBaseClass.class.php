<?php
	/**
	 * This is the Base Class for ALL classes in the system.  It provides
	 * proper error handling of property getters and setters.  It also
	 * provides the OverrideAttribute functionality.
	 */
	abstract class QBaseClass {
		/**
		 * Override method to perform a property "Get"
		 * This will get the value of $strName
		 * All inhereted objects that call __get() should always fall through
		 * to calling parent::__get() in a try/catch statement catching
		 * for CallerExceptions.
		 *
		 * @param string $strName Name of the property to get
		 * @return mixed the returned property
		 */
		public function __get($strName) {
			$objReflection = new ReflectionClass($this);
			throw new QUndefinedPropertyException("GET", $objReflection->getName(), $strName);
		}

		/**
		 * Override method to perform a property "Set"
		 * This will set the property $strName to be $mixValue
		 * All inhereted objects that call __set() should always fall through
		 * to calling parent::__set() in a try/catch statement catching
		 * for CallerExceptions.
		 *
		 * @param string $strName Name of the property to set
		 * @param string $mixValue New value of the property
		 * @return mixed the property that was set
		 */
		public function __set($strName, $mixValue) {
			$objReflection = new ReflectionClass($this);
			throw new QUndefinedPropertyException("SET", $objReflection->getName(), $strName);
		}

		/**
		 * This allows you to set any properties, given by a name-value pair list
		 * in mixOverrideArray.
		 *
		 * Each item in mixOverrideArray needs to be either a string in the format
		 * of Property=Value or an array in the format of array(Property => Value).
		 * OverrideAttributes() will basically call
		 * $this->Property = Value for each string element in the array.
		 *
		 * Value can be surrounded by quotes... but this is optional.
		 *
		 * @param mixed[] $objOverrideArray the array of name-value pair items of properties/attributes to override
		 * @return void
		 */
		public final function OverrideAttributes($mixOverrideArray) {
			// Iterate through the OverrideAttribute Array
			if ($mixOverrideArray) foreach ($mixOverrideArray as $mixOverrideItem) {
				if (is_array($mixOverrideItem)) {
					foreach ($mixOverrideItem as $strKey=>$mixValue)
						// Apply the override
						try {
							$this->__set($strKey, $mixValue);
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
				} else {
					// Extract the Key and Value for this OverrideAttribute
					$intPosition = strpos($mixOverrideItem, "=");
					if ($intPosition === false)
						throw new QCallerException(sprintf("Improperly formatted OverrideAttribute: %s", $mixOverrideItem));
					$strKey = substr($mixOverrideItem, 0, $intPosition);
					$mixValue = substr($mixOverrideItem, $intPosition + 1);

					// Ensure that the Value is properly formatted (unquoted, single-quoted, or double-quoted)
					if (substr($mixValue, 0, 1) == "'") {
						if (substr($mixValue, strlen($mixValue) - 1) != "'")
							throw new QCallerException(sprintf("Improperly formatted OverrideAttribute: %s", $mixOverrideItem));
						$mixValue = substr($mixValue, 1, strlen($mixValue) - 2);
					} else if (substr($mixValue, 0, 1) == '"') {
						if (substr($mixValue, strlen($mixValue) - 1) != '"')
							throw new QCallerException(sprintf("Improperly formatted OverrideAttribute: %s", $mixOverrideItem));
						$mixValue = substr($mixValue, 1, strlen($mixValue) - 2);
					}

					// Apply the override
					try {
						$this->__set($strKey, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				}
			}
		}
	}
?>