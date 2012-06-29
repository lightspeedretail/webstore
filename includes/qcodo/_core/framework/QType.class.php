<?php
	/**
	 * The exception that is thrown by QType::Cast
	 * if an invalid cast is performed.  InvalidCastException
	 * derives from CallerException, and therefore should be handled
	 * similar to how CallerExceptions are handled (e.g. IncrementOffset should
	 * be called whenever an InvalidCastException is caught and rethrown).
	 */
	class QInvalidCastException extends QCallerException {
		public function __construct($strMessage, $intOffset = 2) {
			parent::__construct($strMessage, $intOffset);
		}
	}

	/**
	 * Type Library to add some support for strongly named types.
	 *
	 * PHP does not support strongly named types.  The Qcodo type library
	 * and Qcodo typing in general attempts to bring some structure to types
	 * when passing in values, properties, parameters to/from Qcodo framework objects
	 * and methods.
	 *
	 * The Type library attempts to allow as much flexibility as possible to
	 * set and cast variables to other types, similar to how PHP does it natively,
	 * but simply adds a big more structure to it.
	 *
	 * For example, regardless if a variable is an integer, boolean, or string,
	 * QType::Cast will allow the flexibility of those values to interchange with
	 * each other with little to no issue.
	 *
	 * In addition to value objects (ints, bools, floats, strings), the Type library
	 * also supports object casting.  While technically casting one object to another
	 * is not a true cast, QType::Cast does at least ensure that the tap being "casted"
	 * to is a legitamate subclass of the object being "cast".  So if you have ParentClass,
	 * and you have a ChildClass that extends ParentClass,
	 *		$objChildClass = new ChildClass();
	 *		$objParentClass = new ParentClass();
	 *		Type::Cast($objChildClass, 'ParentClass'); // is a legal cast
	 *		Type::Cast($objParentClass, 'ChildClass'); // will throw an InvalidCastException
	 *
	 * For values, specifically int to string conversion, one different between
	 * QType::Cast and PHP (in order to add structure) is that if an integer contains
	 * alpha characters, PHP would normally allow that through w/o complaint, simply
	 * ignoring any numeric characters past the first alpha character.  QType::Cast
	 * would instead throw an InvalidCastException to let the developer immedaitely
	 * know that something doesn't look right.
	 *
	 * In theory, the type library should maintain the same level of flexibility
	 * PHP developers are accostomed to, while providing a mechanism to limit
	 * careless coding errors and tough to figure out mistakes due to PHP's sometimes
	 * overly laxed type conversions.
	 */
