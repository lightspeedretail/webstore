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

    public function __construct($objParentObject, $strControlId = null) {
        try {
            parent::__construct($objParentObject, $strControlId);
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset;
            throw $objExc;
        }

        // Define amount of pages to view in pagination
        $this->IndexCount = 7;

        // Todo :: This deserves further cleanup
		if($this->url)
			$url = $this->url;
		else
			$url = "index.php?";

        // Define left and right button content
		$this->strLabelForPrevious = "<img src=\"" .
			templateNamed('css/images/breadcrumbs_arrowleft.png') .
			"\" alt=\"" . _sp("Previous") . "\" />";
		$this->strLabelForNext = "<img src=\"" .
			templateNamed('css/images/breadcrumbs_arrowright.png') .
			"\" alt=\"" . _sp("Next") . "\" />";
    }

    public function GetControlHtmlPreviousPage() {
        $strLabel = $this->LabelForPrevious;
        $strClass = 'previous';
        $intPageId = '';

        if ($this->intPageNumber > 1) {
            $intPageId = $this->intPageNumber - 1;
            $strLabel = $this->GetControlHtmlPage($intPageId, $strLabel, true);
        }

        return $this->GetControlHtmlItem($strLabel, $strClass);
    }

    public function GetControlHtmlNextPage() {
        $strLabel = $this->LabelForNext;
        $strClass = 'next';
        $intPageId = '';

        if ($this->intPageNumber < $this->PageCount) {
            $intPageId = $this->intPageNumber + 1;
            $strLabel = $this->GetControlHtmlPage($intPageId, $strLabel, true);
        }

        return $this->GetControlHtmlItem($strLabel, $strClass);
    }

    public function GetControlHtmlPage($intPageId, $strLabel = '', 
        $blnInner = false) {

        if (!$strLabel)
            $strLabel = $intPageId;
        $strClass = '';

        $this->strActionParameter = $intPageId;

        if ($intPageId != $this->intPageNumber)
            $strLabel = sprintf('<a page="%s" href="%s" %s>%s</a>',
                $intPageId, 
                $this->url . "&page={$intPageId}",
                $this->GetActionAttributes(),
                $strLabel
            );
        else $strClass = 'current';

        if ($blnInner) return $strLabel;
        else return $this->GetControlHtmlItem($strLabel, $strClass);
    }

    public function GetControlHtmlItem($strLabel, $strClass = '') {
        if ($strClass)
            $strClass = ' class="' . $strClass . '"';

        return sprintf('<li%s>%s</li>' . PHP_EOL, $strClass, $strLabel);
    }


	public function GetControlHtml() {
        $this->objPaginatedControl->DataBind();

        // Define the container
        $strStyle = $this->GetStyleAttributes();
        if ($strStyle)
            $strStyle = sprintf(' style="%s"', $strStyle);

        $strToReturn = sprintf('<div id="%s" %s%s>' . PHP_EOL, 
            $this->strControlId, 
            $strStyle,
            $this->GetAttributes(true, false)
        );

        $strToReturn .= '  <ul>' . PHP_EOL;
        $strToReturn .= $this->GetControlHtmlPreviousPage();

        if ($this->PageCount <= $this->intIndexCount) {
            // Display all pages when we have fewer pages than IndexCount
            for ($intIndex = 1; $intIndex <= $this->PageCount; $intIndex++)
                $strToReturn .= $this->GetControlHtmlPage($intIndex);
        } 
        else {
            // We have more pages than we have IndexCount
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

                $strStartElipse = $this->GetControlHtmlPage(1);
				$strStartElipse .= '<li><b>...</b></li>' . PHP_EOL;
			}

			if ($this->intPageNumber > $intRightBunchTrigger) {
				$intPageEnd = $this->PageCount;
				$strEndElipse = "";
			} else {
				$intPageEnd = max($intMinimumEndOfBunch,
					$this->intPageNumber + $intRightOfBunchCount);
				$strEndElipse = '<li><b>...</b></li>' . PHP_EOL;
                $strEndElipse .= $this->GetControlHtmlPage($this->PageCount);
			}

			$strToReturn .= $strStartElipse;
            for ($intIndex = $intPageStart; $intIndex <= $intPageEnd; $intIndex++)
                $strToReturn .= $this->GetControlHtmlPage($intIndex);
			$strToReturn .= $strEndElipse;
		}

        $strToReturn .= $this->GetControlHtmlNextPage();
		$strToReturn .= '  </ul>' . PHP_EOL;
		$strToReturn .= '</div>' . PHP_EOL;

		return $strToReturn;
    }
}
