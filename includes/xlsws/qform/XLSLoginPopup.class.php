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
 * Dialog box detailing the Login / Password box
 */
class XLSLoginPopup extends QDialogBox {
	// Define standard messages
	public $strNoEmail = 'Please enter your email address';
	public $strLoginInactive = 'Your login is not active yet. Please contact us if you need assistance.';
	public $strPasswordSent = 'Your password has been sent to your e-mail address at %s';
	public $strEmailInexistant = 'Sorry, we could not locate the e-mail address %s';

	// Define QTextBox
	public $txtEmail;
	public $txtPwd;

	// Define QLabel
	public $lblErr;

	// Define QButton
	public $btnLogin;

	// Define QControlProxy
	public $pxyCancel;
	public $pxyForgotPwd;

	/**
	 * Overload the constructor to initialize our fields
	 * @param obj $objParentObject
	 * @param int $strControlId
	 */
	public function __construct($objParentObject, $strControlId = null) {
		parent::__construct($objParentObject, $strControlId);

		$this->strTemplate = templateNamed('login_box.tpl.php');

		$this->Visible = false;

		$this->txtEmail = new QTextBox($this , "loginEmail");
		$this->txtEmail->AddAction(new QEnterKeyEvent(),
			new QAjaxAction("performLogin"));
		$this->txtEmail->AddAction(new QEnterKeyEvent(),
			new QTerminateAction());

		$this->txtPwd = new QTextBox($this  , "loginPassword");
		$this->txtPwd->TextMode = QTextMode::Password;
		$this->txtPwd->AddAction(new QEnterKeyEvent(),
			new QAjaxControlAction( $this, "performLogin"));
		$this->txtPwd->AddAction(new QEnterKeyEvent(),
			new QTerminateAction());

		$this->lblErr = new QLabel($this);
		$this->lblErr->Text = '';

		$this->btnLogin = new QButton($this);
		$this->btnLogin->AddAction(new QClickEvent(),
			new QAjaxControlAction( $this, "performLogin"));
		$this->btnLogin->Text = _sp('Sign In');

		$this->pxyForgotPwd = new QControlProxy($this);
		$this->pxyForgotPwd->AddAction(new QClickEvent(),
			new QAjaxControlAction($this , "performForgotPassword"));
		$this->pxyForgotPwd->AddAction(new QClickEvent(),
			new QTerminateAction());

		$this->pxyCancel = new QControlProxy($this);
		$this->pxyCancel->AddAction(new QClickEvent(),
			new QAjaxControlAction($this, 'doCancel'));
		$this->pxyCancel->AddAction(new QClickEvent(),
			new QTerminateAction());
	}

	public function performLogin($strFormId, $strControlId, $strParameter) {
		$email = strtolower(trim($this->txtEmail->Text));

		if($email == '') {
			$this->lblErr->Text = (_sp($this->strNoEmail));
			return;
		}

		$this->Form->performLogin($strFormId, $strControlId, $strParameter);

	}

	public function doShow() {
		$this->ShowDialogBox();
		$this->txtEmail->Focus();
	}

	public function doCancel($strFormId, $strControlId, $strParameter) {
		$this->HideDialogBox();
	}

	public function performForgotPassword($strFormId, $strControlId,
		$strParameter) {

		$email = strtolower(trim($this->txtEmail->Text));

		if(!$email){
			$this->lblErr->Text = (_sp($this->strNoEmail));
			return;
		}
		$cust = Customer::LoadByEmail($email);

		if($cust && $cust->AllowLogin==0) {
			_xls_display_msg(_sp($this->strLoginInactive));
		}
		else if ($cust) {
			$cust->GenerateTmpPwd();

			_xls_mail(_xls_mail_name($cust->Mainname , $cust->Email),
				_sp("Password reminder"),
				_xls_mail_body_from_template(templatenamed(
					'email_forgot_password.tpl.php'),
					array('cust' =>$cust)
				)
			);

			$this->lblErr->Text = sprintf(
				_sp($this->strPasswordSent), $cust->Email);
		}
		else {
			$this->lblErr->Text = (sprintf(
				_sp($this->strEmailInexistant), $email));
		}
	}
}
