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
 * xlsws_track_order class
 * This is the controller class for the order lookup page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the order lookup page
 */
class xlsws_track_order extends xlsws_index {
	protected $customer; //the loaded customer object

	protected $txtOrderId; //the "order/sro" id input text field
	protected $txtZipCode; //the zipcode input text field (only used with version 2.0.1 and below)
	protected $txtEmailphone; //the input text box for the email address

	protected $dtrOrder; //the order with its pertaining values
	protected $pxyOrder; //the actions associated to the order

	protected $btnSearch; //input button for search

	protected $orderViewPnl; //the panel where the order details are viewed
	protected $orderViewCustomerPnl; //the order view panel showing customer details
	protected $orderViewItemsPnl; //the order view panel showing the line items for the order
	protected $orderViewNotesPnl; //the order view panel showing notes for the order
	protected $orderSearchPnl; //the search panel
	protected $orderResultPnl; //the results panel

	protected $dtgItem; //the line item

	protected $lblIdStr; //the label for the WO- or O- number
	protected $lblPrintedNotes; //the label for the printed notes
	protected $lblOrderStatus; //the label for order status
	protected $lblOrderDate; //the label for order date

	protected $butBack; //the "go back" button

	protected $errSpan; //a span that shows an error on top of this page if any
	protected $lblSubTotal; //the subtotal label of the order
	protected $order = false; //the order object which gets loaded on runtime
	protected $show_submit_order = false; //show the submit order button, by default false

	protected $lblPaymentNotes; //the label for notes on the payment method
	protected $lblShippingNotes; //the label for notes on the shipping method
	protected $new_order;

	protected $lblOrderMsg; //a span that shows an error on top of this page if any

	/**
	 * build_content_box - builds the content box to search orders
	 * @param none
	 * @return none
	 */
	protected function build_content_box() {
		$this->orderSearchPnl = new XLSContentBox($this->mainPnl);
		$this->orderSearchPnl->Name = _sp("Search Orders");
	}


	/**
	 * build_back_widget - builds the back button for order lookup
	 * @param none
	 * @return none
	 */
	protected function build_back_widget() {
		$this->butBack = new QButton($this->orderViewPnl);
		$this->butBack->Text = _sp("Back");
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_content_box();
		$this->build_orderid_widget($this->orderSearchPnl,"Order ID");
		$this->build_emailphone_widget($this->orderSearchPnl);
		$this->build_search_widget($this->orderSearchPnl);
	}

	/**
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->txtEmailphone->AddAction(new QEnterKeyEvent(), new QJavaScriptAction('document.getElementById(\'' . $this->btnSearch->ControlId  . '\').click();'));
		$this->txtOrderId->AddAction(new QEnterKeyEvent(), new QJavaScriptAction('document.getElementById(\'' . $this->btnSearch->ControlId  . '\').click();'));
		$this->pxyOrder->AddAction(new QClickEvent(), new QServerAction('display_order_click'));
		$this->btnSearch->AddAction(new QClickEvent(), new QServerAction('search_Order'));
	}

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

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('order_track.tpl.php');

		$this->crumbs[] = array('key'=>'xlspg=myaccount' , 'case'=> '' , 'name'=> _sp('My Orders'));

		$this->errSpan = new QLabel($this->mainPnl);
		$this->errSpan->CssClass='modal_reg_err_msg';

		// Wait icon
		$this->objDefaultWaitIcon = new QWaitIcon($this);

		$this->orderResultPnl = new XLSContentBox($this->mainPnl);
		$this->orderResultPnl->Name = _sp("Your Orders");
		$this->orderResultPnl->Visible = false;

		$this->dtrOrder = new QDataRepeater($this->orderResultPnl);
		$this->dtrOrder->Template = templateNamed('order_list.tpl.php');
		$this->pxyOrder = new QControlProxy($this);

		$this->build_widgets();

		//DEPRECIATED FROM WEB STORE 2.0.2 ONWARDS, USED IN CASE DEVELOPERS PREFER TO USE ZIPCODE FOR LOOKUPS
		$this->txtZipCode = new XLSTextBox($this->orderSearchPnl);
		$this->txtZipCode->Name = _sp("Zipcode");
		$this->txtZipCode->Display = false;
		//END DEPRECIATED SEGMENT

		$orders = array();

		if($this->customer) {
			// find ORDERs

			// Load by email and main phone
			$orders = Cart::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::Cart()->Email, $this->customer->Email),
					QQ::OrCondition(
						QQ::Equal(QQN::Cart()->Type, CartType::order),
						QQ::Equal(QQN::Cart()->Type, CartType::saved)
					)
				),
				QQ::Clause(
					QQ::OrderBy(QQN::Cart()->IdStr, false )
				)
			);

			if(count($orders) > 0) {
				// hide the panel
				$this->orderSearchPnl->Visible = false;
				$this->orderResultPnl->Visible = true;

				$this->dtrOrder->DataSource = $orders;
			}
		}

		// The View Detail Panel
		$this->orderViewPnl = new QPanel($this->mainPnl);
		$this->orderViewPnl->Template = templateNamed('order_view.tpl.php');
		$this->orderViewPnl->Visible = false;

		$this->lblIdStr = new QLabel($this->orderViewPnl);
		$this->lblOrderDate = new QLabel($this->orderViewPnl);
		$this->lblOrderStatus = new QLabel($this->orderViewPnl);

		$this->orderViewItemsPnl = new QPanel($this->orderViewPnl);

		$this->show_submit_order = _xls_stack_pop('xls_submit_order');
		$this->new_order = $this->show_submit_order; //maintain the boolean value for this since the previous variable gets altered later

		$this->lblOrderMsg = new QLabel($this->mainPnl);
		$this->bind_widgets();

		if($this->show_submit_order)
			 $this->lblOrderMsg->Text = _sp("Thank you for your order.");

		if(isset($_GET['dosearch'])) {
			$this->txtOrderId->Text = $_GET['orderid'];
			$this->txtEmailphone->Text = $_GET['emailphone'];
			$this->search_Order();
		}

		if(isset($_GET['getuid'])) {
			$this->show_submit_order = $_GET['getuid'];
			$this->search_Order();
		}

		if ($_GET['sendemail'] == "true" && isset($_GET['oid'])) {
			//$order = Cart::QuerySingle(QQ::Equal(QQN::Cart()->Linkid , $_GET['oid']));
			//$this->send_email($order);
			return;
		}

		$this->order_display($this->order , $this->orderViewItemsPnl);
	}

	/**
	 * search_Order - Searches for an order with the provided email and order id
	 * @param none
	 * @return none
	 */
	protected function search_Order() {
		if($this->show_submit_order) {
			$this->order = Cart::QuerySingle(QQ::Equal(QQN::Cart()->Linkid , $this->show_submit_order));
		} else {
			$orderid = trim($this->txtOrderId->Text);
			// $zipcode = trim($this->txtZipCode->Text);
			$emailphone = trim($this->txtEmailphone->Text);

			$this->order = Cart::QuerySingle(
				QQ::AndCondition(
					QQ::Equal(QQN::Cart()->IdStr, $orderid),
					QQ::OrCondition(
						QQ::Equal(QQN::Cart()->Email, $emailphone),
						QQ::Equal(QQN::Cart()->Phone, _xls_number_only($emailphone))
					)
				)
				//, QQ::Equal(QQN::Cart()->Zipcode , $zipcode))
			);
		}

		if(!$this->order) {
			_xls_display_msg("No order was found for the given Order/Repair ID and Email/Phone.");
			$this->errSpan->Text = _sp("No order was found for the given Order/Repair ID and Email/Phone.");
			$this->errSpan->Visible= true;
			return;
		}
		else
			$this->errSpan->Visible= false;

		$this->display_ORDER();
	}

