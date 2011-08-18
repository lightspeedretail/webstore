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
	/*see xlsws_index for shared widgets*/
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
	protected $errSpan; //the span that generates or shows the error
	protected $lblVerifyImage; //the verify image label that shows the captcha image

	protected $languages; //list of languages to choose from
	protected $currencies; //list of currencies to choose from (unused)
	protected $phone_types; //list of phone types to choose fromn

	public $customer; //the customer object if any

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		//billing details
		$this->build_fname_widget($this, 'firstname');
		$this->build_lname_widget($this, 'lastname');
		$this->build_company_widget($this , 'company');
		$this->build_phone_widget($this , 'mphone');
		$this->build_phone_types_widget();

		$this->build_add1_widget($this->pnlBillingAdde , 'billstreet1');
		$this->build_add2_widget($this->pnlBillingAdde , 'billstreet2');
		$this->build_country_widget($this->pnlBillingAdde , 'billcountry');
		$this->build_state_widget($this->pnlBillingAdde , 'billstate');
		$this->build_city_widget($this->pnlBillingAdde , 'billcity');
		$this->build_zip_widget($this->pnlBillingAdde , 'billzip');
		$this->build_shipsame_widget();

		//shipping details
		$this->build_add1_widget($this->pnlShippingAdde , 'shipstreet1');
		$this->build_add2_widget($this->pnlShippingAdde , 'shipstreet2');
		$this->build_country_widget($this->pnlShippingAdde , 'shipcountry');
		$this->build_state_widget($this->pnlShippingAdde , 'shipstate');
		$this->build_city_widget($this->pnlShippingAdde , 'shipcity');
		$this->build_zip_widget($this->pnlShippingAdde , 'shipzip');

		$this->build_email_widget($this);
		$this->build_email_confirm_widget();
		$this->build_password_widget();
		$this->build_password_confirm_widget();
		$this->build_newsletter_widget();
		$this->build_htmlemail_widget();

		$this->build_captcha_widget($this);
	}

	/**
	 * checkLoginShippingFields - checks and populates shipping address fields for if a client
	 * has an already entered shipping address
	 * @param none
	 * @return none
	 */
	private function checkLoginShippingFields() {
		//Address1
		if($this->customer)
			$this->txtCRShipAddr1->Text=$this->customer->Address21;

		//Address2
		if($this->customer)
			$this->txtCRShipAddr2->Text=$this->customer->Address22;

		//Country
		if($this->customer)
			$this->txtCRShipCountry->SelectedValue=$this->customer->Country2;
		else
			$this->txtCRShipCountry->SelectedValue=_xls_get_conf('DEFAULT_COUNTRY');

		if($this->customer) {
			$this->txtCRShipState->SelectedValue=$this->customer->State2;
		}

		//City
		if($this->customer)
			$this->txtCRShipCity->Text=$this->customer->City2;

		// Postal/Zip Code
		if($this->customer)
			$this->txtCRShipZip->Text=$this->customer->Zip2;
	}

	/**
	 * build_email_confirm_widget - builds the confirm email input type textbox on customer register
	 * @param none
	 * @return none
	 */
	protected function build_email_confirm_widget() {
		$this->txtCRConfEmail = new XLSTextBox($this , 'emailconf');

		if($this->customer)
			$this->txtCRConfEmail->Text=$this->customer->Email;

		$this->txtCRConfEmail->Required =  $this->txtCRConfEmail->ValidateTrimmed =  true;
	}

	/**
	 * build_password_widget - builds the password input type on customer register
	 * @param none
	 * @return none
	 */
	protected function build_password_widget() {
		$this->txtCRPass = new XLSTextBox($this , 'password');
		$this->txtCRPass->TextMode =QTextMode::Password;
		$this->txtCRPass->Required = true;
		$this->txtCRPass->ValidateTrimmed = true;
		$this->txtCRPass->MinLength = _xls_get_conf('MIN_PASSWORD_LEN' , 6);
	}

	/**
	 * build_captcha_widget - builds the captcha code with the input textbox to enter this code
	 * @param Qpanel - the Qpanel these widgets should be laid out in
	 * @return none
	 */
	protected function build_captcha_widget($qpanel) {
		$this->lblVerifyImage = new QPanel($qpanel);
		$this->lblVerifyImage->CssClass='customer_reg_draw_verify';
		$this->lblVerifyImage->Text=_xls_verify_img();

		// verify code
		$this->txtCRVerify = new XLSTextBox($this);
		$this->txtCRVerify->SetCustomAttribute("autocomplete" , "off");
	}

	/**
	 * build_password_confirm_widget - builds the confirm password input type password on customer register
	 * @param none
	 * @return none
	 */
	protected function build_password_confirm_widget() {
		$this->txtCRConfPass = new XLSTextBox($this , 'passwordconf');
		$this->txtCRConfPass->TextMode =QTextMode::Password;

		if($this->customer) {
			$this->txtCRPass->Required = $this->txtCRConfPass->Required = false;
			$this->txtCRPass->MinLength = null;
		}
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
	 * bind_widgets - binds callback actions for the widgets
	 * @param none
	 * @return none
	 */
	protected function bind_widgets() {
		$this->txtCRBillAddr1->AddAction(new QChangeEvent(), new QAjaxAction('txtBillAddr1_Change'));
		$this->txtCRBillAddr2->AddAction(new QChangeEvent(), new QAjaxAction('txtBillAddr2_Change'));
		$this->txtCRBillState->AddAction(new QChangeEvent(), new QAjaxAction('txtBillState_Change'));
		$this->txtCRBillCity->AddAction(new QChangeEvent(), new QAjaxAction('txtBillCity_Change'));
		$this->txtCRBillZip->AddAction(new QChangeEvent(), new QAjaxAction('txtBillZip_Change'));
		$this->txtCRBillCountry->AddAction(new QChangeEvent(), new QAjaxAction('txtBillCountry_Change'));
		$this->btnSave->AddAction(new QClickEvent(), new QServerAction('btnSave_Click'));
		$this->txtCRShipCountry->AddAction(new QChangeEvent() , new QAjaxAction('shipCountry_Change'));
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

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('customer_register.tpl.php');

		// Wait icon
		$this->objDefaultWaitIcon = new QWaitIcon($this);

		if(!$this->customer)
			$this->crumbs[] = array('key'=>'xlspg=customer_register' , 'case'=> '' , 'name'=> _sp('Register'));
		else{
			$this->crumbs[] = array('key'=>'xlspg=myaccount' , 'case'=> '' , 'name'=> _sp('My Account'));
			$this->crumbs[] = array('key'=>'xlspg=customer_register' , 'case'=> '' , 'name'=> _sp('Edit Account Details'));
		}

		// Define the layout

		//error msg
		$this->errSpan = new QPanel($this);
		$this->errSpan->CssClass='customer_reg_err_msg';

		//************ Billing panel
		$this->pnlBillingAdde = new QPanel($this);
		$this->pnlBillingAdde->CssClass = "c1";
		$this->pnlBillingAdde->Template = templateNamed('reg_billing_address.tpl.php');

		//************ Shipping panel
		$this->pnlShippingAdde = new QPanel($this);
		$this->pnlShippingAdde->CssClass = "c2";
		$this->pnlShippingAdde->Template = templateNamed('reg_shipping_address.tpl.php');

		$this->build_widgets();

		//save button, not intended to be extended or overloaded
		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Submit');
		$this->btnSave->CausesValidation = true;
		$this->btnSave->PrimaryButton = true;

		$this->bind_widgets();
		$this->checkLoginShippingFields();

		$this->shipping_elements(true);

		Visitor::add_view_log('', ViewLogType::registration);
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
			$states = State::LoadArrayByCountryCode($country_code , QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder , QQN::State()->State)));

			$this->txtCRShipState->RemoveAllItems();
			foreach($states as $state) {
				$this->txtCRShipState->AddItem($state->State, $state->Code);
			}

			if($this->chkSame->Checked) {
				$this->txtCRBillCountry->SelectedValue=$this->txtCRShipCountry->SelectedValue;

				$country_code = $this->txtCRShipCountry->SelectedValue;

				if ($country_code) {
					$states = State::LoadArrayByCountryCode($country_code , QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder , QQN::State()->State)));

					$this->txtCRBillState->RemoveAllItems();

					foreach($states as $state) {
						$this->txtCRBillState->AddItem($state->State, $state->Code);
					}
				}
			}
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
			$states = State::LoadArrayByCountryCode($country_code , QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder , QQN::State()->State)));

			$this->txtCRBillState->RemoveAllItems();

			foreach($states as $state) {
				$this->txtCRBillState->AddItem($state->State, $state->Code);
			}

			if(count($states) > 0)
				$this->txtCRBillState->focus();
		}

		if($this->chkSame->Checked) {
			$this->txtCRShipCountry->SelectedValue = $country_code;
			$this->shipCountry_Change($strFormId, $strControlId, $strParameter);
		}
	}

	/**
	 * btnSave_Click - Function that fires when you click the submit button
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function btnSave_Click($strFormId, $strControlId, $strParameter) {
		if($this->customer)
			$objCustomer = Customer::LoadByRowId($this->customer->Rowid);
		else
			$objCustomer = new Customer();

		$objCustomer->Email= strtolower(trim($this->txtCREmail->Text));
		$objCustomer->Password = md5(trim($this->txtCRPass->Text));
		$objCustomer->Firstname = trim($this->txtCRFName->Text);
		$objCustomer->Lastname = trim($this->txtCRLName->Text);
		$objCustomer->Mainname = (($this->customer) && ($this->customer->Mainname != '')) ? $this->customer->Mainname : (trim($this->txtCRFName->Text) . " " . trim($this->txtCRLName->Text));
		$objCustomer->Company = trim($this->txtCRCompany->Text);
		$objCustomer->Homepage = trim($this->txtCRHomePage->Text);
		$objCustomer->Mainephonetype = trim($this->txtCRMPhoneType->SelectedValue);
		$objCustomer->Mainphone = trim($this->txtCRMPhone->Text);
		$objCustomer->Phonetype1 = trim($this->txtCRPhoneType1->SelectedValue);
		$objCustomer->Phone1 = trim($this->txtCRPhone1->Text);
		$objCustomer->Phonetype2 = trim($this->txtCRPhoneType2->SelectedValue);
		$objCustomer->Phone2 = trim($this->txtCRPhone2->Text);
		$objCustomer->Phonetype3 = trim($this->txtCRPhoneType3->SelectedValue);
		$objCustomer->Phone3 = trim($this->txtCRPhone3->Text);
		$objCustomer->Phonetype4 = trim($this->txtCRPhoneType4->SelectedValue);
		$objCustomer->Phone4 = trim($this->txtCRPhone4->Text);

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

		$objCustomer->NewsletterSubscribe = $this->chkNewsletter->Checked;
		$objCustomer->HtmlEmail = $this->chkHtmlEmail->Checked;

		//Moderate login
		if(!$objCustomer->AllowLogin && _xls_get_conf('MODERATE_REGISTRATION', 0))
			$objCustomer->AllowLogin = 0;
		else
			$objCustomer->AllowLogin = 1;

		if(function_exists('_custom_before_customer_save'))
			_custom_before_customer_save($objCustomer);

		//****
		if(!$this->customer || !$this->customer->Rowid) {
			$objCustomer->Created= new QDateTime(QDateTime::Now);
			$objCustomer->IdCustomer='';
			$objCustomer->Save();

			// remind old password
			$objCustomer->Password = trim($this->txtCRPass->Text);

			_xls_mail(_xls_mail_name($objCustomer->Mainname , $objCustomer->Email) , _sp("Welcome to ") . _xls_get_conf('STORE_NAME') , _xls_mail_body_from_template(templatenamed('email_customer_register.tpl.php') , array('cust' =>$objCustomer)));
		} else
			$objCustomer->Save();

		if(function_exists('_custom_after_customer_save'))
			_custom_after_customer_save($objCustomer);

		if(!$objCustomer->AllowLogin) {
			_xls_display_msg("Thank you for becoming a member. A representative will be in touch with you shortly about your login.");
		} else {
			Customer::Login($objCustomer->Email , $objCustomer->Password);

			if($url = _xls_stack_get('register_redirect_uri'))
				_rd($url);
			else
				_rd('index.php?xlspg=myaccount');
		}
	}

	/*DEPRECIATED*/
	public function chkAddiContact_Click($strFormId, $strControlId, $strParameter) {
		if($this->chkAdditionalContact->Checked) {
			QApplication::ExecuteJavaScript("document.getElementById('customer_reg_addi_contact').style.display='block';", true);
		} else {
			QApplication::ExecuteJavaScript("document.getElementById('customer_reg_addi_contact').style.display='none';", true);
		}
	}

	/**
	 * shipping_elements - Enable or disable shipping address fields on the checkout form dynamically
	 * @param boolean true or false to enable or disable an element
	 * @return none
	 */
	protected function shipping_elements($enable) {
		$this->txtCRShipAddr1->Enabled = $enable;
		$this->txtCRShipAddr2->Enabled = $enable;
		$this->txtCRShipCountry->Enabled = $enable;
		$this->txtCRShipState->Enabled = $enable;
		$this->txtCRShipCity->Enabled = $enable;
		$this->txtCRShipZip->Enabled = $enable;
	}


	/**
	 * chkSame_Click - Event handler for when someone checks shipping address is the same as billing address
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function chkSame_Click($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked) {
			$this->pnlShippingAdde->Opacity = 50;

			$this->txtCRShipAddr1->Text = $this->txtCRBillAddr1->Text;
			$this->txtCRShipAddr2->Text = $this->txtCRBillAddr2->Text;
			$this->txtCRShipCountry->SelectedValue = $this->txtCRBillCountry->SelectedValue;

			$country_code = $this->txtCRBillCountry->SelectedValue;

			if ($country_code) {
				$states = State::LoadArrayByCountryCode($country_code, QQ::Clause(QQ::OrderBy(QQN::State()->SortOrder, QQN::State()->State)));

				$this->txtCRShipState->RemoveAllItems();
				foreach($states as $state) {
					$this->txtCRShipState->AddItem($state->State, $state->Code);
				}
			}

			$this->txtCRShipState->SelectedValue = $this->txtCRBillState->SelectedValue;
			$this->txtCRShipCity->Text = $this->txtCRBillCity->Text;
			$this->txtCRShipZip->Text = $this->txtCRBillZip->Text;
			$this->shipping_elements(false);
		} else {
			$this->shipping_elements(true);
			$this->pnlShippingAdde->Opacity = 100;
		}
	}

	/**
	 * txtBillAddr1_Change - Event that fires when billing address 1 changes
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function txtBillAddr1_Change($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked) {
			$this->txtCRShipAddr1->Text=$this->txtCRBillAddr1->Text;
		}
	}

	/**
	 * txtBillAddr2_Change - Event that fires when billing address 2 changes
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function txtBillAddr2_Change($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked) {
			$this->txtCRShipAddr2->Text=$this->txtCRBillAddr2->Text;
		}
	}

	/**
	 * txtBillCity_Change - Event that fires when billing city changes
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function txtBillCity_Change($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked){
			$this->txtCRShipCity->Text=$this->txtCRBillCity->Text;
		}
	}

	/**
	 * txtBillZip_Change - Event that fires when billing zipcode changes
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function txtBillZip_Change($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked) {
			$this->txtCRShipZip->Text=$this->txtCRBillZip->Text;
		}
	}

	/**
	 * txtBillState_Change - Event that fires when billing state changes
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function txtBillState_Change($strFormId, $strControlId, $strParameter) {
		if($this->chkSame->Checked) {
			$this->txtCRShipState->SelectedValue=$this->txtCRBillState->SelectedValue;
		}
	}

	/**
	 * Form_Validate - Validates all form fields for valid input
	 * @param none
	 * @return none
	 */
	protected function Form_Validate() {
		global $_SESSION;

		$this->errSpan->Text='';
		$this->errSpan->CssClass='customer_reg_err_msg';

		if(_xls_verify_img_txt() != (($this->txtCRVerify->Text))) {
			$this->errSpan->Text= _sp("Wrong Verification Code.");
			return false;
		}

		elseif($this->txtCREmail->Text == "" || $this->txtCRMPhone->Text == "" || $this->txtCRFName->Text == "" || $this->txtCRLName->Text == "" || $this->txtCRBillAddr1->Text == "" || $this->txtCRBillCountry->SelectedValue == "" || $this->txtCRBillCity->Text == "" || $this->txtCRBillZip->Text == "" ) {
			$this->errSpan->Text= _sp('Please complete required fields.  Required fields are marked with an asterisk *');
			return false;
		}

		if(!preg_match( '/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i', $this->txtCREmail->Text )) {
			$email=$this->txtCREmail->Text;
			$this->errSpan->Text= $email . _sp(" - Is Not A Correct E-mail Address.");
			return false;
		}

		if($this->txtCREmail->Text != $this->txtCRConfEmail->Text) {
			$this->errSpan->Text= $this->txtCRConfEmail->Warning =_sp("E-mail addresses do not match.");
			return false;
		}

		// validate zip code
		$country = Country::LoadByCode($this->txtCRBillCountry->SelectedValue);
		if ($country)
			if (!$this->txtCRBillZip->Validate($country->ZipValidatePreg)) {
				$this->errSpan->Text = _sp($this->txtCRBillZip->LabelForInvalid);
				return false;
			}

		if ($this->txtCRBillCountry->SelectedValue != $this->txtCRShipCountry->SelectedValue)
			$country = Country::LoadByCode($this->txtCRShipCountry->SelectedValue);

		if ($country)
			if (!$this->txtCRShipZip->Validate($country->ZipValidatePreg)) {
				$this->errSpan->Text = _sp($this->txtCRShipZip->LabelForInvalid);
				return false;
			}

		if($this->txtCRPass->Text != $this->txtCRConfPass->Text){
			$this->errSpan->Text= $this->txtCRConfPass->Warning = _sp("Passwords do not match");
			return false;
		}

		if(trim($this->txtCRPass->Text) != '' || !$this->customer){
			if($error = Customer::pwdStrength($this->txtCRPass->Text)){
				$this->errSpan->Text= $this->txtCRPass->Warning = $error;
				return false;
			}
		}

		// check that email address is unique
		$cust = Customer::LoadByEmail(strtolower(trim($this->txtCREmail->Text)));

		if($cust  && (($this->customer && $this->customer->Rowid != $cust->Rowid ) || (!$this->customer) )){
			$this->errSpan->Text= $this->txtCREmail->Warning = _sp("Another customer with this e-mail address already exists. Please login ");
			return false;
		}

		$this->errSpan->Text='';
		return true;
	}

	public function require_ssl() {
		return true;
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_cregister::Run('xlsws_cregister', templateNamed('index.tpl.php'));
