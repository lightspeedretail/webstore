<?php
	class QLexer extends QBaseClass {
		private $objRegexMode = array();
		private $objModeHandlers = array();
		private $objTokenModeMap = array();
		private $objTokens = array();
		private $objModeStack;
	
		const UNMATCHED = "__UNMATCHED__";
		const DefaultMode = "default_mode";
	
		public function __construct($strStartMode = QLexer::DefaultMode) {
			$this->objModeStack = new QStack();
			$this->objModeStack->Push($strStartMode);
		}
	
		public function addPattern($strPattern, $strTokenName, $strMode = QLexer::DefaultMode) {
			if(!isset($this->objRegexMode[$strMode])) {
				$this->objRegexMode[$strMode] = new QRegex();
			}
			$this->objRegexMode[$strMode]->addPattern($strPattern,$strTokenName);
		}
	
		public function addEntryPattern($strPattern, $strTokenName, $strMode = QLexer::DefaultMode, $strNewMode) {
			if(!isset($this->objRegexMode[$strMode])) {
				$this->objRegexMode[$strMode] = new QRegex();
			}
			$this->objRegexMode[$strMode]->addPattern($strPattern,$strTokenName);
			$this->objTokenModeMap[$strTokenName] = $strNewMode;
		}
	
		public function addExitPattern($strPattern, $strTokenName, $strMode, $strNewMode = QLexer::DefaultMode) {
			if(!isset($this->objRegexMode[$strMode])) {
				$this->objRegexMode[$strMode] = new QRegex();
			}
			$this->objRegexMode[$strMode]->addPattern($strPattern,$strTokenName);
			$this->objTokenModeMap[$strTokenName] = "__exit";
		}
	
		public function Tokenize(&$strRaw) {
			$objTokens = array();
			$intLength = strlen($strRaw);
			while (is_array($objParsed = $this->Reduce($strRaw))) {
				list($strUnmatched,$strMatched,$strToken) = $objParsed;
				if($strUnmatched != "") {
					array_push($objTokens,array("token"=>QLexer::UNMATCHED,'raw'=>$strUnmatched));
				}
	
				if(array_key_exists($strToken,$this->objTokenModeMap)) {
					if($this->objTokenModeMap[$strToken] == "__exit") {
						$this->objModeStack->Pop();
						array_push($objTokens,array("token"=>$strToken,'raw'=>$strMatched));
						return $objTokens;
					}
					else {
						$this->objModeStack->Push($this->objTokenModeMap[$strToken]);
						array_push($objTokens,array("token"=>$strToken,'raw'=>$this->Tokenize($strRaw)));
					}
				}
				else {
					array_push($objTokens,array("token"=>$strToken,'raw'=>$strMatched));
				}
			}
	
			if($objParsed) {
				array_push($objTokens,array("token"=>QLexer::UNMATCHED,'raw'=>$strRaw));
			}
	
			/**
			 * If we get here, we've parsed everything possible.  Pop one 
			 * off the stack and see if we can continue.
			 */
			if($this->objModeStack->Size() > 1) // Don't short the stack
				$this->objModeStack->Pop();
	
			/**
			 * Try a little data cleanup
			 */
			if(is_array($objParsed = $this->Reduce($strRaw))) {
				list($strUnmatched,$strMatched,$strToken) = $objParsed;
				if($strMatched != "") {
					// We got a match when we pop the stack ...
					// Replace things the way they should be.
					$objLastToken = array_pop($objTokens);
					$objLastToken["raw"] = $strUnmatched;
					array_push($objTokens,$objLastToken);
					$strRaw = $strMatched . $strRaw;
				}
			}
	
			return $objTokens;
		}
	
		private function Reduce(&$strRaw) {
			// Are we in a valid mode?
			if (!isset($this->objRegexMode[$this->objModeStack->PeekLast()])) {
				return FALSE;
			}
	
			// Empty String?
			if ($strRaw === "") {
				return TRUE;
			}
	
			$strToken = $this->objRegexMode[$this->objModeStack->PeekLast()]->match($strRaw, $strMatch);
			if ($strToken) {
				// Where in the string did we match?
				$intMatchPosition = strpos($strRaw, $strMatch);
				$strUnparsed = substr($strRaw, 0, $intMatchPosition);
				$strRaw = substr($strRaw, $intMatchPosition + strlen($strMatch));
				return array($strUnparsed, $strMatch, $strToken);
			}
	
			return TRUE;
		}
	}
?>