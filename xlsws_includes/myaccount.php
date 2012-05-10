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
 * xlsws_myaccount class
 * This is the controller class for the my account page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the my account page
 */
class xlsws_myaccount extends xlsws_index {
	protected $customer; //the loaded customer object
	protected $repairs; //the list of SROs associated to the customer
	protected $orders; //the list of orders associated to the customer
	protected $giftregistries; //the list of gift registries associated to the customer

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS;

		$customer = Customer::GetCurrent();

		if($customer)
			$this->customer = $customer;
		else
			_xls_display_msg("Sorry, you have to be logged in to access this page.");

		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->mainPnl->Template = templateNamed('myaccount.tpl.php');

		$this->crumbs[] = array('link'=>'myaccount/pg/' , 'case'=> '' , 'name'=> _sp('My account'));

		$this->get_orders();
		$this->get_repairs();

		$this->giftregistries = GiftRegistry::QueryArray(QQ::Equal(QQN::GiftRegistry()->CustomerId, Customer::GetCurrent()->Rowid));
		
		_xls_add_formatted_page_title('My Account');
		
	}
	
	/**
	 * get_orders - get list of orders for this customer
	 * @param none
	 * @return none
	 */
	protected function get_orders() {
		$this->orders = Cart::QueryArray(
			QQ::AndCondition(
				QQ::OrCondition(
					QQ::Equal(QQN::Cart()->Email , $this->customer->Email)
					// QQ::Equal(QQN::Cart()->Phone , $this->customer->Mainphone)
				),
				QQ::OrCondition(
					QQ::Equal(QQN::Cart()->Type , CartType::order),
					QQ::Equal(QQN::Cart()->Type,CartType::saved)
				)
			),
			QQ::Clause(
				QQ::OrderBy(QQN::Cart()->IdStr, false)
			)
		);
	}

	/**
	 * get_repairs - get list of SROS for this customer
	 * @param none
	 * @return none
	 */
	protected function get_repairs() {
		$this->repairs = Sro::QueryArray(
			QQ::OrCondition(
				QQ::Equal(QQN::Sro()->CustomerEmailPhone, $this->customer->Email)
			),
			QQ::Clause(
				QQ::OrderBy(QQN::Sro()->LsId, false)
			)
		);
	}

	
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_myaccount::Run('xlsws_myaccount', templateNamed('index.tpl.php'));
