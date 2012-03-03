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

    // NEW
    protected $CustomerControl;

    protected $BillingContactControl;
    protected $ShippingContactControl;
    protected $CalculateShippingControl;

    protected $ShippingControl;
    protected $PaymentControl;
    protected $PromoControl;

    protected $CartControl;

    protected $VerifyControl;
    protected $CaptchaControl;
    protected $CommentControl;
    protected $TermsControl;

    protected $objDestination;
    protected $objTaxCode;

    // TODO
    protected function Form_PreRender() {
        // Todo ... inefficient
        $this->UpdateCartControl();
        parent::Form_PreRender();
	}

    protected function BuildCustomerControl() {
        $this->CustomerControl = $objControl = 
            new XLSCustomerControl($this, 'CustomerContact');
        $this->BillingContactControl = 
            $this->CustomerControl->Billing;
        $this->ShippingContactControl = 
            $this->CustomerControl->Shipping;

        return $objControl;
    }

    protected function UpdateCustomerControl() {

    }

    protected function BindCustomerControl() {
        $this->ShippingContactControl->Address->State->AddAction(
            new QChangeEvent(),new QAjaxAction('DoCalculateShippingClick')
        );
        $this->ShippingContactControl->Address->Zip->AddAction(
            new QChangeEvent(),new QAjaxAction('DoCalculateShippingClick')
        );
        $this->CustomerControl->CheckSame->AddAction(
            new QChangeEvent(),new QAjaxAction('DoCalculateShippingClick')
        );
    }

    protected function BuildCalculateShippingControl() {
        $this->CalculateShippingControl = $objControl = 
            new QButton($this, 'CalculateShippingCtrl');
        $objControl->Text = _sp('Calculate shipping');
        $objControl->CssClass = 'button rounded';
    }

    protected function UpdateCalculateShippingControl() {
        return $this->CalculateShippingControl;
    }

    protected function BindCalculateShippingControl() {
        $objControl = $this->CalculateShippingControl;
        $objControl->AddAction(
            new QClickEvent(),
            new QAjaxAction('DoCalculateShippingClick')
        );
    }

    public function DoCalculateShippingClick($strFormId, $strControlId, 
        $strParameter) {

        return $this->UpdateAfterShippingAddressChange();
    }

    protected function BuildShippingControl() {
        $this->ShippingControl = $objControl = 
            new XLSShippingControl($this, 'Shipping');
        $objControl->Name = 'Shipping';
        $objControl->Template = templateNamed('checkout_shipping.tpl.php');
        
        return $objControl;
    }

    protected function UpdateShippingControl() {
        $this->ShippingControl->Update();
    }

    protected function BindShippingControl() {
    }

    protected function BuildPaymentControl() {
        $this->PaymentControl = $objControl = 
            new XLSPaymentControl($this, 'Payment');
        $objControl->Name = 'Payment';
        $objControl->Template = templateNamed('checkout_payment.tpl.php');
        
        return $objControl;
    }

    protected function UpdatePaymentControl() {
        $this->PaymentControl->Update();
    }

    protected function BindPaymentControl() {
    }

    protected function BuildCartControl() {
        $this->CartControl = $objControl = 
            new QPanel($this, 'Cart');
        $objControl->Name = _sp('Cart');
 
        return $objControl;
    }

    protected function UpdateCartControl() {
        $objControl = $this->CartControl;
        $objCart = Cart::GetCart();

        if ($objCart->Count == 0)
            _xls_display_msg(_sp('Your cart is empty. Please add items' . 
            ' to your cart before you check out.'));

        $objCart->UpdateCart(true, true, true, false);

        $this->order_display($objCart, $objControl);
        $this->update_order_display($objCart);

        return $this->CartControl;
    }

    protected function BindCartControl() {
        return $this->CartControl;
    }

    protected function BuildPromoControl() {
        $objControl = $this->PromoControl = 
            new XLSPromoControl($this, 'Promo');
		$objControl->Template = templateNamed('promo_code.tpl.php');

        return $objControl;
    }

    protected function UpdatePromoControl() {
        return $this->PromoControl;
    }
    
    protected function BindPromoControl() {
        return $this->PromoControl;
    }

    protected function BuildVerifyControl() {
        $objControl = $this->VerifyControl = 
            new QPanel($this, 'Verify');
        $objControl->Name = 'Submit your order';
        $objControl->Template = 
            $objControl->GetTemplatePath('checkout_verify.tpl.php');
    }

    protected function UpdateVerifyControl() {
        return $this->VerifyControl;
    }

    protected function BindVerifyControl() {
        return $this->VerifyControl;
    }

    protected function BuildCaptchaControl() {
        $objParent = $this->VerifyControl;
        if (!$objParent)
            $objParent = $this;

        $objControl = $this->CaptchaControl = 
            new XLSCaptchaControl($objParent, 'Captcha');

        return $objControl;
    }

    protected function UpdateCaptchaControl() {
        return $this->CaptchaControl;
    }
    
    protected function BindCaptchaControl() {
        return $this->CaptchaControl;
    }

    protected function BuildCommentControl() {
        $objControl = $this->CommentControl = 
            new XLSTextControl($this, 'Comment');
        $objControl->TextMode = QTextMode::MultiLine;
        
        return $objControl;
    }

    protected function UpdateCommentControl() {
        return $this->CommentControl;
    }

    protected function BindCommentControl() {
        return $this->CommentControl;
    }

    protected function BuildTermsControl() {
        $objControl = $this->TermsControl = 
            new QCheckBox($this, 'Terms');
        $objControl->Required = true;
 
        return $objControl;
    }

    protected function UpdateTermsControl() {
        return $this->TermsControl;
    }

    protected function BindTermsControl() {
        return $this->TermsControl;
    }

    public function UpdateAfterShippingAddressChange() {
        $blnValid = $this->ValidateControlAndChildren($this->CustomerControl);

        $this->ShippingControl->Enabled = $blnValid;
        $this->PaymentControl->Enabled = $blnValid;

        if ($blnValid) { 
            $this->SetDestination();
            $this->SetTaxCode();
        }
        else { 
            $this->ResetDestination();
            $this->ResetTaxCode();
        }

        $this->UpdateShippingControl();
        $this->UpdatePaymentControl();
        
        $this->UpdateCartControl();
    }

    public function UpdateAfterShippingMethodChange() {
        
    }

    protected function ResetDestination() {
        $this->objDestination = null;

        return $this->objDestination;
    }

    protected function SetDestination() {
        $objShippingControl = $this->ShippingContactControl;

        if (!$objShippingControl) 
            return false;

        $strCountry = $objShippingControl->Address->Country->Value;
        $strState = $objShippingControl->Address->State->Value;
        $strZip = $objShippingControl->Address->Zip->Value;

        if (!$strCountry || !$strZip)
            return $this->ResetDestination();

        $objDestination = Destination::LoadMatching(
            $strCountry, $strState, $strZip
        );

        if (!$objDestination)
            $objDestination = Destination::LoadDefault();

        if ($objDestination)
            return $this->objDestination = $objDestination;
        else
            return $this->ResetDestination();
    }

    protected function ResetTaxCode() {
        $objCart = Cart::GetCart();
        $objCart->FkTaxCodeId = -1;

        $this->objTaxCode = null;

        return $this->objTaxCode;
    }

    protected function SetTaxCode() {
        $objCart = Cart::GetCart();
        $objTaxCode = null;

        if ($this->objDestination)
            $objTaxCode = $this->objDestination->Taxcode;
        else {
            $objTaxCodes = TaxCode::LoadAll(
                QQ::Clause(
                    QQ::OrderBy(QQN::TaxCode()->ListOrder)
                )
            );

            if ($objTaxCodes && count($objTaxCodes) > 0)
                $objTaxCode = $objTaxCodes[0];
        }

        if (!is_null($objTaxCode) && !($objTaxCode instanceof TaxCode))
            $objTaxCode = TaxCode::Load($objTaxCode);
        
        if (is_null($objTaxCode))
            return $this->ResetTaxCode();

        if ($objTaxCode != $this->objTaxCode) {
            $objCart->FkTaxCodeId = $objTaxCode->Rowid;
            $this->objTaxCode = $objTaxCode;
        }

        return $this->objTaxCode;
    }

    protected function BuildForm() {
        $this->BuildCustomerControl();
        $this->BuildCalculateShippingControl();
        $this->BuildShippingControl();
        $this->BuildPaymentControl();
        $this->BuildCartControl();
        $this->BuildPromoControl();
        $this->BuildVerifyControl();
        $this->BuildCaptchaControl();
        $this->BuildCommentControl();
        $this->BuildTermsControl();
    }

    protected function UpdateForm() {
        $this->UpdateCustomerControl();
        $this->UpdateCalculateShippingControl();
        $this->UpdateShippingControl();
        $this->UpdatePaymentControl();
        $this->UpdateCartControl();
        $this->UpdatePromoControl();
        $this->UpdateVerifyControl();
        $this->UpdateCaptchaControl();
        $this->UpdateCommentControl();
        $this->UpdateTermsControl();
    }

    protected function BindForm() {
        $this->BindCustomerControl();
        $this->BindCalculateShippingControl();
        $this->BindShippingControl();
        $this->BindPaymentControl();
        $this->BindCartControl();
        $this->BindPromoControl();
        $this->BindVerifyControl();
        $this->BindCaptchaControl();
        $this->BindCommentControl();
        $this->BindTermsControl();
    }

    public function __isset($strName) {
        switch ($strName) {
            case 'pnlPromoCode':
                if ($this->PromoControl) return true;
                else return false;
            case 'butCalcShipping':
                if ($this->CalculateShippingControl) return true;
                else return false;
            case 'chkSame':
                if ($this->CustomerControl->CheckSame) return true;
                else return false;
        }

    }

    public function __get($strName) {
        switch ($strName) {
            case 'txtCRFName':
                return $this->BillingContactControl->FirstName;

            case 'txtCRLName': 
                return $this->BillingContactControl->LastName;

            case 'txtCRCompany': 
                return $this->BillingContactControl->Company;

            case 'txtCRMPhone': 
                return $this->BillingContactControl->Phone;

            case 'txtCREmail': 
                return $this->BillingContactControl->Email;

            case 'txtCRBillAddr1':
                return $this->BillingContactControl->Street1;
            
            case 'txtCRBillAddr2':
                return $this->BillingContactControl->Street2;

            case 'txtCRBillCity':
                return $this->BillingContactControl->City;

            case 'txtCRBillCountry':
                return $this->BillingContactControl->Country;

            case 'txtCRBillState':
                return $this->BillingContactControl->State;

            case 'txtCRBillZip':
                return $this->BillingContactControl->Zip;

            case 'txtCRShipFirstname': 
                return $this->ShippingContactControl->FirstName;

            case 'txtCRShipLastname': 
                return $this->ShippingContactControl->LastName;

            case 'txtCRShipCompany': 
                return $this->ShippingContactControl->Company;

            case 'txtCRShipPhone': 
                return $this->ShippingContactControl->Phone;

            case 'txtCRShipAddr1':
                return $this->ShippingContactControl->Street1;
            
            case 'txtCRShipAddr2':
                return $this->ShippingContactControl->Street2;

            case 'txtCRShipCity':
                return $this->ShippingContactControl->City;

            case 'txtCRShipCountry':
                return $this->ShippingContactControl->Country;

            case 'txtCRShipState':
                return $this->ShippingContactControl->State;

            case 'txtCRShipZip':
                return $this->ShippingContactControl->Zip;

            case 'chkSame':
                return $this->CustomerControl->CheckSame;

            case 'butCalcShipping':
                return $this->CalculateShippingControl;

            case 'pnlCustomer':
                return $this->BillingContactControl->Info;

            case 'pnlBillingAdde':
                return $this->BillingContactControl->Address;

            case 'pnlShippingAdde':
                return $this->ShippingContactControl;

            case 'pnlShipping':
                return $this->ShippingControl;

            case 'pnlPayment':
                return $this->PaymentControl;

            case 'pnlCart':
                return $this->CartControl;

            case 'pnlPromoCode':
                return $this->PromoControl;

            case 'txtPromoCode':
                return $this->PromoControl->Input;

            case 'btnPromoVerify':
                return $this->PromoControl->Submit;

            case 'lblPromoErr':
                return $this->PromoControl->Label;

            case 'pnlVerify':
                return $this->VerifyControl;

            case 'lblVerifyImage':
                return $this->CaptchaControl->Code;

            case 'txtCRVerify':
                return $this->CaptchaControl->Input;

            case 'txtNotes':
                return $this->CommentControl;

            case 'chkAgree':
                return $this->TermsControl;

            case 'customer':
                return Customer::GetCurrent();

            case 'cart':
                return Cart::GetCart();

            default:
                try { 
                    return parent::__get($strName);
                }
                catch (QCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }


	/*see xlsws_index for shared widgets*/
	protected $lstCRShipPrevious; //input select box for previously shipped addresses

	protected $btnSubmit; //input submit for the submit button on the bottom

	protected $errSpan; //span block that displays an error on top of the checkout form if any

	protected $lblWait; //the label for the wait icon (optional)
	protected $icoWait; //the actual wait icon

	protected $lstShippingMethod; //input select box that shows applicable shipping methods to pick from

	protected $lstPaymentMethod; //input select box that shows applicable payment methods to pick from

	protected $pnlLoginRegister; //The QPanel that shows login and register buttons on the checkout page solely

	protected $pnlWait; //The QPanel that shows the wait icon(s)

	protected $pxyCheckout; //Handler for checkout

	protected $giftRegistry; //Instantiated WishList object of the current shopper's cart relating to a registry (if purchased from there)

	protected $checktaxonly; //used in the shipping cost function to just do a recalculation of taxes and not every other aspect like shipping


	protected $butLogin; //The login button solely on the checkout page
	protected $butRegister; //The register button solely on the checkout page

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
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		global $XLSWS_VARS;
        
        $customer = Customer::GetCurrent();
        $cart = Cart::GetCart();

        $this->BuildForm();
        $this->UpdateForm();
        $this->BindForm();

        #
        # Provide support for activating the shipping and payment modules
        # when the customer contact informatin is adequately pre-populated. 
        #
        # Then, since this is on the initial page load, reset the validation
        # errors that may be.  
        #
        $this->UpdateAfterShippingAddressChange();
        $this->CustomerControl->ValidationReset();

		$this->checktaxonly = false;

		if(isset($XLSWS_VARS['complete_order'])) {
			$this->handle_order();
			return;
		}

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('checkout.tpl.php');

		$this->check_guest_checkout();

		// Wait icon
        $this->objDefaultWaitIcon = new QWaitIcon($this);

		$this->crumbs[] = array('key'=>'xlspg=cart' , 'case'=> '' , 'name'=> _sp('Cart'));
		$this->crumbs[] = array('key'=>'xlspg=checkout' , 'case'=> '' , 'name'=> _sp('Check Out'));

		// Define the layout
        $this->check_registry();

		//error msg
		$this->errSpan = new QPanel($this);
		$this->errSpan->CssClass='customer_reg_err_msg';

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
	 * Form_Validate - Validates all form fields for valid input
	 * @param none
	 * @return none
	 */
	protected function Form_Validate() {
		$this->errSpan->Text='';
		$this->errSpan->CssClass='customer_reg_err_msg';

		$errors = array();

        if (!$this->CaptchaControl->Validate())
			$errors[] = _sp("Wrong Verification Code.");

        if (!$this->CustomerControl->Validate())
			$errors[] = _sp('Please complete the required fields marked with an asterisk *');
        
        
        if (!$this->ShippingControl->Validate())
			$errors[] =  _sp("Shipping error. Please choose a valid shipping method.");

        if (!$this->PaymentControl->Validate())
            $errors[] =  _sp("Payment error");

        if (!$this->TermsControl->Checked)
			$errors[] =  _sp("You must agree to terms and conditions to place an order");

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
        if (!$this->Validate())
            return;
        
        $this->CustomerControl->Visible = false;
        $this->ShippingControl->Visible = false;
        $this->CartControl->Visible = false;
        $this->PromoControl->Visible = false;
        $this->VerifyControl->Visible = false;
        $this->PaymentControl->Visible = false;
		$this->pnlLoginRegister->Visible = false;

        $objCart = Cart::GetCurrent();

		if(trim($objCart->Currency) == '')
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
        $this->CustomerControl->Visible = true;
        $this->ShippingControl->Visible = true;
        $this->CartControl->Visible = true;
        $this->PromoControl->Visible = true;
        $this->VerifyControl->Visible = true;
        $this->PaymentControl->Visible = true;

		if(!$this->customer)
			$this->pnlLoginRegister->Visible = true;

        $this->UpdateCartControl();

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
