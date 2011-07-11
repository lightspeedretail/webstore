<?php
	// TODO: Update the SERIOUSLY OUT OF DATE documentation for Forms and Controls!!!
	// ALL CONTROLS eventually inheret from this abstract Control class
	// All controls must implement the following four abstract functions:
	//	string Render()
	//	string GetJavaScriptAction()
	//	void ParsePostData()
	//	bool Validate()

	// Please see comments below, by each method, for more information about those methods
	
	// Control has many properties.  Please note that not every control will utilize every single one of these properties.
	//
	// Appearance properties dictate how the control should appear (e.g. font, color, borders, etc.)
	//
	// Behavior properties:
	// * "AccessKey" allows you to specify what Alt-Letter combination will automatically focus that control on the form
	// * "CausesValidation" flag says whether or not the form should run through its validation routine if this control
	//   has a ServerAction defined and is acted upon
	// * "Enabled" specifies whether or not this is enabled (it will grey out the control and make it inoperable if set to true)
	// * "Required" specifies whether or not this is required (will cause a validation error if the form is trying to
	//   be validated and this control is left blank)
	// * "TabIndex" specifies the index/tab order on a form
	// * "ToolTip" specifies the text to be displayed when the mouse is hovering over the control
	// * "ValidationError" (readonly) is the string that contains the validation error (if applicable) or will be blank if
	//   (1) the form did not undergo its validation routine or (2) this control had no error
	// * "Visible" specifies whether or not the control should be rendered.  If "Visible" is false, calling Form::RenderControl
	//   on this object will end up displaying nothing.  (Keep in mind that the control's "Render" method doesn't display
	//   anything, it simply returns the HTML as a string which can then be printf'd... therefore, the control's render method
	//   will still return the string of the html of the control even if Visible is set to false.
	//
	// Keep in mind that Controls that are not Enabled or not Visible will not go through the form's Validation routine.
	//
	// Layout properties:
	// * "Height" is the height of the control.  Left as a string so that you can specify as "15" or things like "15px"
	//	 "15em", "15pt", etc.
	// * "Width" is the width of the control.
	//
	// The following Layout properties are used if the control is rendered "With Name" (e.g. Control::RenderWithName)
	// See RenderWithName() for more information
	// * "HtmlBefore" is HTML that is shown before the control, itself
	// * "HtmlAfter" is HTML that is shown after the control, itself
	// * "Instructions" is instructions that is shown next to the control's name label
	// * "Warning" is warning text (looks like an error, but it can be user defined) that will be shown next to the control's
	//   name label
	//
	// Misc Properties:
	// * "Id" (readonly) is the Id of the control.  So $txtMyTextbox = new TextBox("txtMyTextbox") specifies that this Textbox
	//   control's Id is "txtMyTextbox".  Please note that the Id in quotes MUST be the same as the object's variable name.
	// * "FormId" (readonly) is the string of the form's id.
	// * "Name" will display as the Control's name label when called by Control::RenderWithName
	// * "Rendered" controlled by the Form to specify whether or not a specific control has been rendered on the page
	//   This is to ensure that no single control is rendered twice on the same form.  (Bad bad bad things would happen
	//   if the exact same control is rendered twice on the same form)
	// * "ServerAction" is either TRUE *OR* the php function name that will be called if this control is "acted" upon.
	//   To figure out how to 'act' upon a control, refer to the control's GetJavaScriptAction, which specifies whether
	//   a control's action is 'onclick', 'onchange', etc.  If ServerAction is TRUE, it simply means that acting on that control
	//   will casue the Form to PostBack.  If ServerAction is a specific php function name, that function will be called
	//   during the postback process.  (See comments for "Form::RenderBegin()" at the top of Form.inc for more information)
	//   Three parameters are always passed in to the php function that will be called:
	//     - string strFormId (FormId of hte form in question)
	//     - string strContorlId (ControLId of the control being acted upon)
	//     - string strParameterId (optional, user-specified parameters that contain additional information)
	//   Please note that within any php function, you do not immediately have access to the global variables, including
	//   the controls and the forms, themselves.  For that reason, eval(Form::EventHandler) should always be called at the
	//   top of every PHP Function which is a ServerAction in order to give you access to those global variables.
	// * "ClientAction" is any client javascript that will be executed if this control is "acted" upon.
	//
	// If both a ClientAction **AND** ServerAction is defined on a control, the control will first execute the ClientAction.
	// At any time if javascript in the clientaction does a "return false", the serveraction will NOT be executed.  This
	// is useful for things like:
	//		$btnDelete = new Button("btnDelete");
	//		$frmForm = $frmForm->AddControl($btnDelete);
	//		$btnDelete->ServerAction = "PerformDelete";
	//		$btnDelete->ClientAction = "return confirm('Are you SURE you want to DELETE this item?')";
	// This will cause the javascript pop up to first allow the user to confirm that s/he wants to delete.  If "Ok" is pressed,
	// the form is posted-back, validation routine executed (if CausesValidation is true), and ServerAction will then kickoff.
	// If "Cancel" is pressed, nothing will happen (the form will NOT post-back, no other actions will be executed).

	abstract class QControlBase extends QBaseClass {
		///////////////////////////
		// Private Member Variables
		///////////////////////////

		// APPEARANCE
		protected $strBackColor = null;
		protected $strBorderColor = null;
		protected $strBorderStyle = QBorderStyle::NotSet;
		protected $strBorderWidth = null;
		protected $strCssClass = null;
		protected $blnDisplay = true;
		protected $strDisplayStyle = QDisplayStyle::NotSet;
		protected $blnFontBold = false;
		protected $blnFontItalic = false;
		protected $strFontNames = null;
		protected $blnFontOverline = false;
		protected $strFontSize = null;
		protected $blnFontStrikeout = false;
		protected $blnFontUnderline = false;
		protected $strForeColor = null;
		protected $intOpacity = null;

		// BEHAVIOR
		protected $strAccessKey = null;
		protected $mixCausesValidation = false;
		protected $strCursor = QCursor::NotSet;
		protected $blnEnabled = true;
		protected $blnRequired = false;
		protected $intTabIndex = 0;
		protected $strToolTip = null;
		protected $strValidationError = null;
		protected $blnVisible = true;

		// LAYOUT
		protected $strHeight = null;
		protected $strWidth = null;

		protected $strHtmlBefore = null;
		protected $strHtmlAfter = null;
		protected $strInstructions = null;
		protected $strWarning = null;

		protected $strOverflow = QOverflow::NotSet;
		protected $strPosition = QPosition::NotSet;
		protected $strTop = null;
		protected $strLeft = null;

		protected $blnMoveable = false;

		// MISC	
		protected $strControlId;
		protected $objForm = null;
		protected $objParentControl = null;
		protected $objChildControlArray = array();
		protected $strName = null;
		protected $blnRendered = false;
		protected $blnRendering = false;
		protected $blnOnPage = false;
		protected $blnModified = false;
		protected $blnWrapperModified = false;
		protected $strRenderMethod;
		protected $strCustomAttributeArray = null;
		protected $strCustomStyleArray = null;
		protected $objActionArray = array();
		protected $strActionParameter = null;

		// SETTINGS
		protected $strJavaScripts = null;
		protected $strStyleSheets = null;
		protected $strFormAttributes = null;
		protected $blnActionsMustTerminate = false;
		protected $blnIsBlockElement = false;

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			if ($objParentObject instanceof QForm)
				$this->objForm = $objParentObject;
			else if ($objParentObject instanceof QControl) {
				$this->objParentControl = $objParentObject;
//				$this->objParentControl->blnModified = true;
				$this->objForm = $objParentObject->Form;
			} else
				throw new QCallerException('ParentObject must be either a QForm or QControl object');

			if (strlen($strControlId) == 0)
				$this->strControlId = $this->objForm->GenerateControlId();
			else {
				// Verify ControlId is only AlphaNumeric Characters
				$strMatches = array();
				$strPattern = '/[A-Za-z0-9]*/';
				preg_match($strPattern, $strControlId, $strMatches);
				if (count($strMatches) && ($strMatches[0] == $strControlId))
					$this->strControlId = $strControlId;
				else
					throw new QCallerException('ControlIDs must be only alphanumeric chacters: ' . $strControlId);
			}
			try {
				$this->objForm->AddControl($this);
				if ($this->objParentControl)
					$this->objParentControl->AddChildControl($this);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public static function CreatePersistent($strClassName, $objParentObject, $strControlId) {
			if ($objParentObject instanceof QForm) {
				$objForm = $objParentObject;
				$objParentControl = null;
			} else if ($objParentObject instanceof QControl) {
				$objForm = $objParentObject->Form;
				$objParentControl = $objParentObject;
			} else
				throw new QCallerException('Parent Object must be a QForm or QControl');

			if (array_key_exists($objForm->FormId . '_' . $strControlId, $_SESSION) && $_SESSION[$objForm->FormId . '_' . $strControlId]) {
				$objToReturn = unserialize($_SESSION[$objForm->FormId . '_' . $strControlId]);
				$objToReturn->objParentControl = $objParentControl;
				$objToReturn->objForm = $objForm;
				try {
					$objToReturn->objForm->AddControl($objToReturn);
					if ($objToReturn->objParentControl)
						$objToReturn->objParentControl->AddChildControl($objToReturn);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
			} else {
				$objToReturn = new $strClassName($objParentObject, $strControlId);
			}

			$objForm->PersistControl($objToReturn);
			return $objToReturn;
		}

		protected function PersistPrepare() {
			$this->objForm = null;
			$this->objParentControl = null;
			$this->objActionArray = array();
			$this->objChildControlArray = array();
			$this->blnRendered = null;
			$this->blnRendering = null;
			$this->blnOnPage = null;
			$this->blnModified = null;
			$this->mixCausesValidation = null;
		}
		public function Persist() {
			$objControl = clone($this);
			$objControl->PersistPrepare();
			$_SESSION[$this->objForm->FormId . '_' . $this->strControlId] = serialize($objControl);
		}

		public function AddChildControl(QControl $objControl) {
			$this->blnModified = true;
			$this->objChildControlArray[$objControl->ControlId] = $objControl;
			$objControl->objParentControl = $this;
		}

		public function GetChildControls($blnUseNumericIndexes = true) {
			if ($blnUseNumericIndexes) {
				$objToReturn = array();
				foreach ($this->objChildControlArray as $objChildControl)
					array_push($objToReturn, $objChildControl);
				return $objToReturn;
			} else
				return $this->objChildControlArray;
		}

		public function GetChildControl($strControlId) {
			if (array_key_exists($strControlId, $this->objChildControlArray))
				return $this->objChildControlArray[$strControlId];
			else
				return null;
		}

		public function RemoveChildControls($blnRemoveFromForm) {
			foreach ($this->objChildControlArray as $objChildControl) {
				$this->RemoveChildControl($objChildControl->ControlId, $blnRemoveFromForm);
			}
		}

		public function RemoveChildControl($strControlId, $blnRemoveFromForm) {
			$this->blnModified = true;
			if (array_key_exists($strControlId, $this->objChildControlArray)) {
				$objChildControl = $this->objChildControlArray[$strControlId];
				$objChildControl->objParentControl = null;
				unset($this->objChildControlArray[$strControlId]);

				if ($blnRemoveFromForm)
					$this->objForm->RemoveControl($objChildControl->ControlId);
			}
		}

		public function AddAction($objEvent, $objAction) {
			if (!($objEvent instanceof QEvent)) {
				throw new QCallerException('First parameter of AddAction is expecting an object of type QEvent');
			}

			if (!($objAction instanceof QAction)) {
				throw new QCallerException('Second parameter of AddAction is expecting an object of type QAction');
			}
			
			// Modified
			$this->blnModified = true;
			
			// Store the Event object in the Action object
			$objAction->Event = $objEvent;

			// Pull out the Event Name
			$strEventName = $objEvent->JavaScriptEvent;

			if (!array_key_exists($strEventName, $this->objActionArray))
				$this->objActionArray[$strEventName] = array();
			array_push($this->objActionArray[$strEventName], $objAction);
		}

		public function AddActionArray($objEvent, $objActionArray) {
			if (!($objEvent instanceof QEvent)) {
				throw new QCallerException('First parameter of AddAction is expecting on object of type QEvent');
			}

			foreach ($objActionArray as $objAction) {
				$objAction = clone($objAction);
				$this->AddAction($objEvent, $objAction);
			}
		}

		/**
		 * Removes all events for a given event name.
		 * Be sure and use a QFooEvent::EventName constant here.
		 *
		 * @param string $strEventName
		 */
		public function RemoveAllActions($strEventName) {
			// Modified
			$this->blnModified = true;

			$this->objActionArray[$strEventName] = array();
		}

		public function GetAllActions($strEventType, $strActionType = null) {
			$objArrayToReturn = array();
			if ($this->objActionArray) foreach ($this->objActionArray as $objActionArray) {
				foreach ($objActionArray as $objAction)
					if (get_class($objAction->Event) == $strEventType) {
//					if ($objAction->Event instanceof $strEventType) {
						if ((!$strActionType) ||
							($objAction instanceof $strActionType))
							array_push($objArrayToReturn, $objAction);
					}
			}
			
			return $objArrayToReturn;
/*				return array();
			if (!array_key_exists($strEvent, $this->objActionArray) || (count($this->objActionArray[$strEvent]) == 0))
				return null;

			if ($strActionType) {
				$objToReturn = array();
				if ($this->objActionArray[$strEvent]) foreach ($this->objActionArray[$strEvent] as $objAction) {
					if ($objAction instanceof $strActionType)
						array_push($objToReturn, $objAction);
				}

				return $objToReturn;
			} else {
				return $this->objActionArray[$strEvent];
			}*/
		}

		// Custom Attributes are other html name-value pairs that can be rendered within the control.
		// For example, on a textbox, you can render any number of additional name-value pairs, to assign
		// additional javascript actions, additional formatting, etc.
		//		$txtTextbox = new Textbox("txtTextbox");
		//		$txtTextbox->SetCustomAttribute("onfocus", "alert('You are about to edit this field')");
		//		$txtTextbox->SetCustomAttribute("nowrap", "nowrap");
		//		$txtTextbox->SetCustomAttribute("blah", "foo");
		// Will render:
		//		<input type="text" ...... onfocus="alert('You are about to edit this field')" nowrap="nowrap" blah="foo" />
		public function SetCustomAttribute($strName, $strValue) {
			$this->blnModified = true;
			if (!is_null($strValue))
				$this->strCustomAttributeArray[$strName] = $strValue;
			else {
				$this->strCustomAttributeArray[$strName] = null;
				unset($this->strCustomAttributeArray[$strName]);
			}
		}
		
		public function GetCustomAttribute($strName) {
			if ((is_array($this->strCustomAttributeArray)) && (array_key_exists($strName, $this->strCustomAttributeArray)))
				return $this->strCustomAttributeArray[$strName];
			else
				throw new QCallerException(sprintf("Custom Attribute does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		public function RemoveCustomAttribute($strName) {
			$this->blnModified = true;
			if ((is_array($this->strCustomAttributeArray)) && (array_key_exists($strName, $this->strCustomAttributeArray))) {
				$this->strCustomAttributeArray[$strName] = null;
				unset($this->strCustomAttributeArray[$strName]);
			} else
				throw new QCallerException(sprintf("Custom Attribute does not exist in Control '%s': %s", $this->strControlId, $strName));
		}



		public function SetCustomStyle($strName, $strValue) {
			$this->blnModified = true;
			if (!is_null($strValue))
				$this->strCustomStyleArray[$strName] = $strValue;
			else {
				$this->strCustomStyleArray[$strName] = null;
				unset($this->strCustomStyleArray[$strName]);
			}
		}
		
		public function GetCustomStyle($strName) {
			if ((is_array($this->strCustomStyleArray)) && (array_key_exists($strName, $this->strCustomStyleArray)))
				return $this->strCustomStyleArray[$strName];
			else
				throw new QCallerException(sprintf("Custom Style does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		public function RemoveCustomStyle($strName) {
			$this->blnModified = true;
			if ((is_array($this->strCustomStyleArray)) && (array_key_exists($strName, $this->strCustomStyleArray))) {
				$this->strCustomStyleArray[$strName] = null;
				unset($this->strCustomStyleArray[$strName]);
			} else
				throw new QCallerException(sprintf("Custom Style does not exist in Control '%s': %s", $this->strControlId, $strName));
		}

		/**
		 * This will add a CssClass name to the CssClass property (if it does not yet exist),
		 * updating the CssClass property accordingly.
		 * @param string $strCssClassName
		 */
		public function AddCssClass($strCssClassName) {
			$blnAdded = false;
			$strNewCssClass = '';
			$strCssClassName = trim($strCssClassName);

			foreach (explode(' ', $this->strCssClass) as $strCssClass)
				if ($strCssClass = trim($strCssClass)) {
					if ($strCssClass == $strCssClassName)
						$blnAdded = true;
					$strNewCssClass .= $strCssClass . ' ';
				}
			if (!$blnAdded)
				$this->CssClass = $strNewCssClass . $strCssClassName;
			else
				$this->CssClass = trim($strNewCssClass);
		}

		/**
		 * This will remove a CssClass name from the CssClass property (if it exists),
		 * updating the CssClass property accordingly.
		 * @param string $strCssClassName
		 */
		public function RemoveCssClass($strCssClassName) {
			$strNewCssClass = '';
			$strCssClassName = trim($strCssClassName);
			foreach (explode(' ', $this->strCssClass) as $strCssClass)
				if ($strCssClass = trim($strCssClass)) {
					if ($strCssClass != $strCssClassName)
						$strNewCssClass .= $strCssClass . ' ';
				}
			$this->CssClass = trim($strNewCssClass);
		}

		// This abstract method must be implemented by all controls.
		//
		// When utilizing formgen, the programmer should never access form variables directly (e.g. via the $_FORM array).
		// It can be assumed that at *ANY* given time, a control's values/properties will be "up to date" with whatever the
		// webuser has entered in.
		// 
		// When a Form is Created via Form::Create(string), the form will go through to check and see if it is a first-run
		// of a form, or if it is a post-back.  If it is a postback, it will go through its own private array of controls
		// and call ParsePostData on EVERY control it has.  Each control is responsible for "knowing" how to parse the 
		// $_POST data to update its own values/properties based on what was returned to via the postback.
		abstract public function ParsePostData();

		// This is utilized by Render methods to display various name-value HTML attributes for the control
		// Control's implementation contains the very-basic set of HTML attributes... it is expected
		// that most subclasses will extend this method's functionality to add Control-specific HTML attributes
		// (e.g. textbox will likely add the maxlength html attribute, etc.)
		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = "";

			if (!$this->blnEnabled)
				$strToReturn .= 'disabled="disabled" ';
			if ($this->intTabIndex)
				$strToReturn .= sprintf('tabindex="%s" ', $this->intTabIndex);
			if ($this->strToolTip)
				$strToReturn .= sprintf('title="%s" ', QApplication::HtmlEntities($this->strToolTip));
			if ($this->strCssClass)
				$strToReturn .= sprintf('class="%s" ', $this->strCssClass);
			if ($this->strAccessKey)
				$strToReturn .= sprintf('accesskey="%s" ', $this->strAccessKey);

			if ($blnIncludeCustom)
				$strToReturn .= $this->GetCustomAttributes();

			if ($blnIncludeAction)
				$strToReturn .= $this->GetActionAttributes();

			return $strToReturn;
		}
		
		public function GetCustomAttributes() {
			$strToReturn = '';
			if ($this->strCustomAttributeArray)
				foreach ($this->strCustomAttributeArray as $strKey => $strValue) {
					$strToReturn .= sprintf('%s="%s" ', $strKey, $strValue);
				}

			return $strToReturn;
		}

		public function GetActionAttributes() {
			$strToReturn = '';
			foreach ($this->objActionArray as $strEventName => $objActions)
				$strToReturn .= $this->GetJavaScriptForEvent($strEventName);
			return $strToReturn;
		}

		public function GetJavaScriptForEvent($strEventName) {
			return QAction::RenderActions($this, $strEventName, $this->objActionArray[$strEventName]);
		}

		// Similar to GetAttributes, but specifically for CSS name/value pairs that will render within
		// a control's HTML "style" attribute
		public function GetStyleAttributes() {
			$strToReturn = "";

			if ($this->strWidth)
				if (is_numeric($this->strWidth))
					$strToReturn .= sprintf("width:%spx;", $this->strWidth);
				else
					$strToReturn .= sprintf("width:%s;", $this->strWidth);
			if ($this->strHeight)
				if (is_numeric($this->strHeight))
					$strToReturn .= sprintf("height:%spx;", $this->strHeight);
				else
					$strToReturn .= sprintf("height:%s;", $this->strHeight);
			
			if (($this->strDisplayStyle) && ($this->strDisplayStyle != QDisplayStyle::NotSet))
				$strToReturn .= sprintf("display:%s;", $this->strDisplayStyle);
			
			if ($this->strForeColor)
				$strToReturn .= sprintf("color:%s;", $this->strForeColor);
			if ($this->strBackColor)
				$strToReturn .= sprintf("background-color:%s;", $this->strBackColor);
			if ($this->strBorderColor)
				$strToReturn .= sprintf("border-color:%s;", $this->strBorderColor);
			if (strlen(trim($this->strBorderWidth)) > 0) {
				$strBorderWidth = null;
				try {
					$strBorderWidth = QType::Cast($this->strBorderWidth, QType::Integer);
				} catch (QInvalidCastException $objExc) {}

				if (is_null($strBorderWidth))
					$strToReturn .= sprintf('border-width:%s;', $this->strBorderWidth);
				else
					$strToReturn .= sprintf('border-width:%spx;', $this->strBorderWidth);

				if ((!$this->strBorderStyle) || ($this->strBorderStyle == QBorderStyle::NotSet))
					// For "No Border Style" -- apply a "solid" style because width is set
						$strToReturn .= "border-style:solid;";
			}
			if (($this->strBorderStyle) && ($this->strBorderStyle != QBorderStyle::NotSet))
				$strToReturn .= sprintf("border-style:%s;", $this->strBorderStyle);

			if ($this->strFontNames)
				$strToReturn .= sprintf("font-family:%s;", $this->strFontNames);
			if ($this->strFontSize) {
				if (is_numeric($this->strFontSize))
					$strToReturn .= sprintf("font-size:%spx;", $this->strFontSize);
				else
					$strToReturn .= sprintf("font-size:%s;", $this->strFontSize);
			}
			if ($this->blnFontBold)
				$strToReturn .= "font-weight:bold;";
			if ($this->blnFontItalic)
				$strToReturn .= "font-style:italic;";
			
			$strTextDecoration = "";
			if ($this->blnFontUnderline)
				$strTextDecoration .= "underline ";
			if ($this->blnFontOverline)
				$strTextDecoration .= "overline ";
			if ($this->blnFontStrikeout)
				$strTextDecoration .= "line-through ";
			
			if ($strTextDecoration) {
				$strTextDecoration = trim($strTextDecoration);
				$strToReturn .= sprintf("text-decoration:%s;", $strTextDecoration);
			}

			if (($this->strCursor) && ($this->strCursor != QCursor::NotSet))
				$strToReturn .= sprintf("cursor:%s;", $this->strCursor);

			if (($this->strOverflow) && ($this->strOverflow != QOverflow::NotSet))
				$strToReturn .= sprintf("overflow:%s;", $this->strOverflow);

			if (!is_null($this->intOpacity))
				if (QApplication::IsBrowser(QBrowserType::InternetExplorer))
					$strToReturn .= sprintf('filter:alpha(opacity=%s);', $this->intOpacity);
				else
					$strToReturn .= sprintf('opacity:%s;', $this->intOpacity / 100.0);

			if ($this->strCustomStyleArray) foreach ($this->strCustomStyleArray as $strKey => $strValue)
				$strToReturn .= sprintf('%s:%s;', $strKey, $strValue);

			return $strToReturn;
		}


		// The Render, RenderWithName, and RenderWithError functions should call
		// this renderhelper FIRST in order to check for and perform attribute overrides (if any)
		// 
		// All render methods should take in an optional first boolean parameter
		// blnDisplayOutput (default to true), and then any number of attribute overrides.
		
		// Any "Render" method (e.g. Render, RenderWithName, RenderWithError) should
		// call the RenderHelper FIRST in order to:
		// * Check for and perform attribute overrides
		// * Check to see if this control is "Visible".  If it is Visible=false, then
		//   the renderhelper will cause the method to immedaitely return
		// Proper usage within the first line of the REnder() method is:
		// 		if ($this->RenderHelper(func_get_args())) return;
		protected function RenderHelper($mixParameterArray, $strRenderMethod) {
			// Make sure the form is already "RenderBegun"
			if ((!$this->objForm) || ($this->objForm->FormStatus != QForm::FormStatusRenderBegun)) {
				if (!$this->objForm)
					$objExc = new QCallerException('Control\'s form does not exist.  It could be that you are attempting to render after RenderEnd() has been called on the form.');
				else if ($this->objForm->FormStatus == QForm::FormStatusRenderEnded)
					$objExc = new QCallerException('Control cannot be rendered after RenderEnd() has been called on the form.');
				else
					$objExc = new QCallerException('Control cannot be rendered until RenderBegin() has been called on the form.');
				
				// Incremement because we are two-deep below the call stack
				// (e.g. the Render function call, and then this RenderHelper call)
				$objExc->IncrementOffset();
				throw $objExc;
			}
			
			// Make sure this hasn't yet been rendered
			if (($this->blnRendered) || ($this->blnRendering)) {
				$objExc = new QCallerException('This control has already been rendered: ' . $this->strControlId);

				// Incremement because we are two-deep below the call stack
				// (e.g. the Render function call, and then this RenderHelper call)
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Let's remember *which* render method was used to render this control
			$this->strRenderMethod = $strRenderMethod;

			// Apply any overrides (if applicable)
			if (count($mixParameterArray) > 0) {
				if (gettype($mixParameterArray[0]) != QType::String) {
					// Pop the first item off the array
					$mixParameterArray = array_reverse($mixParameterArray);
					array_pop($mixParameterArray);
					$mixParameterArray = array_reverse($mixParameterArray);
				}

				// Override
				try {
					$this->OverrideAttributes($mixParameterArray);
				} catch (QCallerException $objExc) {
					// Incremement Twice because we are two-deep below the call stack
					// (e.g. the Render function call, and then this RenderHelper call)
					$objExc->IncrementOffset();
					$objExc->IncrementOffset();
					throw $objExc;
				}
			}

			// Because we may be re-rendering a parent control, we need to make sure all "children" controls are marked as NOT being on the page.
			foreach ($this->GetChildControls() as $objChildControl)
				$objChildControl->blnOnPage = false;

			// Finally, let's specify that we have begun rendering this control
			$this->blnRendering = true;
		}

		protected function GetNonWrappedHtml() {}

		public function Focus() {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").focus();', $this->strControlId));
		}

		public function Blink($strFromColor = '#ffff66', $strToColor = '#ffffff') {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").blink("%s", "%s");', $this->strControlId, $strFromColor, $strToColor));
		}

		public function GetEndScript() {
			if ($this->blnMoveable)
//				return sprintf('qcodo.registerControlMoveable("%s"); ', $this->strControlId);
				return sprintf('qc.regCM("%s"); ', $this->strControlId);
			else
				return null;
		}

		public function GetEndHtml() {}
/*		public function GetEndHtml() {
			if ($this->blnMoveable)
				return sprintf('<span id="%s_ctlmask" style="position:absolute;"></span>', $this->strControlId);
			else
				return null;
		}*/

		/**
		 * If not yet rendered during this server/ajax event, will force the control to redraw/refresh
		 * Otherwise, this will do nothing
		 */
		public function Refresh() {
			if ((!$this->blnRendered) && (!$this->blnRendering))
				$this->blnModified = true;
		}

		protected function RenderOutput($strOutput, $blnDisplayOutput, $blnForceAsBlockElement = false) {
			// First, let's mark this control as being rendered and is ON the Page
			$this->blnRendering = false;
			$this->blnRendered = true;
			$this->blnOnPage = true;

			// Determine whether or not $strOutput is considered a XHTML "Block" Element
			if (($blnForceAsBlockElement) || ($this->blnIsBlockElement))
				$blnIsBlockElement = true;
			else
				$blnIsBlockElement = false;

			// Check for Visibility
			if (!$this->blnVisible)
				$strOutput = '';

			$strStyle = '';
			if (($this->strPosition) && ($this->strPosition != QPosition::NotSet))
				$strStyle .= sprintf('position:%s;', $this->strPosition);

			if (!$this->blnDisplay)
				$strStyle .= 'display:none;';
			else if ($blnIsBlockElement)
				$strStyle .= 'display:inline;';

			if (strlen(trim($this->strLeft)) > 0) {
				$strLeft = null;
				try {
					$strLeft = QType::Cast($this->strLeft, QType::Integer);
				} catch (QInvalidCastException $objExc) {}

				if (is_null($strLeft))
					$strStyle .= sprintf('left:%s;', $this->strLeft);
				else
					$strStyle .= sprintf('left:%spx;', $this->strLeft);
			}

			if (strlen(trim($this->strTop)) > 0) {
				$strTop = null;
				try {
					$strTop = QType::Cast($this->strTop, QType::Integer);
				} catch (QInvalidCastException $objExc) {}

				if (is_null($strTop))
					$strStyle .= sprintf('top:%s;', $this->strTop);
				else
					$strStyle .= sprintf('top:%spx;', $this->strTop);
			}

			switch ($this->objForm->CallType) {
				case QCallType::Ajax:
					// If we have a ParentControl and the ParentControl has NOT been rendered, then output
					// as standard HTML
					if (($this->objParentControl) && ($this->objParentControl->Rendered || $this->objParentControl->Rendering)) {
						if ($strStyle)
							$strStyle = sprintf('style="%s"', $strStyle);

						if ($blnIsBlockElement)
							$strOutput = sprintf('<div id="%s_ctl" %s>%s</div>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
						else
							$strOutput = sprintf('<span id="%s_ctl" %s>%s</span>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
//						$strOutput = sprintf('<ins id="%s_ctl" style="%stext-decoration:inherit;">%s</ins>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
//						$strOutput = sprintf('<q id="%s_ctl" style="%s">%s</q>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
					} else {
						// Otherwise, we are rendering as a top-level AJAX response
						// Surround Output HTML around CDATA tags
						$strOutput = QString::XmlEscape($strOutput);
						$strOutput = sprintf('<control id="%s">%s</control>', $this->strControlId, $strOutput);


//					QApplication::ExecuteJavaScript(sprintf('qcodo.registerControl("%s"); ', $this->strControlId), true);
//					QApplication::ExecuteJavaScript(sprintf('qc.regC("%s"); ', $this->strControlId), true);

//					$strScript = $this->GetEndScript();
//					if ($strScript)
//						QApplication::ExecuteJavaScript($strScript);
						
						if (($this->blnWrapperModified) && ($this->blnVisible))
//							QApplication::ExecuteJavaScript(sprintf('qcodo.getWrapper("%s").style.cssText = "%s"; ', $this->strControlId, $strStyle));
							QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").style.cssText = "%stext-decoration:inherit;"; ', $this->strControlId, $strStyle));
					}
					break;

				default:
					if ($strStyle)
						$strStyle = sprintf('style="%s"', $strStyle);

//					$strOutput = sprintf('<div id="%s_ctl" style="%s">%s</div>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
//					$strOutput = sprintf('<ins id="%s_ctl" style="%stext-decoration:inherit;">%s</ins>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
					if ($blnIsBlockElement)
						$strOutput = sprintf('<div id="%s_ctl" %s>%s</div>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
					else
						$strOutput = sprintf('<span id="%s_ctl" %s>%s</span>%s', $this->strControlId, $strStyle, $strOutput, $this->GetNonWrappedHtml());
					break;
			}

			// Output or Return
			if ($blnDisplayOutput)
				print($strOutput);
			else
				return $strOutput;
		}

		// This is usually a one-word containing the HTML attribute name that defines how a specific
		// control should be "acted" upon.  This typically would be something like "onclick" or "onchange".
//		abstract public function GetJavaScriptAction();

		// This method will render the control, itself, and will return the rendered HTML as a string
		abstract protected function GetControlHtml();

		// This method will perform attribute overriding (if any),
		// And it will either display the rendered HTML (if blnDisplayOutput is true, which
		// it is by default), or it will return the rendered HTML as a string.
		public function Render($blnDisplayOutput = true) {
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);
			
			try {
				$strOutput = $this->GetControlHtml();
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strOutput, $blnDisplayOutput);
		}

		public function RenderAjax($blnDisplayOutput = true) {
			// Only render if this control has been modified at all
			if ($this->blnModified) {

				// Render if (1) object has no parent or (2) parent was not rendered nor currently being rendered
				if ((!$this->objParentControl) || ((!$this->objParentControl->Rendered) && (!$this->objParentControl->Rendering))) {
					$strRenderMethod = $this->strRenderMethod;
					if ($strRenderMethod)
						return $this->$strRenderMethod($blnDisplayOutput);
				}
			}
		}

		protected function RenderChildren($blnDisplayOutput = true) {
			$strToReturn = "";

			foreach ($this->GetChildControls() as $objControl)
				if (!$objControl->Rendered)
					$strToReturn .= $objControl->Render($blnDisplayOutput);

			if ($blnDisplayOutput) {
				print($strToReturn);
				return null;
			} else
				return $strToReturn;
		}

		public function SetFocus() {
			QApplication::ExecuteJavaScript(sprintf('qc.getW("%s").focus()', $this->strControlId));
		}

		public function RenderWithError($blnDisplayOutput = true) {
			// Call RenderHelper
			$this->RenderHelper(func_get_args(), __FUNCTION__);

			try {
				$strOutput = $this->GetControlHtml();

				if ($this->strValidationError)
					$strOutput .= sprintf('<br /><span class="warning">%s</span>', $this->strValidationError);
				else if ($this->strWarning)
					$strOutput .= sprintf('<br /><span class="warning">%s</span>', $this->strWarning);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Call RenderOutput, Returning its Contents
			return $this->RenderOutput($strOutput, $blnDisplayOutput);
		}


		// This method defines how a control should validate itself based on the value/properties it has
		// It should also include the handling of ensuring the "Required" requirements are obeyed if this control's
		// "Required" flag is set to true.
		//
		// For Controls that can't realistically be "validated" (e.g. labels, datagrids, etc.), those controls should simply
		// have Validate() return true.
		abstract public function Validate();
		

		
		// The following three methods are only intended to be called by code within the Form class.
		// It must be declared as public so that a form object can have access ot them, but it really should never be
		// called by user code.
		public function ResetFlags() {
			$this->blnRendered = false;
			$this->blnModified = false;
			$this->blnWrapperModified = false;
		}
		
		public function ResetOnPageStatus() {
			$this->blnOnPage = false;
		}

		public function MarkAsModified() {
			$this->blnModified = true;
		}

		public function MarkAsWrapperModified() {
			$this->blnWrapperModified = true;
		}
		
		public function MarkAsRendered() {
			$this->blnRendered = true;
		}

		public function SetForm($objForm) {
			$this->objForm = $objForm;
		}

		public function SetParentControl($objControl) {
			// Mark this object as modified
			$this->MarkAsModified();

			// Mark the old parent (if applicable) as modified
			if ($this->objParentControl)
				$this->objParentControl->RemoveChildControl($this->ControlId, false);

			// Mark the new parent (if applicable) as modified
			if ($objControl)
				$objControl->AddChildControl($this);
		}

		public function ValidationReset() {
			if (($this->strValidationError) || ($this->strWarning))
				$this->blnModified = true;
			$this->strValidationError = null;
			$this->strWarning = null;
		}

		public function VarExport($blnReturn = true) {
			if ($this->objForm)
				$this->objForm = $this->objForm->FormId;
			if ($this->objParentControl)
				$this->objParentControl = $this->objParentControl->ControlId;
			if ($blnReturn)
				return var_export($this, true);
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "BackColor": return $this->strBackColor;
				case "BorderColor": return $this->strBorderColor;
				case "BorderStyle": return $this->strBorderStyle;
				case "BorderWidth": return $this->strBorderWidth;
				case "CssClass": return $this->strCssClass;
				case "Display": return $this->blnDisplay;
				case "DisplayStyle": return $this->strDisplayStyle;
				case "FontBold": return $this->blnFontBold;
				case "FontItalic": return $this->blnFontItalic;
				case "FontNames": return $this->strFontNames;
				case "FontOverline": return $this->blnFontOverline;
				case "FontSize": return $this->strFontSize;
				case "FontStrikeout": return $this->blnFontStrikeout;
				case "FontUnderline": return $this->blnFontUnderline;
				case "ForeColor": return $this->strForeColor;
				case "Opacity": return $this->intOpacity;

				// BEHAVIOR
				case "AccessKey": return $this->strAccessKey;
				case "CausesValidation": return $this->mixCausesValidation;
				case "Cursor": return $this->strCursor;
				case "Enabled": return $this->blnEnabled;
				case "Required": return $this->blnRequired;
				case "TabIndex": return $this->intTabIndex;
				case "ToolTip": return $this->strToolTip;
				case "ValidationError": return $this->strValidationError;
				case "Visible": return $this->blnVisible;
			
				// LAYOUT
				case "Height": return $this->strHeight;
				case "Width": return $this->strWidth;
				case "HtmlBefore": return $this->strHtmlBefore;
				case "HtmlAfter": return $this->strHtmlAfter;
				case "Instructions": return $this->strInstructions;
				case "Warning": return $this->strWarning;

				case "Overflow": return $this->strOverflow;
				case "Position": return $this->strPosition;
				case "Top": return $this->strTop;
				case "Left": return $this->strLeft;

				case "Moveable": return $this->blnMoveable;

				// MISC
				case "ControlId": return $this->strControlId;
				case "Form": return $this->objForm;
				case "ParentControl": return $this->objParentControl;

				case "Name": return $this->strName;
				case "Rendered": return $this->blnRendered;
				case "Rendering": return $this->blnRendering;
				case "OnPage": return $this->blnOnPage;
				case "RenderMethod": return $this->strRenderMethod;
				case "Modified": return $this->blnModified;
				case "WrapperModified": return $this->blnWrapperModified;
				case "ActionParameter": return $this->strActionParameter;
				case "ActionsMustTerminate": return $this->blnActionsMustTerminate;
				
				// SETTINGS
				case "JavaScripts": return $this->strJavaScripts;
				case "StyleSheets": return $this->strStyleSheets;
				case "FormAttributes": return (array) $this->strFormAttributes;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
		
		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				// APPEARANCE
				case "BackColor": 
					try {
						$this->strBackColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderColor":
					try {
						$this->strBorderColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderStyle":
					try {
						$this->strBorderStyle = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BorderWidth":
					try {
						$this->strBorderWidth = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CssClass":
					try {
						$this->strCssClass = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Display":
					try {
						$this->blnDisplay = QType::Cast($mixValue, QType::Boolean);
						$this->MarkAsWrapperModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DisplayStyle":
					try {
						$this->strDisplayStyle = QType::Cast($mixValue, QType::String);
						if (($this->strDisplayStyle == QDisplayStyle::Block) ||
							($this->strDisplayStyle == QDisplayStyle::Inline))
							$this->strDisplayStyle = $this->strDisplayStyle;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontBold":
					try {
						$this->blnFontBold = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontItalic":
					try {
						$this->blnFontItalic = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontNames":
					try {
						$this->strFontNames = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontOverline":
					try {
						$this->blnFontOverline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontSize":
					try {
						$this->strFontSize = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontStrikeout":
					try {
						$this->blnFontStrikeout = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontUnderline":
					try {
						$this->blnFontUnderline = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForeColor":
					try {
						$this->strForeColor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Opacity":
					try {
						$this->intOpacity = QType::Cast($mixValue, QType::Integer);
						if (($this->intOpacity < 0) || ($this->intOpacity > 100))
							throw new QCallerException('Opacity must be an integer value between 0 and 100');
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "AccessKey":
					try {
						$this->strAccessKey = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CausesValidation":
					try {
						$this->mixCausesValidation = $mixValue;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Cursor":
					try {
						$this->strCursor = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Enabled":
					try {
						$this->blnEnabled = QType::Cast($mixValue, QType::Boolean);
						$this->strValidationError = null;
						$this->strWarning = null;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Required":
					try {
						$this->blnRequired = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "TabIndex":
					try {
						$this->intTabIndex = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ToolTip":
					try {
						$this->strToolTip = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Visible":
					try {
						$this->blnVisible = QType::Cast($mixValue, QType::Boolean);
						$this->strValidationError = null;
						$this->strWarning = null;
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			
				// LAYOUT
				case "Height":
					try {
						$this->strHeight = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Width":
					try {
						$this->strWidth = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HtmlBefore":
					try {
						$this->strHtmlBefore = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "HtmlAfter":
					try {
						$this->strHtmlAfter = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Instructions":
					try {
						$this->strInstructions = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Warning":
					try {
						$this->strWarning = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Overflow":
					try {
						$this->strOverflow = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Position":
					try {
						$this->strPosition = QType::Cast($mixValue, QType::String);
						$this->MarkAsWrapperModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Top":
					try {
						$this->strTop = QType::Cast($mixValue, QType::String);
						$this->MarkAsWrapperModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Left":
					try {
						$this->strLeft = QType::Cast($mixValue, QType::String);
						$this->MarkAsWrapperModified();
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "Moveable":
					try {
						$this->blnMoveable = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// MISC
				case "Name":
					try {
						$this->strName = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ActionParameter":
					try {
						$this->strActionParameter = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
						break;
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}
	}
?>