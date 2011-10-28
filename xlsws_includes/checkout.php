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
 * xlsws_checkout class
 * This is the controller class for the checkout page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the checkout page
 */
class xlsws_checkout extends xlsws_index {
	/*see xlsws_index for shared widgets*/
	protected $lstCRShipPrevious; //input select box for previously shipped addresses

	protected $txtNotes; //input textarea for notes and comments

	protected $btnSubmit; //input submit for the submit button on the bottom

	protected $chkAgree; //input checkbox to agree to terms and conditions

	protected $chkSame; //input checkbox for shipping is the same as billing address

	protected $errSpan; //span block that displays an error on top of the checkout form if any

	protected $lblVerifyImage; //the label where the verification catpcha image loads

	protected $lblWait; //the label for the wait icon (optional)
	protected $icoWait; //the actual wait icon

	protected $lstShippingMethod; //input select box that shows applicable shipping methods to pick from

	protected $lstPaymentMethod; //input select box that shows applicable payment methods to pick from

	protected $pnlCart; //The QPanel that shows the minicart at the bottom of the checkout page
	protected $pnlCustomer; //The QPanel that shows the input fields for the customer details (name, phone email)
	protected $pnlShipping; //The QPanel that shows the shipping options or the select shipping message when no address is entered
	protected $pnlPayment; //The QPanel that shows the payment options or the select payment message when no address is entered
	protected $pnlVerify; //The QPanel that shows the verification catpcha image

	protected $pnlLoginRegister; //The QPanel that shows login and register buttons on the checkout page solely

	protected $pnlWait; //The QPanel that shows the wait icon(s)

	protected $pxyCheckout; //Handler for checkout

	protected $cart; //Instantiated Cart object of the current shopper's cart
	protected $giftRegistry; //Instantiated WishList object of the current shopper's cart relating to a registry (if purchased from there)

	protected $blnShippingShown = false; //Boolean for whether shipping is presently shown or not

	public $shipping_fields = array(); //the various fields needed for generating the shipping choice listbox
	protected $lblShippingCost; //the label that shows the cost of shipping on the checkout page
	protected $fltShippingCost = FALSE; //the amount of shipping, but default none
	protected $strShippingLastMethod = false; //the last shipping method used, by default none

	public $fltPaymentTotal; //the total payment amount
	protected $lblPaymentTotal; //the label that shows total of the order
	protected $blnPaymentShown = false; //is the total payment amount shown?
	public $payment_fields = array(); //the various fields required to generate the payment options list box
	protected $checktaxonly; //used in the shipping cost function to just do a recalculation of taxes and not every other aspect like shipping

	public $customer; //Instantiated Customer object of the current shopper's cart

	protected $butLogin; //The login button solely on the checkout page
	protected $butRegister; //The register button solely on the checkout page

	protected $butCalcShipping; //The calculate shipping button

	protected $pnlPromoCode; //the Qpanel for the promo code widget
	protected $txtPromoCode; //the textbox to enter the promo code
	protected $btnPromoVerify; //the button that applies the promo code
	protected $lblPromoErr; //the area where promo code return messages show

	/**
	 * Form_PreRender - Preloads important details needed before this checkout form can be rendered
	 * @param none
	 * @return none
	 */
	protected function Form_PreRender() {
		$this->cart = Cart::GetCart();
		$this->order_display($this->cart , $this->pnlCart);
		parent::Form_PreRender();
	}

	/**
	 * handle_order - handles an order if the complete_order parameter has been passed via GET or POST
	 * and completes the transaction if valid
	 * @param none
	 * @return none
	 */
	protected function handle_order() {
		global $XLSWS_VARS;

		try {
			Cart::LoadCartByLink($XLSWS_VARS['complete_order'] , false);
		} catch(Exception $e) {
			_xls_display_msg(_sp('Cart not be found for this order'));
			return;
		}

		$cart = Cart::GetCart();

		if($cart->Type != CartType::awaitpayment) {
			_xls_display_msg(_sp('Selected cart is not waiting for payment'));
			return;
		}

		if(isset($XLSWS_VARS['payment_data_store'])) {
			$cart->PaymentData = $XLSWS_VARS['payment_data_store'];
			Cart::SaveCart($cart);
		}

		$this->completeOrder($cart , $this->customer );

		return;
	}

	/**
	 * check_guest_checkout - check if the store is configured to allow guest checkout and
	 * whether the current client is a guest
	 * @param none
	 * @return none
	 */
	protected function check_guest_checkout() {
		$customer = Customer::GetCurrent();
		// check guest checkout
		if(!$customer && !_xls_get_conf('ALLOW_GUEST_CHECKOUT', 1))
			_xls_display_msg(_sp("You have to login to check out"), "index.php?xlspg=checkout");
	}

	/**
	 * populate_previously_shipped - if the customer is logged in, populate dropdown with previously shipped addresses
	 * @param none
	 * @return none
	 */
	protected function populate_previously_shipped() {
		if($this->customer) {
			$cart_addes = Cart::QueryArray(
				QQ::AndCondition(
					QQ::Equal(QQN::Cart()->CustomerId, $this->customer->Rowid),
					QQ::Equal(QQN::Cart()->Type, CartType::order)
				),
				QQ::Clause(
					QQ::OrderBy(QQN::Cart()->Rowid, false)
				)
			);

			if($cart_addes && (count($cart_addes) > 0)) {
				$this->lstCRShipPrevious = new XLSListBox($this->pnlShippingAdde);
				$this->lstCRShipPrevious->Width = "300px";
				$this->lstCRShipPrevious->SetCustomStyle("clear","both");
				$this->lstCRShipPrevious->DisplayStyle = "block";

				$this->lstCRShipPrevious->AddItem(_sp(" - Please Select - ") , 0);

				$adde_prev = array();

				foreach($cart_addes as $adde) {
					$a = sprintf(
						"%s %s %s %s, %s",
						$adde->ShipFirstname,
						$adde->ShipLastname,
						$adde->ShipCompany,
						$adde->ShipAddress1,
						$adde->ShipCity
					);

					if(in_array($a , $adde_prev))
						continue;

					$this->lstCRShipPrevious->AddItem($a , $adde->Rowid);
					$adde_prev[] = $a;
				}

				$this->lstCRShipPrevious->AddAction(new QChangeEvent() , new QAjaxAction('prevAdde'));
				if($this->lstCRShipPrevious->ItemCount <= 1){
					$this->lstCRShipPrevious->Visible = false;
				}
			}
		}
	}

	/**
	 * build_shipping_panel - builds the panel that holds shipping options on checkout
	 * @param none
	 * @return none
	 */
	protected function build_shipping_panel() {
		$this->pnlShipping = new XLSFieldSetBox($this);
		$this->pnlShipping->Name = _sp("Shipping");
	}

	/**
	 * build_payment_panel - builds the panel that holds payment options on checkout
	 * @param none
	 * @return none
	 */
	protected function build_payment_panel() {
		$this->pnlPayment = new XLSFieldSetBox($this);
		$this->pnlPayment->Name = _sp('Payment');
	}

