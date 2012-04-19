<?php

class XLSPasswordControl extends XLSCompositeControl {
    protected $arrRegisteredChildren = array(
        'Password1','Password2'
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

   
     
}

