<?php

	// This class will render an WYCIWYG area -- which can either be FCKeditor
	//  or <textarea> depending on the QFCKeditorTextMode (see below).
	// * "Text" is the contents of the textbox, itself
	// * "MaxLength" is the "maxlength" html attribute (applicable for SingleLine textboxes)
	// * "MinLength" is the minimum requred length to pass validation
	// * "TextMode" can be "SingleLine", "MultiLine", and "Password".
	// * "CrossScripting" can be Allow, HtmlEntities, or Deny.  Deny is the default.
	//   Prevents cross scripting hacks.  HtmlEntities causes framework to automatically call php
	//   function htmlentities on the input data.  Allow allows everything to come through without altering
	//   at all.  USE "ALLOW" judiciously: using ALLOW on text entries, and then outputting that data
	//   WILL allow hackers to perform cross scripting hacks.

	// Properties used only when plaintext (textarea):
	// * "ReadOnly" is the "readonly" html attribute (making a textbox "ReadOnly" is very similar to setting
	//   the textbox to Enabled=false.  There are only subtle display-differences, I believe, between the two.
	// * "Wrap" is the "wrap" html attribute (applicable for MultiLine textboxes)
	//

	// You may also change a number of the configurationoptions in the includes/FCKeditor/fckconfig.js from
	// this class.
	// Currently supported properties:
	// (Anyone feel free to add more documentation to these properties. You can find default values in includes/FCKeditor/fckconfig.js)