	/**
	 * build_calcshiping - builds the calculate shipping button on checkout
	 * @param none
	 * @return none
	 */
	protected function build_calcshipping() {
		$this->butCalcShipping = new QButton($this->pnlShippingAdde , 'btnCalcShipping');
		$this->butCalcShipping->Text = _sp("Calculate shipping");
		$this->butCalcShipping->AddAction(new QClickEvent() , new QAjaxAction('setupShipping'));
		$this->butCalcShipping->CssClass = "button rounded";
	}

	/**
	 * build_notes - builds the notes textarea
	 * @param none
	 * @return none
	 */
	protected function build_notes() {
		$this->txtNotes = new XLSTextBox($this->pnlVerify);
		$this->txtNotes->TextMode = QTextMode::MultiLine;
	}

	/**
	 * build_login_register - builds the two login and register buttons panel
	 * @param none
	 * @return none
	 */
	protected function build_login_register() {
		$this->pnlLoginRegister = new QPanel($this->mainPnl);

		$this->butLogin = new QButton($this->pnlLoginRegister);
		$this->butLogin->Text = _sp('Login');
		$this->butLogin->AddAction(new QClickEvent() , new QAjaxAction('butLogin_Click'));


		$this->butRegister = new QButton($this->pnlLoginRegister);
		$this->butRegister->Text = _sp("Register");
		$this->butRegister->AddAction(new QClickEvent() , new QServerAction('butRegister_Click'));

		$this->pnlLoginRegister->Template = templateNamed('checkout_login_register.tpl.php');

		if($this->customer)
			$this->pnlLoginRegister->Visible = false;
	}

	/**
	 * build_promocode_widget - builds the widget to enter a promo code
	 * @param none
	 * @return none
	 */
	protected function build_promocode_widget() {
		$this->pnlPromoCode = new QPanel($this->mainPnl);
		$this->pnlPromoCode->Name = _sp("Promotional Code");
		$this->pnlPromoCode->Template = templateNamed('promo_code.tpl.php');

		$this->lblPromoErr = new QLabel($this->pnlPromoCode);
		$this->lblPromoErr->Display = false;
		$this->lblPromoErr->HtmlEntities = false;
		$this->lblPromoErr->SetCustomStyle('color','red');

		$this->txtPromoCode = new XLSTextBox($this->pnlPromoCode , 'txtPromoCode');
		$this->btnPromoVerify = new QButton($this->pnlPromoCode , 'btnPromoVerify');

		$this->btnPromoVerify->CssClass = "button rounded";
	}

	/**
	 * check_registry - checks if the purchase was made with gift registry and sets options appropriately
	 * @param none
	 * @return none
	 */
	protected function check_registry() {
		$this->giftRegistry = $this->cart->GiftRegistryObject;

		if($this->giftRegistry instanceof GiftRegistry){

			//is it ship to me?
			if($this->giftRegistry->ShipOption != 'Ship to buyer'){

				$cust = Customer::Load($this->giftRegistry->CustomerId);

				// TODO is this a security risk if the gift registry owner's address is shown?

				$this->txtCRShipAddr1->Text = $cust->Address21;
				$this->txtCRShipAddr1->ReadOnly = true;
				$this->txtCRShipAddr2->Text = $cust->Address22;
				$this->txtCRShipAddr2->ReadOnly = true;
				$this->txtCRShipCity->Text = $cust->City2;
				$this->txtCRShipCity->ReadOnly = true;
				$this->txtCRShipZip->Text  = $cust->Zip2;
				$this->txtCRShipZip->ReadOnly = true;
				// there is no readonly for select items so adding only a single item
				$this->txtCRShipCountry->RemoveAllItems();
				$this->txtCRShipCountry->AddItem(Country::LoadByCode($cust->Country2)->Country , $cust->Country2);
				$this->txtCRShipCountry->SelectedValue = $cust->Country2;

				$this->txtCRShipState->RemoveAllItems();
				$this->txtCRShipState->AddItem(State::LoadByCountryCodeCode( $cust->Country2, $cust->State2)->State , $cust->State2);
				$this->txtCRShipState->SelectedValue = $cust->State2;


				$this->txtCRShipFirstname->Text  = $cust->Firstname;
				$this->txtCRShipFirstname->ReadOnly = true;
				$this->txtCRShipLastname->Text  = $cust->Lastname;
				$this->txtCRShipLastname->ReadOnly = true;
				$this->txtCRShipCompany->Text  = $cust->Company;
				$this->txtCRShipCompany->ReadOnly = true;

				$this->txtCRShipPhone->Text = $cust->Mainphone;
				$this->txtCRShipPhone->ReadOnly = true;

				$this->chkSame->Checked = false;
				$this->chkSame->Visible = false;
				$this->pnlShippingAdde->Visible = true;
			}
		}
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_email_widget($this->pnlCustomer);

		//billing details
		$this->build_fname_widget($this->pnlCustomer , 'firstname');
		$this->build_lname_widget($this->pnlCustomer , 'lastname');
		$this->build_company_widget($this->pnlCustomer , 'company');
		$this->build_phone_widget($this->pnlCustomer , 'phone');
		$this->build_add1_widget($this->pnlBillingAdde , 'billstreet1');
		$this->build_add2_widget($this->pnlBillingAdde , 'billstreet2');
		$this->build_country_widget($this->pnlBillingAdde , 'billcountry');
		$this->build_state_widget($this->pnlBillingAdde , 'billstate');
		$this->build_city_widget($this->pnlBillingAdde , 'billcity');
		$this->build_zip_widget($this->pnlBillingAdde , 'billzip');
		$this->build_shipsame_widget();

		//shipping details
		$this->build_fname_widget($this->pnlShippingAdde  , 'shipfirstname');
		$this->build_lname_widget($this->pnlShippingAdde  , 'shiplastname');
		$this->build_company_widget($this->pnlShippingAdde  , 'shipcompany');
		$this->build_phone_widget($this->pnlShippingAdde , 'shipphone');
		$this->build_add1_widget($this->pnlShippingAdde , 'shipstreet1');
		$this->build_add2_widget($this->pnlShippingAdde , 'shipstreet2');
		$this->build_country_widget($this->pnlShippingAdde , 'shipcountry');
		$this->build_state_widget($this->pnlShippingAdde , 'shipstate');
		$this->build_city_widget($this->pnlShippingAdde , 'shipcity');
		$this->build_zip_widget($this->pnlShippingAdde , 'shipzip');
		$this->build_promocode_widget();
		$this->build_captcha_widget($this->mainPnl);
		$this->build_notes();
	}


