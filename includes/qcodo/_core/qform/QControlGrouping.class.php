<?php
	abstract class QControlGrouping extends QBaseClass {
		protected $strGroupingId;
		protected $objControlArray = array();
		protected $blnModified = false;

		public function __construct(QForm $objForm, $strGroupingId) {
			if (strlen($strGroupingId) == 0)
				$this->strGroupingId = $objForm->GenerateControlId();
			else {
				// Verify ControlId is only AlphaNumeric Characters
				$strMatches = array();
				$strPattern = '/[A-Za-z0-9]*/';
				preg_match($strPattern, $strGroupingId, $strMatches);
				if (count($strMatches) && ($strMatches[0] == $strGroupingId))
					$this->strGroupingId = $strGroupingId;
				else
					throw new QCallerException('GroupingIDs must be only alphanumeric chacters: ' . $strGroupingId);
			}
			try {
				$objForm->AddGrouping($this);
			} catch (QCallerException $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}
		}

		public function __get($strName) {
			switch ($strName) {
				case "GroupingId": return $this->strGroupingId;
				case "Modified": return $this->blnModified;

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

		public function AddControl(QControl $objControl) {
			$this->blnModified = true;
			$strControlId = $objControl->ControlId;
			$this->objControlArray[$strControlId] = $objControl;
		}

		public function RemoveControl($strControlId) {
			$this->blnModified = true;
			if (array_key_exists($strControlId, $this->objControlArray)) {
				// Remove this control
				unset($this->objControlArray[$strControlId]);
			}
		}

		public function GetAllControls() {
			return $this->objControlArray;
		}

		abstract public function Render();
	}
?>