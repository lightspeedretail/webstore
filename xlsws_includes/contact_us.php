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
 * xlsws_contact_us class
 * This is the controller class for the contact us page
 * This class is responsible for querying the database for various aspects needed on this page
 * and assigning template variables to the views related to the contact us page
 */
class xlsws_contact_us extends xlsws_index {
	protected $txtName; //input text box for name
	protected $txtEmail; //input text box for email
	protected $txtPhone; //input text box for phone number
	protected $txtSubject; //input text box for subject
	protected $txtMsg; //input textarea for the message or comments
	protected $txtVerify; //input text box for the captcha verification image
	protected $lblVerifyImage; //the label where the dynamic captcha image renders
	protected $btnSubmit; //input button to submit the contact form
	protected $lblError; //the label that shows an error message
	protected $page = ''; //the current page

	/**
	 * build_name_widget - builds the input type name textbox
	 * @param none
	 * @return none
	 */
	protected function build_name_widget() {
		$this->txtName = new XLSTextBox($this->mainPnl);
		$this->txtName->Required = true;
	}

	/**
	 * build_email_widget - builds the input type email textbox
	 * @param none
	 * @return none
	 */
	protected function build_email_widget() {
		$this->txtEmail = new XLSTextBox($this->mainPnl);
		$this->txtEmail->Required = true;
	}

	/**
	 * build_phone_widget - builds the input type phone number textbox
	 * @param none
	 * @return none
	 */
	protected function build_phone_widget() {
		$this->txtPhone = new XLSTextBox($this->mainPnl);
	}

	/**
	 * build_subject_widget - builds the input type subject textbox
	 * @param none
	 * @return none
	 */
	protected function build_subject_widget() {
		$this->txtSubject = new XLSTextBox($this->mainPnl);
		$this->txtSubject->Required = true;
	}

	/**
	 * build_comments_widget - builds the input type comments textarea
	 * @param none
	 * @return none
	 */
	protected function build_comments_widget() {
		$this->txtMsg = new QTextBox($this->mainPnl);
		$this->txtMsg->TextMode = QTextMode::MultiLine;
		$this->txtMsg->Required = true;
	}

	/**
	 * build_captcha_widget - builds the captcha code with the input textbox to enter this code
	 * @param Qpanel - the Qpanel these widgets should be laid out in (unused in this case)
	 * @return none
	 */
	protected function build_captcha_widget($qpanel) {
		//image verification

		$this->lblVerifyImage = new QPanel($this);
		$this->lblVerifyImage->CssClass='customer_reg_draw_verify';
		$this->lblVerifyImage->Text=_xls_verify_img();

		// verify code
		$this->txtVerify = new XLSTextBox($this);
		$this->txtVerify->Required = true;
		$this->txtVerify->SetCustomAttribute("autocomplete" , "off");
	}

	/**
	 * build_widgets - builds the widgets needed for the template
	 * @param none
	 * @return none
	 */
	protected function build_widgets() {
		$this->build_name_widget();
		$this->build_email_widget();
		$this->build_subject_widget();
		$this->build_phone_widget();
		$this->build_comments_widget();
		$this->build_captcha_widget();
	}

	/**
	 * checkLoginFields - checks and populates fields for if a client
	 * has an already logged in
	 * @param none
	 * @return none
	 */
	private function checkLoginFields() {
		if($customer)
			$this->txtName->Text = $customer->Mainname;

		if($customer)
			$this->txtEmail->Text = $customer->Email;

		if($customer)
			$this->txtPhone->Text = $customer->Mainphone;
	}

	/**
	 * build_main - constructor for this controller
	 * @param none
	 * @return none
	 */
	protected function build_main() {
		$customer = Customer::GetCurrent();

		$pageR = CustomPage::LoadByKey('contactus');

		if($pageR)
			$this->page = $pageR->Page;

		$this->mainPnl = new QPanel($this);
		$this->mainPnl->Template = templateNamed('contact_us.tpl.php');

		$this->crumbs[] = array('key'=>'xlspg=contact_us' , 'case'=> '' , 'name'=> _sp($pageR->Title));

		_xls_add_page_title(_sp($pageR->Title));

		$this->lblError = new QLabel($this->mainPnl);

		$this->build_widgets();
		$this->checkLoginFields();
		/*The controls below are not intended to be modified*/
		//save button
		$this->btnSubmit = new QButton($this);
		$this->btnSubmit->Text = _sp('Submit');
		$this->btnSubmit->AddAction(new QClickEvent() , new QServerAction('butSubmit_click'));
		$this->btnSubmit->CausesValidation = true;

		Visitor::add_view_log('', ViewLogType::contactus);
	}


	/**
	 * Form_Validate - Validates all form fields for valid input
	 * @param none
	 * @return none
	 */
	protected function Form_Validate() {
		$this->lblError->Text='';
		$this->lblError->CssClass='customer_reg_err_msg';

		if(_xls_verify_img_txt() != (($this->txtVerify->Text))){
			$this->lblError->Text= "Wrong Verification Code.";
			return false;
		}

		if(!preg_match('/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i', $this->txtEmail->Text )){
			$email=$this->txtEmail->Text;
			$this->lblError->Text= $email . _sp(" - Is Not A Correct E-mail Address");
			return false;
		}

		_xls_mail(
			_xls_get_conf('ORDER_FROM'),
			_xls_get_conf('STORE_NAME' , 'Web') . " " . _sp("Inquiry"). " : " . $this->txtSubject->Text,
			_xls_mail_body_from_template(
				templateNamed('email_msg.tpl.php'),
				array(
					'msg' => sprintf(
						" Customer Name %s <br/>\nCustomer Email %s<br/>\n Customer Phone %s<br/>\n<br/>\n%s",
						$this->txtName->Text,
						$this->txtEmail->Text,
						$this->txtPhone->Text,
						nl2br(strip_tags($this->txtMsg->Text))
					)
				)
			)
		);

		return true;
	}

	/**
	 * btnSubmit_Click - Submits the contact form
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	protected function butSubmit_click($strFormId, $strControlId, $strParameter) {
		_xls_display_msg(_sp("Thank you for your inquiry. A representative will contact you shortly."));
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_contact_us::Run('xlsws_contact_us', templateNamed('index.tpl.php'));