	/**
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->txtCRBillCountry->AddAction(new QChangeEvent(), new QAjaxAction('txtBillCountry_Change'));

		$this->txtCRBillAddr1->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRBillAddr2->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRBillState->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRBillCity->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRBillZip->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRBillCountry->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRFName->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRLName->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));
		$this->txtCRCompany->AddAction(new QChangeEvent(), new QAjaxAction('BillAddrChange'));

		$this->txtCRShipAddr1->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipAddr2->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipState->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipCity->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipZip->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipCountry->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipFirstname->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipLastname->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipCompany->AddAction(new QChangeEvent(), new QAjaxAction('setupShipping'));
		$this->txtCRShipCountry->AddAction(new QChangeEvent() , new QAjaxAction('shipCountry_Change'));

		$this->butCalcShipping->AddAction(new QClickEvent() , new QAjaxAction('setupShipping'));
		$this->btnPromoVerify->AddAction(new QClickEvent() , new QAjaxAction('validatePromoCode'));
		$this->btnPromoVerify->AddAction(new QClickEvent() , new QToggleEnableAction($this->btnPromoVerify, false));

		$this->btnPromoVerify->AddAction(new QClickEvent() , new QToggleEnableAction($this->txtPromoCode, false));
	}

	/**
	 * checkLoginShippingFields - checks and populates shipping address fields for if a client
	 * has an already entered shipping address
	 * @param none
	 * @return none
	 */
	private function checkLoginShippingFields() {
		if($this->cart->ShipFirstname != '')
			$this->txtCRShipFirstname->Text=$this->cart->ShipFirstname;
		elseif($this->customer)
			$this->txtCRShipFirstname->Text=$this->customer->Firstname;

		if($this->cart->ShipLastname != '')
			$this->txtCRShipLastname->Text=$this->cart->ShipLastname;
		elseif($this->customer)
			$this->txtCRShipLastname->Text=$this->customer->Lastname;

		if($this->cart->ShipPhone != '')
			$this->txtCRShipPhone->Text=$this->cart->ShipPhone;
		elseif($this->customer)
			$this->txtCRShipPhone->Text=$this->customer->Mainphone;

		if($this->cart->ShipCompany != '')
			$this->txtCRShipCompany->Text=$this->cart->ShipCompany;
		elseif($this->customer)
			$this->txtCRShipCompany->Text=$this->customer->Company;

		//Address1
		if($this->cart->ShipAddress1 != '')
			$this->txtCRShipAddr1->Text=$this->cart->ShipAddress1;
		elseif($this->customer)
			$this->txtCRShipAddr1->Text=$this->customer->Address21;

		//Address2
		if($this->cart->ShipAddress2 != '')
			$this->txtCRShipAddr2->Text=$this->cart->ShipAddress2;
		elseif($this->customer)
			$this->txtCRShipAddr2->Text=$this->customer->Address22;

		//Country
		if($this->cart->ShipCountry != '')
			$this->txtCRShipCountry->SelectedValue=$this->cart->ShipCountry;
		elseif($this->customer)
			$this->txtCRShipCountry->SelectedValue=$this->customer->Country2;
		else
			$this->txtCRShipCountry->SelectedValue=_xls_get_conf('DEFAULT_COUNTRY');

		if($this->customer || $this->cart->ShipState){
			if($this->cart->ShipState)
				$this->txtCRShipState->SelectedValue=$this->cart->ShipState;
			else
				$this->txtCRShipState->SelectedValue=$this->customer->State2;
		}
		$this->txtCRShipState->Name = _sp('State');

		//City
		if($this->cart->ShipCity != '')
			$this->txtCRShipCity->Text=$this->cart->ShipCity;
		elseif($this->customer)
			$this->txtCRShipCity->Text=$this->customer->City2;

		// Postal/Zip Code
		if($this->cart->ShipZip != '')
			$this->txtCRShipZip->Text=$this->cart->ShipZip;
		elseif($this->customer)
			$this->txtCRShipZip->Text=$this->customer->Zip2;
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS;

		$customer = Customer::GetCurrent();

		$this->checktaxonly = false;

		if(!$customer)
			$this->customer = null;
		else{
			$this->customer = $customer;
			Cart::UpdateCartCustomer(); // load the customer tax in!
		}

		if(isset($XLSWS_VARS['complete_order'])) {
			$this->handle_order();
			return;
		}

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('checkout.tpl.php');

		// setup the cart
		$this->pnlCart = new QPanel($this->mainPnl);
		$this->pnlCart->Name = _sp("Cart");
		$this->cart = Cart::GetCart();

		$this->order_display($this->cart , $this->pnlCart);

		if($this->cart->Count == 0)
			_xls_display_msg(_sp("Your cart is empty. Please add items to your cart before you check out."));

		$this->check_guest_checkout();

		// Wait icon
		$this->objDefaultWaitIcon = new QWaitIcon($this);

		$this->crumbs[] = array('key'=>'xlspg=cart' , 'case'=> '' , 'name'=> _sp('Cart'));
		$this->crumbs[] = array('key'=>'xlspg=checkout' , 'case'=> '' , 'name'=> _sp('Check Out'));

		// Define the layout

		//error msg
		$this->errSpan = new QPanel($this);
		$this->errSpan->CssClass='customer_reg_err_msg';

		$this->pnlCustomer = new QPanel($this->mainPnl);
		$this->pnlCustomer->Template = templateNamed("checkout_reg_account_info.tpl.php");

		$this->build_shipping_panel();
		$this->build_payment_panel();

		$this->pnlBillingAdde = new QPanel($this->mainPnl);
		$this->pnlBillingAdde->Template = templateNamed('reg_billing_address.tpl.php');
		$this->pnlBillingAdde->CssClass = "c1";

		$this->pnlShippingAdde = new QPanel($this->mainPnl);
		$this->pnlShippingAdde->Template = templateNamed('reg_shipping_address.tpl.php');
		$this->pnlShippingAdde->CssClass = "c2";

		$this->build_widgets();

		$this->build_calcshipping();

		//************ Shipping info
		$this->checkLoginShippingFields();

		//************ Change events
		$this->bind_widgets();

		// Previously shipped addresses
		$this->populate_previously_shipped();

		// Gift Registry check!
		$this->check_registry();

		/*The below attributes are not intended to be directly overloaded or modified*/
		// Checkout agree
		$this->chkAgree = new QCheckBox($this->pnlVerify);

		//submit order button
		$this->btnSubmit = new QButton($this->pnlVerify);
		$this->btnSubmit->Text = _sp('Submit Order');
		$this->btnSubmit->CausesValidation = true;
		$this->btnSubmit->PrimaryButton = true;

		$this->btnSubmit->AddAction(new QClickEvent(), new QServerAction('btnSubmit_Click'));

		// Wait
		$this->pnlWait = new QPanel($this->mainPnl);
		$this->pnlWait->Visible = false;
		$this->pnlWait->AutoRenderChildren = true;

		$this->lblWait = new QLabel($this->pnlWait);
		$this->lblWait->Text = _sp("Please wait while we process your order");
		$this->lblWait->CssClass = "checkout_process_label";

		$this->icoWait = new QWaitIcon($this->pnlWait);

		$this->pxyCheckout = new QButton($this->mainPnl , 'pxyCheckout');
		$this->pxyCheckout->CssClass = "xlshidden";
		$this->pxyCheckout->AddAction(new QClickEvent(500) , new QServerAction('processCheckout'));
		$this->build_login_register();

		$this->setupShipping();

		$this->setupPayment(true);
	}

