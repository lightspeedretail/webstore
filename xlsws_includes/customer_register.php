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
 * xlsws_cregister class
 * This is the controller class for the customer registration page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the register page
 */
class xlsws_cregister extends xlsws_index {

  	// NEW
    protected $CustomerControl;

    protected $BillingContactControl;
    protected $ShippingContactControl;
    protected $CalculateShippingControl;

    protected $PasswordControlWrapper;
    protected $PasswordControl;

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

  	protected $errSpan; //the span that generates or shows the error
  	
	/*
	protected $txtCRConfEmail; //input text box confirm email address
	protected $txtCRPass; //input password box for password
	protected $txtCRConfPass; //input password box for confirm password
	protected $txtCRLanguage; //input textbox for language
	protected $txtCRPricingLevel; //input text box for customer pricing level (not presently used)
	protected $txtCRCurrency; //input text box for customer currency (not presently used, uses store currency)
	protected $txtCRHomePage; //Website for customer **unused by current front end**
	protected $txtCRMName; //input text box middle name (presently unused)
	protected $txtCRMPhoneType; //input text box phone type
	protected $txtCRMPhone; //input text box phone number
	protected $txtCRPhoneType1; //input text box phone type 1
	protected $txtCRPhone1; //input text box phone 1
	protected $txtCRPhoneType2; //input text box phone type 2
	protected $txtCRPhone2; //input text box phone type 2
	protected $txtCRPhoneType3; //input text box phone type 3
	protected $txtCRPhone3; //input text box phone 3
	protected $txtCRPhoneType4;  //input text box phone type 4
	protected $txtCRPhone4; //input text box phone 4
	protected $chkNewsletter; //subscribe to our newsletter tickmark (unused by the backend)
	protected $chkHtmlEmail; //input checkbox for receiving html email

	protected $btnSave; //the actual save or submit button
	protected $chkSame; //input checkbox for shipping address is the same as billing
	protected $chkAdditionalContact; //checkbox for additional contact (unused)
	
	protected $lblVerifyImage; //the verify image label that shows the captcha image

	protected $languages; //list of languages to choose from
	protected $currencies; //list of currencies to choose from (unused)
	protected $phone_types; //list of phone types to choose fromn
*/
	public $customer; //the customer object if any
	
	
	protected function BuildCustomerControl() {
        $this->CustomerControl = $objControl = 
            new XLSRegisterCustomerControl($this, 'CustomerContact'); 
        $this->BillingContactControl = 
            $this->CustomerControl->Billing;
        $this->ShippingContactControl = 
            $this->CustomerControl->Shipping;

        return $objControl;
    }
    

    protected function UpdateCustomerControl() {
        $objControl = $this->CustomerControl;


        return $objControl;
    }

    protected function BindCustomerControl() {
        return $this->CustomerControl;
    }

    protected function BuildPasswordControlWrapper() {
        $objControl = $this->PasswordControlWrapper = 
            new QPanel($this, 'PasswordWrapper');
        $objControl->Name = 'Set your password';
        $objControl->Template = templateNamed('customer_register_password.tpl.php');
                    
    }

    protected function UpdatePasswordControlWrapper() {
        return $this->PasswordControlWrapper;
    }

    protected function BindPasswordControlWrapper() {
        return $this->PasswordControlWrapper;
    }
    
    protected function BuildPasswordControl() {
        $objParent = $this->PasswordControlWrapper;
        if (!$objParent) 
          $objParent = $this;

        $objControl = $this->PasswordControl = 
            new XLSPasswordControl($objParent, 'CreatePassword');

        return $objControl;
            
    }

    protected function UpdatePasswordControl() {
    	
        return $this->PasswordControl;
    }

    protected function BindPasswordControl() {
        return $this->PasswordControl;
    }
    

    protected function BuildVerifyControl() {
        $objControl = $this->VerifyControl = 
            new QPanel($this, 'Verify');
        $objControl->Name = 'Submit your order';
        $objControl->Template = templateNamed('customer_register_verify.tpl.php');
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

	protected function BuildSubmitControl() {
        $objControl = $this->SubmitControl = 
            new QButton($this, 'Submit');
        $objControl->Text = _sp('Submit');
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
            	new QToggleEnableAction($objControl, false),
            	new QAjaxAction('ToggleCheckoutControls',false),
                new QServerAction('DoSubmitControlClick')
            )
        );
	
