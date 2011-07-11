<?php
	// Paginator Class
	abstract class QPaginatorBase extends QControl {
		///////////////////////////
		// Private Member Variables
		///////////////////////////
		
		// BEHAVIOR
		protected $intItemsPerPage = 15;
		protected $intPageNumber = 1;
		protected $intTotalItemCount = 0;
		protected $blnUseAjax = false;
		
		protected $objPaginatedControl;
		
		// SETUP
		protected $blnIsBlockElement = false;

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			try {
				parent::__construct($objParentObject, $strControlId);
			} catch (QCallerException  $objExc) {
				$objExc->IncrementOffset();
				throw $objExc;
			}

			// Setup Pagination Events
			if ($this->blnUseAjax)
				$this->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'Page_Click'));
			else
				$this->AddAction(new QClickEvent(), new QServerControlAction($this, 'Page_Click'));

			$this->AddAction(new QClickEvent(), new QTerminateAction());
		}

		public function ParsePostData() {}
		public function Validate() {return true;}
		public function GetJavaScriptAction() {return 'onclick';}

		public function Page_Click($strFormId, $strControlId, $strParameter) {
			$this->objPaginatedControl->PageNumber = QType::Cast($strParameter, QType::Integer);			
		}

		public function SetPaginatedControl(QPaginatedControl $objPaginatedControl) {
			$this->objPaginatedControl = $objPaginatedControl;

			$this->UseAjax = $objPaginatedControl->UseAjax;
			$this->ItemsPerPage = $objPaginatedControl->ItemsPerPage;
			$this->PageNumber = $objPaginatedControl->PageNumber;
			$this->TotalItemCount = $objPaginatedControl->TotalItemCount;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// BEHAVIOR
				case "ItemsPerPage": return $this->intItemsPerPage;
				case "PageNumber": return $this->intPageNumber;
				case "TotalItemCount": return $this->intTotalItemCount;
				case "UseAjax": return $this->blnUseAjax;
				case "PageCount":
					return floor($this->intTotalItemCount / $this->intItemsPerPage) +
						((($this->intTotalItemCount % $this->intItemsPerPage) != 0) ? 1 : 0);

				case "PaginatedControl":
					return $this->objPaginatedControl;

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
				// BEHAVIOR
				case "ItemsPerPage":
					try {
						if ($mixValue > 0)
							return ($this->intItemsPerPage = QType::Cast($mixValue, QType::Integer));
						else
							return ($this->intItemsPerPage = 10);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "PageNumber":
					try {
						if ($mixValue > 0)
							return ($this->intPageNumber = QType::Cast($mixValue, QType::Integer));
						else if ($mixValue == QPaginatedControl::LastPage)
							return ($this->intPageNumber = QPaginatedControl::LastPage);
						else
							return ($this->intPageNumber = 1);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "TotalItemCount":
					try {
						if ($mixValue > 0)
							return ($this->intTotalItemCount = QType::Cast($mixValue, QType::Integer));
						else
							return ($this->intTotalItemCount = 0);
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "UseAjax":
					try {
						$blnToReturn = ($this->blnUseAjax = QType::Cast($mixValue, QType::Boolean));
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

					// Because we are switching to/from Ajax, we need to reset the events
					$this->RemoveAllActions('onclick');
					if ($this->blnUseAjax)
						$this->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'Page_Click'));
					else
						$this->AddAction(new QClickEvent(), new QServerControlAction($this, 'Page_Click'));

					$this->AddAction(new QClickEvent(), new QTerminateAction());

					return $blnToReturn;

				default:
					try {
						return (parent::__set($strName, $mixValue));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
					break;
			}
		}
	}
?>