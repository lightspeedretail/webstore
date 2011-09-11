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
 * This is the controller class for the main "edit cart" page on the front
 * end. This class is responsible for querying the database for various
 * aspects needed on this page and assigning template variables to the
 * views related to the edit cart page
 */
class xlsws_cart extends xlsws_index {
	protected $cart; //Cart database object instantiation

	protected $btn_update; //the "update cart" button widget
	protected $btn_checkout; //the "check out" button widget
	protected $btn_clearCart; //the "clear cart" button widget
	protected $btn_sendCart;  //the "send cart" button widget

	// TODO :: This ought to be a property of the Cart object
	protected $dtgCart; //association of line items to the cart

	protected $dxMsg; //Any messages that show up on the cart send lightbox
	protected $dxSendCart; // An instantation of see XLSCartSend.class.php

	protected $pxyBackToCart; // Actions to perform when clicking Continue
	protected $pxyCheckOut; // Actions to do on checkout

	/**
	 * build_update_widget - builds the update cart submit button
	 * @param none
	 * @return none
	 */
	protected function build_update_widget() {
		$this->btn_update = new QButton($this->mainPnl);
		$this->btn_update->Text = _sp("Update Cart");
	}

	/**
	 * build_clear_widget - builds the clear cart button
	 * @param none
	 * @return none
	 */
	protected function build_clear_widget() {
		$this->btn_clearCart = new QButton($this->mainPnl);
		$this->btn_clearCart->Text = _sp("Clear Cart");
	}

	/**
	 * build_send_widget - builds the send cart button
	 * @param none
	 * @return none
	 */
	protected function build_send_widget() {
		// Only cart types of Cart can be sent
		if($this->cart->Type == CartType::cart){
			$this->btn_sendCart = new QButton($this->mainPnl);
			$this->btn_sendCart->Text = _sp("Email Cart");
			$this->btn_sendCart->AddAction(new QClickEvent(),
				new QAjaxAction('sendCart'));
		}
	}

	/**
	 * build_send_box - builds the send cart modal box
	 * @param none
	 * @return none
	 */
	protected function build_send_box() {
		$this->dxSendCart = new XLSCartSend($this->mainPnl);
		$this->dxSendCart->Visible = false;
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_update_widget();
		$this->build_clear_widget();
		$this->build_send_widget();
		$this->build_send_box();
	}

	/**
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->btn_update->AddAction(new QClickEvent(),
			new QAjaxAction('cartUpdate'));
		$this->btn_clearCart->AddAction(new QClickEvent(),
			new QConfirmAction(
				_sp('Are you sure you want to clear the cart contents?')
			));
		$this->btn_clearCart->AddAction(new QClickEvent(),
			new QServerAction('clearCart'));
		$this->pxyBackToCart->AddAction(new QClickEvent(),
			new QServerAction('continue_shopping'));
		$this->pxyBackToCart->AddAction(new QClickEvent(),
			new QJavaScriptAction('return false;'));
		$this->pxyCheckOut->AddAction(new QClickEvent(),
			new QServerAction('check_out_customer_register'));
		$this->pxyCheckOut->AddAction(new QClickEvent(),
			new QJavaScriptAction('return false;'));
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS;

		//is there a Get Cart?
		if(isset($XLSWS_VARS['getcart'])) {
			try {
				Cart::LoadCartByLink($XLSWS_VARS['getcart']);
				_rd("index.php?xlspg=cart");
			} catch (Exception $objExc) {
				_xls_display_msg($objExc->getMessage());
			}
		}

		$this->cart = Cart::GetCart();

		if ($this->cart->Count == 0)
			_xls_display_msg(_sp("Your cart is empty. " .
				"Please add items to this cart."));

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('cart.tpl.php');
		$this->mainPnl->AutoRenderChildren = false;

		$this->crumbs[] = array('key'=>'xlspg=cart',
			'case'=> '',
			'name'=> _sp('Shopping Cart'));

		//new XLSGrid($this->mainPnl);
		$this->dtgCart = new QDataRepeater($this->mainPnl);
		$this->dtgCart->Template = templateNamed('cart_item.tpl.php');
		$this->dtgCart->UseAjax = true;
		$this->dtgCart->SetDataBinder('dtgCart_Bind');

		$this->misc_components['order_subtotal'] =
			new QLabel($this->mainPnl);
		$this->misc_components['order_subtotal']->Text =
			$this->cart->Subtotal;
		$this->misc_components['order_subtotal']->CssClass =
			"cart_line_selltotal";

		if(_xls_get_conf('TAX_INCLUSIVE_PRICING','') == '1')
			$this->misc_components['order_subtotal']->Display = false;

		if($this->cart->FkTaxCodeId >= 0){
			$this->order_display_tax($this->cart , $this->mainPnl);
		}

		$this->build_widgets();
		$this->pxyBackToCart = new QControlProxy($this);
		$this->pxyCheckOut = new QControlProxy($this);
		$this->bind_widgets();
	}

	/**
	 * check_out_customer_register
	 * Checking script to see if user needs to proceed to checkout or to
	 * register page;
	 * @param string strFormId
	 * @param integer strControlId
	 * @param string strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function check_out_customer_register($strFormId, $strControlId, $strParameter){
		$customer = Customer::GetCurrent();

		// 170209 - request by ian to go the checkout page direct
		_rd("index.php?xlspg=checkout");

		return;

		// if loggged in - to go the shipping page
		if($customer)
			_rd("index.php?xlspg=checkout");

		_rd("index.php?xlspg=checkout_customer");
	}

	/**
	 * sendCart - Show email cart modal box
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function sendCart($strFormId, $strControlId, $strParameter){
		if($this->cart->Type == CartType::cart)
			$this->dxSendCart->ShowDialogBox();
		else
			QApplication::ExecuteJavaScript("alert('" . _sp("This cart cannot be e-mailed") . "')");
	}

	/**
	 * saveCart - Save a cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function saveCart($strFormId, $strControlId, $strParameter) {
		$customer = Customer::GetCurrent();

		if(!$customer) {
			$this->dxMsg->Text = "You have to login to save Cart. Click here to login or click here to become a member.";
			$this->dxMsg->ShowDialogBox();
		}

		return;
	}

	/**
	 * minusIcon - Attach a minus icon with actions to a line item
	 * @param object $item :: CartItem object for the line item in your cart
	 * @return Rendered minus icon with actions attached
	 */
	public function minusIcon($item) {
		$strControlId = 'minus' . $item->Rowid;

		$minusIcon = $this->GetControl($strControlId);

		if (!$minusIcon) {
			// For quotes it is readonly
			if($item->CartType == CartType::quote)
				return '';

			$minusIcon = new QControlProxy($this->dtgCart, $strControlId);

			$minusIcon->CssClass = "cart_qty_box";

			$minusIcon->AddAction(new QClickEvent() ,  new QAjaxAction('minusClick'));
			$minusIcon->AddAction(new QClickEvent() ,  new QJavaScriptAction('return false;'));
		}

		return $minusIcon->RenderAsEvents($item->Rowid , false);
	}

