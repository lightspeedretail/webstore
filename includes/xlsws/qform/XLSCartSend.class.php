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

   /*
     *  XLSCartSend
     * 
     * Creates the Send Cart modal box
     * 
     *             
     */


class XLSCartSend extends QDialogBox{
		
		public $txtToEmail;
		public $txtToName;
		public $txtMsg;
		
		public $txtFromEmail;
		public $txtFromName;
		
		public $btnSend;
		public $btnCancel;
		
		public $lblMsg;
		
		public $lblVerifyImage;
		public $txtVerify;
		
		public $objDefaultWaitIcon;
		
		public $errSpan;
		
		
		
		
        public function __construct($objParentObject, $strControlId = null) {
        	
        	
            parent::__construct($objParentObject, $strControlId);

            $this->strTemplate = templateNamed('cart_send.tpl.php');
            
            $customer = Customer::GetCurrent();
            
			// Wait icon
			$this->objDefaultWaitIcon = new QWaitIcon($this);

            $this->txtToEmail = new QTextBox($this);
            $this->txtToEmail->Required = true;
            $this->txtToEmail->ValidateTrimmed = true;
            $this->addEnterKeySendActions($this->txtToEmail);
            

            
            $this->txtToName = new QTextBox($this);
            $this->txtToName->Required = true;
            $this->txtToName->ValidateTrimmed = true;
            $this->addEnterKeySendActions($this->txtToName);
            
            
            $this->txtMsg = new QTextBox($this);
            $this->txtMsg->TextMode = QTextMode::MultiLine;

            
            $this->txtFromName = new QTextBox($this);
            $this->txtFromName->Required = true;
            $this->txtFromName->ValidateTrimmed = true;
            $this->addEnterKeySendActions($this->txtFromName);
            
            if($customer)
            	$this->txtFromName->Text = $customer->Mainname;
            
            $this->txtFromEmail = new QTextBox($this);
            $this->txtFromEmail->Required = true;
            $this->txtFromEmail->ValidateTrimmed = true;
            $this->addEnterKeySendActions($this->txtFromEmail);

            if($customer)
            	$this->txtFromEmail->Text = $customer->Email;
            
            

			
			$this->lblVerifyImage = new QPanel($this);
			$this->lblVerifyImage->CssClass='modal_reg_draw_verify';
			$this->lblVerifyImage->Text=_xls_verify_img();			

			
			// verify code
			$this->txtVerify = new QTextBox($this);
			$this->txtVerify->Required = true;
            $this->txtVerify->ValidateTrimmed = true;
            $this->addEnterKeySendActions($this->txtVerify);
						
			
            $this->errSpan = new QLabel($this);
            $this->errSpan->CssClass='modal_reg_err_msg';	
			
            
            $this->btnSend = new QButton($this);
            $this->btnSend->Text = _sp('Send');
            $this->btnSend->AddAction(new QClickEvent(), new QToggleDisplayAction($this->objDefaultWaitIcon));
            $this->btnSend->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'doSend'));
            $this->btnSend->CausesValidation = true;
            
            $this->btnCancel = new QButton($this);
            $this->btnCancel->Text = _sp('Cancel');
            $this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'doCancel'));
            
            
        }
        
        protected function addEnterKeySendActions($textField)
        {
        	$textField->CausesValidation = true;
        	$textField->AddAction(new QEnterKeyEvent(), new QToggleDisplayAction($this->objDefaultWaitIcon));
        	$textField->AddAction(new QEnterKeyEvent(), new QAjaxControlAction($this, 'doSend'));
                $textField->AddAction(new QEnterKeyEvent(), new QTerminateAction());
        	
        }
		
		
        public function Validate(){


        	$valid = true;

        	if(_xls_verify_img_txt() != (($this->txtVerify->Text))){
				$this->txtVerify->Warning = _sp("Wrong Verification Code.");
				$valid = false && $valid;
			
			}       	

			if(!isValidEmail($this->txtFromEmail->Text)){
				$this->txtFromEmail->Warning = _sp("Invalid E-mail Address.");
				$valid = false && $valid;
			}
			
			if(!isValidEmail($this->txtToEmail->Text)){
				$this->txtToEmail->Warning = _sp("Invalid E-mail Address.");
				$valid = false && $valid;
			}
			
        	$this->objDefaultWaitIcon->Display = false;			
        	
        	return ($valid && parent::Validate());
        	
        }
        
        
        public function doCancel($strFormId, $strControlId, $strParameter){
        	$this->HideDialogBox();
        }
        

        public function doSend($strFormId, $strControlId, $strParameter){
        	
        	// Clone the current cart
        	$cart = Cart::CloneCart();
        	
        	$cart->PrintedNotes =  $this->txtMsg->Text;
        	
        	$cart->Save();
        	
        	// Generate && send Email
			_xls_mail(_xls_mail_name($this->txtToName->Text , $this->txtToEmail->Text) , _sp("You have a Cart") , _xls_mail_body_from_template(templatenamed('email_cart_send.tpl.php') , array('cart' =>$cart , 'obj' =>$this)) , $this->txtFromEmail->Text );        	

			
			$this->errSpan->CssClass='modal_reg_success_msg';
			$this->errSpan->Text = _sp("Cart has been sent successfully!");
        	
			$this->btnCancel->Text = _sp('Close');
			
			$this->objDefaultWaitIcon->Display = false;
        	
        }
		
        
        
        
        
	}
?>