	/**
	 * display_order_click - Load a cart and display it after a click command is sent from order lookup
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function display_order_click($strFormId, $strControlId, $strParameter) {
		$this->order = Cart::LoadByRowid($strParameter);

		if($this->order)
			$this->display_ORDER();
		else
			QApplication::ExecuteJavaScript("alert('" . _sp("Order not found.")  . "')");
	}

	/**
	 * display_ORDER - Displays the order currently loaded
	 * @param none
	 * @return none
	 */
	protected function display_ORDER() {
		if(!$this->order)
			return;

		$this->lblIdStr->Text = $this->order->IdStr;
		$this->lblOrderStatus->Text = _sp($this->order->Status);

		// Color the Orders
		$this->lblOrderStatus->CssClass = $this->order_status_css($this->order->Status);

		if($this->order->DatetimePosted instanceof QDateTime)
			$this->lblOrderDate->Text = $this->order->DatetimePosted->format(_xls_get_conf( 'DATE_FORMAT' , 'D d M y'));

		// show shipping and payment details
		$this->lblShippingNotes = new QLabel($this->orderViewPnl);

		if($this->order->ShippingModule !='' ) {
			$obj = $this->loadModule($this->order->ShippingModule , 'shipping');

			if($obj) {
				$msg = $obj->message($this->order);
			}

			$this->lblShippingNotes->Text = $msg;
		}

		$this->lblPaymentNotes = new QLabel($this->orderViewPnl);

		if($this->order->PaymentModule !='' ) {
			$obj = $this->loadModule($this->order->PaymentModule , 'payment');

			if($obj) {
				$msg = $obj->message($this->order);
			}

			$this->lblPaymentNotes->Text = $msg;
		}

		$this->orderViewPnl->Visible= true;
		$this->orderSearchPnl->Visible= false;
		$this->orderResultPnl->Visible= false;
		$this->orderResultPnl->Visible= false;
		$this->order_display($this->order , $this->orderViewItemsPnl);
		if ($this->new_order) {
			QApplication::ExecuteJavaScript("$(document).ready(function() { $.get('index.php?xlspg=order_track&sendemail=true&oid=".$_GET['getuid'] . "',function(data) { });});");
		}
	}

	/**
	 * order_status_css - sets the appropriate CSS class for the "status" of the order based on its status in the database
	 * @param string $status :: The status of the order
	 * @return string :: The CSS class associated to the status passed
	 */
	protected function order_status_css($status) {
		if($status == "Invoiced"){
			return "order_status_invoiced";
		}elseif($status == "None"){
			return "order_status_none";
		}else
			return "order_status_others";
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_track_order::Run('xlsws_track_order', templateNamed('index.tpl.php'));
