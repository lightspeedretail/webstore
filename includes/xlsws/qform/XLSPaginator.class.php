<?php
 /*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/*
 * XLSPaginator
 *
 * Extends class used to create and navigate multipage display
 */

class XLSPaginator extends QPaginator {
	public $url = false;

	public function GetControlHtml() {
		$this->strLabelForPrevious = "<img src=\"" .
			templateNamed('css/images/breadcrumbs_arrowleft.png') .
			"\" alt=\"" . _sp("Previous") . "\" />";
		$this->strLabelForNext = "<img src=\"" .
			templateNamed('css/images/breadcrumbs_arrowright.png') .
			"\" alt=\"" . _sp("Next") . "\" />";

		if($this->url)
			$url = $this->url;
		else
			$url = "index.php?";

		$this->objPaginatedControl->DataBind();

		$strStyle = $this->GetStyleAttributes();
		if ($strStyle)
			$strStyle = sprintf(' style="%s"', $strStyle);

		$strToReturn = sprintf('<div id="%s"%s%s>', $this->strControlId,
			$strStyle, $this->GetAttributes(true, false));

		$strToReturn .= "<ul>\n";

		if ($this->intPageNumber <= 1)
			$strToReturn .= sprintf('<li%s>%s</li>', '',
			$this->strLabelForPrevious);
		else {
			$this->strActionParameter = $this->intPageNumber - 1;
			$strToReturn .= sprintf('<li><a href="%s" %s%s>%s</a></li>',
				$url,
				$this->GetActionAttributes(),
				'',
				$this->strLabelForPrevious);
		}

		if ($this->PageCount <= $this->intIndexCount) {
			// We have less pages than total indexcount
			// So just display all page indexes
			for ($intIndex = 1; $intIndex <= $this->PageCount;
				$intIndex++)
			{
				if ($this->intPageNumber == $intIndex) {
					$strToReturn .= sprintf('<li%s>%s</li>', '', $intIndex);
				} else {
					$this->strActionParameter = $intIndex;
					$strToReturn .=
						sprintf('<li><a href="%s" %s%s>%s</a></li>',
							$url . "&page=$intIndex",
							$this->GetActionAttributes(),
							'',
							$intIndex);
				}
			}
		} else {
			$intMinimumEndOfBunch = $this->intIndexCount - 2;
			$intMaximumStartOfBunch = $this->PageCount -
				$this->intIndexCount + 3;

			$intLeftOfBunchCount = floor(($this->intIndexCount - 5) / 2);
			$intRightOfBunchCount = round(($this->intIndexCount - 5.0) /
				2.0);

			$intLeftBunchTrigger = 4 + $intLeftOfBunchCount;
			$intRightBunchTrigger = $intMaximumStartOfBunch +
				round(($this->intIndexCount - 8.0) / 2.0);

			if ($this->intPageNumber < $intLeftBunchTrigger) {
				$intPageStart = 1;
				$strStartElipse = "";
			} else {
				$intPageStart = min($intMaximumStartOfBunch,
					$this->intPageNumber - $intLeftOfBunchCount);

				$this->strActionParameter = 1;
				$strStartElipse = sprintf('<li><a href="" %s%s>%s</a></li>',
					$this->GetActionAttributes(), '', 1);
				$strStartElipse .= '<li><b>...</b></li>';
			}

			if ($this->intPageNumber > $intRightBunchTrigger) {
				$intPageEnd = $this->PageCount;
				$strEndElipse = "";
			} else {
				$intPageEnd = max($intMinimumEndOfBunch,
					$this->intPageNumber + $intRightOfBunchCount);
				$strEndElipse = '<li><b>...</b></li>';

				$this->strActionParameter = $this->PageCount;
				$strEndElipse .= sprintf('<li><a href="" %s%s>%s</a></li>',
					$this->GetActionAttributes(), '', $this->PageCount);
			}

			$strToReturn .= $strStartElipse;
			for ($intIndex = $intPageStart; $intIndex <= $intPageEnd;
				$intIndex++)
			{
				if ($this->intPageNumber == $intIndex) {
					$strToReturn .= sprintf('<li><span %s>%s</span></li>',
						'', $intIndex);
				} else {
					$this->strActionParameter = $intIndex;
					$strToReturn .=
						sprintf('<li><a href="" %s%s>%s</a></li>',
							$this->GetActionAttributes(),
							'',
							$intIndex);
				}
			}
			$strToReturn .= $strEndElipse;
		}

		if ($this->intPageNumber >= $this->PageCount)
			$strToReturn .= sprintf('<li%s>%s</li>', '',
				$this->strLabelForNext);
		else {
			$this->strActionParameter = $this->intPageNumber + 1;
			$strToReturn .= sprintf('<li><a href="" %s%s>%s</a></li>',
				$this->GetActionAttributes(), '',
				$this->strLabelForNext);
		}

		$strToReturn .= "</ul>\n";
		$strToReturn .= '</div>';

		return $strToReturn;
	}
}
