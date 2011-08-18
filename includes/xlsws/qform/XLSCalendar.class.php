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

/**
* Creates jQuery style date picker
*/
class XLSCalendar extends QTextBox {
	public $xlsTimestamp;
	public $intTimestamp;
	public $DateTime;
	protected $strJavaScripts = 'jquery-ui.js';
	protected $strCssScripts = 'datepicker.css';

	public function __construct($objParent , $strControlId = null) {
		parent::__construct($objParent , $strControlId);

		$locale = _xls_get_conf('LOCALE' , 'en_US');
		$locale = strtolower($locale);
		$localejs = __JS_ASSETS__ . '/' .
			"ui.datepicker-" . $locale . ".js";

		// Add locale JS file
		if (file_exists($localejs))
			$this->strJavaScripts .=
				',' . "ui.datepicker-" . $locale . ".js";
	}

	public function __get($strName) {
		if ($strName == "DateTime") {
			return $this->GetDateFromLabel($this->intTimestamp);
		}
		else
			return parent::__get($strName);
	}

	public function GetDateFromLabel($dttPrevDate = null) {
		$strDate = trim($this->Text);
		if ($strDate) {
			$dttDate = new QDateTime($strDate);
			if ($dttDate->IsNull()) {
				return $dttPrevDate; // reset when bad date is entered
			}
			return $dttDate;
		} else {
			return null;
		}
	}

	public function __set($strName, $mixValue) {
		if($strName == "DateTime") {
			$z = new DateTimeZone(date_default_timezone_get());
			if($mixValue instanceof QDateTime )
				$this->intTimestamp = $mixValue;
			else
				$this->intTimestamp =
					QDateTime::FromTimestamp($mixValue , $z);

			$this->Text =
				$this->intTimestamp->PhpDate(_xls_get_conf('DATE_FORMAT'));
		}else
			parent::__set($strName , $mixValue);
	}

	public function GetEndScript() {
		$str = parent::GetEndScript();
		if(!QApplication::IsBrowser(QBrowserType::InternetExplorer_6_0))
			$str .= "$(document).ready(function(){ $('#" .
				$this->strControlId . "').datepicker(); });";
		return $str;
	}
}
