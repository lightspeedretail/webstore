<?php

class XLSCaptchaControl extends XLSCompositeControl {
    protected $arrRegisteredChildren = array(
         'Input','Code'
    );

    protected $strLabelForInput = 'Enter the text from above.';
    protected $strLabelForValidationError = 'Wrong Verification Code.';

    // Objects
    protected $objCodeControl;
    protected $objInputControl;
    protected $objCaptchaResponse;    

    protected function BuildCodeControl() {
        $objControl = $this->objCodeControl = 
            new QLabel($this, $this->GetChildName('Code'));
        $objControl->HtmlEntities = false;
        $objControl->CssClass = 'customer_reg_draw_verify';

        $this->UpdateCodeControl();
        $this->BindCodeControl();

        $objControl->ValidationReset();

        return $objControl;
    }

    protected function GetCodeContent() {
    
    	//Will be removed in 2.3, customers should migrate and get account
    	if (_xls_get_conf('CAPTCHA_STYLE' , '0')=='1') {
	        $strImage = '<img id="captcha-verif-img" src="verify-img.php"' . 
	            ' alt="Verification Code"/>';
	        $strRefresh = '<div id="captcha-verif-refresh"' . 
	            ' alt="Select a new sequence"' . 
	            ' onclick="javascript:verfimg=\'verify-img.php?rnd=\' +' .
	            ' Math.random();setCaptchaImage(verfimg);">' . 
	            ' <img src="' . __CAPTCHA_ASSETS__ . 
	                '/images/refresh.gif" alt="Select a new sequence"/>' .
	            '</div>';
	        $strAudio =  '<div id="captcha-verif-audio">' . 
	            '<a href="' . __CAPTCHA_ASSETS__ . '/securimage_play.php">' .
	            '<img alt="Listen to letter sequence" src="' .
	                __CAPTCHA_ASSETS__ .
	                '/images/audio_icon.gif"/>' .
	        '</a></div>';
			return "$strImage $strRefresh $strAudio";
		}
		
		
		require_once(__INCLUDES__."/recaptcha/recaptchalib.php");
		$publickey = _xls_get_conf('RECAPTCHA_PUBLIC_KEY' , '');
		unset($this->objCaptchaResponse);
 		return recaptcha_get_html($publickey,null,(_xls_get_conf('ENABLE_SSL',0)=='1' ? true : false));

    }

    protected function UpdateCodeControl($strMessage = null) {
        $objControl = $this->objCodeControl;
        $objInput = $this->objInputControl;

        $objControl->Text = $this->GetCodeContent();

        return $objControl;
    }

    protected function BindCodeControl() {
        return $this->objCodeControl;
    }

    protected function BuildInputControl() { error_log(__function__);
    if (_xls_get_conf('CAPTCHA_STYLE' , '0')=='1') {
        $objControl = $this->objInputControl = 
            new XLSTextControl($this, $this->GetChildName('Input'));
        $objControl->Name = _sp($this->strLabelForInput);
        $objControl->SetCustomAttribute('autocomplete', 'off');

        $this->UpdateInputControl();
        $this->BindInputControl();
       } else {

			$objControl = $this->objInputControl = 
	            new QLabel($this, $this->GetChildName('Input'));
			$objControl->Name = _sp('');
 
		}
		
        return $objControl;
        
    }

    protected function UpdateInputControl() {
        return $this->objInputControl;
    }

    protected function BindInputControl() {
        $objControl = $this->objInputControl;

        if (!$objControl)
            return;

        $objControl->AddAction(
            new QChangeEvent(),
            new QAjaxControlAction($this, 'DoInputControlChange')
        );
    }

    public function DoInputControlChange() {
        return $this->Validate();
    }

    public function Validate() {error_log(__class__.' '.__function__);
        $objCode = $this->objCodeControl;
        $objInput = $this->objInputControl;
		$blnValid = 0;
		$strError = "";
		
        if (!$objCode || !$objInput)
            return true;
  //This gets hit a couple of more times on Submit due to other validation processes          
		//Will be removed in 2.3, customers should migrate and get account
    	if (_xls_get_conf('CAPTCHA_STYLE' , '0')=='1') {
        	require_once(SECIMG_DIR . '/securimage.php');
       		$objSecurimage = new Securimage();
			if ($objSecurimage->getCode() == $objInput->Text)
				$blnValid=1;
			else $strError = "Invalid Entry, try again";
		}
		else
		{


			require_once(__INCLUDES__."/recaptcha/recaptchalib.php");
			$privatekey = _xls_get_conf('RECAPTCHA_PRIVATE_KEY' , '');
			if (!isset($this->objCaptchaResponse) && 
				isset($_POST["recaptcha_response_field"]) && 
				strlen($_POST["recaptcha_response_field"])>0)
	  			$this->objCaptchaResponse = recaptcha_check_answer ($privatekey,
	                $_SERVER["REMOTE_ADDR"],
	                $_POST["recaptcha_challenge_field"],
	                $_POST["recaptcha_response_field"]);
	
			$blnValid = $this->objCaptchaResponse->is_valid; error_log("blnvalid is ".$blnValid);
			$strError = $this->objCaptchaResponse->error;
			//$objCode->Refresh();
		}
		
		if (!$blnValid) { error_log("validation failed");
			$objInput->ValidationError = $strError;
			return false;
		} else { error_log("validation passed");
        	$objInput->ValidationReset();
        	$this->ValidationReset(false);
        	return true;
        }
        
        
    }

    protected function UpdateControl() {
        $objCustomer = Customer::GetCurrent();

        if ($objCustomer->Rowid) { 
            $this->Enabled = false;
            $this->Visible = false;
        }
        else {
            $this->Enabled = true;
            $this->Visible = true;
        }
    }

    public function __get($strName) {
        switch ($strName) {
            case 'CodeControl': return $this->objCodeControl;
            case 'InputControl': return $this->objInputControl;
            default: return parent::__get($strName);
        }
    }
}

/* vim: set ft=php ts=4 sw=4 tw=0 et: */
