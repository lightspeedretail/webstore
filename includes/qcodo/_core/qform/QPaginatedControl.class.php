<?php
	abstract class QPaginatedControl extends QControl {
		// APPEARANCE
		protected $strNoun;
		protected $strNounPlural;

		// BEHAVIOR
		protected $objPaginator = null;
		protected $objPaginatorAlternate = null;
		protected $blnUseAjax = false;

		// MISC
		protected $objDataSource;

		// SETUP
		protected $blnIsBlockElement = true;
		
		// DATABIND CALLBACK
		protected $strDataBindMethod;
		protected $objDataBindControl;

		const LastPage = -9999;

		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strNoun = QApplication::Translate('item');
			$this->strNounPlural = QApplication::Translate('items');
		}

		// This overriding function ensures that DataSource is set to null
		// before serializing the object to the __formstate
		// (Due to the potentially humungous size of some datasets, it is more efficient
		// to requery than to serialize and put as a hidden form element)
		public function __serialize() {
			$this->objDataSource = null;
		}

		// PaginatedControls should (in general) never have anything that ever needs to be validated -- so this always
		// returns true.
		public function Validate() {
			return true;
		}
		
		public function SetDataBinder($strMethodName, $objParentControl = null) {
			$this->strDataBindMethod = $strMethodName;
			$this->objDataBindControl = $objParentControl;
		}

		public function DataBind() {
			// Run the DataBinder (if applicable)
			if (($this->objDataSource === null) && ($this->strDataBindMethod) && (!$this->blnRendered))
				try {
					$this->objForm->CallDataBinder($this->strDataBindMethod, $this, $this->objDataBindControl);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				// APPEARANCE
				case "Noun": return $this->strNoun;
				case "NounPlural": return $this->strNounPlural;

				// BEHAVIOR
				case "Paginator": return $this->objPaginator;
				case "PaginatorAlternate": return $this->objPaginatorAlternate;
				case "UseAjax": return $this->blnUseAjax;
				case "ItemsPerPage":
					if ($this->objPaginator)
						return $this->objPaginator->ItemsPerPage;
					else
						return null;
				case "TotalItemCount":
					if ($this->objPaginator)
						return $this->objPaginator->TotalItemCount;
					else
						return null;

				// MISC
				case "DataSource": return $this->objDataSource;

				case "LimitClause":
					if ($this->objPaginator) {
						return QQ::LimitInfo($this->objPaginator->ItemsPerPage, $this->ItemOffset);
					}
					return null;

				case "LimitInfo":
					if ($this->objPaginator) {
						return $this->ItemOffset . ',' . $this->objPaginator->ItemsPerPage;
					}
					return null;

				case "ItemCount": return count($this->objDataSource);

				case "ItemOffset":
					if ($this->objPaginator) {
						// If Paginator PageNumber is set to "LastPage", then set it to the total PageCount
						if ($this->objPaginator->PageNumber == QPaginatedControl::LastPage) {
							$this->objPaginator->PageNumber = $this->objPaginator->PageCount;
						}

						return ($this->objPaginator->PageNumber - 1) * $this->objPaginator->ItemsPerPage;
					}
					return null;

				case 'PageNumber':
					if ($this->objPaginator)
						return $this->objPaginator->PageNumber;
					else
						return null;

				case 'PageCount':
					if ($this->objPaginator)
						return $this->objPaginator->PageCount;
					else
						return null;

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
				case "Noun":
					try {
						return ($this->strNoun = QType::Cast($mixValue, QType::String));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "NounPlural":
					try {
						return ($this->strNounPlural = QType::Cast($mixValue, QType::String));
						break;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				// BEHAVIOR
				case "Paginator":
					try {
						$objToReturn = ($this->objPaginator = QType::Cast($mixValue, 'QPaginatorBase'));
						if ($this->objPaginator) {
							if ($this->objPaginator->Form->FormId != $this->Form->FormId)
								throw new QCallerException('The assigned paginator must belong to the same form that this control belongs to.');
							$objToReturn->SetPaginatedControl($this);
						}
						return $objToReturn;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "PaginatorAlternate":
					try {
						$objToReturn = ($this->objPaginatorAlternate = QType::Cast($mixValue, 'QPaginatorBase'));
						if ($this->objPaginatorAlternate->Form->FormId != $this->Form->FormId)
							throw new QCallerException('The assigned paginator must belong to the same form that this control belongs to.');
						$objToReturn->SetPaginatedControl($this);
						return $objToReturn;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

				case "UseAjax":
					try {
						$objToReturn = ($this->blnUseAjax = QType::Cast($mixValue, QType::Boolean));
						
						if ($this->objPaginator)
							$this->objPaginator->UseAjax = $objToReturn;
						if ($this->objPaginatorAlternate)
							$this->objPaginatorAlternate->UseAjax = $objToReturn;

						return $objToReturn;
					} catch (QInvalidCastException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case "ItemsPerPage":
					if ($this->objPaginator) {
						try {
							$intToReturn = ($this->objPaginator->ItemsPerPage = $mixValue);

							if ($this->objPaginatorAlternate)
								($this->objPaginatorAlternate->ItemsPerPage = $intToReturn);

							return $intToReturn;
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					} else
						throw new QCallerException('Setting ItemsPerPage requires a Paginator to be set');
				case "TotalItemCount":
					if ($this->objPaginator) {
						try {
							$intToReturn = ($this->objPaginator->TotalItemCount = $mixValue);

							if ($this->objPaginatorAlternate)
								($this->objPaginatorAlternate->TotalItemCount = $intToReturn);

							return $intToReturn;
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					} else
						throw new QCallerException('Setting TotalItemCount requires a Paginator to be set');

				// MISC
				case "DataSource": 
					return ($this->objDataSource = $mixValue);

				case "PageNumber":
					if ($this->objPaginator) {
						try {
							$intToReturn = ($this->objPaginator->PageNumber = $mixValue);

							if ($this->objPaginatorAlternate)
								($this->objPaginatorAlternate->PageNumber = $intToReturn);

							return $intToReturn;
						} catch (QCallerException $objExc) {
							$objExc->IncrementOffset();
							throw $objExc;
						}
					} else
						throw new QCallerException('Setting PageNumber requires a Paginator to be set');

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

	class QDataBindException extends Exception {
		private $intOffset;
		private $strTraceArray;
		private $strQuery;

		public function __construct(QCallerException $objExc) {
			parent::__construct($objExc->getMessage(), $objExc->getCode());
			$this->intOffset = $objExc->Offset;
			$this->strTraceArray = $objExc->TraceArray;

			if ($objExc instanceof QDatabaseExceptionBase)
				$this->strQuery = $objExc->Query;

			$this->file = $this->strTraceArray[$this->intOffset]['file'];
			$this->line = $this->strTraceArray[$this->intOffset]['line'];
		}

		public function __get($strName) {
			switch($strName) {
				case "Offset":
					return $this->intOffset;
					
				case "BackTrace":
					$objTraceArray = debug_backtrace();
					return (var_export($objTraceArray, true));
					
				case "Query":
					return $this->strQuery;
			}
		}
	}
?>