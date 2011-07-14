<?php
	class QDropZoneGrouping extends QControlGrouping {
		protected $blnAllowSelf;
		protected $blnAllowSelfParent;

		public function __construct(QForm $objForm, $strGroupingId, $blnAllowSelfParent = false, $blnAllowSelf = false) {
			parent::__construct($objForm, $strGroupingId);
			$this->blnAllowSelf = $blnAllowSelf;
			$this->blnAllowSelfParent = $blnAllowSelfParent;
		}

		// Only to be called by Form
		public function Render() {
			if ($this->blnModified) {
				$strToReturn = '';
				foreach ($this->objControlArray as $objControl) {
					if ($objControl->Rendered)
						// Puts into JS the adding of a control into a Drop Zone Group
						$strToReturn .= sprintf('qc.getW("%s").a2DZG("%s", %s, %s); ',
							$objControl->ControlId, $this->strGroupingId,
							($this->blnAllowSelf) ? '1' : '0',
							($this->blnAllowSelfParent) ? '1' : '0');
				}
				$this->blnModified = false;

				return $strToReturn;
			}
		}
	}
?>