/*
		ToolbarSet						// Allows you to change the current toolbarset. (you can create new toolbarsets in fckconfig.js)
		CustomConfigurationsPath
		EditorAreaCSS					// Allows you to set an CSS-file for the editorarea.
		DocType							// Sets the doctype when doing FullPage-editing.
		BaseHref						// Whats the baseHref of your site?
		FullPage						// Do you want to edit a fullpage-html (with headers and bodytags)
		Debug							// debug the FCKeditor?
		AllowQueryStringDebug
		SkinPath						// The path of the skin you want to use?
		PluginsPath
		AutoDetectLanguage
		DefaultLanguage
		ContentLangDirection
		ProcessHTMLEntities
		IncludeLatinEntities
		IncludeGreekEntities
		FillEmptyBlocks
		FormatSource
		FormatOutput
		FormatIndentator
		ForceStrongEm
		GeckoUseSPAN
		StartupFocus
		ForcePasteAsPlainText
		AutoDetectPasteFromWord
		ForceSimpleAmpersand
		TabSpaces
		ShowBorders
		UseBROnCarriageReturn
		ToolbarStartExpanded
		ToolbarCanCollapse
		IEForceVScroll
		IgnoreEmptyParagraphValue
		PreserveSessionOnFileBrowser
		FloatingPanelsZIndex
		FontColors
		FontNames
		FontSizes
		FontFormats
		StylesXmlPath
		TemplatesXmlPath
		SpellChecker
		IeSpellDownloadUrl
		MaxUndoLevels
		DisableImageHandles
		DisableTableHandles
		LinkDlgHideTarget
		LinkDlgHideAdvanced
		LinkDlgHideAdvanced
		ImageDlgHideLink
		ImageDlgHideAdvanced
		FlashDlgHideAdvanced
		LinkBrowser
		LinkBrowserURL
		LinkBrowserWindowWidth
		LinkBrowserWindowHeight
		ImageBrowser
		ImageBrowserURL
		ImageBrowserWindowWidth
		ImageBrowserWindowHeight
		FlashBrowser
		FlashBrowserURL
		FlashBrowserWindowWidth
		FlashBrowserWindowHeight
		LinkUpload
		LinkUploadURL
		LinkUploadAllowedExtensions
		LinkUploadDeniedExtensions
		ImageUpload
		ImageUploadURL
		ImageUploadAllowedExtensions
		ImageUploadDeniedExtensions
		FlashUpload
		FlashUploadURL
		FlashUploadAllowedExtensions
		FlashUploadDeniedExtensions
		SmileyPath
		SmileyImages
		SmileyColumns
		SmileyWindowWidth
		SmileyWindowHeight

*/

	/*

		Enumerations used for this control
		Should be moved to _enumeration.inc if implemented into codebase.

	*/
	abstract class QFCKeditorTextMode {
		const XHTML = 'XHTML';
		const Plain = 'Plain';
	}

	class QFCKeditor extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		
		
		// APPEARANCE
		protected $strText = null;
		protected $strBasePath= '/assets/js/fckeditor/';
		// BEHAVIOR
		protected $intMaxLength = 0;
		protected $intMinLength = 0;
		protected $arrConfig 	= array();

		protected $strToolbarSet=	'Default';

		protected $strTextMode = QFCKeditorTextMode::XHTML;
		protected $strCrossScripting = QCrossScripting::Deny;

		// TEXTAREA - when QFCKeditorTextMode::Plain
		protected $blnReadOnly = false;
		protected $blnWrap = true;

		
		public function ValidateCrossScript(){
			switch ($this->strCrossScripting) {
				case QCrossScripting::Allow:
					return true;
					break;
				default:
					// Deny the Use of CrossScripts
					// Check for cross scripting patterns
					// TODO: Change this to RegExp
					$strText = strtolower($this->strText);
					if ((strpos($strText, '<script') !== false) ||
					(strpos($strText, '<applet') !== false) ||
					//(strpos($strText, '<embed') !== false) ||
					(strpos($strText, '<style') !== false) ||
					(strpos($strText, '<link') !== false) ||
					(strpos($strText, '<body') !== false) ||
					//(strpos($strText, '<iframe') !== false) ||
					(strpos($strText, 'javascript:') !== false) ||
					(strpos($strText, ' onfocus=') !== false) ||
					(strpos($strText, ' onblur=') !== false) ||
					(strpos($strText, ' onkeydown=') !== false) ||
					(strpos($strText, ' onkeyup=') !== false) ||
					(strpos($strText, ' onkeypress=') !== false) ||
					(strpos($strText, ' onmousedown=') !== false) ||
					(strpos($strText, ' onmouseup=') !== false) ||
					(strpos($strText, ' onmouseover=') !== false) ||
					(strpos($strText, ' onmouseout=') !== false) ||
					(strpos($strText, ' onmousemove=') !== false) ||
					(strpos($strText, ' onclick=') !== false) ||
					(strpos($strText, '<object') !== false))
					return false;
			}
			return true;
		}
		
		
		//////////
		// Methods
		//////////
		public function ParsePostData() {
			// Check to see if this Control's Value was passed in via the POST data
			if (array_key_exists($this->strControlId, $_POST)) {
				// It was -- update this Control's value with the new value passed in via the POST arguments
				$this->strText = $_POST[$this->strControlId];


			}
		}

		public function GetAttributes($blnIncludeCustom = true, $blnIncludeAction = true) {
			$strToReturn = parent::GetAttributes();
			if ($this->strTextMode == QFCKeditorTextMode::Plain) {
				if (!$this->blnWrap)
					$strToReturn .= 'wrap="false" ';
				if ($this->blnReadOnly)
					$strToReturn .= 'readonly="readonly" ';
			}

			return $strToReturn;
		}

		protected function GetControlHtml() {

			if($this->strTextMode == QFCKeditorTextMode::XHTML){
				require_once(__DOCROOT__.__JS_ASSETS__.'/fckeditor/fckeditor.php');
				$FCKeditor = new FCKeditor($this->strControlId);
				$FCKeditor->Value = $this->strText;
				$FCKeditor->BasePath = $this->strBasePath;

				$FCKeditor->ToolbarSet = $this->strToolbarSet;
				$FCKeditor->Height = $this->strHeight;
				$FCKeditor->Width = $this->strWidth;
				$FCKeditor->Config = $this->arrConfig;     
				$strToReturn = $FCKeditor->CreateHtml();
			}
			else {
				$strStyle = $this->GetStyleAttributes();
				if ($strStyle)
					$strStyle = sprintf('style="%s"', $strStyle);

				$strToReturn = sprintf('<textarea name="%s" id="%s" %s%s>%s</textarea>',
				$this->strControlId,
				$this->strControlId,
				$this->GetAttributes(),
				$strStyle,
				htmlentities($this->strText));
			}

			return $strToReturn;
		}

		public function GetJavaScriptAction(){
			return '';
		}

		public function Validate() {
			$this->strValidationError = "";
			
			if(!$this->ValidateCrossScript()){
				_xls_log("Illgal javascript insertion attempt by " . Visitor::get_visitor_name());
				$this->strValidationError = _sp(sprintf("Illegal javascript detected in text. Please remove. "));
				return false;
			}
			
			if ($this->intMinLength > (strlen($this->strText))) {

				if ($this->strName)
					$this->strValidationError = _sp(sprintf("%s too short (must be at least %s characters long)",$this->strName,$this->intMinLength));
				else
					$this->strValidationError = _sp(sprintf("Entry too short (must be at least %s characters long)",$this->intMinLength));
				return false;
			}

			if ($this->blnRequired) {
				if (strlen($this->strText) > 0)
					return true;
				else {
					if ($this->strName)
						$this->strValidationError = _sp(sprintf("%s is required", $this->strName));
					else
						$this->strValidationError = _sp("Required");
					return false;
				}
			}
			return true;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Columns": return $this->intColumns;
				case "Text": return $this->strText;

				// BEHAVIOR
				case "AllowScripts": return $this->blnAllowScripts;
				case "MaxLength": return $this->intMaxLength;
				case "MinLength": return $this->intMinLength;
				case "ReadOnly": return $this->blnReadOnly;
				case "Rows": return $this->intRows;
				case "TextMode": return $this->strTextMode;

				// LAYOUT
				case "Wrap": return $this->blnWrap;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						if (array_key_exists($strName,$this->arrConfig)){
							return $this->arrConfig[$strName];
						}
						else {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					}
			}
		}

		/////////////////////////
		// Public Properties: SET
		/////////////////////////
		public function __set($strName, $mixValue) {
			$this->blnModified = true;

			switch ($strName) {
				case "BasePath":
					try {
						$this->strBasePath = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				// APPEARANCE
				case "Text":
					try {
						$this->strText = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "TextMode":
					try {
						$this->strTextMode = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "CrossScripting":
					try {
						$this->strCrossScripting = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}					
					
				// BEHAVIOR
				case "AllowScripts":
					try {
						$this->blnAllowScripts = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MaxLength":
					try {
						$this->intMaxLength = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MinLength":
					try {
						$this->intMinLength = QType::Cast($mixValue, QType::Integer);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ReadOnly":
					try {
						$this->blnReadOnly = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}


				// LAYOUT
				case "Wrap":
					try {
						$this->blnWrap = QType::Cast($mixValue, QType::Boolean);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// FCKCONFIG (see includes/FCKeditor/fckconfig.js)

				case "ToolbarSet":
					try {
						$this->strToolbarSet = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "CustomConfigurationsPath":
					try {
						$this->arrConfig['CustomConfigurationsPath'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "EditorAreaCSS":
					try {
						$this->arrConfig['EditorAreaCSS'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "EditorAreaStyles":
					try {
						$this->arrConfig['EditorAreaStyles'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ToolbarComboPreviewCSS":
					try {
						$this->arrConfig['ToolbarComboPreviewCSS'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}					
				case "DocType":
					try {
						$this->arrConfig['DocType'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "BaseHref":
					try {
						$this->arrConfig['BaseHref'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FullPage":
					try {
						$this->arrConfig['FullPage'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "Debug":
					try {
						$this->arrConfig['Debug'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "AllowQueryStringDebug":
					try {
						$this->arrConfig['AllowQueryStringDebug'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SkinPath":
					try {
						$this->arrConfig['SkinPath'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "PluginsPath":
					try {
						$this->arrConfig['PluginsPath'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "AutoDetectLanguage":
					try {
						$this->arrConfig['AutoDetectLanguage'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DefaultLanguage":
					try {
						$this->arrConfig['DefaultLanguage'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ContentLangDirection":
					try {
						$this->arrConfig['ContentLangDirection'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ProcessHTMLEntities":
					try {
						$this->arrConfig['ProcessHTMLEntities'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IncludeLatinEntities":
					try {
						$this->arrConfig['IncludeLatinEntities'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IncludeGreekEntities":
					try {
						$this->arrConfig['IncludeGreekEntities'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FillEmptyBlocks":
					try {
						$this->arrConfig['FillEmptyBlocks'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FormatSource":
					try {
						$this->arrConfig['FormatSource'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FormatOutput":
					try {
						$this->arrConfig['FormatOutput'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FormatIndentator":
					try {
						$this->arrConfig['FormatIndentator'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForceStrongEm":
					try {
						$this->arrConfig['ForceStrongEm'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "GeckoUseSPAN":
					try {
						$this->arrConfig['GeckoUseSPAN'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "StartupFocus":
					try {
						$this->arrConfig['StartupFocus'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForcePasteAsPlainText":
					try {
						$this->arrConfig['ForcePasteAsPlainText'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "AutoDetectPasteFromWord":
					try {
						$this->arrConfig['AutoDetectPasteFromWord'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ForceSimpleAmpersand":
					try {
						$this->arrConfig['ForceSimpleAmpersand'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "TabSpaces":
					try {
						$this->arrConfig['TabSpaces'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ShowBorders":
					try {
						$this->arrConfig['ShowBorders'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "UseBROnCarriageReturn":
					try {
						$this->arrConfig['UseBROnCarriageReturn'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ToolbarStartExpanded":
					try {
						$this->arrConfig['ToolbarStartExpanded'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ToolbarCanCollapse":
					try {
						$this->arrConfig['ToolbarCanCollapse'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IEForceVScroll":
					try {
						$this->arrConfig['IEForceVScroll'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IgnoreEmptyParagraphValue":
					try {
						$this->arrConfig['IgnoreEmptyParagraphValue'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "PreserveSessionOnFileBrowser":
					try {
						$this->arrConfig['PreserveSessionOnFileBrowser'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FloatingPanelsZIndex":
					try {
						$this->arrConfig['FloatingPanelsZIndex'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontColors":
					try {
						$this->arrConfig['FontColors'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontNames":
					try {
						$this->arrConfig['FontNames'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontSizes":
					try {
						$this->arrConfig['FontSizes'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FontFormats":
					try {
						$this->arrConfig['FontFormats'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "StylesXmlPath":
					try {
						$this->arrConfig['StylesXmlPath'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "TemplatesXmlPath":
					try {
						$this->arrConfig['TemplatesXmlPath'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SpellChecker":
					try {
						$this->arrConfig['SpellChecker'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "IeSpellDownloadUrl":
					try {
						$this->arrConfig['IeSpellDownloadUrl'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "MaxUndoLevels":
					try {
						$this->arrConfig['MaxUndoLevels'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DisableImageHandles":
					try {
						$this->arrConfig['DisableImageHandles'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "DisableTableHandles":
					try {
						$this->arrConfig['DisableTableHandles'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkDlgHideTarget":
					try {
						$this->arrConfig['LinkDlgHideTarget'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkDlgHideAdvanced":
					try {
						$this->arrConfig['LinkDlgHideAdvanced'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkDlgHideAdvanced":
					try {
						$this->arrConfig['LinkDlgHideAdvanced'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageDlgHideLink":
					try {
						$this->arrConfig['ImageDlgHideLink'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageDlgHideAdvanced":
					try {
						$this->arrConfig['ImageDlgHideAdvanced'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashDlgHideAdvanced":
					try {
						$this->arrConfig['FlashDlgHideAdvanced'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkBrowser":
					try {
						$this->arrConfig['LinkBrowser'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkBrowserURL":
					try {
						$this->arrConfig['LinkBrowserURL'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkBrowserWindowWidth":
					try {
						$this->arrConfig['LinkBrowserWindowWidth'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkBrowserWindowHeight":
					try {
						$this->arrConfig['LinkBrowserWindowHeight'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageBrowser":
					try {
						$this->arrConfig['ImageBrowser'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageBrowserURL":
					try {
						$this->arrConfig['ImageBrowserURL'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageBrowserWindowWidth":
					try {
						$this->arrConfig['ImageBrowserWindowWidth'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageBrowserWindowHeight":
					try {
						$this->arrConfig['ImageBrowserWindowHeight'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashBrowser":
					try {
						$this->arrConfig['FlashBrowser'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashBrowserURL":
					try {
						$this->arrConfig['FlashBrowserURL'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashBrowserWindowWidth":
					try {
						$this->arrConfig['FlashBrowserWindowWidth'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashBrowserWindowHeight":
					try {
						$this->arrConfig['FlashBrowserWindowHeight'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkUpload":
					try {
						$this->arrConfig['LinkUpload'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkUploadURL":
					try {
						$this->arrConfig['LinkUploadURL'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkUploadAllowedExtensions":
					try {
						$this->arrConfig['LinkUploadAllowedExtensions'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "LinkUploadDeniedExtensions":
					try {
						$this->arrConfig['LinkUploadDeniedExtensions'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageUpload":
					try {
						$this->arrConfig['ImageUpload'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageUploadURL":
					try {
						$this->arrConfig['ImageUploadURL'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageUploadAllowedExtensions":
					try {
						$this->arrConfig['ImageUploadAllowedExtensions'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ImageUploadDeniedExtensions":
					try {
						$this->arrConfig['ImageUploadDeniedExtensions'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashUpload":
					try {
						$this->arrConfig['FlashUpload'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashUploadURL":
					try {
						$this->arrConfig['FlashUploadURL'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashUploadAllowedExtensions":
					try {
						$this->arrConfig['FlashUploadAllowedExtensions'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "FlashUploadDeniedExtensions":
					try {
						$this->arrConfig['FlashUploadDeniedExtensions'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SmileyPath":
					try {
						$this->arrConfig['SmileyPath'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SmileyImages":
					try {
						$this->arrConfig['SmileyImages'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SmileyColumns":
					try {
						$this->arrConfig['SmileyColumns'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SmileyWindowWidth":
					try {
						$this->arrConfig['SmileyWindowWidth'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "SmileyWindowHeight":
					try {
						$this->arrConfig['SmileyWindowHeight'] = QType::Cast($mixValue, QType::String);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				default:
					try {
						parent::__set($strName, $mixValue);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>