abstract class QType
    {
        // Constants
		const String = 'string';
		const Integer = 'integer';
		const Float = 'double';
		const Boolean = 'boolean';
		const Object = 'object';
		const ArrayType = 'array';
		const DateTime = 'QDateTime';
		const Resource = 'resource';

        /**
         * This faux constructor method throws a caller exception.
         * The Type object should never be instantiated, and this constructor
         * override simply guarantees it.
         *
         * @access public
         * @return void
         */
        public final function __construct()
        {
            throw new QCallerException('Type should never be instantiated.  All methods and variables are publically statically accessible.');
        }

        /**
         *
         * @static
         * @final
         * @access public
         * @param mixed $mixItem
         * @param mixed $mixType
         * @return mixed
         */
        public final static function Cast($mixItem, $mixType)
        {
            // Return nulls as nulls
            if(is_null($mixItem)) return null;
            try
            {
                // Object
                if(is_object($mixItem))
                {
                    if(is_string($mixType) && $mixItem instanceof $mixType) return $mixItem;
                    try
                    {
                        return QType::Cast((string)$mixItem, $mixType);
                    }
                    catch(Exception $objExcepton)
                    {
                        throw new QInvalidCastException('Unable to cast Object to String');
                    }
                }
                // Array
                if(is_array($mixItem))
                {
                    if($mixType == QType::ArrayType) return $mixItem;
                    throw new QInvalidCastException(sprintf('Unable to cast Array to %s', self::Constant($mixType)));
                }
                // String, Number or Boolean
                if(is_string($mixItem) || is_numeric($mixItem) || is_bool($mixItem))
                {
                    switch ($mixType)
                    {
                        // Cast to Boolean
                        case QType::Boolean:
                            if (is_bool($mixItem))
                                return $mixItem;
                            if (is_null($mixItem))
                                return false;
                            if (strlen($mixItem) == 0)
                                return false;
                            if (strtolower($mixItem) == 'false')
                                return false;
                            return (bool)$mixItem;
                        // Cast To Integer
                        case QType::Integer:
                            if (strlen($mixItem) == 0) return null;
                            $mixNewItem = (int)$mixItem;
                            if($mixNewItem == $mixItem) return $mixNewItem;
                            throw new QInvalidCastException(sprintf('Unable to cast value to %s: %s', self::Constant($mixType), $mixItem));
                        // Cast to Float
                        case QType::Float:
                            if (strlen($mixItem) == 0) return null;
                            $mixNewItem = (float)$mixItem;
                            if($mixNewItem == $mixItem) return $mixNewItem;
                            throw new QInvalidCastException(sprintf('Unable to cast value to %s: %s', self::Constant($mixType), $mixItem));
                        // Cast to String
                        case QType::String:
                            return (string)$mixItem;
                        // Unable to Cast
                        default:
                            throw new QInvalidCastException(sprintf('Unable to cast value to %s: %s', self::Constant($mixType), $mixItem));
                    }
                }
            }
            catch (QCallerException $objException)
            {
                $objException->IncrementOffset();
                throw $objException;
            }
            catch(Exception $objException)
            {
                throw new QInvalidCastException(sprintf('Unable to cast %s to %s', $mixItem, $mixType));
            }
            // Resource
            if(is_resource($mixItem)) throw new QInvalidCastException('Resources can not be cast');
            // Unknown type
            throw new QInvalidCastException(sprintf('Unable to determine type of item to be cast: %s', $mixItem));
        }

		/**
		 * Used by the Qcodo Code Generator to allow for the code generation of
		 * the actual "Type::Xxx" constant, instead of the text of the constant,
		 * in generated code.
		 *
		 * It is rare for Constant to be used manually outside of Code Generation.
		 *
		 * @param string $intType the type to convert to 'constant' form
		 * @return string the text of the Text:Xxx Constant
		 */
		public final static function Constant($intType)
        {
			switch ($intType)
            {
				case QType::Object: return 'QType::Object';
				case QType::String: return 'QType::String';
				case QType::Integer: return 'QType::Integer';
				case QType::Float: return 'QType::Float';
				case QType::Boolean: return 'QType::Boolean';
				case QType::ArrayType: return 'QType::ArrayType';
				case QType::Resource: return 'QType::Resource';
				case QType::DateTime: return 'QType::DateTime';

				default:
					// Could not determine type
					throw new QInvalidCastException(sprintf('Unable to determine type of item to lookup its constant: %s', $intType));
			}
		}

        /**
         * Determines QType from DocString type...
         *
         * @final
         * @static
         * @param string $strType
         * @return int
         */
		public final static function TypeFromDoc($strType)
        {
			switch (strtolower($strType))
            {
				case 'string':
				case 'str':
					return QType::String;

				case 'integer':
				case 'int':
					return QType::Integer;

				case 'float':
				case 'flt':
				case 'double':
				case 'dbl':
				case 'single':
				case 'decimal':
					return QType::Float;

				case 'bool':
				case 'boolean':
				case 'bit':
					return QType::Boolean;

				case 'datetime':
				case 'date':
				case 'time':
				case 'qdatetime':
					return QType::DateTime;

				case 'null':
				case 'void':
					return 'void';

				default:
					try
                    {
						$objReflection = new ReflectionClass($strType);
						return $strType;
					} 
                    catch (ReflectionException $objExc)
                    {
						throw new QInvalidCastException(sprintf('Unable to determine type of item from PHPDoc Comment to lookup its QType or Class: %s', $strType));
					}
			}
		}

		/**
		 * Used by the Qcodo Code Generator and QSoapService class to allow for the xml generation of
		 * the actual "s:type" Soap Variable types.
		 *
		 * @param string $intType the type to convert to 'constant' form
		 * @return string the text of the SOAP standard s:type variable type
		 */
		public final static function SoapType($intType)
        {
			switch ($intType)
            {
				case QType::String: return 'string';
				case QType::Integer: return 'int';
				case QType::Float: return 'float';
				case QType::Boolean: return 'boolean';
				case QType::DateTime: return 'dateTime';

				case QType::ArrayType:
				case QType::Object:
				case QType::Resource:
				default:
					// Could not determine type
					throw new QInvalidCastException(sprintf('Unable to determine type of item to lookup its constant: %s', $intType));
			}
		}
    }
?>