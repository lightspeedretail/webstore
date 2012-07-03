<?php
	// The built in SOAP WSDL Cache doesn't work very well
	// We use QCache to implement a much smarter WSDL cache mechanism
	// Because of this, we *MUST* set wsdl_cache_enabled to FALSE
	ini_set('soap.wsdl_cache_enabled', false);

	class QSoapParameter extends QBaseClass {
		protected $strName;
		protected $strType;
		protected $blnArray;
		protected $blnReference;

		public function __construct($strName, $strType, $blnArray, $blnReference) {
			$this->strName = $strName;
			$this->strType = $strType;
			$this->blnArray = $blnArray;
			$this->blnReference = $blnReference;
		}

		public function GetWsdlMessagePart(&$strComplexTypesArray) {
			if ($this->blnArray) {
				try {
					$strType = QType::SoapType($this->strType);
					$strArrayTypeName = QSoapService::GetArrayTypeName($strType);
					$strToReturn = sprintf('<part name="%s" type="xsd1:%s"/>', $this->strName, $strArrayTypeName);

					QSoapService::AlterComplexTypesArrayForArrayType($strArrayTypeName, $strType, $strComplexTypesArray);
					return $strToReturn;
				} catch (QInvalidCastException $objExc) {}

				$strArrayTypeName = QSoapService::GetArrayTypeName($this->strType);
				QSoapService::AlterComplexTypesArrayForArrayType($strArrayTypeName, 'xsd1:' . $this->strType, $strComplexTypesArray);
				$strToReturn = sprintf('<part name="%s" type="xsd1:%s"/>', $this->strName, $strArrayTypeName);
			} else {
				try {
					return sprintf('<part name="%s" type="xsd:%s"/>', $this->strName, QType::SoapType($this->strType));
				} catch (QInvalidCastException $objExc) {}

				$strToReturn = sprintf('<part name="%s" type="xsd1:%s"/>', $this->strName, $this->strType);
			}

			$objReflection = new ReflectionMethod($this->strType, 'AlterSoapComplexTypeArray');
			$objReflection->invoke(null, &$strComplexTypesArray, false);
			return $strToReturn;
		}

		public function __get($strName) {
			try {
				switch ($strName) {
					case 'Name': return $this->strName;
					case 'Type': return $this->strType;
					case 'Array': return $this->blnArray;
					case 'Reference': return $this->blnReference;
					default: return parent::__get($strName);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Name': return ($this->strName = QType::Cast($mixValue, QType::String));
					case 'Type': return ($this->strType= QType::Cast($mixValue, QType::String));
					case 'Array': return ($this->blnArray = QType::Cast($mixValue, QType::Boolean));
					case 'Reference': return ($this->blnReference = QType::Cast($mixValue, QType::Boolean));
					default: return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		
		public function Display() {
			return sprintf('%s%s %s$%s',
				$this->strType,
				($this->blnArray) ? '[]' : '',
				($this->blnReference) ? '&' : '',
				$this->strName);
		}

		public function DisplayReturn() {
			return sprintf('%s%s',
				$this->strType,
				($this->blnArray) ? '[]' : '');
		}
		
		public function IsObject() {
			switch ($this->strType) {
				case QType::String:
				case QType::Integer:
				case QType::Float:
				case QType::Boolean:
				case QType::DateTime:
					return false;
			}

			return true;
		}
		
		public function GetPhpFromSoapCode($strArgumentName) {
			// Check to see if it's an object
			if ($this->IsObject()) {
				if ($this->blnArray)
					// Handle Array of Objects
					return sprintf('%s = %s::GetArrayFromSoapArray(%s); ', $strArgumentName, $this->strType, $strArgumentName);
				else
					// Handle Single Object
					return sprintf('%s = %s::GetObjectFromSoapObject(%s); ', $strArgumentName, $this->strType, $strArgumentName);


			// Check to see if its a DateTime
			} else if ($this->strType == QType::DateTime) {
				if ($this->blnArray)
					return sprintf('$dttArray = array(); foreach(%s as $strDate) { ' .
						'array_push($dttArray, new QDateTime($strDate)); } %s = $dttArray; ',
						$strArgumentName, $strArgumentName);
				else
					return sprintf('%s = new QDateTime(%s); ', $strArgumentName, $strArgumentName);

			// It's a simple Variable
			} else 
				return null;
		}

		public function GetSoapFromPhpCode($strArgumentName) {
			// Check to see if it's an object
			if ($this->IsObject()) {
				if ($this->blnArray)
					return sprintf('%s::GetSoapArrayFromArray(%s)', $this->strType, $strArgumentName);
				else
					return sprintf('%s::GetSoapObjectFromObject(%s, true)', $this->strType, $strArgumentName);

			// Check to see if its a DateTime
			} else if ($this->strType == QType::DateTime) {
				if ($this->blnArray)
					return sprintf('QDateTime::GetSoapDateTimeArray(%s)', $strArgumentName);
				else
					return sprintf('%s->__toString(QDateTime::FormatSoap)', $strArgumentName);

			// It's a simple Variable
			} else
				return $strArgumentName;
		}
	}

	class QSoapMethod extends QBaseClass {
		protected $strName;
		protected $objParameters = array();
		protected $objReturnParameter;

		public function __construct($strName) {
			$this->strName = $strName;
		}

		public function AddParameter(QSoapParameter $objParameter) {
			$this->objParameters[$objParameter->Name] = $objParameter;
		}

		public function GetParameter($strName) {
			if (array_key_exists($strName, $this->objParameters))
				return $this->objParameters[$objParameter->Name];
			else
				return null;
		}

		public function GetAllParameters() {
			return $this->objParameters;
		}

		public function GetWsdlPortTypeOperation() {
			return sprintf('<operation name="%s">' . 
				'<input message="tns:%sRequest"/>' .
				'<output message="tns:%sResponse"/>' .
				'</operation>',
				$this->strName,
				$this->strName,
				$this->strName);
		}

		public function GetWsdlBindingOperation($strNamespace) {
			$strSoapBody = sprintf('<soap:body use="encoded" namespace="%s/%s" encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"/>',
				$strNamespace, $this->strName);
			return sprintf('<operation name="%s"><soap:operation soapAction="%s/%s"/>' .
				'<input>%s</input>' .
				'<output>%s</output></operation>',
				$this->strName,
				$strNamespace,
				$this->strName,
				$strSoapBody,
				$strSoapBody);
		}

		public function GetWsdlMethodMessages(&$strComplexTypesArray) {
			$strRequest = '';
			foreach ($this->objParameters as $objParameter)
				$strRequest .= $objParameter->GetWsdlMessagePart($strComplexTypesArray);

			$strResponse = '';
			if ($this->objReturnParameter)
				$strResponse = $this->objReturnParameter->GetWsdlMessagePart($strComplexTypesArray);
			foreach ($this->objParameters as $objParameter)
				if ($objParameter->Reference)
					$strResponse .= $objParameter->GetWsdlMessagePart($strComplexTypesArray);

			$strToReturn = sprintf('<message name="%sRequest">%s</message>', $this->strName, $strRequest);
			$strToReturn .= sprintf('<message name="%sResponse">%s</message>', $this->strName, $strResponse);
			return $strToReturn;
		}

		public function __get($strName) {
			try {
				switch ($strName) {
					case 'Name': return $this->strName;
					case 'ReturnParameter': return $this->objReturnParameter;
					default: return parent::__get($strName);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function __set($strName, $mixValue) {
			try {
				switch ($strName) {
					case 'Name': return ($this->strName = QType::Cast($mixValue, QType::String));
					case 'ReturnParameter': return ($this->objReturnParameter = QType::Cast($mixValue, 'QSoapParameter'));
					default: return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
		
		public function Display() {
			$strParameters = '';
			foreach ($this->objParameters as $objParameter)
				$strParameters .= ', ' . $objParameter->Display();
			if ($strParameters)
				$strParameters = substr($strParameters, 2);
			$strToReturn = sprintf('public %s %s(%s)', ($this->objReturnParameter) ? $this->objReturnParameter->DisplayReturn() : 'void', $this->strName, $strParameters);
			return $strToReturn;
		}
	}

	class QSoapService extends QBaseClass {
		public static $DefaultNamespace = 'http://qcodo.defaultnamespace.com';

		protected $objMethodArray = array();
		protected $strNamespace;
		protected $strClassName;
		protected $objSoapServer;

		public static function GetArrayTypeName($strType) {
			return sprintf('ArrayOf%s', ucfirst($strType));
		}

		public static function AlterComplexTypesArrayForArrayType($strArrayTypeName, $strType, &$strComplexTypesArray) {
			if (!array_key_exists($strArrayTypeName, $strComplexTypesArray))
				$strComplexTypesArray[$strArrayTypeName] = sprintf(
					'<complexType name="%s"><complexContent><restriction base="soapenc:Array">' . 
					'<attribute ref="soapenc:arrayType" wsdl:arrayType="%s[]"/>' .
					'</restriction></complexContent></complexType>',
					$strArrayTypeName,
					$strType);
		}

		public static function Run($strClassName, $strNamespace = null) {			
			QApplication::$EncodingType = 'UTF-8';
			
			$objWsdlCache = new QCache('soap', QApplication::$ScriptName, 'wsdl', QApplication::$ScriptFilename);
			$objDiscoCache = new QCache('soap', QApplication::$ScriptName, 'disco', QApplication::$ScriptFilename);
			$objClassWrapperCache = new QCache('soap', QApplication::$ScriptName, 'class.php', QApplication::$ScriptFilename);

			// Reflect through this QSoapService
			$strDisco = $objDiscoCache->GetData();

			if (($strDisco === false) || (!$strNamespace))
				$objReflection = new ReflectionClass($strClassName);

			// Figure Out Namespace
			if (!$strNamespace) {
				$objReflectionProperties = $objReflection->getStaticProperties();
				$strNamespace = $objReflectionProperties['DefaultNamespace'];
			}
			$strNamespace = trim($strNamespace);
			if (QString::LastCharacter($strNamespace) == '/')
				$strNamespace = substr($strNamespace, 0, strlen($strNamespace) - 1);

			// Check for Cached Disco
			if ($strDisco === false) {
				// Instantiate Service and Setup new Soap Methods
				$objService = new $strClassName($strClassName, $strNamespace);
	
				// Setup SOAP Methods
				try {
					$objService->SetupSoapMethods($objReflection);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

				// Get Disco, Wsdl and Wrapper, and cache them!
				$objWsdlCache->SaveData($objService->GetWsdl());
				$objDiscoCache->SaveData($objService->GetDisco());
				$objClassWrapperCache->SaveData($objService->GetClassWrapper());
			}

			// Process Service Browse (e.g. if accessed via GET)
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				if (array_key_exists('QUERY_STRING', $_SERVER))
					switch (strtolower($_SERVER['QUERY_STRING'])) {
						case 'disco':
							header('Content-Type: text/xml');
							_p('<?xml version="1.0" encoding="' . QApplication::$EncodingType . '"?>', false);
							_p($objDiscoCache->GetData(), false);
							return;
						case 'wsdl':
							header('Content-Type: text/xml');
							_p('<?xml version="1.0" encoding="' . QApplication::$EncodingType . '"?>', false);
							_p($objWsdlCache->GetData(), false);
							return;
					}

				printf('<link rel="alternate" type="text/xml" href="%s?disco"/><a href="%s?disco">Web Service Discovery File</a> &nbsp;|&nbsp; <a href="%s?wsdl">Web Service Description File (WSDL)</a>',
					QApplication::$ScriptName, QApplication::$ScriptName, QApplication::$ScriptName);
				return;
			}

			// Process Service Execution (e.g. accessed via a POST)
			$objService = new $strClassName($strClassName, $strNamespace);

			// Get the Request
			$strRequest = file_get_contents("php://input");

			// Create the Service Class Wrapper
			require($objClassWrapperCache->GetFilePath());

			// Use PHP 5.1+'s SoapServer class to handle the actual work
			$objService->objSoapServer = new SoapServer($objWsdlCache->GetFilePath());
			$objService->objSoapServer->setClass($strClassName . 'Wrapper', $strClassName, $strNamespace);
			$objService->objSoapServer->handle($strRequest);
		}

		public function GetClassWrapper() {
			$strNewClass = sprintf('<?php class %sWrapper extends %s { ', $this->strClassName, $this->strClassName);

			foreach ($this->objMethodArray as $objMethod) {
				$strParameterArray = array();
				foreach ($objMethod->GetAllParameters() as $objParameter) {
					$strParameterDefinition = sprintf('%s %s$%s',
						($objParameter->IsObject() || $objParameter->Type == QType::DateTime) ? $objParameter->Type : '',
						($objParameter->Reference) ? '&' : '',
						$objParameter->Name
					);
					$strParameterArray[] = trim($strParameterDefinition);
				}

				$strNewClass .= sprintf('public function %s(%s) { $objArgs = func_get_args(); ', $objMethod->Name, implode(', ', $strParameterArray));

				// Setup Input/Output Parameter Lists
				$strInputParameterArray = array();
				$strOutputParameterArray = array();

				if ($objMethod->ReturnParameter)
					array_push($strOutputParameterArray, $objMethod->ReturnParameter->GetSoapFromPhpCode('$objToReturn'));

				$intIndex = 0;
				foreach ($objMethod->GetAllParameters() as $objParameter) {
					$strArgumentName = sprintf('$objArgs[%s]', $intIndex);

					// Add it to the Input Parameters
					array_push($strInputParameterArray, $strArgumentName);

					$strNewClass .= $objParameter->GetPhpFromSoapCode($strArgumentName);
					if ($objParameter->Reference)
						array_push($strOutputParameterArray, $objParameter->GetSoapFromPhpCode($strArgumentName));

					$intIndex++;
				}

				// Make the Function Call
				if ($objMethod->ReturnParameter)
					$strNewClass .= '$objToReturn = ';
				$strNewClass .= sprintf('parent::%s(%s); ', $objMethod->Name, implode(', ', $strInputParameterArray));

				// Return the Results
				if (count($strOutputParameterArray) == 1)
					$strNewClass .= sprintf('return %s;} ', $strOutputParameterArray[0]);
				else if (count($strOutputParameterArray) > 1)
					$strNewClass .= sprintf('return array(%s);} ', implode(', ', $strOutputParameterArray));
				else
					$strNewClass .= '} ';
			}

			$strNewClass .= '} ?>';

			// Make it a little easier to read
			$strNewClass = str_replace('{', "{\r\n", $strNewClass);
			$strNewClass = str_replace('}', "}\r\n", $strNewClass);
			$strNewClass = str_replace(';', ";\r\n", $strNewClass);

			return $strNewClass;
		}

		public function GetLocation() {
			return sprintf('http%s://%s%s',
				(array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS']) ? 's' : '',
				$_SERVER['HTTP_HOST'],
				QApplication::$ScriptName);
		}

		public function GetWsdlTypes($strComplexTypesArray) {
			$strToReturn = sprintf('<types><schema xmlns="http://www.w3.org/2001/XMLSchema" targetNamespace="%s" ' .
				'xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:soapenc="http://schemas.xmlsoap.org/soap/encoding/">',
				$this->strNamespace);

			foreach ($strComplexTypesArray as $strComplexType)
				$strToReturn .= $strComplexType;

			$strToReturn .= '</schema></types>';
			return $strToReturn;
		}

		protected function GetWsdlService() {
			$strToReturn = sprintf('<service name="%s"><documentation/>', $this->strClassName);
			$strToReturn .= '<port name="Port" binding="tns:Binding">';
			$strToReturn .= sprintf('<soap:address location="%s"/></port></service>', $this->GetLocation());
			return $strToReturn;
		}

		protected function GetWsdlBinding() {
			$strToReturn = '<binding name="Binding" type="tns:PortType"><soap:binding transport="http://schemas.xmlsoap.org/soap/http" style="rpc"/>';

			foreach ($this->objMethodArray as $objMethod)
				$strToReturn .= $objMethod->GetWsdlBindingOperation($this->strNamespace);

			$strToReturn .= '</binding>';
			return $strToReturn;
		}
		
		protected function GetWsdlPortType() {
			$strToReturn = '<portType name="PortType">';

			foreach ($this->objMethodArray as $objMethod)
				$strToReturn .= $objMethod->GetWsdlPortTypeOperation();

			$strToReturn .= '</portType>';
			return $strToReturn;
		}

		protected function GetDisco() {
			$strUrl = $this->GetLocation();
			$strToReturn = '<discovery xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
				'xmlns="http://schemas.xmlsoap.org/disco/">';
			$strToReturn .= sprintf('<contractRef ref="%s?wsdl" docRef="%s" xmlns="http://schemas.xmlsoap.org/disco/scl/" />', $strUrl, $strUrl);
			$strToReturn .= sprintf('<soap address="%s" xmlns:q1="%s" binding="q1:%sSoap" xmlns="http://schemas.xmlsoap.org/disco/soap/" />',
				$strUrl, $this->strNamespace, $this->strClassName);
			$strToReturn .= '</discovery>';
			return $strToReturn;
		}

		protected function GetWsdl() {
			$strToReturn = sprintf('<definitions name="%s" ' . 
				'targetNamespace="%s" ' .
				'xmlns="http://schemas.xmlsoap.org/wsdl/" ' .
				'xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" ' .
				'xmlns:tns="%s" ' .
				'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ' .
				'xmlns:xsd1="%s">',
				$this->strClassName, $this->strNamespace, $this->strNamespace, $this->strNamespace);

			// Service
			$strToReturn .= $this->GetWsdlService();

			// Binding
			$strToReturn .= $this->GetWsdlBinding();

			// PortType
			$strToReturn .= $this->GetWsdlPortType();

			// Messages
			$strComplexTypesArray = array();
			foreach ($this->objMethodArray as $objMethod)
				$strToReturn .= $objMethod->GetWsdlMethodMessages($strComplexTypesArray);

			// Types
			$strToReturn .= $this->GetWsdlTypes($strComplexTypesArray);

			// WSDL Definition End
			$strToReturn .= '</definitions>';

			return $strToReturn;
		}

		protected function SetupSoapMethods(ReflectionClass $objReflection) {
			$objReflectionMethods = $objReflection->getMethods();
			if ($objReflectionMethods) foreach ($objReflectionMethods as $objReflectionMethod) {
				if ($objReflectionMethod->isPublic() && !$objReflectionMethod->isAbstract() &&
					!$objReflectionMethod->isStatic() &&  !$objReflectionMethod->isConstructor() &&
					!$objReflectionMethod->isDestructor() &&
					($objReflectionMethod->getDeclaringClass()->getName() != 'QBaseClass')) {
					$objMethod = new QSoapMethod($objReflectionMethod->getName());
					
					$strComments = $objReflectionMethod->getDocComment();
					if ($strComments) {
						// Use Comments to Calculate strType and blnArray
						$strTypeArray = array();
						$blnArrayArray = array();
						$strCommentArray = explode("\n", $strComments);
						foreach ($strCommentArray as $strCommentLine) {
							$strCommentLine = trim($strCommentLine);
							$strMatches = array();
	
							preg_match_all ("/[\s]*\*[\s]*@param[\s]+([a-zA-Z0-9_]+)(\[\])?[\s]+[&$]*([a-zA-Z0-9_]+)/", $strCommentLine, $strMatches);
							if ((count($strMatches) == 4) &&
								(count($strMatches[0]) == 1) &&
								(count($strMatches[1]) == 1) &&
								(count($strMatches[2]) == 1) &&
								(count($strMatches[3]) == 1)) {
								$strType = $strMatches[1][0];
								$strArray = $strMatches[2][0];
								$strName = $strMatches[3][0];
	
								$strTypeArray[$strName] = $strType;
								if ($strArray == '[]')
									$blnArrayArray[$strName] = true;
								else
									$blnArrayArray[$strName] = false;
							} else {
								$strMatches = array();
								preg_match_all ("/[\s]*\*[\s]*@return[\s]+([a-zA-Z0-9_]+)(\[\])?/", $strCommentLine, $strMatches);								
								
								if ((count($strMatches) == 3) &&
									(count($strMatches[0]) == 1) &&
									(count($strMatches[1]) == 1) &&
									(count($strMatches[2]) == 1)) {
									$strType = $strMatches[1][0];
									$strArray = $strMatches[2][0];
									
									if ($strArray == '[]')
										$blnArray = true;
									else
										$blnArray = false;
										
									try {
										$strType = QType::TypeFromDoc($strType);
									} catch (QCallerException $objExc) {
										$objExc->IncrementOffset();
										throw $objExc;
									}

									if ($strType != 'void')
										$objMethod->ReturnParameter = new QSoapParameter($objMethod->Name . 'Result', $strType, $blnArray, false);
								}
							}
						}

						$objParameters = $objReflectionMethod->getParameters();
						if ($objParameters) foreach ($objParameters as $objParameter) {
							$blnArray = false;
							$strName = $objParameter->getName();
							
							if (array_key_exists($strName, $strTypeArray)) {
								try {
									$strType = QType::TypeFromDoc($strTypeArray[$strName]);
								} catch (QCallerException $objExc) {
									$objExc->IncrementOffset();
									throw $objExc;
								}
							} else {
								throw new QCallerException('Unable to determine Parameter Type for Method from PHPDoc Comment: ' . $objReflectionMethod->getName() . '(' . $strName . ')');
							}

							if (array_key_exists($strName, $blnArrayArray))
								$blnArray = $blnArrayArray[$strName];
							
							$objMethod->AddParameter(new QSoapParameter($strName, $strType, $blnArray, $objParameter->isPassedByReference()));
						}
						
						array_push($this->objMethodArray, $objMethod);
					}
				}
			}
		}

		public function __construct($strClassName, $strNamespace) {
			$this->strClassName = $strClassName;
			$this->strNamespace = $strNamespace;
		}
		
		public function __get($strName) {
			switch ($strName) {
				case 'ClassName': return $this->strClassName;
				case 'Namespace': return $this->strNamespace;
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