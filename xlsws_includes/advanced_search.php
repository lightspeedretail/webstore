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
 * xlsws_advanced_search class
 * This is the controller class for the category advanced search page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views in the advanced search page
 */
class xlsws_advanced_search extends xlsws_index {
	public $txtSearch;
	public $lstFilters;
	public $txtMsg;
	public $txtStartPrice;
	public $txtEndPrice;
	public $btnSearch;
	public $lblMsg;
	public $objDefaultWaitIcon;
	public $errSpan;
	protected $lblError;

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		$this->mainPnl = new QPanel($this,'MainPanel');

		$this->mainPnl->Template = templateNamed('search_advanced.tpl.php');

		$this->crumbs[] = array(
			'link'=>'advanced-search/pg/',
			'case'=> '',
			'name'=> _sp("Advanced Search")
		);

		_xls_add_page_title(_sp("Advanced Search"));

		$this->lblError = new QLabel($this->mainPnl);

		$this->txtSearch = new XLSTextBox($this->mainPnl);
		$this->txtSearch->Required = true;
		$this->txtSearch->ValidateTrimmed = true;

		$this->txtStartPrice = new XLSTextBox($this->mainPnl);

		$this->txtEndPrice = new XLSTextBox($this->mainPnl);

		$this->lstFilters = new XLSListBox($this->mainPnl);
		$this->lstFilters->Name = _sp('Search Filters');
		$this->lstFilters->AddItem("Current Category", "1");
		$this->lstFilters->AddItem("Entire Store", "2");

		$this->btnSearch = new QButton($this->mainPnl);
		$this->btnSearch->Text = _sp('Search');
		$this->btnSearch->AddAction(new QClickEvent(), new QAjaxAction('butSubmit_click'));
	}

	/**
	 * Form_Validate - validation for this controller with form input fields
	 * @param none
	 * @return boolean true
	 */
	protected function Form_Validate() {
		$this->lblError->Text='';
		$this->lblError->CssClass='customer_reg_err_msg';

		return true;
	}

	/**
	 * butSubmit_click - The callback handler for when the search button is pressed
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function butSubmit_click($strFormId, $strControlId, $strParameter){
		$cat_arr = explode(".",$_GET['c']);
		$cat = array_pop($cat_arr);
		_rd(
			"index.php?advsearch=true&search=" . urlencode($this->txtSearch->Text) .
			"&startprice=" . $this->txtStartPrice->Text .
			"&endprice=" . $this->txtEndPrice->Text .
			"&filter=" . $this->lstFilters->SelectedValue .
			"&c=".$cat
		);
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_advanced_search::Run('xlsws_advanced_search', templateNamed('index.tpl.php'));
