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
 * xlsws_fpassword class
 * This is the controller class to generate a forgot password email
 */
class xlsws_fpassword extends xlsws_index {
	protected $txtFEmail; //The email address for the password missing
	protected $txtFVerify; //the verification captcha code (unused, ignore)
	protected $btnSave; //the save button (unused, ignore)
	protected $lblVerifyImage; //ignore

	/*Constructor for this module, unused by views*/
	protected function build_main() {
		$this->mainPnl = new QPanel($this,'MainPanel');
		$this->mainPnl->Template = templateNamed('forgot_password.tpl.php');

		$this->crumbs[] = array('link'=>'forgot-password/pg/' , 'case'=> '' , 'name'=> _sp('Forgot Password?'));

		//email
		$this->txtFEmail = new XLSTextBox($this);
		$this->txtFEmail->Required =  $this->txtFEmail->ValidateTrimmed =  true;
		$this->txtFEmail->AddAction(new QEnterKeyEvent(), new QServerAction("getPassword"));
		$this->txtFEmail->CausesValidation = true;

		//image verification
		$this->lblVerifyImage = new QPanel($this);
		$this->lblVerifyImage->CssClass='customer_reg_draw_verify';
		$this->lblVerifyImage->Text=_xls_verify_img();

		// verify code
		$this->txtFVerify = new XLSTextBox($this);
		$this->txtFVerify->AddAction(new QEnterKeyEvent(), new QServerAction("getPassword"));
		$this->txtFVerify->CausesValidation = true;

		//save button
		$this->btnSave = new QButton($this);
		$this->btnSave->Text = _sp('Get Password');
		$this->btnSave->CausesValidation = true;

		$this->btnSave->AddAction(new QClickEvent(), new QServerAction('getPassword'));
	}

	/**
	 * getPassword - Get a new temporary password and send it
	 * country based on country chosen
	 * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
	 * @return none
	 */
	public function getPassword($strFormId, $strControlId, $strParameter) {
		$cust = Customer::LoadByEmail(strtolower(trim($this->txtFEmail->Text)));

		if($cust && $cust->AllowLogin==0) {
			_xls_display_msg(
				_sp("Your login is not active yet. ") .
				_sp("Please ") . "<a href=\"index.php?xlspg=contact_us\">" . _sp("contact us") . "</a>" .
				_sp(" if you need urgent assistance.")
			);

		} elseif($cust) {
			_xls_mail(
				_xls_mail_name($cust->Mainname, $cust->Email),
				_sp("Password reminder"),
				_xls_mail_body_from_template(templatenamed('email_forgot_password.tpl.php'), array('cust' =>$cust))
			);

			_xls_display_msg(_sp("Your password has been sent to your e-mail address at " . $cust->Email));
		} else {
			_xls_display_msg(_sp("Sorry, we could not locate the e-mail address " . $this->txtFEmail->Text));
		}
	}

	/**
	 * Form_Validate - Validates all form fields for valid input
	 * @param none
	 * @return none
	 */
	protected function Form_Validate() {
		global $_SESSION;

		if(_xls_verify_img_txt() != $this->txtFVerify->Text) {
			$this->txtFVerify->Warning = "Wrong Verification Code.";
			return false;
		}

		// check that email address is unique
		$cust = Customer::LoadByEmail(strtolower(trim($this->txtFEmail->Text)));

		if(!$cust) {
			$this->txtFEmail->Warning = _sp("E-mail address specified does not exist in our customer registry.");
			return false;
		}

		return true;
	}
}

if(!defined('CUSTOM_STOP_XLSWS'))
	xlsws_fpassword::Run('xlsws_fpassword', templateNamed('index.tpl.php'));
