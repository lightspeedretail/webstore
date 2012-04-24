<?php

class XLSCustomerInfoControl extends XLSCustomerComposite {
    protected $arrRegisteredChildren = array(
        'FirstName', 'LastName', 'Company', 'Phone', 'Email','EmailConfirm'
    );

    protected function BuildFirstNameControl() {
        $objControl = 
            new XLSTextControl($this, $this->GetChildName('FirstName'));
        $objControl->Name = _sp('Firstname');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 64);

        $this->UpdateFirstNameControl();
        $this->BindFirstNameControl();

        return $objControl;
    }

    protected function UpdateFirstNameControl() {
    }

    protected function BindFirstNameControl() {
    }

    protected function BuildLastNameControl() {
        $objControl = 
            new XLSTextControl($this, $this->GetChildName('LastName'));
        $objControl->Name = _sp('Lastname');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 64);

        $this->UpdateLastNameControl();
        $this->BindLastNameControl();

        return $objControl;
    }

    protected function UpdateLastNameControl() {
    }

    protected function BindLastNameControl() {
    }

    protected function BuildCompanyControl() {
        $objControl = 
            new XLSTextControl($this, $this->GetChildName('Company'));
        $objControl->Name = _sp('Company');
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 255);

        $this->UpdateCompanyControl();
        $this->BindCompanyControl();

        return $objControl;
    }

    protected function UpdateCompanyControl() {
    }

    protected function BindCompanyControl() {
    }

    protected function BuildPhoneControl() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('Phone'));
        $bjControl->Name = _sp('Phone');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 64);

        $this->UpdatePhoneControl();
        $this->BindPhoneControl();

        return $objControl;
    }

    protected function UpdatePhoneControl() {
    }

    protected function BindPhoneControl() {
    }

    protected function BuildEmailControl() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('Email'));
        $objControl->Name = _sp('Email');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 255);
		$objControl->Width = 200;
		
        $this->UpdateEmailControl();
        $this->BindEmailControl();

        return $objControl;
    }
    
    protected function UpdateEmailControl() {
    }

    protected function BindEmailControl() {
    }


    protected function BuildEmailConfirmControl() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('EmailConfirm'));
        $objControl->Name = _sp('EmailConfirm');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 255);
        $objControl->Width = 200;

        $this->UpdateEmailConfirmControl();
        $this->BindEmailConfirmControl();

        return $objControl;
    }
    
    protected function UpdateEmailConfirmControl() {
    }

    protected function BindEmailConfirmControl() {
    }

	public function Validate() { 

	 	$objEmail = $this->GetChildByName('Email');
        $objConfirm = $this->GetChildByName('EmailConfirm');
        
        $blnReturn = true;
        
        if ($objEmail && $objEmail->OnPage)
        	if(!isValidEmail($objEmail->Text))        
        		{ $objEmail->ValidationError = "Not a properly formatted email address"; $blnReturn = false;}

		if ($objConfirm && $objConfirm->OnPage) {
		    if($objConfirm->Text != '' && !isValidEmail($objConfirm->Text))        
	        	{ $objConfirm->ValidationError = "Not a properly formatted email address"; $blnReturn = false;}
			elseif ($objConfirm->Text != '' && $objEmail->Text != $objConfirm->Text)
	        	{ $objEmail->ValidationError = "Email Addresses do not match"; $blnReturn = false;}
			
		}

		//Because Validate may be called multiple times, we don't want to keep
		//calling externally, so our Validate is a separate function
		return $blnReturn;
	
	}

}