	/**
	 * butLogin_Click - Event that gets fired when someone presses login on the checkout page, shows the login modal box
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function butLogin_Click($strFormId, $strControlId, $strParameter) {
		_xls_stack_add('login_redirect_uri' , "index.php?xlspg=checkout");

		$this->dxLogin->Visible = true;
	}

	/**
	 * butRegister_Click - Event that gets fired when someone presses register on the checkout page, redirects to customer register if not logged in
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function butRegister_Click($strFormId, $strControlId, $strParameter) {
		_xls_stack_add('register_redirect_uri' , "index.php?xlspg=checkout");

		_rd("index.php?xlspg=customer_register");
	}

	/**
	 * prevAdde - Event that gets fired when someone selects a previously shipped address, populating shipping input fields
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function prevAdde($strFormId, $strControlId, $strParameter) {
		if(!$this->lstCRShipPrevious)
			return;

		if(!$this->lstCRShipPrevious->SelectedValue)
			return;

		$cart_adde = Cart::Load($this->lstCRShipPrevious->SelectedValue);

		if(!$cart_adde)
			return;

		if($cart_adde->CustomerId != $this->customer->Rowid)
			return;

		$this->txtCRShipFirstname->Text = $cart_adde->ShipFirstname;
		$this->txtCRShipLastname->Text = $cart_adde->ShipLastname;
		$this->txtCRShipCompany->Text = $cart_adde->ShipCompany;
		$this->txtCRShipAddr1->Text = $cart_adde->ShipAddress1;
		$this->txtCRShipAddr2->Text = $cart_adde->ShipAddress2;
		$this->txtCRShipCity->Text = $cart_adde->ShipCity;
		$this->txtCRShipPhone->Text  = $cart_adde->ShipPhone;
		$this->txtCRShipCountry->SelectedValue = $cart_adde->ShipCountry;

		$this->shipCountry_Change($strFormId, $strControlId, $strParameter); // this will load the appropriate states

		$this->txtCRShipState->SelectedValue = $cart_adde->ShipState;
		$this->txtCRShipZip->Text = $cart_adde->ShipZip;

		$this->setupShipping();
	}

	/**
	 * validatePromoCode - Validates and applies an entered promo code if applicable
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function DisplayPromoErrWidget($strMessage) {
		$this->lblPromoErr->Text = $strMessage;
		$this->lblPromoErr->Display = true;
		$this->btnPromoVerify->Enabled = true;
		$this->txtPromoCode->Enabled = true;
	}

	protected function validatePromoCode(
		$strFormId, $strControlId, $strParameter) {

		$bolPromoApplied = false;
		$objPromoCode = PromoCode::LoadByCode($this->txtPromoCode->Text);
		$discountType = PromoCodeType::Flat;

		if (!$objPromoCode) {
			$this->DisplayPromoErrWidget(_sp('Invalid Promo Code'));
			return;
		}

		if ($this->cart->FkPromoId > 0) {
			$this->DisplayPromoErrWidget(_sp('A Promo Code has already' .
				' been applied to this order'));
			return;
		}

		if (!$objPromoCode->Started) {
			$this->DisplayPromoErrWidget(_sp('Promo Code is not ' .
				'active yet'));
			return;
		}

		if ($objPromoCode->Expired || !$objPromoCode->HasRemaining) {
			$this->DisplayPromoErrWidget(_sp('Promo Code has expired' .
				' or has been used up.'));
			return;
		}

		if ($objPromoCode->Threshold > $this->cart->Subtotal) {
			$this->DisplayPromoErrWidget(_sp(
				'Promo Code only valid when cart exceeds ' .
				_xls_currency($objPromoCode->Threshold) . '.'));
			return;
		}

		$this->cart->FkPromoId = $objPromoCode->Rowid;
		$bolPromoApplied = $this->cart->UpdatePromoCode(true);

		if ($bolPromoApplied) {
			$this->cart->UpdateCart();

			if($this->lblPaymentTotal instanceof QLabel)
				$this->lblPaymentTotal->Text =
					_xls_currency($this->cart->Total);

			$this->DisplayPromoErrWidget(sprintf(
				_sp('Promo Code applied at') . " %s",
				PromoCodeType::Display($objPromoCode->Type,
				$objPromoCode->Amount)));

			$this->update_order_display($this->cart);
			$this->pnlCart->Refresh();
			$this->setupShipping();
		} else {
			$this->DisplayPromoErrWidget(_sp('Promo Code does not apply' .
				' to your cart.'));
		}
	}

	/**
	 * shipping_elements - Enable or disable shipping address fields on the checkout form dynamically
	 * @param boolean true or false to enable or disable an element
	 * @return none
	 */
	protected function shipping_elements($enable) {
		$this->txtCRShipFirstname->Enabled=$enable;
		$this->txtCRShipLastname->Enabled = $enable;
		$this->txtCRShipCompany->Enabled=$enable;
		$this->txtCRShipPhone->Enabled=$enable;
		$this->txtCRShipAddr1->Enabled = $enable;
		$this->txtCRShipAddr2->Enabled = $enable;
		$this->txtCRShipCountry->Enabled = $enable;
		$this->txtCRShipState->Enabled = $enable;
		$this->txtCRShipCity->Enabled = $enable;
		$this->txtCRShipZip->Enabled = $enable;

		if($this->lstCRShipPrevious)
			$this->lstCRShipPrevious->Enabled = $enable;
	}

	/**
	 * setupShipping - Sets up the shipping cost based on the information the client has entered
	 * @param none
	 * @return none
	 */
	public function setupShipping(){
		// shipping influences the taxcode used, because this is country/state dependant.
		// however, there is a special case when using store pickup -- in these cases, no matter the ship address, local ("default") taxcode applies...
		// check for this first:

		$taxCodeId = -1; // the default/indicates failure to lookup.
		if ($this->lstShippingMethod) {
			// we seem to have a selected shipping method.
			if ($this->lstShippingMethod->SelectedValue == XLS_STORE_PICKUP_SHIPPINGMETHOD_SELECTVALUE) {
				// it *is* the store pickup method, so our default tax code will apply
				$defTaxCode = _xls_tax_default_taxcode();
				$this->cart->blnStorePickup = true;
				if ($defTaxCode) {
					$taxCodeId = $defTaxCode->Rowid;
				}
			}
		}

		$country = Country::LoadByCode($this->txtCRShipCountry->SelectedValue);
		if ($country) {
			$this->txtCRShipZip->Validate($country->ZipValidatePreg);
			$this->txtCRShipZip->Refresh();
		}

		if ($taxCodeId < 0) {
			// taxCodeId was _not_ set by selecting store_pickup so we use the destination if we can...

			$dest = Destination::LoadMatching(
				$this->txtCRShipCountry->SelectedValue,
				$this->txtCRShipState->SelectedValue,
				$this->txtCRShipZip->Text);

			if($dest)
				$taxCodeId = $dest->Taxcode;
		}

		$this->cart->FkTaxCodeId = $taxCodeId;
		$this->cart->UpdateCart();
		$this->cart->SyncSave();

		if ($this->checktaxonly) {
			$this->checktaxonly = false;
			return;
		}
		$select = false;
		$this->pnlShipping->RemoveChildControls(true);
		$this->lblShippingCost = new QLabel($this->pnlShipping);

		// do I at least have country and postcode?
		if(trim($this->txtCRShipCountry->SelectedValue) == '' || trim($this->txtCRShipZip->Text) == '' ){
			$this->lblShippingCost->Text = _sp('Please provide shipping address (Country, State , Zip/Postal Code) to receive a shipping quote.');

			$this->blnShippingShown = false;
			$this->shipping_fields = array();
			return;
		}

		$this->pnlShipping->RemoveChildControls(true);

		$this->lstShippingMethod = new XLSListBox($this->pnlShipping);
		$this->lstShippingMethod->Name = _sp('Choose Shipping Method');
		$this->lstShippingMethod->CssClass = "checkout_shipping_select";

		$this->objDefaultWaitIcon = new QWaitIcon($this->pnlShipping);
		$objSubmitListItemActions = array(
			new QToggleEnableAction($this->lstShippingMethod),
			new QAjaxAction('shippingMethod_Change'),
		);

		$this->lstShippingMethod->AddActionArray(new QChangeEvent(), $objSubmitListItemActions);

		// get shipping methods - sorted!
		$shippingModules = Modules::QueryArray(
			QQ::Equal(QQN::Modules()->Type, 'shipping'),
			QQ::Clause(
				QQ::OrderBy(QQN::Modules()->SortOrder)
			)
		);

		if(count($shippingModules) ==  0)
			return;

		$current = current($shippingModules);

		foreach($shippingModules as $s) {
			$obj = $this->loadModule($s->File , 'shipping');

			if(!$obj) // could not load class
				continue;

			if(!$obj->check())
				continue;

			$msg = $obj->name();

			$this->lstShippingMethod->AddItem($msg , $s->File);
		}

		$select = _xls_stack_get('xlsws_shipping_method');

		if(!$select) {
			$this->lstShippingMethod->SelectedIndex = 0;
		} else
			$this->lstShippingMethod->SelectedValue = $select;

		if(!$this->lstShippingMethod->SelectedValue)
			return;

		$obj = $this->loadModule($this->lstShippingMethod->SelectedValue, 'shipping');

		// only add fields if this shipping module is being drawn fresh!
		//if(!$this->shipping_fields || (count($this->shipping_fields) == 0) || ($this->lstShippingMethod->SelectedValue != $this->strShippingLastMethod)){
		$this->shipping_fields = $obj->customer_fields($this->pnlShipping);

		foreach($this->shipping_fields as $field) {
			$field->AddAction(new QChangeEvent() , new QAjaxAction('shippingCost'));
			$key = "SHIPPING " . $this->lstShippingMethod->SelectedValue . " " . $field->Name;

			if($val = _xls_stack_get($key)) {

				if($field instanceof QListBoxBase )
					$field->SelectedValue = $val;
				elseif($field instanceof QTextBoxBase )
					$field->Text = $val;

			}

		}

		$this->strShippingLastMethod = $this->lstShippingMethod->SelectedValue;
		//}

		$this->lblShippingCost = new QLabel($this->pnlShipping);


		$this->pnlShipping->Refresh();

		$this->blnShippingShown = true;

		// load up saved up field values

		$this->shippingCost();

		$this->setupPayment(true);
	}

