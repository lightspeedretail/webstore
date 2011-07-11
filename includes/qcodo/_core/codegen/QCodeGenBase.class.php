<?php
	function QcodoHandleCodeGenParseError($__exc_errno, $__exc_errstr, $__exc_errfile, $__exc_errline) {
		$strErrorString = str_replace("SimpleXMLElement::__construct() [<a href='function.SimpleXMLElement---construct'>function.SimpleXMLElement---construct</a>]: ", '', $__exc_errstr);
		QCodeGen::$RootErrors .= sprintf("%s\r\n", $strErrorString);
	}

	/**
	 * This is the CodeGen class which performs the code generation
	 * for both the Object-Relational Model (e.g. Data Objects) as well as
	 * the draft Forms, which make up simple HTML/PHP scripts to perform
	 * basic CRUD functionality on each object.
	 */
	abstract class QCodeGenBase extends QBaseClass {
		// Class Name Suffix/Prefix
		protected $strClassPrefix;
		protected $strClassSuffix;

		// Errors and Warnings
		protected $strErrors;

		// PHP Reserved Words.  They make up:
		// Invalid Type names -- these are reserved words which cannot be Type names in any user type table
		// Invalid Table names -- these are reserved words which cannot be used as any table name
	        const PhpReservedWords = 'new, null, break, return, switch, self, case, const, clone, continue, declare, default, echo, else, elseif, empty, exit, eval, if, try, throw, catch, public, private, protected, function, extends, foreach, for, while, do, var, class, static, abstract, isset, unset, implements, interface, instanceof, include, include_once, require, require_once, abstract, and, or, xor, array, list, false, true, global, parent, print, exception, namespace, goto, final, endif, endswitch, enddeclare, endwhile, use, as, endfor, endforeach, this';

		// Relative Paths (from __QCODO_CORE__) to the CORE Template and Subtemplate Directories
		const TemplatesPath = '/codegen/templates/';
//		const SubTemplatesPath = '/codegen/subtemplates/';

		// Relative Paths (from __QCODO__) to the CUSTOM Template and Subtemplate Directories
		const TemplatesPathCustom = '/codegen/templates/';
//		const SubTemplatesPathCustom = '/codegen/subtemplates/';

		// DebugMode -- for Template Developers
		// This will output the current evaluated template/statement to the screen
		// On "eval" errors, you can click on the "View Rendered Page" to see what currently
		// is being evalled or evaluated, which should hopefully aid in template debugging.
		const DebugMode = false;

		/**
		 * This static array contains an array of active and executed codegen objects, based
		 * on the XML Configuration passed in to Run()
		 *
		 * @var QCodeGen[] array of active/executed codegen objects
		 */
		public static $CodeGenArray;

		/**
		 * This is the array representation of the parsed SettingsXml
		 * for reportback purposes.
		 *
		 * @var string[] array of config settings
		 */
		protected static $SettingsXmlArray;

		/**
		 * This is the SimpleXML representation of the Settings XML file
		 *
		 * @var SimpleXmlObject the XML representation
		 */
		protected static $SettingsXml;
		
		public static $SettingsFilePath;

		/**
		 * Application Name (from CodeGen Settings)
		 *
		 * @var string $ApplicationName
		 */
		protected static $ApplicationName;

		/**
		 * Template Escape Begin (from CodeGen Settings)
		 *
		 * @var string $TemplateEscapeBegin
		 */
		protected static $TemplateEscapeBegin;
		protected static $TemplateEscapeBeginLength;

		/**
		 * Template Escape End (from CodeGen Settings)
		 *
		 * @var string $TemplateEscapeEnd
		 */
		protected static $TemplateEscapeEnd;
		protected static $TemplateEscapeEndLength;
		
		public static $RootErrors = '';

		public static function GetSettingsXml() {
			$strCrLf = "\r\n";

			$strToReturn = sprintf('<codegen>%s', $strCrLf);
			$strToReturn .= sprintf('	<name application="%s"/>%s', QCodeGen::$ApplicationName, $strCrLf);
			$strToReturn .= sprintf('	<templateEscape begin="%s" end="%s"/>%s', QCodeGen::$TemplateEscapeBegin, QCodeGen::$TemplateEscapeEnd, $strCrLf);
			$strToReturn .= sprintf('	<dataSources>%s', $strCrLf);
			foreach (QCodeGen::$CodeGenArray as $objCodeGen)
				$strToReturn .= $strCrLf . $objCodeGen->GetConfigXml();
			$strToReturn .= sprintf('%s	</dataSources>%s', $strCrLf, $strCrLf);
			$strToReturn .= '</codegen>';

			return $strToReturn;
		}

		public static function Run($strSettingsXmlFilePath) {
			QCodeGen::$CodeGenArray = array();
			QCodeGen::$SettingsFilePath = $strSettingsXmlFilePath;

			if (!file_exists($strSettingsXmlFilePath)) {
				QCodeGen::$RootErrors = 'FATAL ERROR: CodeGen Settings XML File (' . $strSettingsXmlFilePath . ') was not found.';
				return;
			}

			if (!is_file($strSettingsXmlFilePath)) {
				QCodeGen::$RootErrors = 'FATAL ERROR: CodeGen Settings XML File (' . $strSettingsXmlFilePath . ') was not found.';
				return;
			}

			// Try Parsing the Xml Settings File
			try {
				QApplication::SetErrorHandler('QcodoHandleCodeGenParseError', E_ALL);
				QCodeGen::$SettingsXml = new SimpleXMLElement(file_get_contents($strSettingsXmlFilePath));
				QApplication::RestoreErrorHandler();
			} catch (Exception $objExc) {
				QCodeGen::$RootErrors .= 'FATAL ERROR: Unable to parse CodeGenSettings XML File: ' . $strSettingsXmlFilePath;
				QCodeGen::$RootErrors .= "\r\n";
				QCodeGen::$RootErrors .= $objExc->getMessage();
				return;
			}

			// Set the Template Escaping
			QCodeGen::$TemplateEscapeBegin = QCodeGen::LookupSetting(QCodeGen::$SettingsXml, 'templateEscape', 'begin');
			QCodeGen::$TemplateEscapeEnd = QCOdeGen::LookupSetting(QCodeGen::$SettingsXml, 'templateEscape', 'end');
			QCodeGen::$TemplateEscapeBeginLength = strlen(QCodeGen::$TemplateEscapeBegin);
			QCodeGen::$TemplateEscapeEndLength = strlen(QCodeGen::$TemplateEscapeEnd);

			if ((!QCodeGen::$TemplateEscapeBeginLength) || (!QCodeGen::$TemplateEscapeEndLength)) {
				QCodeGen::$RootErrors .= "CodeGen Settings XML Fatal Error: templateEscape begin and/or end was not defined\r\n";
				return;
			}

			// Application Name
			QCodeGen::$ApplicationName = QCodeGen::LookupSetting(QCodeGen::$SettingsXml, 'name', 'application');

			// Iterate Through DataSources
			if (QCodeGen::$SettingsXml->dataSources->asXML())
				foreach (QCodeGen::$SettingsXml->dataSources->children() as $objChildNode) {
					switch (dom_import_simplexml($objChildNode)->nodeName) {
						case 'database':
							QCodeGen::$CodeGenArray[] = new QDatabaseCodeGen($objChildNode);
							break;
						case 'restService':
							QCodeGen::$CodeGenArray[] = new QRestServiceCodeGen($objChildNode);
							break;
						default:
							QCodeGen::$RootErrors .= sprintf("Invalid Data Source Type in CodeGen Settings XML File (%s): %s\r\n",
								$strSettingsXmlFilePath, dom_import_simplexml($objChildNode)->nodeName);
							break;
					}
				}
		}

		/**
		 * This will lookup either the node value (if no attributename is passed in) or the attribute value
		 * for a given Tag.  Node Searches only apply from the root level of the configuration XML being passed in
		 * (e.g. it will not be able to lookup the tag name of a grandchild of the root node)
		 * 
		 * If No Tag Name is passed in, then attribute/value lookup is based on the root node, itself.
		 *
		 * @param SimpleXmlElement $objNode
		 * @param string $strTagName
		 * @param string $strAttributeName
		 * @param string $strType
		 * @return mixed the return type depends on the QType you pass in to $strType
		 */
		static protected function LookupSetting($objNode, $strTagName, $strAttributeName = null, $strType = QType::String) {
			if ($strTagName)
				$objNode = $objNode->$strTagName;

			if ($strAttributeName) {
				switch ($strType) {
					case QType::Integer:
						try {
							$intToReturn = QType::Cast($objNode[$strAttributeName], QType::Integer);
							return $intToReturn;
						} catch (Exception $objExc) {
							return null;
						}
					case QType::Boolean:
						try {
							$blnToReturn = QType::Cast($objNode[$strAttributeName], QType::Boolean);
							return $blnToReturn;
						} catch (Exception $objExc) {
							return null;
						}
					default:
						$strToReturn = trim(QType::Cast($objNode[$strAttributeName], QType::String));
						return $strToReturn;
				}
			} else {
				$strToReturn = trim(QType::Cast($objNode, QType::String));
				return $strToReturn;
			}
		}
		
		/**
		 * 
		 */
		public static function GenerateAggregate() {
			$objDbOrmCodeGen = array();
			$objRestServiceCodeGen = array();

			foreach (QCodeGen::$CodeGenArray as $objCodeGen) {
				if ($objCodeGen instanceof QDatabaseCodeGen)
					array_push($objDbOrmCodeGen, $objCodeGen);
				if ($objCodeGen instanceof QRestServiceCodeGen)
					array_push($objRestServiceCodeGen, $objCodeGen);
			}

			$strToReturn = array();
			array_merge($strToReturn, QDatabaseCodeGen::GenerateAggregateHelper($objDbOrmCodeGen));
//			array_push($strToReturn, QRestServiceCodeGen::GenerateAggregateHelper($objRestServiceCodeGen));

			return $strToReturn;
		}

		/**
		 * Given a template prefix (e.g. db_orm_, db_type_, rest_, soap_, etc.), pull
		 * all the _*.tpl templates from any subfolders of the template prefix in QCodeGen::TemplatesPath and QCodeGen::TemplatesPathCustom,
		 * and call GenerateFile() on each one.  If there are any template files that reside
		 * in BOTH TemplatesPath AND TemplatesPathCustom, then only use the TemplatesPathCustom one (which
		 * in essence overrides the one in TemplatesPath).
		 *
		 * @param string $strTemplatePrefix the prefix of the templates you want to generate against
		 * @param mixed[] $mixArgumentArray array of arguments to send to EvaluateTemplate
		 * @return boolean success/failure on whether or not all the files generated successfully
		 */
		public function GenerateFiles($strTemplatePrefix, $mixArgumentArray) {
			// Make sure both our Template and TemplateCustom paths are valid
			$strTemplatePath = sprintf('%s%s%s', __QCODO_CORE__, QCodeGen::TemplatesPath, $strTemplatePrefix);
			if (!is_dir($strTemplatePath))
				throw new Exception(sprintf("QCodeGen::TemplatesPath does not appear to be a valid directory:\r\n%s", $strTemplatePath));

			$strTemplatePathCustom = sprintf('%s%s', __QCODO__, QCodeGen::TemplatesPathCustom);
			if (!is_dir($strTemplatePathCustom))
				throw new Exception(sprintf("QCodeGen::TemplatesPathCustom does not appear to be a valid directory:\r\n%s", $strTemplatePathCustom));
			$strTemplatePathCustom .= $strTemplatePrefix;

			// Create an array of arrays of standard templates and custom (override) templates to process
			// Index by [module_name][filename] => true/false where
			// module name (e.g. "class_gen", "form_delegates) is name of folder within the prefix (e.g. "db_orm")
			// filename is the template filename itself (in a _*.tpl format)
			// true = override (use custom) and false = do not override (use standard)
			$strTemplateArray = array();

			// Go through standard templates first
			$objDirectory = opendir($strTemplatePath);
			while ($strModuleName = readdir($objDirectory))
				if (($strModuleName != '.') && ($strModuleName != '..') &&
					($strModuleName != 'SVN') && ($strModuleName != 'CVS') &&
					is_dir($strTemplatePath . '/' . $strModuleName)) {

					// We're in a valid Module -- look for any _*.tpl template files
					$objModuleDirectory = opendir($strTemplatePath . '/' . $strModuleName);
					while ($strFilename = readdir($objModuleDirectory))
						if ((QString::FirstCharacter($strFilename) == '_') &&
							(substr($strFilename, strlen($strFilename) - 4) == '.tpl'))
							$strTemplateArray[$strModuleName][$strFilename] = false;
			}

			// Go through and create or override with any custom templates
			if (is_dir($strTemplatePathCustom)) {
				$objDirectory = opendir($strTemplatePathCustom);
				while ($strModuleName = readdir($objDirectory))
					if (($strModuleName != '.') && ($strModuleName != '..') &&
						($strModuleName != 'SVN') && ($strModuleName != 'CVS') &&
						is_dir($strTemplatePathCustom . '/' . $strModuleName)) {
						$objModuleDirectory = opendir($strTemplatePathCustom . '/' . $strModuleName);
						while ($strFilename = readdir($objModuleDirectory))
							if ((QString::FirstCharacter($strFilename) == '_') &&
								(substr($strFilename, strlen($strFilename) - 4) == '.tpl'))
								$strTemplateArray[$strModuleName][$strFilename] = true;
					}
			}

			// Finally, iterate through all the TempalteFiles and call GenerateFile to Evaluate/Generate/Save them
			$blnSuccess = true;
			foreach ($strTemplateArray as $strModuleName => $strFileArray)
				foreach ($strFileArray as $strFilename => $blnOverrideFlag)
					if (!$this->GenerateFile($strTemplatePrefix . '/' . $strModuleName, $strFilename, $blnOverrideFlag, $mixArgumentArray))
						$blnSuccess = false;

			return $blnSuccess;
		}

		/**
		 * Enter description here...
		 *
		 * @param string $strModuleName
		 * @param string $strFilename
		 * @param boolean $blnOverrideFlag whether we are using the _core template, or using a custom one
		 * @param mixed[] $mixArgumentArray
		 * @param boolean $blnSave wheather or not to actually perform the save
		 * @return mixed returns the evaluated template or boolean save success.
		 */
		public function GenerateFile($strModuleName, $strFilename, $blnOverrideFlag, $mixArgumentArray, $blnSave = true) {
			// Figure out the actual TemplateFilePath
			if ($blnOverrideFlag)
				$strTemplateFilePath = __QCODO__ . QCodeGen::TemplatesPathCustom . $strModuleName . '/' . $strFilename;
			else
				$strTemplateFilePath = __QCODO_CORE__ . QCodeGen::TemplatesPath . $strModuleName . '/' . $strFilename;

			// Setup Debug/Exception Message
			if (QCodeGen::DebugMode) _p("Evaluating $strTemplateFilePath<br/>", false);
			$strError = 'Template\'s first line must be <template OverwriteFlag="boolean" DocrootFlag="boolean" TargetDirectory="string" DirectorySuffix="string" TargetFileName="string"/>: ' . $strTemplateFilePath;

			// Check to see if the template file exists, and if it does, Load It
			if (!file_exists($strTemplateFilePath))
				throw new QCallerException('Template File Not Found: ' . $strTemplateFilePath);
			$strTemplate = file_get_contents($strTemplateFilePath);

			// Evaluate the Template
			$strTemplate = $this->EvaluateTemplate($strTemplate, $strModuleName, $mixArgumentArray);

			// Parse out the first line (which contains path and overwriting information)
			$intPosition = strpos($strTemplate, "\n");
			if ($intPosition === false)
				throw new Exception($strError);

			$strFirstLine = trim(substr($strTemplate, 0, $intPosition));
			$strTemplate = substr($strTemplate, $intPosition + 1);

			$objTemplateXml = null;
			// Attempt to Parse the First Line as XML
			try {
				@$objTemplateXml = new SimpleXMLElement($strFirstLine);
			} catch (Exception $objExc) {}

			if (is_null($objTemplateXml) || (!($objTemplateXml instanceof SimpleXMLElement)))
				throw new Exception($strError);

			$blnOverwriteFlag = QType::Cast($objTemplateXml['OverwriteFlag'], QType::Boolean);
			$blnDocrootFlag = QType::Cast($objTemplateXml['DocrootFlag'], QType::Boolean);
			$strTargetDirectory = QType::Cast($objTemplateXml['TargetDirectory'], QType::String);
			$strDirectorySuffix = QType::Cast($objTemplateXml['DirectorySuffix'], QType::String);
			$strTargetFileName = QType::Cast($objTemplateXml['TargetFileName'], QType::String);

			if (is_null($blnOverwriteFlag) || is_null($strTargetFileName) || is_null($strTargetDirectory) || is_null($strDirectorySuffix) || is_null($blnDocrootFlag))
				throw new Exception($strError);

			if ($blnSave && $strTargetDirectory) {
				// Figure out the REAL target directory
				if ($blnDocrootFlag)
					$strTargetDirectory = __DOCROOT__ . $strTargetDirectory . $strDirectorySuffix;
				else
					$strTargetDirectory = $strTargetDirectory . $strDirectorySuffix;

				// Create Directory (if needed)
				if (!is_dir($strTargetDirectory))
					if (!QApplication::MakeDirectory($strTargetDirectory, 0777))
						throw new Exception('Unable to mkdir ' . $strTargetDirectory);

				// Save to Disk
				$strFilePath = sprintf('%s/%s', $strTargetDirectory, $strTargetFileName);
				if ($blnOverwriteFlag || (!file_exists($strFilePath))) {
					$intBytesSaved = file_put_contents($strFilePath, $strTemplate);

					// CHMOD to full read/write permissions (applicable only to nonwindows)
					// Need to ignore error handling for this call just in case
					QApplication::SetErrorHandler(null);
					chmod($strFilePath, 0666);
					QApplication::RestoreErrorHandler();

					return ($intBytesSaved == strlen($strTemplate));
				} else
					// Becuase we are not supposed to overwrite, we should return "true" by default
					return true;
			}

			// Why Did We Not Save?
			if ($blnSave)
				// We WANT to Save, but Qcodo Configuration says that this functionality/feature should no longer be generated
				// By definition, we should return "true"
				return true;
			else
				// Running GenerateFile() specifically asking it not to save -- so return the evaluated template instead
				return $strTemplate;
		}



		protected function EvaluateSubTemplate($strSubTemplateFilename, $strModuleName, $mixArgumentArray) {
			if (QCodeGen::DebugMode) _p("Evaluating $strSubTemplateFilename<br/>", false);

			// Try the Custom SubTemplate Path
			$strFilename = sprintf('%s%s%s/%s', __QCODO__, QCodeGen::TemplatesPathCustom, $strModuleName, $strSubTemplateFilename);
			if (file_exists($strFilename))
				return $this->EvaluateTemplate(file_get_contents($strFilename), $strModuleName, $mixArgumentArray);

			// Try the Standard SubTemplate Path
			$strFilename = sprintf('%s%s%s/%s', __QCODO_CORE__, QCodeGen::TemplatesPath, $strModuleName, $strSubTemplateFilename);
			if (file_exists($strFilename))
				return $this->EvaluateTemplate(file_get_contents($strFilename), $strModuleName, $mixArgumentArray);

			// SubTemplate Does Not Exist
			throw new QCallerException('CodeGen SubTemplate Does Not Exist within the "' . $strModuleName . '" module: ' . $strSubTemplateFilename);
		}

		protected function EvaluateTemplate($strTemplate, $strModuleName, $mixArgumentArray) {
			// First remove all \r from the template (for Win/*nix compatibility)
			$strTemplate = str_replace("\r", '', $strTemplate);

			// Get all the arguments and set them locally
			if ($mixArgumentArray) foreach ($mixArgumentArray as $strName=>$mixValue) {
				$$strName = $mixValue;
			}

			// Of course, we also need to locally allow "objCodeGen"
			$objCodeGen = $this;

			// Look for the Escape Begin
			$intPosition = strpos($strTemplate, QCodeGen::$TemplateEscapeBegin);
			
			// Get Database Escape Identifiers
			$strEscapeIdentifierBegin = QApplication::$Database[$this->intDatabaseIndex]->EscapeIdentifierBegin;
			$strEscapeIdentifierEnd = QApplication::$Database[$this->intDatabaseIndex]->EscapeIdentifierEnd;

			// Evaluate All Escaped Clauses
			while ($intPosition !== false) {
				$intPositionEnd = strpos($strTemplate, QCodeGen::$TemplateEscapeEnd, $intPosition);

				// Get and cleanup the Eval Statement
				$strStatement = substr($strTemplate, $intPosition + QCodeGen::$TemplateEscapeBeginLength, 
										$intPositionEnd - $intPosition - QCodeGen::$TemplateEscapeEndLength);
				$strStatement = trim($strStatement);

				if (substr($strStatement, 0, 1) == '=') {
					// Remove Trailing ';' if applicable
					if (substr($strStatement, strlen($strStatement) - 1) == ';')
						$strStatement = trim(substr($strStatement, 0, strlen($strStatement) - 1));

					// Remove Head '='
					$strStatement = trim(substr($strStatement, 1));
					
					// Add 'return' eval
					$strStatement = sprintf('return (%s);', $strStatement);
				} else if (substr($strStatement, 0, 1) == '@') {
					// Remove Trailing ';' if applicable
					if (substr($strStatement, strlen($strStatement) - 1) == ';')
						$strStatement = trim(substr($strStatement, 0, strlen($strStatement) - 1));

					// Remove Head '@'
					$strStatement = trim(substr($strStatement, 1));

					// Calculate Template Filename
					$intVariablePosition = strpos($strStatement, '(');
					
					if ($intVariablePosition === false)
						throw new Exception('Invalid include subtemplate Command: ' . $strStatement);
					$strTemplateFile = substr($strStatement, 0, $intVariablePosition);

					$strVariableList = substr($strStatement, $intVariablePosition + 1);
					// Remove trailing ')'
					$strVariableList = trim(substr($strVariableList, 0, strlen($strVariableList) - 1));

					$strVariableArray = explode(',', $strVariableList);

					// Clean Each Variable
					for ($intIndex = 0; $intIndex < count($strVariableArray); $intIndex++) {
						// Trim
						$strVariableArray[$intIndex] = trim($strVariableArray[$intIndex]);
						
						// Remove trailing and head "'"
						$strVariableArray[$intIndex] = substr($strVariableArray[$intIndex], 1, strlen($strVariableArray[$intIndex]) - 2);
						
						// Trim Again
						$strVariableArray[$intIndex] = trim($strVariableArray[$intIndex]);
					}

					// Ensure each variable exists!
					foreach ($strVariableArray as $strVariable)
						if(!isset($$strVariable))
							throw new Exception(sprintf('Invalid Variable %s in include subtemplate command: %s', $strVariable, $strStatement));

					// Setup the ArgumentArray for this subtemplate
					$mixTemplateArgumentArray = array();
					foreach ($strVariableArray as $strVariable)
						$mixTemplateArgumentArray[$strVariable] = $$strVariable;

					// Get the Evaluated Template!
					$strEvaledStatement = $this->EvaluateSubTemplate($strTemplateFile . '.tpl', $strModuleName, $mixTemplateArgumentArray);

					// Set Statement to NULL so that the method knows to that the statement we're replacing
					// has already been eval'ed
					$strStatement = null;
				}

				if (substr($strStatement, 0, 1) == '-') {
					// Backup a number of characters
					$intPosition = $intPosition - strlen($strStatement);
					$strStatement = '';
					
					
				// Check if we're starting an open-ended statemen
				} else if (substr($strStatement, strlen($strStatement) - 1) == '{') {
					// We ARE in an open-ended statement

					// SubTemplate is the contents of this open-ended template
					$strSubTemplate = substr($strTemplate, $intPositionEnd + QCodeGen::$TemplateEscapeEndLength);

					// Parse through the rest of the template, and pull the correct SubTemplate,
					// Keeping in account nested open-ended statements
					$intLevel = 1;

					$intSubPosition = strpos($strSubTemplate, QCodeGen::$TemplateEscapeBegin);
					while (($intLevel > 0) && ($intSubPosition !== false)) {
						$intSubPositionEnd = strpos($strSubTemplate, QCodeGen::$TemplateEscapeEnd, $intSubPosition);
						$strFragment = substr($strSubTemplate, $intSubPosition + QCodeGen::$TemplateEscapeEndLength,
							$intSubPositionEnd - $intSubPosition - QCodeGen::$TemplateEscapeEndLength);
						$strFragment = trim($strFragment);
						
						$strFragmentLastCharacter = substr($strFragment, strlen($strFragment) - 1);

						if ($strFragmentLastCharacter == '{') {
							$intLevel++;
						} else if ($strFragmentLastCharacter == '}') {
							$intLevel--;
						}

						if ($intLevel)
							$intSubPosition = strpos($strSubTemplate, QCodeGen::$TemplateEscapeBegin, $intSubPositionEnd);
					}
					if ($intLevel != 0)
						throw new Exception("Improperly Terminated OpenEnded Command following; $strStatement");

					$strSubTemplate = substr($strSubTemplate, 0, $intSubPosition);

					// Remove First Carriage Return (if applicable)
					$intCrPosition = strpos($strSubTemplate, "\n");
					if ($intCrPosition !== false) {
						$strFragment = substr($strSubTemplate, 0, $intCrPosition + 1);
						if (trim($strFragment) == '') {
							// Nothing exists before the first CR
							// Go ahead and chop it off
							$strSubTemplate = substr($strSubTemplate, $intCrPosition + 1);
						}
					}

					// Remove blank space after the last carriage return (if applicable)
					$intCrPosition = strrpos($strSubTemplate, "\n");
					if ($intCrPosition !== false) {
						$strFragment = substr($strSubTemplate, $intCrPosition + 1);
						if (trim($strFragment) == '') {
							// Nothing exists after the last CR
							// Go ahead and chop it off
							$strSubTemplate = substr($strSubTemplate, 0, $intCrPosition + 1);
						}
					}
					
					// Figure out the Command and calculate SubTemplate
					$strCommand = substr($strStatement, 0, strpos($strStatement, ' '));
					switch ($strCommand) {
						case 'foreach':
							$strFullStatement = $strStatement;

							// Remove leading 'foreach' and trailing '{'
							$strStatement = substr($strStatement, strlen('foreach'));
							$strStatement = substr($strStatement, 0, strlen($strStatement) - 1);
							$strStatement = trim($strStatement);
							
							// Ensure that we've got a "(" and a ")"
							if ((QString::FirstCharacter($strStatement) != '(') ||
								(QString::LastCharacter($strStatement) != ')'))
								throw new Exception("Improperly Formatted foreach: $strFullStatement");
							$strStatement = trim(substr($strStatement, 1, strlen($strStatement) - 2));
							
							// Pull out the two sides of the "as" clause
							$strStatement = explode(' as ', $strStatement);
							if (count($strStatement) != 2)
								throw new Exception("Improperly Formatted foreach: $strFullStatement");
							
							$objArray = eval(sprintf('return %s;', trim($strStatement[0])));
							$strSingleObjectName = trim($strStatement[1]);
							$strNameKeyPair = explode('=>', $strSingleObjectName);

							$mixArgumentArray['_INDEX'] = 0;
							if (count($strNameKeyPair) == 2) {
								$strSingleObjectKey = trim($strNameKeyPair[0]);
								$strSingleObjectValue = trim($strNameKeyPair[1]);
								
								// Remove leading '$'
								$strSingleObjectKey = substr($strSingleObjectKey, 1);
								$strSingleObjectValue = substr($strSingleObjectValue, 1);

								// Iterate to setup strStatement
								$strStatement = '';
								if ($objArray) foreach ($objArray as $$strSingleObjectKey => $$strSingleObjectValue) {
									$mixArgumentArray[$strSingleObjectKey] = $$strSingleObjectKey;
									$mixArgumentArray[$strSingleObjectValue] = $$strSingleObjectValue;
									
									$strStatement .= $this->EvaluateTemplate($strSubTemplate, $strModuleName, $mixArgumentArray);
									$mixArgumentArray['_INDEX']++;
								}
							} else {
								// Remove leading '$'
								$strSingleObjectName = substr($strSingleObjectName, 1);

								// Iterate to setup strStatement
								$strStatement = '';
								if ($objArray) foreach ($objArray as $$strSingleObjectName) {
									$mixArgumentArray[$strSingleObjectName] = $$strSingleObjectName;
									
									$strStatement .= $this->EvaluateTemplate($strSubTemplate, $strModuleName, $mixArgumentArray);
									$mixArgumentArray['_INDEX']++;
								}
							}
							
							break;
							
						case 'if':
							$strFullStatement = $strStatement;

							// Remove leading 'if' and trailing '{'
							$strStatement = substr($strStatement, strlen('if'));
							$strStatement = substr($strStatement, 0, strlen($strStatement) - 1);
							$strStatement = trim($strStatement);
							
							
							if (eval(sprintf('return (%s);', $strStatement))) {
								$strStatement = $this->EvaluateTemplate($strSubTemplate, $strModuleName, $mixArgumentArray);
							} else
								$strStatement = '';
							
							break;
						default:
							throw new Exception("Invalid OpenEnded Command: $strStatement");
					}
					
					// Reclculate intPositionEnd
					$intPositionEnd = $intPositionEnd + QCodeGen::$TemplateEscapeEndLength + $intSubPositionEnd;
					
					// If nothing but whitespace between $intPositionEnd and the next CR, then remove the CR
					$intCrPosition = strpos($strTemplate, "\n", $intPositionEnd + QCodeGen::$TemplateEscapeEndLength);
					if ($intCrPosition !== false) {
						$strFragment = substr($strTemplate, $intPositionEnd + QCodeGen::$TemplateEscapeEndLength, $intCrPosition - ($intPositionEnd + QCodeGen::$TemplateEscapeEndLength));
						if (trim($strFragment == '')) {
							// Nothing exists after the escapeend and the next CR
							// Go ahead and chop it off
							$intPositionEnd = $intCrPosition - QCodeGen::$TemplateEscapeEndLength + 1;
						}
					} else {
						$strFragment = substr($strTemplate, $intPositionEnd + QCodeGen::$TemplateEscapeEndLength);
						if (trim($strFragment == '')) {
							// Nothing exists after the escapeend and the end
							// Go ahead and chop it off
							$intPositionEnd = strlen($strTemplate);
						}
					}

					
					
					// Recalcualte intPosition
					// If nothing but whitespace between $intPosition and the previous CR, then remove the Whitespace (keep the CR)
					$strFragment = substr($strTemplate, 0, $intPosition);
					$intCrPosition = strrpos($strFragment, "\n");

					
					if ($intCrPosition !== false) {
						$intLfLength = 1;
					} else {
						$intLfLength = 0;
						$intCrPosition = 0;
					}

					// Inlcude the previous "\r" if applicable
					if (($intCrPosition > 1) && (substr($strTemplate, $intCrPosition - 1, 1) == "\r")) {
						$intCrLength = 1;
						$intCrPosition--;
					} else
						$intCrLength = 0;
					$strFragment = substr($strTemplate, $intCrPosition, $intPosition - $intCrPosition);
					
					if (trim($strFragment) == '') {
						// Nothing exists before the escapebegin and the previous CR
						// Go ahead and chop it off (but not the CR or CR/LF)
						$intPosition = $intCrPosition + $intLfLength + $intCrLength;
					}
				} else {
					if (is_null($strStatement))
						$strStatement = $strEvaledStatement;
					else {
						if (QCodeGen::DebugMode) _p("Evalling: $strStatement<br/>", false);
						// Perform the Eval
						$strStatement = eval($strStatement);
					}
				}

				// Do the Replace
				$strTemplate = substr($strTemplate, 0, $intPosition) . $strStatement . substr($strTemplate, $intPositionEnd + QCodeGen::$TemplateEscapeEndLength);

				// GO to the next Escape Marker (if applicable)
				$intPosition = strpos($strTemplate, QCodeGen::$TemplateEscapeBegin);
			}
			return $strTemplate;
		}






		///////////////////////
		// COMMONLY OVERRIDDEN CONVERSION FUNCTIONS
		///////////////////////

		protected function ClassNameFromTableName($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return sprintf('%s%s%s',
				$this->strClassPrefix,
				QConvertNotation::CamelCaseFromUnderscore($strTableName),
				$this->strClassSuffix);
		}

		protected function VariableNameFromColumn(QColumn $objColumn) {
			return QConvertNotation::PrefixFromType($objColumn->VariableType) .
				QConvertNotation::CamelCaseFromUnderscore($objColumn->Name);
		}

		protected function PropertyNameFromColumn(QColumn $objColumn) {
			return QConvertNotation::CamelCaseFromUnderscore($objColumn->Name);
		}

		protected function TypeNameFromColumnName($strName) {
			return QConvertNotation::CamelCaseFromUnderscore($strName);
		}
		
		protected function ReferenceColumnNameFromColumn(QColumn $objColumn) {
			$strColumnName = $objColumn->Name;
			$intNameLength = strlen($strColumnName);
			
			// Does the column name for this reference column end in "_id"?
			if (($intNameLength > 3) && (substr($strColumnName, $intNameLength - 3) == "_id")) {
				// It ends in "_id" but we don't want to include the "Id" suffix
				// in the Variable Name.  So remove it.
				$strColumnName = substr($strColumnName, 0, $intNameLength - 3);
			} else {
				// Otherwise, let's add "_object" so that we don't confuse this variable name
				// from the variable that was mapped from the physical database
				// E.g., if it's a numeric FK, and the column is defined as "person INT",
				// there will end up being two variables, one for the Person id integer, and
				// one for the Person object itself.  We'll add Object t o the name of the Person object
				// to make this deliniation.
				$strColumnName = sprintf("%s_object", $strColumnName);
			}
			
			return $strColumnName;
		}

		protected function ReferenceVariableNameFromColumn(QColumn $objColumn) {
			$strColumnName = $this->ReferenceColumnNameFromColumn($objColumn);
			return QConvertNotation::PrefixFromType(QType::Object) .
				QConvertNotation::CamelCaseFromUnderscore($strColumnName);
		}

		protected function ReferencePropertyNameFromColumn(QColumn $objColumn) {
			$strColumnName = $this->ReferenceColumnNameFromColumn($objColumn);
			return QConvertNotation::CamelCaseFromUnderscore($strColumnName);
		}

		protected function VariableNameFromTable($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return QConvertNotation::PrefixFromType(QType::Object) .
				QConvertNotation::CamelCaseFromUnderscore($strTableName);
		}
		
		protected function ReverseReferenceVariableNameFromTable($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return $this->VariableNameFromTable($strTableName);
		}

		protected function ReverseReferenceVariableTypeFromTable($strTableName) {
			$strTableName = $this->StripPrefixFromTable($strTableName);
			return $this->ClassNameFromTableName($strTableName);
		}

		protected function ParameterCleanupFromColumn(QColumn $objColumn, $blnIncludeEquality = false) {
			if ($blnIncludeEquality)
				return sprintf('$%s = $objDatabase->SqlVariable($%s, true);',
					$objColumn->VariableName, $objColumn->VariableName);
			else
				return sprintf('$%s = $objDatabase->SqlVariable($%s);',
					$objColumn->VariableName, $objColumn->VariableName);
		}

		// To be used to list the columns as input parameters, or as parameters for sprintf
		protected function ParameterListFromColumnArray($objColumnArray) {
			return $this->ImplodeObjectArray(', ', '$', '', 'VariableName', $objColumnArray);
		}

		protected function ImplodeObjectArray($strGlue, $strPrefix, $strSuffix, $strProperty, $objArrayToImplode) {
			$strArrayToReturn = array();
			if ($objArrayToImplode) foreach ($objArrayToImplode as $objObject) {
				array_push($strArrayToReturn, sprintf('%s%s%s', $strPrefix, $objObject->__get($strProperty), $strSuffix));
			}
			
			return implode($strGlue, $strArrayToReturn);
		}

		protected function TypeTokenFromTypeName($strName) {
			$strToReturn = '';
			for($intIndex = 0; $intIndex < strlen($strName); $intIndex++)
				if (((ord($strName[$intIndex]) >= ord('a')) &&
					 (ord($strName[$intIndex]) <= ord('z'))) ||
					((ord($strName[$intIndex]) >= ord('A')) &&
					 (ord($strName[$intIndex]) <= ord('Z'))) ||
					((ord($strName[$intIndex]) >= ord('0')) &&
					 (ord($strName[$intIndex]) <= ord('9'))) ||
					($strName[$intIndex] == '_'))
					$strToReturn .= $strName[$intIndex];

			if (is_numeric(QString::FirstCharacter($strToReturn)))
				$strToReturn = '_' . $strToReturn;
			return $strToReturn;
		}

		protected function FormControlVariableNameForColumn(QColumn $objColumn) {
			if ($objColumn->Identity)
				return sprintf('lbl%s', $objColumn->PropertyName);
				
			if ($objColumn->Timestamp)
				return sprintf('lbl%s', $objColumn->PropertyName);

			if ($objColumn->Reference)
				return sprintf('lst%s', $objColumn->Reference->PropertyName);

			switch ($objColumn->VariableType) {
				case QType::Boolean:
					return sprintf('chk%s', $objColumn->PropertyName);
				case QType::DateTime:
					return sprintf('cal%s', $objColumn->PropertyName);
				default:
					return sprintf('txt%s', $objColumn->PropertyName);
			}
		}
		protected function FormControlClassForColumn(QColumn $objColumn) {
			if ($objColumn->Identity)
				return 'QLabel';
				
			if ($objColumn->Timestamp)
				return 'QLabel';

			if ($objColumn->Reference)
				return 'QListBox';

			switch ($objColumn->VariableType) {
				case QType::Boolean:
					return 'QCheckBox';
				case QType::DateTime:
					return 'QDateTimePicker';
				case QType::Integer:
					return 'QIntegerTextBox';
				case QType::Float:
					return 'QFloatTextBox';
				default:
					return 'QTextBox';
			}
		}

		protected function FormControlVariableNameForUniqueReverseReference(QReverseReference $objReverseReference) {
			if ($objReverseReference->Unique) {
				return sprintf("lst%s", $objReverseReference->ObjectDescription);
			} else
				throw new Exception('FormControlVariableNameForUniqueReverseReference requires ReverseReference to be unique');
		}

		protected function FormControlVariableNameForManyToManyReference(QManyToManyReference $objManyToManyReference) {
			return sprintf("lst%s", $objManyToManyReference->ObjectDescriptionPlural);
		}

		protected function FormLabelVariableNameForColumn(QColumn $objColumn) {
			return 'lbl' . $objColumn->PropertyName;
		}


		protected function FormLabelVariableNameForUniqueReverseReference(QReverseReference $objReverseReference) {
			if ($objReverseReference->Unique) {
				return sprintf("lbl%s", $objReverseReference->ObjectDescription);
			} else
				throw new Exception('FormControlVariableNameForUniqueReverseReference requires ReverseReference to be unique');
		}

		protected function FormLabelVariableNameForManyToManyReference(QManyToManyReference $objManyToManyReference) {
			return sprintf("lbl%s", $objManyToManyReference->ObjectDescriptionPlural);
		}

		protected function FormControlTypeForColumn(QColumn $objColumn) {
			if ($objColumn->Identity)
				return 'QLabel';

			if ($objColumn->Timestamp)
				return 'QLabel';

			if ($objColumn->Reference)
				return 'QListBox';

			switch ($objColumn->VariableType) {
				case QType::Boolean:
					return 'QCheckBox';
				case QType::DateTime:
					return 'QCalendar';
				case QType::Float:
					return 'QFloatTextBox';
				case QType::Integer:
					return 'QIntegerTextBox';
				case QType::String:
					return 'QTextBox';
				default:
					throw new Exception('Unknown type for Column: %s' . $objColumn->VariableType);
			}
		}

		protected function CalculateObjectMemberVariable($strTableName, $strColumnName, $strReferencedTableName) {
			return sprintf('%s%s%s%s',
				QConvertNotation::PrefixFromType(QType::Object),
				$this->strAssociatedObjectPrefix,
				$this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, false),
				$this->strAssociatedObjectSuffix);
		}

		protected function CalculateObjectPropertyName($strTableName, $strColumnName, $strReferencedTableName) {
			return sprintf('%s%s%s',
				$this->strAssociatedObjectPrefix,
				$this->CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, false),
				$this->strAssociatedObjectSuffix);
		}

		// TODO: These functions need to be documented heavily with information from "lexical analysis on fk names.txt"
		protected function CalculateObjectDescription($strTableName, $strColumnName, $strReferencedTableName, $blnPluralize) {
			// Strip Prefixes (if applicable)
			$strTableName = $this->StripPrefixFromTable($strTableName);
			$strReferencedTableName = $this->StripPrefixFromTable($strReferencedTableName);

			// Starting Point
			$strToReturn = QConvertNotation::CamelCaseFromUnderscore($strTableName);

			if ($blnPluralize)
				$strToReturn = $this->Pluralize($strToReturn);
				
			if ($strTableName == $strReferencedTableName) {
				// Self-referencing Reference to Describe

				// If Column Name is only the name of the referenced table, or the name of the referenced table with "_id",
				// then the object description is simply based off the table name.
				if (($strColumnName == $strReferencedTableName) ||
					($strColumnName == $strReferencedTableName . '_id'))
					return sprintf('Child%s', $strToReturn);
				
				// Rip out trailing "_id" if applicable
				$intLength = strlen($strColumnName);
				if (($intLength > 3) && (substr($strColumnName, $intLength - 3) == "_id"))
					$strColumnName = substr($strColumnName, 0, $intLength - 3);
	
				// Rip out the referenced table name from the column name
				$strColumnName = str_replace($strReferencedTableName, "", $strColumnName);
				
				// Change any double "_" to single "_"
				$strColumnName = str_replace("__", "_", $strColumnName);
				$strColumnName = str_replace("__", "_", $strColumnName);

				$strColumnName = QConvertNotation::CamelCaseFromUnderscore($strColumnName);

				// Special case for Parent/Child
				if ($strColumnName == 'Parent')
					return sprintf('Child%s', $strToReturn);

				return sprintf("%sAs%s",
					$strToReturn, $strColumnName);

			} else {
				// If Column Name is only the name of the referenced table, or the name of the referenced table with "_id",
				// then the object description is simply based off the table name.
				if (($strColumnName == $strReferencedTableName) ||
					($strColumnName == $strReferencedTableName . '_id'))
					return $strToReturn;

				// Rip out trailing "_id" if applicable
				$intLength = strlen($strColumnName);
				if (($intLength > 3) && (substr($strColumnName, $intLength - 3) == "_id"))
					$strColumnName = substr($strColumnName, 0, $intLength - 3);
	
				// Rip out the referenced table name from the column name
				$strColumnName = str_replace($strReferencedTableName, "", $strColumnName);
				
				// Change any double "_" to single "_"
				$strColumnName = str_replace("__", "_", $strColumnName);
				$strColumnName = str_replace("__", "_", $strColumnName);
				
				return sprintf("%sAs%s",
					$strToReturn,
					QConvertNotation::CamelCaseFromUnderscore($strColumnName));
			}
		}
		
		// this is called for ReverseReference Object Descriptions for association tables (many-to-many)
		protected function CalculateObjectDescriptionForAssociation($strAssociationTableName, $strTableName, $strReferencedTableName, $blnPluralize) {
			// Strip Prefixes (if applicable)
			$strTableName = $this->StripPrefixFromTable($strTableName);
			$strAssociationTableName = $this->StripPrefixFromTable($strAssociationTableName);
			$strReferencedTableName = $this->StripPrefixFromTable($strReferencedTableName);

			// Starting Point
			$strToReturn = QConvertNotation::CamelCaseFromUnderscore($strReferencedTableName);

			if ($blnPluralize)
				$strToReturn = $this->Pluralize($strToReturn);

			// Let's start with strAssociationTableName

			// Rip out trailing "_assn" if applicable
			$strAssociationTableName = str_replace($this->strAssociationTableSuffix, '', $strAssociationTableName);
			
			// Take out strTableName if applicable (both with and without underscores)
			$strAssociationTableName = str_replace($strTableName, '', $strAssociationTableName);
			$strTableName = str_replace('_', '', $strTableName);
			$strAssociationTableName = str_replace($strTableName, '', $strAssociationTableName);
			
			// Take out strReferencedTableName if applicable (both with and without underscores)
			$strAssociationTableName = str_replace($strReferencedTableName, '', $strAssociationTableName);
			$strReferencedTableName = str_replace('_', '', $strReferencedTableName);
			$strAssociationTableName = str_replace($strReferencedTableName, '', $strAssociationTableName);

			// Change any double "__" to single "_"
			$strAssociationTableName = str_replace("__", "_", $strAssociationTableName);
			$strAssociationTableName = str_replace("__", "_", $strAssociationTableName);
			$strAssociationTableName = str_replace("__", "_", $strAssociationTableName);
			
			// If we have nothing left or just a single "_" in AssociationTableName, return "Starting Point"
			if (($strAssociationTableName == "_") || ($strAssociationTableName == ""))
				return sprintf("%s%s%s",
					$this->strAssociatedObjectPrefix,
					$strToReturn,
					$this->strAssociatedObjectSuffix);
			
			// Otherwise, add "As" and the predicate
			return sprintf("%s%sAs%s%s",
				$this->strAssociatedObjectPrefix,
				$strToReturn,
				QConvertNotation::CamelCaseFromUnderscore($strAssociationTableName),
				$this->strAssociatedObjectSuffix);
		}

		// This is called by AnalyzeAssociationTable to calculate the GraphPrefixArray for a self-referencing association table (e.g. directed graph)
		protected function CalculateGraphPrefixArray($objForeignKeyArray) {
			// Analyze Column Names to determine GraphPrefixArray
			if ((strpos(strtolower($objForeignKeyArray[0]->ColumnNameArray[0]), 'parent') !== false) ||
				(strpos(strtolower($objForeignKeyArray[1]->ColumnNameArray[0]), 'child') !== false)) {
				$strGraphPrefixArray[0] = '';
				$strGraphPrefixArray[1] = 'Parent';
			} else if ((strpos(strtolower($objForeignKeyArray[0]->ColumnNameArray[0]), 'child') !== false) ||
						(strpos(strtolower($objForeignKeyArray[1]->ColumnNameArray[0]), 'parent') !== false)) {
				$strGraphPrefixArray[0] = 'Parent';
				$strGraphPrefixArray[1] = '';
			} else {
				// Use Default Prefixing for Graphs
				$strGraphPrefixArray[0] = 'Parent';
				$strGraphPrefixArray[1] = '';
			}

			return $strGraphPrefixArray;
		}

		protected function VariableTypeFromDbType($strDbType) {
			switch ($strDbType) {
				case QDatabaseFieldType::Bit:
					return QType::Boolean;
				case QDatabaseFieldType::Blob:
					return QType::String;
				case QDatabaseFieldType::Char:
					return QType::String;
				case QDatabaseFieldType::Date:
					return QType::DateTime;
				case QDatabaseFieldType::DateTime:
					return QType::DateTime;
				case QDatabaseFieldType::Float:
					return QType::Float;
				case QDatabaseFieldType::Integer:
					return QType::Integer;
				case QDatabaseFieldType::Time:
					return QType::DateTime;
				case QDatabaseFieldType::VarChar:
					return QType::String;
				throw new Exception("Invalid Db Type to Convert: $strDbType");
			}
		}

		protected function Pluralize($strName) {
			// Special Rules go Here
			switch (true) {	
				case (strtolower($strName) == 'play'):
					return $strName . 's';
			}

			$intLength = strlen($strName);
			if (substr($strName, $intLength - 1) == "y")
				return substr($strName, 0, $intLength - 1) . "ies";
			if (substr($strName, $intLength - 1) == "s")
				return $strName . "es";
			if (substr($strName, $intLength - 1) == "x")
				return $strName . "es";
			if (substr($strName, $intLength - 1) == "z")
				return $strName . "zes";
			if (substr($strName, $intLength - 2) == "sh")
				return $strName . "es";
			if (substr($strName, $intLength - 2) == "ch")
				return $strName . "es";

			return $strName . "s";
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
				case 'Errors':
					return $this->strErrors;
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
			try {
				switch($strName) {
					case 'Errors':
						return ($this->strErrors = QType::Cast($mixValue, QType::String));
					default:
						return parent::__set($strName, $mixValue);
				}
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
			}
		}
	}
?>
