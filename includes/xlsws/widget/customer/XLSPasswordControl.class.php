<?php

class XLSPasswordControl extends XLSCompositeControl {
    protected $arrRegisteredChildren = array(
        'Password1','Password2','NewsletterSubscribe'
    );

    protected function BuildPassword1Control() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('Password1'));
        $objControl->Name = _sp('Password1');
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 25);
        $objControl->TextMode = QTextMode::Password;
        $objControl->SetCustomAttribute('autocomplete', 'off');
		$objControl->MinLength = _xls_get_conf('MIN_PASSWORD_LEN' , 6);

        $this->UpdatePassword1Control();
        $this->BindPassword1Control();

        return $objControl;
    }

    protected function UpdatePassword1Control() {
    	$objControl->Text='';
    }

    protected function BindPassword1Control() {
    }

    protected function BuildPassword2Control() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('Password2'));
        $objControl->Name = _sp('Password2');
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 25);
        $objControl->TextMode = QTextMode::Password;
        $objControl->SetCustomAttribute('autocomplete', 'off');
		$objControl->MinLength = _xls_get_conf('MIN_PASSWORD_LEN' , 6);

        $this->UpdatePassword2Control();
        $this->BindPassword2Control();

        return $objControl;
    }

    protected function UpdatePassword2Control() {
    $objControl->Text='';
    }

    protected function BindPassword2Control() {
    }
    
 	protected function BuildNewsletterSubscribeControl() {
        $objControl =
            new QCheckbox($this, $this->GetChildName('NewsletterSubscribe'));
        $objControl->Name = _sp('NewsletterSubscribe');
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdateNewsletterSubscribeControl();
        $this->BindNewsletterSubscribeControl();

        return $objControl;
    }

    protected function UpdateNewsletterSubscribeControl() {
        $objControl->Text='';

	    if (is_null($objCustomer))
		    $objCustomer = Customer::GetCurrent();

	    if ($objCustomer) {
		    $objNews = $this->GetChildByName('NewsletterSubscribe');
		    $objNews->Checked = $objCustomer->NewsletterSubscribe;
        }

    }

    protected function BindNewsletterSubscribeControl() {
    }
   
   	public function Validate() {
   		$objPassword1 = $this->GetChildByName('Password1');
   		$objPassword2 = $this->GetChildByName('Password2');
   		if (!$objPassword1) return true;
   		if (!$objPassword2) return true;
   		
		if($objPassword1->Text != '' && $objPassword2->Text != '' && $objPassword1->Text != $objPassword2->Text)        
        		{ $objPassword1->ValidationError = _sp("Passwords do not match"); return false; }
        		
		return true;
	}
     
}