	/**
	 * shippingCost - Fetch the cost of shipping based on the method/choice chosen
	 * @param none
	 * @return none
	 */
	protected function shippingCost() {
		$obj = $this->loadModule($this->lstShippingMethod->SelectedValue , 'shipping');

		if(!$obj) // could not load class
			return;

		if(!$obj->check())
			return;

		// remember the fields for holding
		foreach($this->shipping_fields as $field) {
			$key = "SHIPPING " . $this->lstShippingMethod->SelectedValue . " " . $field->Name;
			_xls_stack_pop($key);

			if($field instanceof QListBoxBase)
				_xls_stack_add( $key ,  $field->SelectedValue);
			elseif($field instanceof QTextBoxBase )
				_xls_stack_add( $key ,  $field->Text);
		}

		$cart = Cart::GetCart();

		// Cost for shipping?
		$total = $obj->total(
			$this->shipping_fields,
			$cart,
			$this->txtCRShipCountry->SelectedValue,
			$this->txtCRShipZip->Text,
			$this->txtCRShipState->SelectedValue,
			$this->txtCRShipCity->Text,
			$this->txtCRShipAddr2->Text,
			$this->txtCRShipAddr1->Text,
			$this->txtCRShipCompany->Text,
			$this->txtCRShipLastname->Text,
			$this->txtCRShipFirstname->Text
		);

		$sproduct = '';
		$markup = 0;

		if($total === FALSE) { // no shipping available..

			$this->fltShippingCost = FALSE;
			$this->lblShippingCost->Text = _sp('Error: Unable to get shipping rates');

		} elseif(is_numeric($total)) {
			$this->fltShippingCost = $total;
			$this->lblShippingCost->Text = _xls_currency($total);
		} elseif(is_array($total)) {
			if(isset($total['price']))
				$this->fltShippingCost = $total['price'];
			else
				$this->fltShippingCost = FALSE;

			if(isset($total['msg']) && $total['msg']  && !($this->fltShippingCost === FALSE))
				$this->lblShippingCost->Text =_xls_currency($this->fltShippingCost) . " - " . $total['msg'];
			elseif(isset($total['msg']) && $total['msg']) // probably an error message
				$this->lblShippingCost->Text = $total['msg'];
			elseif($this->fltShippingCost)
				$this->lblShippingCost->Text = _xls_currency($total['price']);
			else
				$this->lblShippingCost->Text = '';

			if(isset($total['product']))
				$sproduct = $total['product'];

			if(isset($total['markup']))
				$markup = $total['markup'];
		} else {
			$this->fltShippingCost = FALSE;
			$this->lblShippingCost->Text = _sp('Error: Unable to get shipping rates');
			_xls_log(
				"ERROR: Could not determine return type for module " .
				$this->lstShippingMethod->SelectedValue .
				". Expected either number OR array('price' => ,  'msg' =>). Return given : " .
				print_r($total , true)
			);
		}

		$this->addShippingToPaymentTotal($sproduct , $markup);
	}

	/**
	 * shippingMethod_Change - Event that fetches the cost of shipping dynamically based on the method/choice chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function shippingMethod_Change($strFormId, $strControlId, $strParameter) {
		$selected = $this->lstShippingMethod->SelectedValue;

		// save the currently selected shipping method
		_xls_stack_add('xlsws_shipping_method' , $selected);

		$this->setupShipping();
	}

	/**
	 * shipCountry_Change - Event that fetches the cost of shipping and populates appropriate states for the shipping
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function shipCountry_Change($strFormId, $strControlId, $strParameter) {
		$country_code = $this->txtCRShipCountry->SelectedValue;

		if ($country_code) {
			$this->add_states_to_listbox_for_country($this->txtCRShipState, $country_code);

			if($this->chkSame->Checked) {
				$this->txtCRBillCountry->SelectedValue=$this->txtCRShipCountry->SelectedValue;
				$country_code = $this->txtCRShipCountry->SelectedValue;
				$this->add_states_to_listbox_for_country($this->txtCRBillState, $country_code);
			}

			$this->txtCRShipZip->Text = '';
		}
	}

	/**
	 * txtBillCountry_Change - Event that fetches the cost of shipping and populates appropriate states for the billing
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function txtBillCountry_Change($strFormId, $strControlId, $strParameter) {
		$country_code = $this->txtCRBillCountry->SelectedValue;

		if ($country_code) {
			if ($this->add_states_to_listbox_for_country($this->txtCRBillState, $country_code))
				$this->txtCRBillState->focus();

			if($this->chkSame->Checked) {
				$this->txtCRShipCountry->SelectedValue = $this->txtCRBillCountry->SelectedValue;
				$this->add_states_to_listbox_for_country($this->txtCRShipState, $country_code);
			}
		}

		$this->txtCRBillZip->Text = '';
	}

	/**
	 * addShippingToPaymentTotal - Adds the current shipping cost to the subtotal
	 * @param Product obj shipping product
	 * @param float markup to add to shipping cost
	 * @return none
	 */
	public function addShippingToPaymentTotal($sproduct = FALSE, $markup = FALSE) {
		$this->cart->ShippingSell = ($this->fltShippingCost === FALSE)?0:$this->fltShippingCost;

		if(!($markup === FALSE))
			$this->cart->ShippingCost = $this->cart->ShippingSell - $markup;
		elseif($this->cart->ShippingCost > 0)
			$this->cart->ShippingCost = $this->cart->ShippingCost;
		else
			$this->cart->ShippingCost = $this->cart->ShippingSell;

		if(!($sproduct === FALSE))
			$this->cart->ShippingMethod = $sproduct;

		$this->cart->UpdateCart();

		Cart::SaveCart($this->cart);

		$this->update_order_display($this->cart);

		$this->pnlCart->Refresh();

		if($this->lblPaymentTotal instanceof QLabel)
			$this->lblPaymentTotal->Text = _xls_currency($this->cart->Total);
	}

