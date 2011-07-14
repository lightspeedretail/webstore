<?php
	class QDataGrid extends QDataGridBase  {
		protected $intCellSpacing = 0;
		protected $intCellPadding = 0;

		///////////////////////////
		// DataGrid Preferences
		///////////////////////////

		// Feel free to specify global display preferences/defaults for all QDataGrid controls
		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException  $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// For example... let's default the CssClass to datagrid
			$this->strCssClass = 'datagrid';
		}

		// Override any of these methods/variables below to alter the way the DataGrid gets rendered

//		protected function GetPaginatorRowHtml() {}

//		protected function GetHeaderRowHtml() {}

//		protected $blnShowFooter = true;		
//		protected function GetFooterRowHtml() {
//			return sprintf('<tr><td colspan="%s" style="text-align: center">Some Footer Can Go Here</td></tr>', count($this->objColumnArray));
//		}

//		protected function GetDataGridRowHtml($objObject) {}
	}
?>