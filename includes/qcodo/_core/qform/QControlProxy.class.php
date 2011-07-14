<?php
	class QControlProxy extends QControl {
		public function GetControlHtml() {
			throw new QCallerException('QControlProxies cannot be rendered.  Use RenderAsEvents() within an HTML tag.');
		}

		public function RenderAsEvents($strActionParameter = null, $blnDisplayOutput = true) {
			$this->strActionParameter = $strActionParameter;
			$strToReturn = $this->GetActionAttributes();

			// Output or Display
			if ($blnDisplayOutput)
				print($strToReturn);
			else
				return $strToReturn;
		}

		public function RenderAsHref($strActionParameter = null, $blnDisplayOutput = true) {
			$this->strActionParameter = $strActionParameter;
			$objActions = $this->GetAllActions('QClickEvent');
			$strToReturn = '';
			foreach ($objActions as $objAction)
				$strToReturn .= $objAction->RenderScript($this);
			if ($strToReturn)
				$strToReturn = 'javascript:' . $strToReturn;
			else
				$strToReturn = 'javascript: return false;';

			// Output or Display
			if ($blnDisplayOutput)
				print($strToReturn);
			else
				return $strToReturn;
		}

		public function ParsePostData() {}
		public function Validate() {return true;}
	}
?>