	/**
	 * setupPayment - Sets up the payment options available
	 * @param boolean remove - not used, ignore
	 * @return none
	 */
	public function setupPayment($remove = false) {
		// without shipping no payment thankyou

		if(!$this->blnShippingShown) {
			$this->blnPaymentShown = false;
			$this->pnlPayment->Visible = false;
			return;
		}

		if($remove)
			$this->pnlPayment->RemoveChildControls(true);

		$this->pnlPayment->Visible =  true;

		$this->lblPaymentTotal = new QLabel($this->pnlPayment);
		$this->lblPaymentTotal->Name = _sp('Total Payable');

		$this->addShippingToPaymentTotal();

		$this->lstPaymentMethod = new XLSListBox($this->pnlPayment);
		$this->lstPaymentMethod->Name = _sp('Choose Payment Method');
		$this->lstPaymentMethod->CssClass = "checkout_payment_select";

		$this->lstPaymentMethod->AddAction(new QChangeEvent() , new QJavaScriptAction("this.disabled = true"));

		$this->lstPaymentMethod->AddAction(new QChangeEvent() , new QAjaxAction('paymentMethod_Change'));

		// get payment methods - sorted!
		$paymentModules = Modules::QueryArray(
			QQ::Equal(QQN::Modules()->Type, 'payment'),
			QQ::Clause(
				QQ::OrderBy(QQN::Modules()->SortOrder)
			)
		);

		if(count($paymentModules) ==  0)
			return;

		$current = current($paymentModules);

		foreach($paymentModules as $p) {
			$obj = $this->loadModule($p->File , 'payment');

			if(!$obj) // could not load class
				continue;

			$msg = $obj->name() ;

			if(!$obj->check())
				continue;

			$this->lstPaymentMethod->AddItem($msg , $p->File);
		}

		$select = _xls_stack_get('xlsws_payment_method');

		if(!$select)
			$this->lstPaymentMethod->SelectedValue = $current->File;
		else
			$this->lstPaymentMethod->SelectedValue = $select;

		$obj = $this->loadModule($this->lstPaymentMethod->SelectedValue, 'payment');

		if($obj) {
			$this->payment_fields = $obj->customer_fields($this->pnlPayment);
		}

		$this->pnlPayment->Refresh();
		$this->blnPaymentShown = true;

		if ($this->customer) {
			$this->checktaxonly = true;
			$this->setupShipping();
		}
	}

