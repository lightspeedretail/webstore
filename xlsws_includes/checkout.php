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

    protected $PreviousAddressControl;

    protected $LoginRegisterControl;
    protected $LoginControl;
    protected $RegisterControl;

    protected $ShippingControl;
    protected $PaymentControl;
    protected $PromoControl;

    protected $CartControl;

    protected $VerifyControl;
    protected $CaptchaControl;
    protected $CommentControl;
    protected $TermsControl;

    protected $SubmitControl;

    protected $LoadActionProxy;

    protected $objRegistry;
    protected $objDestination;
    protected $objTaxCode;

    protected function BuildCustomerControl() {
        $this->CustomerControl = $objControl = 
            new XLSCustomerControl($this, 'CustomerContact');
        $this->BillingContactControl = 
            $this->CustomerControl->Billing;
        $this->ShippingContactControl = 
            $this->CustomerControl->Shipping;

        return $objControl;
    }

    protected function UpdateCustomerFromRegistry() {
        $objCart = Cart::GetCart();
        $objRegistry = $objCart->GiftRegistryObject;

        $this->objRegistry = null;

        if (!$objRegistry)
            return $objRegistry;

        $this->objRegistry = $objRegistry;

        if ($objRegistry->ShipOption == 'Ship to buyer')
            return $objRegistry;

        // TODO :: Posible security / privacy risk
        $objRecipient = Customer::Load($objRegistry->CustomerId);
        if (!$objRecipient)
            return $objRegistry;

        if (!$this->ShippingContactControl)
            return $objRegistry;

        $this->ShippingContactControl->UpdateFieldsFromCustomer($objRecipient);

        if ($this->CustomerControl->CheckSame) {
            $this->CustomerControl->CheckSame->Visible = false;
            $this->CustomerControl->CheckSame->Checked = false;
        }

        $this->ShippingContactControl->Visible = false;

        return $objRegistry;
    }

    protected function UpdateCustomerControl() {
        $objControl = $this->CustomerControl;

        $this->UpdateCustomerFromRegistry();

        return $objControl;
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

    protected function BuildPreviousAddressControl() {
        $objControl = $this->PreviousAddressControl = 
            new XLSListControl($this, 'PreviewAddress');
        $objControl->Width = '300px';
        $objControl->DisplayStyle = 'block';
        $objControl->SetCustomStyle('clear', 'both');

        return $objControl;
    }

    protected function UpdatePreviousAddressControl() {
        $objControl = $this->PreviousAddressControl;

        if (!$objControl)
            return $objControl;

        $objCustomer = Customer::GetCurrent();

        if (!$objCustomer->Rowid) { 
            $objControl->Visible = false;
            $objControl->Enabled = false;
            return $objControl;
        }

        $objControl->RemoveAllItems();
        $objControl->AddPlaceholder();

        $objCartArray = Cart::QueryArray(
            QQ::AndCondition(
                QQ::Equal(QQN::Cart()->CustomerId, $objCustomer->Rowid),
                QQ::Equal(QQN::Cart()->Type, CartType::order)
            ),
            QQ::Clause(
                QQ::OrderBy(QQN::Cart()->Rowid, false)
            )
        );
        $strAddedCartArray = array();

        foreach ($objCartArray as $objCart) { 
            $strLabel = sprintf('%s %s %s %s, %s', 
                $objCart->ShipFirstname, 
                $objCart->ShipLastname, 
                $objCart->ShipCompany, 
                $objCart->ShipAddress1, 
                $objCart->ShipCity
            );

            if (in_array($strLabel, $strAddedCartArray))
                continue;

            $objControl->AddItem($strLabel, $objCart->Rowid);

            $strAddedCartArray[] = $strLabel;
        }

        if ($objControl->ItemCount <= 1) {
            $objControl->Visible = false;
            $objControl->Enabled = false;
        }

        return $objControl;
    }

    protected function BindPreviousAddressControl() {
        $objControl = $this->PreviousAddressControl;

        if (!$objControl)
            return $objControl;
        
        $objControl->AddAction(
            new QChangeEvent(), 
            new QAjaxAction('DoPreviousAddressChange')
        );
    }

    public function DoPreviousAddressChange($strFormId, $strControlId, $strPar){
        $objControl = $this->PreviousAddressControl;

        if (!$objControl)
            return $objControl;

        if (!$objControl->SelectedValue)
            return $objControl;

        $objCart = Cart::Load($objControl->SelectedValue);

        if (!$objCart)
            return $objControl;

        if (!$this->ShippingContactControl)
            return $objControl;

        $this->ShippingContactControl->UpdateFieldsFromCart($objCart);
        $this->UpdateAfterShippingAddressChange();

        return $objControl;
    }

    protected function BuildShippingControl() {
        $this->ShippingControl = $objControl = 
            new XLSShippingControl($this, 'Shipping');
        $objControl->Name = 'Shipping';
        $objControl->Template = templateNamed('checkout_shipping.tpl.php');
        
        return $objControl;
    }

    protected function UpdateShippingControl() {
        $objControl = $this->ShippingControl;

        if (!$objControl)
            return $objControl;

        $objControl->Update();

        return $objControl;
    }

    protected function BindShippingControl() {
        $objControl = $this->ShippingControl;

        if (!$objControl)
            return $objControl;

        return $objControl;
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

    protected function BuildLoginRegisterControl() {
        $objControl = $this->LoginRegisterControl = 
            new QPanel($this, 'LoginRegister');
        $objControl->Template = templateNamed('checkout_login_register.tpl.php');

        return $objControl;
    }

    protected function UpdateLoginRegisterControl() {
        $objControl = $this->LoginRegisterControl;

        if (!$objControl)
            return $objControl;

        $objCustomer = Customer::GetCurrent();
        if ($objCustomer->Rowid)
            $objControl->Visible = false;
        else
            $objControl->Visible = true;

        return $objControl;
    }

    protected function BindLoginRegisterControl() {
        return $this->LoginRegisterControl;
    }

    protected function BuildLoginControl() {
        $objControl = $this->LoginControl = 
            new QButton($this->LoginRegisterControl, 'Login');
        $objControl->Text = _sp('Login');

        return $objControl;
    }

    protected function UpdateLoginControl() {
        return $this->LoginControl;
    }

    protected function BindLoginControl() {
        $objControl = $this->LoginControl;

        if (!$objControl)
            return $objControl;

        $objControl->AddAction(
            new QClickEvent(), 
            new QAjaxAction('DoLoginControlClick')
        );

        return $objControl;
    }

    public function DoLoginControlClick($strFormId, $strControlId, $strParam) {
        _xls_stack_add('login_redirect_uri', 'index.php?xlspg=checkout');
       
        $this->dxLogin->doShow();
    }

    protected function BuildRegisterControl() {
        $objControl = $this->RegisterControl = 
            new QButton($this->LoginRegisterControl, 'Register');
        $objControl->Text = _sp('Register');
 
        return $objControl;
    }

    protected function UpdateRegisterControl() {
        return $this->RegisterControl;
    }

    protected function BindRegisterControl() {
        $objControl = $this->RegisterControl;

        if (!$objControl)
            return $objControl;

        $objControl->AddAction(
            new QClickEvent(), 
            new QServerAction('DoRegisterControlClick')
        );

        return $objControl;
    }

    public function DoRegisterControlClick($strFormId, $strControlId, $strParam) {
        _xls_stack_add('register_redirect_uri' , "index.php?xlspg=checkout");
        _rd("index.php?xlspg=customer_register");
    }

    protected function BuildSubmitControl() {
        $objControl = $this->SubmitControl = 
            new QButton($this, 'Submit');
        $objControl->Text = _sp('Submit Order');
        $objControl->CausesValidation = true;
        $objControl->PrimaryButton = true;
        $objControl->Required = true;
 
        return $objControl;
    }

    protected function UpdateSubmitControl() {
        return $this->SubmitControl;
    }

    protected function BindSubmitControl() {
        $objControl = $this->SubmitControl;

        if (!$objControl)
            return;

        $objControl->AddActionArray(
            new QClickEvent(),
            array(
                new QAjaxAction('ToggleCheckoutControls'),
                new QServerAction('DoSubmitControlClick')
            )
        );

        return $objControl;
    }

	public function DoSubmitControlClick($strFormId, $strControlId, $strParam) {
        $objCart = Cart::GetCart();

        if ($objCart->IdStr && $objCart->Status == CartType::order)
            _rd($objCart->Link);

        $this->CompleteCheckout();
	}

    protected function BuildLoadActionProxy() {
        $objControl = $this->LoadActionProxy = 
            new QButton($this, 'LoadActionProxy');
        $objControl->CssClass = 'xlshidden';
            
        return $objControl;
    }

    protected function UpdateLoadActionProxy() {
        return $this->LoadActionProxy;
    }

    protected function BindLoadActionProxy() {
        $objControl = $this->LoadActionProxy;
        
        if (!$objControl)
            return $objControl;

        $objControl->AddAction(
            new QClickEvent(500),
            new QAjaxAction('DoLoadActionProxyClick')
        );

        return $objControl;
    }

    public function DoLoadActionProxyClick($strFormId, $strControlId, $strParam) {
        $this->UpdateAfterShippingAddressChange();
        $this->CustomerControl->ValidationReset(true);

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

    protected function ToggleCheckoutControls($blnVisibility = false) {
		$this->pnlLoginRegister->Visible = $blnVisibility;
		$this->pnlWait->Visible = $blnVisibility;
        
        $this->CustomerControl->Visible = $blnVisibility;
        $this->ShippingControl->Visible = $blnVisibility;
        $this->CartControl->Visible = $blnVisibility;
        $this->PromoControl->Visible = $blnVisibility;
        $this->VerifyControl->Visible = $blnVisibility;
        $this->PaymentControl->Visible = $blnVisibility;
    }

    protected function CompleteUpdateCart() {
        $objCart = Cart::GetCart();

        if (!$objCart->IdStr)
            $objCart->SetIdStr();

		if (trim($objCart->Currency) == '')
			$objCart->Currency = _xls_get_conf('CURRENCY_DEFAULT' , 'USD');

        $objCart->CustomerId = $objCustomer->Rowid;

        $objCart->Type = CartType::awaitpayment;
        $objCart->Status = 'Awaiting Processing';
        $objCart->DatetimePosted = QDateTime::Now();
        $objCart->Downloaded = 0;
        $objCart->IpHost = _xls_get_ip();

        $objCart->Contact = $objCart->Firstname . ' ' . $objCart->Lastname;
        $objCart->Name = 
            (($objCart->Company) ? ($objCart->Company) : $objCart->Contact);

        $objCart->AddressBill = implode("\n", array(
            $objCustomer->Address11, 
            $objCustomer->Address12,
            $objCustomer->City1,
            $objCustomer->State1,
            $objCustomer->Zip1,
            $objCustomer->Country1
        ));

        $objCart->AddressShip = implode("\n", array(
            $objCart->ShipFirstname . ' ' . 
                $objCart->Lastname .
                (($objCart->ShipCompany) ? ("\n" . $objCart->ShipCompany) : ''),
            $objCart->ShipAddress1,
            $objCart->ShipAddress2,
            $objCart->ShipCity, 
            $objCart->ShipState . ' ' . $objCart->ShipZip,
            $objCart->ShipCountry
        ));

        $objCart->Zipcode = $objCart->ShipZip;
        $objCart->PrintedNotes = $this->CommentControl->Text;
    
        return $objCart;
    }

    protected function CompleteUpdatePromoCode() {
        $objCart = Cart::GetCart();
        $objPromo = null;

        if ($objCart->FkPromoId > 0) {
            $objPromo = PromoCode::Load($objCart->FkPromoId);

            $objCart->PrintedNotes = implode("\n", array(
                $objCart->PrintedNotes,
                sprintf("%s: %s", _sp('Promo Code'), $objPromo->Code)
            ));

			foreach ($cart->GetCartItemArray() as $objItem)
                if ($objItem->Discount > 0)
                    $objCart->PrintedNotes = implode("\n", array(
                        $objCart->PrintedNotes, 
                        sprintf("%s discount: %.2f\n", 
                            $objItem->Code, 
                            $objItem->Discount
                        )
                    ));

			if ($objPromo->QtyRemaining > 0) {
				$objPromo->QtyRemaining--;
				$objPromo->Save();
			}
		}

        return $objPromo;
    }

    protected function CompleteUpdateCustomer() {
        $objCustomer = Customer::GetCurrent();

        return $objCustomer;
    }

    // TODO :: Required ? 
    protected function PrePaymentHooks() {
		if (function_exists('_custom_before_order_process'))
			_custom_before_order_process($cart);
    
        return true;
    }

    // TODO :: Required ? 
    protected function PostPaymentHooks() {
		if (function_exists('_custom_after_order_process'))
			_custom_before_after_process($cart);
    
        return true;
    }

    protected function CompleteCheckout() {
        Visitor::add_view_log('',ViewLogType::checkoutpayment);

        $objCustomer = $this->CompleteUpdateCustomer();
        $objPromo = $this->CompleteUpdatePromoCode();
        $objCart = $this->CompleteUpdateCart();

        $objCart->Save();

        if (!$this->PrePaymentHooks())
            return false;

        $objPaymentModule = $this->loadModule(
            $objCart->PaymentModule . '.php',
            'payment'
        );

		$objCart->PaymentMethod = $objPaymentModule->payment_method($objCart);
		
        $strError = '';
        $mixResponse = $objPaymentModule->process(
            $objCart, $this->PaymentControl->objMethodFields, $strError
        );

		if (is_array($mixResponse))
		{
			if ($mixResponse[0]==true) { //Successful Transaction
            	$objCart->PaymentData = $mixResponse[1];
            } else {
				$this->errSpan->Text = ($mixResponse[1] != '' ? $mixResponse[1] : _sp('Error in processing payment'));
			 	$this->ToggleCheckoutControls(true);
			 	$objCart->PaymentData = $this->errSpan->Text; //Save error as part of cart in case of abandon
			 	//ToDo: verify this isn't an overwrite as a result of a duplicate
			 	$objCart->Save();
            	return false;
            }
		
		}
		elseif ($mixResponse === FALSE) { //Backwards compatibility for any custom modules that just return t/f
            $this->errSpan->Text = ($strError != '' ? $strError : _sp('Error in processing payment'));
            $this->ToggleCheckoutControls(true);
            $objCart->PaymentData = $this->errSpan->Text; //Save error as part of cart in case of abandon
			$objCart->Save();
            return false;
        } 
        else $objCart->PaymentData = $mixResponse;

        
        

        if (!$objPaymentModule->uses_jumper())
            $objCart->PaymentAmount = $objPaymentModule->paid_amount($objCart);

        if (!$this->PostPaymentHooks())
            return false;

		Visitor::add_view_log('',ViewLogType::checkoutfinal);

        if ($objPaymentModule->uses_jumper()) { 
            _xls_stack_add('xls_jumper_form', $mixResponse);
            $objCart->PaymentData = '';
            $objCart->Save();
            _rd('xls_jumper.php');
            return;
        }

        $objCart->Save();

        $this->FinalizeCheckout($objCart, $objCustomer);
    }

    public static function FinalizeCheckout(
        $objCart = null, $objCustomer = null, $blnForward = true
    ) {
        if (!$objCart)
            $objCart = Cart::GetCart();

        if (!$objCustomer)
            $objCustomer = Customer::GetCurrent();

        self::PreFinalizeHooks($objCart, $objCustomer);

        $objCart->Type = CartType::order;
        $objCart->Submitted = QDateTime::Now(true);
        $objCart->Save();

        _xls_stack_add('xls_submit_order', true);

        Cart::ClearCart();

        self::PostFinalizeHooks($objCart, $objCustomer);

        if ($blnForward)
            _rd($objCart->Link);
    }

    // TODO :: Required ? 
    protected static function PreFinalizeHooks($objCart, $objCustomer) {
		if (function_exists('_custom_before_order_complete'))
			_custom_before_order_process($objCart, $objCustomer);
    
        return $objCart;
    }

    // TODO :: Required ? 
    protected static function PostFinalizeHooks($objCart, $objCustomer) {
		if (function_exists('_custom_after_order_complete'))
			_custom_before_after_process($objCart, $objCustomer);
    
        return $objCart;
    }

    protected static function SendCustomerEmail($objCart, $objCustomer) {
        _xls_mail(
            $objCart->Email,
            sprintf('%s %s %s', 
                _xls_get_conf('STORE_NAME', 'Web'),
                _sp('Order Notification'),
                $objCart->IdStr
            ),
            _xls_mail_body_from_template(
                templateNamed('email_order_notification.tpl.php'),
                array(
                    'cart' => $objCart, 
                    'customer' => $objCustomer
                )
            ),
            _xls_get_conf('ORDER_FROM')
        );
    }

    protected static function SendOwnerEmail($objCart, $objCustomer) {
        _xls_mail(
            _xls_get_conf('ORDER_FROM'),
            sprintf('%s %s %s', 
                _xls_get_conf('STORE_NAME', 'Web'),
                _sp('Order Notification'),
                $objCart->IdStr
            ),
            _xls_mail_body_from_template(
                templateNamed('email_order_notification_owner.tpl.php'),
                array(
                    'cart' => $objCart, 
                    'customer' => $objCustomer
                )
            ),
            _xls_get_conf('ORDER_FROM')
        );
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
        $this->BuildLoginRegisterControl();
        $this->BuildLoginControl();
        $this->BuildRegisterControl();
        $this->BuildSubmitControl();
        $this->BuildLoadActionProxy();
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
        $this->UpdateLoginRegisterControl();
        $this->UpdateLoginControl();
        $this->UpdateRegisterControl();
        $this->UpdateSubmitControl();
        $this->UpdateLoadActionProxy();
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
        $this->BindLoginRegisterControl();
        $this->BindLoginControl();
        $this->BindRegisterControl();
        $this->BindSubmitControl();
        $this->BindLoadActionProxy();
    }

    // TODO
    protected function Form_PreRender() {
        // Todo ... inefficient
        $this->UpdateCartControl();
        parent::Form_PreRender();
	}

    protected function Form_PreLoad() {
        parent::Form_PreLoad();

        $objCustomer = Customer::GetCurrent();
        $objCart = Cart::GetCart();

        if ($objCart->Rowid)
            $objCart = $_SESSION['XLSWS_CART'] = Cart::Load($objCart->Rowid);

        if (!$objCustomer->Rowid)
            if (_xls_get_conf('ALLOW_GUEST_CHECKOUT', 1) != 1)
                _xls_display_msg(
                    _sp('You have to login to check out'),
                    'index.php?xlspg=checkout'
                );
    }

	protected function Form_Validate() {
		$this->errSpan->Text='';
		$this->errSpan->CssClass='customer_reg_err_msg';

		$errors = array();

        if (!$this->ValidateControlAndChildren($this->CaptchaControl))
			$errors[] = _sp("Wrong Verification Code.");

        if (!$this->ValidateControlAndChildren($this->CustomerControl))
			$errors[] = _sp('Please complete the required fields marked with an asterisk *');

        if (!$this->ValidateControlAndChildren($this->ShippingControl))
			$errors[] =  _sp("Shipping error. Please choose a valid shipping method.");

        if (!$this->ValidateControlAndChildren($this->PaymentControl))
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

            case 'lstCRShipPrevious':
                return $this->PreviousAddressControl;

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

            case 'btnSubmit':
                return $this->SubmitControl;

            case 'pxyCheckout':
                return $this->LoadActionProxy;

            case 'pnlLoginRegister':
                return $this->LoginRegisterControl;

            case 'butLogin':
                return $this->LoginControl;

            case 'butRegister':
                return $this->RegisterControl;

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
	protected $errSpan; //span block that displays an error on top of the checkout form if any

	protected $lblWait; //the label for the wait icon (optional)
	protected $icoWait; //the actual wait icon


	protected $pnlWait; //The QPanel that shows the wait icon(s)

	protected $pxyCheckout; //Handler for checkout

	protected function build_main() {
		global $XLSWS_VARS;

        $objCustomer = Customer::GetCurrent();
        $objCart = Cart::GetCart();

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('checkout.tpl.php');
        $this->objDefaultWaitIcon = new QWaitIcon($this);

        $this->crumbs[] = array(
            'key' => 'xlspg=checkout',
            'case' => '',
            'name' => _sp('Check Out')
        );
        
        $this->BuildForm();
        $this->UpdateForm();
        $this->BindForm();

		//error msg
		$this->errSpan = new QPanel($this);
		$this->errSpan->CssClass='customer_reg_err_msg';

		// Wait
		$this->pnlWait = new QPanel($this->mainPnl);
		$this->pnlWait->Visible = false;
		$this->pnlWait->AutoRenderChildren = true;

		$this->lblWait = new QLabel($this->pnlWait);
		$this->lblWait->Text = _sp("Please wait while we process your order");
		$this->lblWait->CssClass = "checkout_process_label";

		$this->icoWait = new QWaitIcon($this->pnlWait);

        QApplication::ExecuteJavaScript("document.getElementById('LoadActionProxy').click();");
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

