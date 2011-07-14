<?php
	class QPaginator extends QPaginatorBase {
		// APPEARANCE
		protected $intIndexCount = 10;

		protected $strLabelForPrevious;
		protected $strLabelForNext;

		protected $strCssClass = 'paginator';

		//////////
		// Methods
		//////////
		public function __construct($objParentObject, $strControlId = null) {
			parent::__construct($objParentObject, $strControlId);

			$this->strLabelForPrevious = QApplication::Translate('Previous');
			$this->strLabelForNext = QApplication::Translate('Next');
		}

		public function GetControlHtml() {
			$this->objPaginatedControl->DataBind();

			$strStyle = $this->GetStyleAttributes();
			if ($strStyle)
				$strStyle = sprintf(' style="%s"', $strStyle);

			$strToReturn = sprintf('<span id="%s" %s%s>', $this->strControlId, $strStyle, $this->GetAttributes(true, false));

			if ($this->intPageNumber <= 1)
				$strToReturn .= sprintf('<span class="arrow">%s</span>', $this->strLabelForPrevious);
			else {
				$this->strActionParameter = $this->intPageNumber - 1;
				$strToReturn .= sprintf('<span class="arrow"><a href="" %s>%s</a></span>',
					$this->GetActionAttributes(), $this->strLabelForPrevious);
			}

			$strToReturn .= '<span class="break">|</span>';
			
			if ($this->PageCount <= $this->intIndexCount) {
				// We have less pages than total indexcount -- so let's go ahead
				// and just display all page indexes
				for ($intIndex = 1; $intIndex <= $this->PageCount; $intIndex++) {
					if ($this->intPageNumber == $intIndex) {
						$strToReturn .= sprintf('<span class="selected">%s</span>', $intIndex);
					} else {
						$this->strActionParameter = $intIndex;
						$strToReturn .= sprintf('<span class="page"><a href="" %s>%s</a></span>',
							$this->GetActionAttributes(), $intIndex);
					}
				}
			} else {
				// Figure Out Constants
				
				/**
				 * "Bunch" is defined as the collection of numbers that lies in between the pair of Ellipsis ("...")
				 * 
				 * LAYOUT
				 * 
				 * For IndexCount of 10
				 * 2   213   2 (two items to the left of the bunch, and then 2 indexes, selected index, 3 indexes, and then two items to the right of the bunch)
				 * e.g. 1 ... 5 6 *7* 8 9 10 ... 100
				 * 
				 * For IndexCount of 11
				 * 2   313   2
				 * 
				 * For IndexCount of 12
				 * 2   314   2
				 * 
				 * For IndexCount of 13
				 * 2   414   2
				 * 
				 * For IndexCount of 14
				 * 2   415   2
				 * 
				 * 
				 * 
				 * START/END PAGE NUMBERS FOR THE BUNCH
				 * 
				 * For IndexCount of 10
				 * 1 2 3 4 5 6 7 8 .. 100
				 * 1 .. 4 5 *6* 7 8 9 .. 100
				 * 1 .. 92 93 *94* 95 96 97 .. 100
				 * 1 .. 93 94 95 96 97 98 99 100
				 * 
				 * For IndexCount of 11
				 * 1 2 3 4 5 6 7 8 9 .. 100
				 * 1 .. 4 5 6 *7* 8 9 10 .. 100
				 * 1 .. 91 92 93 *94* 95 96 97 .. 100
				 * 1 .. 92 93 94 95 96 97 98 99 100
				 * 
				 * For IndexCount of 12
				 * 1 2 3 4 5 6 7 8 9 10 .. 100
				 * 1 .. 4 5 6 *7* 8 9 10 11 .. 100
				 * 1 .. 90 91 92 *93* 94 95 96 97 .. 100
				 * 1 .. 91 92 93 94 95 96 97 98 99 100
				 * 
				 * For IndexCount of 13
				 * 1 2 3 4 5 6 7 8 9 11 .. 100
				 * 1 .. 4 5 6 7 *8* 9 10 11 12 .. 100
				 * 1 .. 89 90 91 92 *93* 94 95 96 97 .. 100
				 * 1 .. 90 91 92 93 94 95 96 97 98 99 100
				 */
				$intMinimumEndOfBunch = $this->intIndexCount - 2;
				$intMaximumStartOfBunch = $this->PageCount - $this->intIndexCount + 3;
				
				$intLeftOfBunchCount = floor(($this->intIndexCount - 5) / 2);
				$intRightOfBunchCount = round(($this->intIndexCount - 5.0) / 2.0);

				$intLeftBunchTrigger = 4 + $intLeftOfBunchCount;
				$intRightBunchTrigger = $intMaximumStartOfBunch + round(($this->intIndexCount - 8.0) / 2.0);
				
				if ($this->intPageNumber < $intLeftBunchTrigger) {
					$intPageStart = 1;
					$strStartEllipsis = "";
				} else {
					$intPageStart = min($intMaximumStartOfBunch, $this->intPageNumber - $intLeftOfBunchCount);

					$this->strActionParameter = 1;
					$strStartEllipsis = sprintf('<span class="page"><a href="" %s>%s</a></span>',
						$this->GetActionAttributes(), 1);
					$strStartEllipsis .= '<span class="ellipsis">...</span>';
				}
				
				if ($this->intPageNumber > $intRightBunchTrigger) {
					$intPageEnd = $this->PageCount;
					$strEndEllipsis = "";
				} else {
					$intPageEnd = max($intMinimumEndOfBunch, $this->intPageNumber + $intRightOfBunchCount);
					$strEndEllipsis = '<span class="ellipsis">...</span>';

					$this->strActionParameter = $this->PageCount;
					$strEndEllipsis .= sprintf('<span class="page"><a href="" %s>%s</a></span>',
						$this->GetActionAttributes(), $this->PageCount);
				}

				$strToReturn .= $strStartEllipsis;
				for ($intIndex = $intPageStart; $intIndex <= $intPageEnd; $intIndex++) {
					if ($this->intPageNumber == $intIndex) {
						$strToReturn .= sprintf('<span class="selected">%s</span>', $intIndex);
					} else {
						$this->strActionParameter = $intIndex;
						$strToReturn .= sprintf('<span class="page"><a href="" %s>%s</a></span>',
							$this->GetActionAttributes(), $intIndex);
					}
				}
				$strToReturn .= $strEndEllipsis;
			}
				
	
			$strToReturn .= '<span class="break">|</span>';
	
			if ($this->intPageNumber >= $this->PageCount)
				$strToReturn .= sprintf('<span class="arrow">%s</span>', $this->strLabelForNext);
			else {
				$this->strActionParameter = $this->intPageNumber + 1;
				$strToReturn .= sprintf('<span class="arrow"><a href="" %s>%s</a></span>',
					$this->GetActionAttributes(), $this->strLabelForNext);
			}

			$strToReturn .= '</span>';

			return $strToReturn;
		}

		/////////////////////////
		// Public Properties: GET
		/////////////////////////
		public function __get($strName) {
			switch ($strName) {
				case 'IndexCount':
					return $this->intIndexCount;

				case 'LabelForNext':
					return $this->strLabelForNext;
				case 'LabelForPrevious':
					return $this->strLabelForPrevious;

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
			switch ($strName) {
				case 'IndexCount':
					$this->intIndexCount = QType::Cast($mixValue, QType::Integer);
					if ($this->intIndexCount < 7)
						throw new QCallerException('Paginator must have an IndexCount >= 7');
					return $this->intIndexCount;

				case 'LabelForNext':
					try {
						return ($this->strLabelForNext = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
				case 'LabelForPrevious':
					try {
						return ($this->strLabelForPrevious = QType::Cast($mixValue, QType::String));
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}

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