        return $objControl;
    }

	public function DoSubmitControlClick($strFormId, $strControlId, $strParam) {

        
        	//We only want to check Captcha after everything else has passed, to avoid multiple checks
        	$blnCaptchaValid=1;
        	if (_xls_get_conf('CAPTCHA_REGISTRATION' , '0')=='2' || 
        		(!$this->isLoggedIn() && _xls_get_conf('CAPTCHA_REGISTRATION' , '0')=='1')
        	)
        		$blnCaptchaValid = $this->CaptchaControl->Validate_Captcha();
        		
    		if ($blnCaptchaValid)
    			$this->CompleteRegistration();
		    else
		    	$this->errSpan->Text = "Captcha Validation Error";

        }
	
	
	
	private function CompleteRegistration() {
		if($this->isLoggedIn())
			$objCustomer = Customer::LoadByRowId($this->customer->Rowid);
		else
			$objCustomer = new Customer();

		$objCustomer->Email= strtolower(trim($this->BillingContactControl->Email));
		$objCustomer->Password = md5(trim($this->PasswordControl->Password1->Text));
		$objCustomer->Firstname = trim($this->txtCRFName->Text);
		$objCustomer->Lastname = trim($this->txtCRLName->Text);
		$objCustomer->Mainname = (($this->customer) && ($this->customer->Mainname != '')) ? $this->customer->Mainname : (trim($this->txtCRFName->Text) . " " . trim($this->txtCRLName->Text));
		$objCustomer->Company = trim($this->txtCRCompany->Text);
		$objCustomer->Mainphone = trim($this->txtCRMPhone->Text);
		/*$objCustomer->Homepage = trim($this->txtCRHomePage->Text);
		$objCustomer->Mainephonetype = trim($this->txtCRMPhoneType->SelectedValue);
		
		
		$objCustomer->Phonetype1 = trim($this->txtCRPhoneType1->SelectedValue);
		$objCustomer->Phone1 = trim($this->txtCRPhone1->Text);
		$objCustomer->Phonetype2 = trim($this->txtCRPhoneType2->SelectedValue);
		$objCustomer->Phone2 = trim($this->txtCRPhone2->Text);
		$objCustomer->Phonetype3 = trim($this->txtCRPhoneType3->SelectedValue);
		$objCustomer->Phone3 = trim($this->txtCRPhone3->Text);
		$objCustomer->Phonetype4 = trim($this->txtCRPhoneType4->SelectedValue);
		$objCustomer->Phone4 = trim($this->txtCRPhone4->Text);
		*/
		$objCustomer->Address11 = trim($this->txtCRBillAddr1->Text);
		$objCustomer->Address12 = trim($this->txtCRBillAddr2->Text);
		$objCustomer->Country1 = trim($this->txtCRBillCountry->SelectedValue);
		$objCustomer->State1 = trim($this->txtCRBillState->SelectedValue);
		$objCustomer->City1 = trim($this->txtCRBillCity->Text);
		$objCustomer->Zip1 = trim($this->txtCRBillZip->Text);

		$objCustomer->Address21 = trim($this->txtCRShipAddr1->Text);
		$objCustomer->Address22 = trim($this->txtCRShipAddr2->Text);
		$objCustomer->Country2= trim($this->txtCRShipCountry->SelectedValue);
		$objCustomer->State2= trim($this->txtCRShipState->SelectedValue);
		$objCustomer->City2 = trim($this->txtCRShipCity->Text);
		$objCustomer->Zip2 = trim($this->txtCRShipZip->Text);

		$objCustomer->NewsletterSubscribe = $this->PasswordControl->NewsletterSubscribe->Checked;
		$objCustomer->HtmlEmail = 1;
		$objCustomer->CheckSame = $this->CustomerControl->CheckSame->Checked;
		if (_xls_get_conf('SHIP_SAME_BILLSHIP','0')=='1') $objCustomer->CheckSame = 1;


		//Moderate login
		if(!$objCustomer->AllowLogin && _xls_get_conf('MODERATE_REGISTRATION', 0))
			$objCustomer->AllowLogin = 0;
		else
			$objCustomer->AllowLogin = 1;

		if(function_exists('_custom_before_customer_save'))
			_custom_before_customer_save($objCustomer);

		//****
		if(!$this->isLoggedIn()) {
			$objCustomer->Created= new QDateTime(QDateTime::Now);
			$objCustomer->IdCustomer='';
		}
		
		$objCustomer->Save();
			
		if(function_exists('_custom_after_customer_save'))
			_custom_after_customer_save($objCustomer);

		if(!$this->isLoggedIn()) {

			_xls_mail($objCustomer->Email, _sp("Welcome to ") . _xls_get_conf('STORE_NAME') ,
			_xls_mail_body_from_template(templatenamed('email_customer_register.tpl.php') , array('cust' =>$objCustomer)));
			
			Customer::Login($this->BillingContactControl->Email,$this->PasswordControl->Password1->Text);
			Cart::UpdateCartCustomer();			
		}
			

		if(!$objCustomer->AllowLogin) {
			_xls_display_msg("Thank you for becoming a member. A representative will be in touch with you shortly about your login.");
		} else {
					
				if($url = _xls_stack_get('register_redirect_uri'))
				_rd($url);
			else
				_rd(_xls_site_url("myaccount/pg/"));
		}
		
		
	}


	protected function ToggleCheckoutControls($blnVisibility = false) {
   		$this->pnlLoginRegister->Visible = $blnVisibility;
		        
        $this->CustomerControl->Visible = $blnVisibility;
        $this->ShippingControl->Visible = $blnVisibility;
        $this->VerifyControl->Visible = $blnVisibility;
    }
    

	/**
	 * build_phone_types_widget - builds the phone types listbox
	 * @param none
	 * @return none
	 */
	protected function build_phone_types_widget() {
		$this->txtCRMPhoneType = new XLSListBox($this , 'mphonetype');
		$this->txtCRMPhoneType->AddItem('mobile', 'mobile');
		$this->txtCRMPhoneType->AddItem('work', 'work');
		$this->txtCRMPhoneType->AddItem('home', 'home');

		if($this->customer)
			$this->txtCRMPhoneType->SelectedValue=$this->customer->Mainephonetype;
	}

	/**
	 * build_newsletter_widget - builds the tickbox to choose to news letters
	 * @param none
	 * @return none`
	 */
	protected function build_newsletter_widget() {
		$this->chkNewsletter = new QCheckBox($this, 'newsletter');
		$this->chkNewsletter->Checked = true;
	}

	/**
	 * build_htmlemail_widget - builds the tickbox to choose to receive html formatted emails
	 * @param none
	 * @return none
	 */
	protected function build_htmlemail_widget() {
		$this->chkHtmlEmail = new QCheckBox($this, 'htmlmail');

		if($this->customer)
			$this->chkHtmlEmail->Checked = $this->customer->HtmlEmail;
		else
			$this->chkHtmlEmail->Checked = _xls_get_conf('HTML_EMAIL' , 1);
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		$customer = Customer::GetCurrent();

		if(!$customer)
			$this->customer = null; //for first time insertion
		else
			$this->customer = $customer;

		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->mainPnl->Template = templateNamed('customer_register.tpl.php');

		// Wait icon
		$this->objDefaultWaitIcon = new QWaitIcon($this);

		if($this->isLoggedIn()) {
			$this->crumbs[] = array('link'=>'myaccount/pg/' , 'case'=> '' , 'name'=> _sp('My Account'));
			$this->crumbs[] = array('link'=>'customer_register/pg/' , 'case'=> '' , 'name'=> _sp('Edit Account Details'));
		}
		else $this->crumbs[] = array('link'=>'customer_register/pg/' , 'case'=> '' , 'name'=> _sp('Register'));

		// Define the layout
		
		$this->errSpan = new QPanel($this);
		$this->errSpan->CssClass='customer_reg_err_msg';


		$this->BuildCustomerControl();
		$this->BuildVerifyControl();
		$this->BuildCaptchaControl();
		$this->BuildSubmitControl();
		$this->BuildPasswordControlWrapper();
		$this->BuildPasswordControl();


		$this->UpdateCustomerControl();
		$this->UpdateVerifyControl();
		$this->UpdateCaptchaControl();
		$this->UpdateSubmitControl();
		$this->UpdatePasswordControlWrapper();
		$this->UpdatePasswordControl();


		$this->BindCustomerControl();
		$this->BindVerifyControl();
		$this->BindCaptchaControl();
		$this->BindSubmitControl();
		$this->BindPasswordControlWrapper();
		$this->BindPasswordControl();

		//Force to opt out by presenting checkbox as checked
		if(!$this->isLoggedIn())
			$this->PasswordControl->NewsletterSubscribe->Checked = true;
			

			

	}

	 protected function Form_PreLoad() {
        
        parent::Form_PreLoad();
        
        if ($_SESSION['customer']->Country1=='')
			$_SESSION['customer']->Country1=_xls_get_conf('DEFAULT_COUNTRY');  
		if ($_SESSION['customer']->Country2=='')
			$_SESSION['customer']->Country2=_xls_get_conf('DEFAULT_COUNTRY');
        
        
     }
	
	/**
	 * Form_Validate - Validates all form fields for valid input
	 * @param none
	 * @return none
	 */
	protected function Form_Validate() {
	
		$errors = array();
		
		// check that email address is unique
		$cust = Customer::LoadByEmail(strtolower(trim($this->BillingContactControl->Email->Text)));
		if( $cust && (($this->customer && $this->customer->Rowid != $cust->Rowid ) || (!$this->customer) )) {
			$this->errSpan->Text= _sp("Another customer with this e-mail address already exists. Please login ");
			return false;
		}
		

        if (!$this->ValidateControlAndChildren($this->CustomerControl))
			$errors[] = _sp('Please complete the required fields marked with an asterisk *');


		if (!$this->isLoggedIn()) {
		
			if ($this->txtCRPass->Text=='')
				$errors[] .= _sp("Password Required.");

			if ($this->txtCRPass->Text != $this->txtCRConfPass->Text)
				$errors[] .= _sp("Passwords do not match.");

			if($this->txtCREmail->Text != $this->txtCRConfEmail->Text)
				$errors[] .= _sp("E-mail addresses do not match.");
					
		}			
		
		if ($this->isLoggedIn()) {
	
			//We are changing password
			if (strlen($this->txtCRPass->Text)>0)
				if ($this->txtCRPass->Text != $this->txtCRPassConf->Text)
					$errors[] .= _sp("Passwords do not match.");
		}
		
		
		if (count($errors)) {
			$this->errSpan->Text = join('<br />', $errors);
			$this->ToggleCheckoutControls(true);
			return false;
		}

		$this->errSpan->Text='';
		return true;
	
	}

    
	public function require_ssl() {
		return true;
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
                
            case 'txtCRConfEmail':
                return $this->BillingContactControl->EmailConfirm;    
                
            case 'txtCRPass': 
                return $this->PasswordControl->Password1;

            case 'txtCRConfPass': 
                return $this->PasswordControl->Password2;
                
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

            case 'pnlPassword':
                return $this->PasswordControlWrapper;

            case 'pnlBillingAdde':
                return $this->BillingContactControl->Address;

            case 'pnlShippingAdde':
                return $this->ShippingContactControl;

            case 'lstCRShipPrevious':
                return $this->PreviousAddressControl;

            case 'pnlCart':
                return $this->CartControl;

            case 'pnlVerify':
                return $this->VerifyControl;

            case 'lblVerifyImage': 
                	return $this->CaptchaControl->Code;

            case 'txtCRVerify':
                	return $this->CaptchaControl->Input;

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
    
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_cregister::Run('xlsws_cregister', templateNamed('index.tpl.php'));