	/**
	 * paymentMethod_Change - Sets up fields needed to accept a particular payment method dynamically
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function paymentMethod_Change($strFormId, $strControlId, $strParameter) {
		$selected = $this->lstPaymentMethod->SelectedValue;
		$this->pnlPayment->RemoveChildControls(true);

		// save the currently selected shipping method
		_xls_stack_add('xlsws_payment_method' , $selected);

		$this->setupPayment(false);
	}

	/**
	 * moduleActionProxy - General function to load particular modules for payment and shipping to be used dynamically
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	// Use ActionHolder to respond to all custom actions
	public function moduleActionProxy($strFormId, $strControlId, $strParameter) {
		$control = $this->GetControl($strControlId);

		$found = false;
		// is it shipping?
		foreach($this->shipping_fields as $field) {
			if($field->ControlId == $strControlId) {
				$found = 'shipping';
				break;
			}
		}

		if(!$found) {
			// is it payment?
			foreach($this->payment_fields as $field) {
				if($field->ControlId == $strControlId) {
					$found = 'payment';
					break;
				}
			}

			$objModule = $this->loadModule($this->lstPaymentMethod->SelectedValue , 'payment');
		} else {
			$objModule = $this->loadModule($this->lstShippingMethod->SelectedValue , 'shipping');
		}

		if(!$found || !$objModule) {
			_xls_log("ERROR could not determine source of action $strParameter .");
			return;
		}

		try {
			$objModule->$strParameter($strFormId, $strControlId, $strParameter);
		} catch(Exception $e) {
			_xls_log("ERROR catching action $strParameter on module");
		}

	}

	/**
	 * chkSame_Click - Event handler for when someone checks shipping address is the same as billing address
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function chkSame_Click($strFormId, $strControlId, $strParameter) {
		if(($this->giftRegistry) && ($this->giftRegistry->ShipOption != 'Ship to buyer')) {
			return;
		}

		if($this->chkSame->Checked) {
			if($this->lstCRShipPrevious)
				$this->lstCRShipPrevious->SelectedIndex = 0;

			$this->txtCRShipFirstname->Text=$this->txtCRFName->Text;
			$this->txtCRShipLastname->Text = $this->txtCRLName->Text;
			$this->txtCRShipCompany->Text=$this->txtCRCompany->Text;
			$this->txtCRShipPhone->Text=$this->txtCRMPhone->Text;

			$this->txtCRShipAddr1->Text = $this->txtCRBillAddr1->Text;
			$this->txtCRShipAddr2->Text = $this->txtCRBillAddr2->Text;
			$this->txtCRShipCountry->SelectedValue = $this->txtCRBillCountry->SelectedValue;

			$country_code = $this->txtCRShipCountry->SelectedValue;
			$this->add_states_to_listbox_for_country($this->txtCRShipState, $country_code);

			$this->txtCRShipState->SelectedValue = $this->txtCRBillState->SelectedValue;
			$this->txtCRShipCity->Text = $this->txtCRBillCity->Text;
			$this->txtCRShipZip->Text = $this->txtCRBillZip->Text;

			$this->shipping_elements(false);
			$this->pnlShippingAdde->Opacity = 50;

			$this->setupShipping();
		} else {
			$this->shipping_elements(true);

			$this->pnlShippingAdde->Opacity = 100;
		}
	}

	/**
	 * BillAddrChange - Event handler for when someone changes information about their billing address
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function BillAddrChange($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked) {
			$this->txtCRShipFirstname->Text=$this->txtCRFName->Text;
			$this->txtCRShipLastname->Text = $this->txtCRLName->Text;
			$this->txtCRShipCompany->Text=$this->txtCRCompany->Text;
			$this->txtCRShipPhone->Text=$this->txtCRMPhone->Text;

			$this->txtCRShipAddr1->Text=$this->txtCRBillAddr1->Text;
			$this->txtCRShipAddr2->Text=$this->txtCRBillAddr2->Text;
			$this->txtCRShipCity->Text=$this->txtCRBillCity->Text;
			$this->txtCRShipZip->Text=$this->txtCRBillZip->Text;
			$this->txtCRShipState->SelectedValue=$this->txtCRBillState->SelectedValue;
			$this->txtCRShipCountry->SelectedValue=$this->txtCRBillCountry->SelectedValue;

			$this->setupShipping();

			//if(!$this->blnPaymentShown)
			//	$this->setupPayment();
		}

	}

	/**
	 * Form_Validate - Validates all form fields for valid input
	 * @param none
	 * @return none
	 */
	protected function Form_Validate() {
		$this->errSpan->Text='';
		$this->errSpan->CssClass='customer_reg_err_msg';

		$errors = array();

		if(_xls_verify_img_txt() != (($this->txtCRVerify->Text)))
			$errors[] = _sp("Wrong Verification Code.");

		if (
			$this->txtCREmail->Text == "" ||
			$this->txtCRMPhone->Text == "" ||
			$this->txtCRFName->Text == "" ||
			$this->txtCRLName->Text == "" ||
			$this->txtCRBillAddr1->Text == "" ||
			$this->txtCRBillCountry->SelectedValue == "" ||
			$this->txtCRBillCity->Text == "" ||
			$this->txtCRBillZip->Text == ""
		) {
			$errors[] = _sp('Please complete the required fields marked with an asterisk *');
		}

		if(!isValidEmail($this->txtCREmail->Text )) {
			$email=$this->txtCREmail->Text;
			$errors[] = $email . _sp(" - Is Not A Correct E-mail Address");
		}

		// validate zip code
		$country = Country::LoadByCode($this->txtCRBillCountry->SelectedValue);
		if ($country)
			if (!$this->txtCRBillZip->Validate($country->ZipValidatePreg))
				$errors[] = _sp($this->txtCRBillZip->LabelForInvalid);

		if ($this->txtCRBillCountry->SelectedValue !=
			$this->txtCRShipCountry->SelectedValue)
		$country = Country::LoadByCode($this->txtCRShipCountry->SelectedValue);

		if ($country)
			if (!$this->txtCRShipZip->Validate($country->ZipValidatePreg))
				$errors[] = _sp($this->txtCRShipZip->LabelForInvalid);

		// Can we ship to given address?
		if(_xls_get_conf('SHIP_RESTRICT_DESTINATION' , 0) == 1){

			$dest = Destination::LoadMatching(
				$this->txtCRShipCountry->SelectedValue,
				$this->txtCRShipState->SelectedValue,
				$this->txtCRShipZip->Text
			);

			if(!$dest) {
				$errors[] = sprintf(
					_sp("Sorry, we cannot ship to %s %s %s"),
					$this->txtCRShipState->SelectedName,
					$this->txtCRShipZip->Text,
					$this->txtCRShipCountry->SelectedName
				);
			}

		}

		if ($country) {
			# means we have a country code set in txtCRShipCountry
			$states = $this->states_for_country_code($this->txtCRShipCountry->SelectedValue);

			if (count($states)) {
				if (!$this->txtCRShipState->SelectedValue) {
					$errors[] = _sp("Must select a state/province for this shipping destination country");
				}
			}
		}

		if($this->fltShippingCost === FALSE) {
			$errors[] =  _sp("Shipping error. Please choose a valid shipping method.");
		}

		// validate shipping fields
		$shipModule = $this->lstShippingMethod->SelectedValue;

		if(!$shipModule) {
			$errors[] =  _sp("No shipping method selected");
		} else {
			$shipObj = $this->loadModule($shipModule , 'shipping');

			if (! $shipObj) {
				$errors[] = _sp("No shipping method selected");
			} elseif (! $shipObj->check_customer_fields($this->shipping_fields)) {
				$errors[] =  _sp("Shipping error");
			}
		}

		// validate payment fields
		$paymentModule = $this->lstPaymentMethod->SelectedValue;

		if(!$paymentModule) {
			$errors[] =  _sp("No payment method available");
		} else {
			$paymentObj = $this->loadModule($paymentModule , 'payment');

			if(!$paymentObj->check_customer_fields($this->payment_fields)) {
				$errors[] =  _sp("Payment error");
			}
		}

		if(!$this->chkAgree->Checked) {
			$errors[] =  _sp("You must agree to terms and conditions to place an order");
		}

		if (count($errors)) {
			$this->errSpan->Text = join('<br />', $errors);

			return false;
		}

		$this->errSpan->Text='';
		return true;
	}

	/**
	 * btnSubmit_Click - Submits the checkout form
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function btnSubmit_Click($strFormId, $strControlId, $strParameter) {
		// hide all panels
		$this->pnlBillingAdde->Visible = false;
		$this->pnlCart->Visible = false;
		$this->pnlCustomer->Visible = false;
		$this->pnlPayment->Visible = false;
		$this->pnlShipping->Visible = false;
		$this->pnlShippingAdde->Visible = false;
		$this->pnlVerify->Visible = false;
		$this->pnlLoginRegister->Visible = false;
		$this->pnlPromoCode->Visible = false;

		$this->cart->ShipFirstname = $this->txtCRShipFirstname->Text;
		$this->cart->ShipLastname = $this->txtCRShipLastname->Text;
		$this->cart->ShipCompany = $this->txtCRShipCompany->Text;
		$this->cart->ShipAddress1 = $this->txtCRShipAddr1->Text;
		$this->cart->ShipAddress2 = $this->txtCRShipAddr2->Text;
		$this->cart->ShipCity = $this->txtCRShipCity->Text;
		$this->cart->ShipZip = $this->txtCRShipZip->Text;
		$this->cart->ShipState = $this->txtCRShipState->SelectedValue;
		$this->cart->ShipCountry = $this->txtCRShipCountry->SelectedValue;
		$this->cart->ShipPhone = $this->txtCRShipPhone->Text;
		$this->cart->PrintedNotes = $this->txtNotes->Text;

		if(trim($this->cart->Currency) == '')
			$this->cart->Currency = _xls_get_conf('CURRENCY_DEFAULT' , 'USD');

		$this->cart->SyncSave();

		// show wait panel
		$this->pnlWait->Visible = true;

		QApplication::ExecuteJavaScript("document.getElementById('pxyCheckout').click();");
	}

	/**
	 * showFieldPanels - Makes all checkout form fields visible
	 * @param none
	 * @return none
	 */
	public function showFieldPanels() {
		$this->pnlBillingAdde->Visible = true;
		$this->pnlCart->Visible = true;
		$this->pnlCustomer->Visible = true;
		$this->pnlPayment->Visible = true;
		$this->pnlShipping->Visible = true;
		$this->pnlPromoCode->Visible = true;
		$this->pnlShippingAdde->Visible = true;
		$this->pnlVerify->Visible = true;

		if(!$this->customer)
			$this->pnlLoginRegister->Visible = true;

		$this->cart = Cart::GetCart();
		$this->order_display($this->cart , $this->pnlCart);

		$this->pnlWait->Visible = false;
	}

