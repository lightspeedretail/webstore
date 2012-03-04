<?php

class XLSCaptchaControl extends XLSCompositeControl {
    protected $arrRegisteredChildren = array(
        'Code', 'Input'
    );

    protected $strLabelForInput = 'Enter the text from above.';
    protected $strLabelForValidationError = 'Wrong Verification Code.';

    // Objects
    protected $objCodeControl;
    protected $objInputControl;    

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

    protected function UpdateCodeControl($strMessage = null) {
        $objControl = $this->objCodeControl;
        $objInput = $this->objInputControl;

        $objControl->Text = $this->GetCodeContent();

        return $objControl;
    }

    protected function BindCodeControl() {
        return $this->objCodeControl;
    }

    protected function BuildInputControl() {
        $objControl = $this->objInputControl = 
            new XLSTextControl($this, $this->GetChildName('Input'));
        $objControl->Name = _sp($this->strLabelForInput);
        $objControl->SetCustomAttribute('autocomplete', 'off');

        $this->UpdateInputControl();
        $this->BindInputControl();

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

    public function Validate() {
        $objCode = $this->objCodeControl;
        $objInput = $this->objInputControl;

        if (!$objCode || !$objInput)
            return true;

        require_once(SECIMG_DIR . '/securimage.php');
        $objSecurimage = new Securimage();

        if ($objSecurimage->getCode() != $objInput->Text) {
            $objInput->ValidationError = $this->strLabelForValidationError;
            return false;
        }

        $objInput->ValidationReset();
        $this->ValidationReset(false);

        return true;
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
