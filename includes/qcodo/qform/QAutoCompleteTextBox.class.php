<?php

	class QAutocompleteTextBox extends QTextBox {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		private $strCallback;
		protected $intDelay = 500;
		protected $boolAutofill = true;
		protected $strHelperClass = 'qautocomplete';
		protected $strSelectClass = 'selectQAutocomplete';
		protected $intMinChars = 3;
		protected $arrItems;

		// BEHAVIOR
		protected $strJavaScripts = 'jquery.pack.js,autocomplete.js';

		//////////
		// Methods
		//////////

		public function __construct($objParentObject, $strControlId = null) {
			//Reformat the control id to prevent browser side saved forms
			parent::__construct($objParentObject, $strControlId);
		}

		public function RenderAjax($blnDisplayOutput = true) {
			ob_clean();
			$query = $_REQUEST['value'];
			if(method_exists($this->Form,$this->strCallback)) {
				call_user_func(array($this->Form,$this->strCallback),$query);
			}
			else {
				header("Content-Type: text/xml");
				echo '<?xml version="1.0"?>'."\n<ajaxresponse><item><text>No callback set</text><value>0</value></item></ajaxresponse>\n";

				// echo "No callback set|0";
				return;

				throw new QCallerException("QAutocomplete does not have a valid Callback function assigned");
				return;
			}

			//header("Content-Type: text/xml");
			/*













			echo '<?xml version="1.0"?>'."\n";
			echo "<ajaxresponse>\n";
			*/
			foreach((array)$this->arrItems as $item) {
				/*
				echo "  <item>\n";
				echo "    <text><![CDATA[".$item->Text."]]></text>\n";
				echo "    <value><![CDATA[".$item->Value."]]></value>\n";
				echo "  </item>\n";
				*/
				echo $item->Text."|".$item->Value."\n";
			}
			//echo "</ajaxresponse>\n";
			exit;
		}

		public function Validate() { return true; }

		protected function GetControlHtml() {
			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf('style="%s"', $strStyle);

			$strToReturn = sprintf('<input type="text" name="%s" id="%s" value="%s" %s%s autocomplete="off" />',
				$this->strControlId,
				$this->strControlId,
				htmlentities($this->strText),
				$this->GetAttributes(),
				$strStyle);

			return $strToReturn;
		}

		public function GetEndScript() {
			$strToReturn = '

			// document.getElementById("'.$this->strControlId.'").focus();

			function selectItem(li) {
				if (li.extra) {
					//alert("That\'s \'" + li.extra[0] + "\' you picked.")
				}
			}
			function formatItem(row) {
				return row[0] + "<!-->" + row[1] + "</-->";
			}

			$(document).ready(function() {

				$("#'.$this->strControlId.'").autocomplete( "'.QApplication::$RequestUri.'", 
									{	
										minChars:3, 
										matchSubset:1, 
										matchContains:1, 
										cacheLength:10, 
										onItemSelect:selectItem, 
										formatItem:formatItem, 
										selectOnly:1,
										extraParams: {Qform__FormCallType:"Ajax"}
									});
				/*
				$("#'.$this->strControlId.'").Autocomplete(
					{
						source: "'.QApplication::$RequestUri.'",
						delay: '.$this->intDelay.',
						fx: {
							type: "slide",
							duration: 400
						},
						autofill: true,
						helperClass: "'.$this->strHelperClass.'",
						selectClass: "'.$this->strSelectClass.'",
						minchars: '.$this->intMinChars.', 
						onSelect: function(data) {return;},
						onShow: fadeInSuggestion,
						onHide: fadeOutSuggestion
					}
				);
				*/
			});
			
			';
			return $strToReturn;
		}

		public function SetCallback($strCallback) {
			$this->strCallback = $strCallback;
		}

		public function AddItem($strText,$strValue) {
			$obj = new StdClass();
			$obj->Text = $strText;
			$obj->Value = $strValue;
			$this->arrItems[] = $obj;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Delay": return $this->intDelay;
				case "Autofill": return $this->boolAutofill;
				case "HelperClass": return $this->strHelperClass;
				case "SelectClass": return $this->strSelectClass;
				case "MinChars": return $this->intMinChars;

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

			try {
				switch ($strName) {
					// APPEARANCE
					case "Delay":
						$this->intDelay = QType::Cast($mixValue, QType::Integer);
						break;
					case "Autofill":
						$this->boolAutofill = QType::Cast($mixValue, QType::Boolean);
						break;
					case "HelperClass":
						$this->strHelperClass = QType::Cast($mixValue, QType::String);
						break;
					case "SelectClass":
						$this->strSelectClass = QType::Cast($mixValue, QType::String);
						break;
					case "MinChars":
						$this->intMinChars = QType::Cast($mixValue, QType::Integer);
						break;
					default:
						try {
							parent::__set($strName, $mixValue);
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
						break;
				}
			} catch (QInvalidCastException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}
	}


	// Empty Event
        class QAutoCompleteTextBoxEvent extends QKeyPressEvent {
                // protected $strJavaScriptEvent = '';
        }

?>