	/**
	 * qtyBox - Create and update the "quantity" column for each line item
	 * @param obj $item :: CartItem object for the line item in your cart
	 * @return none
	 */
	public function qtyBox($item){
		$strControlId = 'qty' . $item->Rowid;

		$qtyBox = $this->GetControl($strControlId);

		if (!$qtyBox) {
			// For quotes it is readonly
			if(($item->CartType == CartType::quote) )  // || $item->GiftRegistryItem
				$qtyBox = new QLabel($this->dtgCart, $strControlId);
			else
				$qtyBox = new QIntegerTextBox($this->dtgCart, $strControlId);

			$qtyBox->Text = $item->Qty;

			$qtyBox->ActionParameter = $item->Rowid;

			$qtyBox->CssClass = "cart_qty_box";

			$qtyBox->AddAction(new QChangeEvent(), new QAjaxAction('qtyChange'));
			$qtyBox->AddAction(new QEnterKeyEvent(), new QAjaxAction('qtyChange'));
			$qtyBox->AddAction(new QEnterKeyEvent(), new QTerminateAction());
		}

		return $qtyBox->Render(false);
	}

	/**
	 * clearCart - clears the shopping cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function clearCart($strFormId, $strControlId, $strParameter) {
			Cart::ClearCart();
			_xls_display_msg(_sp("Your cart content has been cleared"));
			return;
	}



	/**
	 * cartUpdate - updates the shopping cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function cartUpdate($strFormId, $strControlId, $strParameter) {
		$this->cart = Cart::GetCart();
		if($this->cart->Count == 0){
			_xls_display_msg(_sp("Your cart is empty. Please add items into this cart."));
		}
		//if($this->cart->FkTaxCodeId)

		$this->update_order_display($this->cart , true);

		return;
	}

	/**
	 * qtyChange - Event that fires when the quantity for a line item changes, updates subtotal/total on the cart page
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function qtyChange($strFormId, $strControlId, $strParameter) {
		$itemid = $strParameter;
		$qtyBox = $this->GetControl($strControlId);

		if(($qtyBox->Text != "" . intval($qtyBox->Text))){
			QApplication::ExecuteJavaScript("alert('" . _sp("Please only enter numbers")  . "')");
			$this->dtgCart->Refresh();
			return;
		}

		Cart::UpdateCartQuantity($itemid , $qtyBox->Text);
		$this->cartUpdate($strFormId, $strControlId, $strParameter);
		$this->mainPnl->Refresh();
	}

	/**
	 * minusClick - Event that gets fired when the green minus sign is pressed on a line item to remove it from cart
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function minusClick($strFormId, $strControlId, $strParameter) {
		$minusIcon = $this->GetControl($strControlId);
		$itemid = $strParameter;
		$minusIcon = $this->GetControl($strControlId);
		Cart::UpdateCartQuantity($itemid , 0);
		$this->cartUpdate($strFormId, $strControlId, $strParameter);
		$this->mainPnl->Refresh();
	}

	/**
	 * dtgCart_Bind - Binds line items to the current cart template
	 * @param none
	 * @return none
	 */
	protected function dtgCart_Bind(){
		$this->cart = Cart::GetCart();
		$this->dtgCart->DataSource = $this->cart->getCartItems();
		$this->misc_components['order_subtotal']->Text = _xls_currency($this->cart->Subtotal);

	}

	/**
	 * showCart - Default function meant to be overriden by other functions to show the cart, default is false
	 * @param none
	 * @return none
	 */
	protected function showCart(){
		return false;
	}

	/**
	 * showDark - Default function meant to be overriden by other functions to show the darkness in the cart
	 * @param none
	 * @return none
	 */
	protected function showDark(){
		return true;
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_cart::Run('xlsws_cart', templateNamed('index.tpl.php'));
