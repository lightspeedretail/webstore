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

require(__QCODO__.'/qform/QJsCalendar.class.php');

/**
 * xlsws_gsearch class
 * This is the controller class for the search results of gift registries found
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to it
 */
class xlsws_gsearch extends xlsws_index {
	protected $txtGDate1;
	protected $txtGDate2;
	protected $btnSearch;
	protected $dtrGiftRegistry;
	protected $errSpan ;
	protected $txtEmail;

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('gift_search.tpl.php');

		$this->crumbs[] = array('key'=>'xlspg=gift_search' , 'case'=> '' , 'name'=> _sp('Wish Lists'));

		$this->txtEmail = new XLSTextBox($this->mainPnl , 'gemail');
		$this->txtEmail->AddAction(new QEnterKeyEvent() , new QServerAction('dosearch'));
		$this->txtEmail->Required = true;
		$this->txtEmail->Text = _xls_stack_get('GIFT_REGISTRY_EMAIL_SEARCH');

		$this->btnSearch = new QButton($this->mainPnl);
		$this->btnSearch->AddAction(new QClickEvent() , new QServerAction('dosearch'));
		$this->btnSearch->Text = _sp('Search');

		$this->dtrGiftRegistry = new QDataRepeater($this->mainPnl);
		$this->dtrGiftRegistry->Template = templateNamed('gift_search_item.tpl.php');
		$this->dtrGiftRegistry->Visible = false;
		$this->dtrGiftRegistry->UseAjax = true;

		if($this->txtEmail->Text != '')
			$this->dosearch($this->FormId , $this->txtEmail->ControlId , '');
	}

	/**
	 * Form_PreRender - preloads aspects before the registry page loads
	 * @param none
	 * @return none
	 */
	protected function Form_PreRender() {
		$this->dtrGiftRegistry->Refresh();
	}

	/**
	 * dosearch - Callback function for when you search for a specific registry by email
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function dosearch($strFormId, $strControlId, $strParameter) {
		if(trim($this->txtEmail->Text) == '') {
			$this->txtEmail->Warning = (_sp('Please enter an e-mail address.'));
			$this->txtEmail->Focus();
			return;
		}

		$email = trim(strtolower($this->txtEmail->Text));
		_xls_stack_add('GIFT_REGISTRY_EMAIL_SEARCH' , $email);
		$customer = Customer::LoadByEmail($email);

		if(!$customer) {
			$this->txtEmail->Warning = (_sp('Sorry no Wish List found.'));
			return;
		}

		$grs = GiftRegistry::LoadArrayByCustomerId($customer->Rowid, QQ::Clause(QQ::OrderBy(QQN::GiftRegistry()->EventDate, false)));

		if(!$grs || (count($grs) == 0)) {
			$this->txtEmail->Warning = (_sp('Sorry no Wish List found.'));
			return;
		}

		$this->dtrGiftRegistry->DataSource = $grs;
		$this->dtrGiftRegistry->Visible = true;
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_gsearch::Run('xlsws_gsearch', templateNamed('index.tpl.php'));