	/**
	 * processCheckout - Process the checkout form with all details to save into the database
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function processCheckout($strFormId, $strControlId, $strParameter) {
		$customer = Customer::GetCurrent();

		// Add log for processing checkout
		Visitor::add_view_log('',ViewLogType::checkoutpayment);

        $cart = Cart::GetCart();
        $cart->SetIdStr();

		$cart->Type = CartType::awaitpayment;

		$cart->AddressBill =
			$this->txtCRBillAddr1->Text . "\n" .
			$this->txtCRBillAddr2->Text . "\n" .
			$this->txtCRBillCity->Text . "\n" .
			$this->txtCRBillState->SelectedValue . " " .
			$this->txtCRBillZip->Text . "\n" . // TODO for countries that doesn't have state?
			$this->txtCRBillCountry->SelectedValue
		;

		$cart->ShipFirstname = $this->txtCRShipFirstname->Text;
		$cart->ShipLastname = $this->txtCRShipLastname->Text;
		$cart->ShipCompany = $this->txtCRShipCompany->Text;
		$cart->ShipAddress1 = $this->txtCRShipAddr1->Text;
		$cart->ShipAddress2 = $this->txtCRShipAddr2->Text;
		$cart->ShipCity = $this->txtCRShipCity->Text;
		$cart->ShipZip = $this->txtCRShipZip->Text;
		$cart->ShipState = $this->txtCRShipState->SelectedValue;
		$cart->ShipCountry = $this->txtCRShipCountry->SelectedValue;
		$cart->ShipPhone = $this->txtCRShipPhone->Text;
		$cart->DatetimePosted = QDateTime::Now();
		$cart->Downloaded = 0;
		$cart->Status = "Awaiting Processing";

		$cart->AddressShip =
			$cart->ShipFirstname . " " .
			$cart->ShipLastname . 
			(($cart->ShipCompany != '') ? ("\n" . $cart->ShipCompany ) : "") . "\n" .
			$cart->ShipAddress1 . "\n" .
			$cart->ShipAddress2 . "\n" .
			$cart->ShipCity . "\n" .
			$cart->ShipState . " " . $cart->ShipZip . "\n" .
			$cart->ShipCountry
		;

		$cart->Zipcode = $cart->ShipZip;

		$cart->Name = (($this->txtCRCompany->Text != '')?($this->txtCRCompany->Text ):($this->txtCRFName->Text . " " . $this->txtCRLName->Text));
		$cart->Contact = ($this->txtCRFName->Text . " " . $this->txtCRLName->Text);
		$cart->Firstname = $this->txtCRFName->Text;
		$cart->Lastname = $this->txtCRLName->Text;
		$cart->Company = $this->txtCRCompany->Text;
		$cart->Email = $this->txtCREmail->Text;
		$cart->Phone = $this->txtCRMPhone->Text;
        $cart->IpHost = _xls_get_ip();
        $cart->Linkid = $cart->Linkid;

		$cart->PrintedNotes = $this->txtNotes->Text;

		if ($cart->FkPromoId > 0) {
			$pcode = PromoCode::Load($cart->FkPromoId);
			$cart->PrintedNotes .= sprintf("\n%s: %s\n", _sp("Promo Code"), $pcode->Code);
			foreach ($cart->GetCartItemArray() as $objItem)
				if ($objItem->Discount>0)
					$cart->PrintedNotes .= sprintf("%s discount: %.2f\n", $objItem->Code, $objItem->Discount);
			if ($pcode->QtyRemaining>0) {
				$pcode->QtyRemaining--;
				$pcode->Save();
			}
		}

		if(function_exists('_custom_before_order_process'))
			_custom_before_order_process($cart);

		// save with all customer data..
		Cart::SaveCart($cart);

		// If Guest checkout then setup a dummy customer which may be used by payment checkout modules..
		if(!$this->customer)
			$customer = new Customer();

		$customer->Company = $this->txtCRCompany->Text;
		$customer->Firstname = $this->txtCRFName->Text;
		$customer->Lastname = $this->txtCRLName->Text;
		$customer->Address11 = $this->txtCRBillAddr1->Text;
		$customer->Address12 = $this->txtCRBillAddr2->Text;
		$customer->City1 = $this->txtCRBillCity->Text;
		$customer->Zip1 = $this->txtCRBillZip->Text;
		$customer->State1 = $this->txtCRBillState->SelectedValue;
		$customer->Country1 = $this->txtCRBillCountry->SelectedValue;
		$customer->Mainphone = $this->txtCRMPhone->Text;
		$customer->Email = $this->txtCREmail->Text;

		if(!$this->customer)
			_xls_stack_add('xls_temp_customer' , $customer);

		$errtext = "";

		$ship_obj = $this->loadModule($this->lstShippingMethod->SelectedValue , 'shipping');

		$resp = $ship_obj->process($cart , $this->shipping_fields , $this->fltShippingCost);

		if($resp === FALSE) {
			$this->errSpan->Text = $errtext?$errtext:_sp('Error in processing shipping option');
			$this->showFieldPanels();
			return;
		}

		// Shipping may add the shipping product to cart so reload cart!
		$cart = Cart::GetCart();

		$cart->ShippingModule = $this->lstShippingMethod->SelectedValue;
		$cart->ShippingData = trim($resp)?$resp:$this->lblShippingCost->Text;
		$cart->ShippingData = trim(str_replace("-" , "" , str_replace("(" . _xls_currency($this->fltShippingCost) . ")" , " " , $cart->ShippingData)));
		$cart->ShippingData = trim(str_replace("-" , "" , str_replace(_xls_currency($this->fltShippingCost) , " " , $cart->ShippingData)));

		$errtext = "";
		$payment_obj = $this->loadModule($this->lstPaymentMethod->SelectedValue , 'payment');

		$resp = $payment_obj->process($cart , $this->payment_fields , $errtext);

		if($payment_obj->uses_jumper())
			_xls_stack_add('xls_jumper_form' , $resp);

		if($resp === FALSE) {
			$this->errSpan->Text = $errtext?$errtext:_sp('Error in processing payment');
			$this->showFieldPanels();
			return;
		}

		$cart->PaymentModule = $this->lstPaymentMethod->SelectedValue;
		$cart->PaymentMethod = $payment_obj->payment_method($cart);
		$cart->PaymentData = $resp;

		if($resp &&  !$payment_obj->uses_jumper())
			$cart->PaymentAmount = $payment_obj->paid_amount($cart);

		// otherwise ALL OK.
		if(function_exists('_custom_after_order_process'))
				_custom_after_order_process($cart);

		// Add log for checkout -- ALL OK
		Visitor::add_view_log('',ViewLogType::checkoutfinal);

		// forward to jumper
		if($payment_obj->uses_jumper()) {
			$cart->Type = CartType::awaitpayment; // set it to await payment for jumping..
			// jumper must return to index.php?xlspg=checkout&complete_order=linkid
			$cart->PaymentData = "";  // unset payment data field for jumper payments
			Cart::SaveCart($cart);
			_rd('xls_jumper.php');
			return;
		}

		Cart::SaveCart($cart);

		if(!$this->customer) {
			if(!($customer = _xls_stack_get('xls_temp_customer')))
				$customer = false;
		} else
			$customer = $this->customer;

		$this->completeOrder( $cart , $customer);
	}

	/*Functions below are overloaded in extended versions only, defaults set below*/
	public function showCart() {
		return false;
	}

	public function showSideBar() {
		return false;
	}

	public function require_ssl() {
		return true;
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_checkout::Run('xlsws_checkout', templateNamed('index.tpl.php'));
