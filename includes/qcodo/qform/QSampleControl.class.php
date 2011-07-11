<?php
	/**
	 * This is a SAMPLE of a custom QControl that you could write.  Think of this as a "starting point".
	 * Remember: EVERYTHING here is meant to be customized!  To use, simply make a copy of this file,
	 * rename the file, and edit the renamed file.  Remember to specify a control Class name which matches the
	 * name of your file.  And then implement your own logic for GetControlHtml().
	 * 
	 * Additionally, you can add customizable logic for any or all of the following, as well:
	 *  - __construct()
	 *  - ParsePostData()
	 *  - Validate()
	 *  - GetEndScript()
	 *  - GetEndHtml()
	 */
	class QSampleControl extends QControl {
		protected $intExample;
		protected $strFoo;

		/**
		 * If this control needs to update itself from the $_POST data, the logic to do so
		 * will be performed in this method.
		 */
		public function ParsePostData() {}

		/**
		 * If this control has validation rules, the logic to do so
		 * will be performed in this method.
		 * @return boolean
		 */
		public function Validate() {return true;}

		/**
		 * Get the HTML for this Control.
		 * @return string
		 */
		public function GetControlHtml() {
			// Pull any Attributes
			$strAttributes = $this->GetAttributes();

			// Pull any styles
			if ($strStyle = $this->GetStyleAttributes())
				$strStyle = 'style="' . $strStyle . '"';

			// Return the HTML.
			return sprintf('<span id="%s" %s%s>Sample Control: %s - %s</span>',
				$this->strControlId, $strAttributes, $strStyle, $this->intExample, $this->strFoo);
		}

		/**
		 * Constructor for this control
		 * @param mixed $objParentObject Parent QForm or QControl that is responsible for rendering this control
		 * @param string $strControlId optional control ID
		 */
		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }

			// Setup Control-specific CSS and JS files to be loaded
			// Paths are relative to the __CSS_ASSETS__ and __JS_ASSETS__ directories
			// Multiple files can be specified, as well, by separating with a comma
//			$this->strJavaScripts = 'custom.js, ../path/to/prototype.js, etc.js';
//			$this->strStyleSheets = 'custom.css';

			// Additional Setup Performed here
			$this->intExample = 28;
			$this->strFoo = 'Hello!';
		}

		// For any JavaScript calls that need to be made whenever this control is rendered or re-rendered
//		public function GetEndScript() {
//			$strToReturn = parent::GetEndScript();
//			return $strToReturn;
//		}

		// For any HTML code that needs to be rendered at the END of the QForm when this control is INITIALLY rendered.
//		public function GetEndHtml() {
//			$strToReturn = parent::GetEndHtml();
//			return $strToReturn;
//		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case 'Example': return $this->intExample;
				case 'Foo': return $this->strFoo;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {

				case 'Example': 
					try {
						return ($this->intExample = QType::Cast($mixValue, QType::Integer));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
				case 'Foo': 
					try {
						return ($this->strFoo = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) { $objExc->IncrementOffset(); throw $objExc; }
			}
		}
	}
?>