<?php

class XLSPromoControl extends XLSCompositeControl {
    protected $arrRegisteredChildren = array(
        'Input', 'Label', 'Submit'
    );

    protected $strLabelForInput = 'Enter a Promotional Code here to receive a discount';

    // Objects
    protected $objInputControl;    
    protected $objLabelControl;    
    protected $objSubmitControl;    

    // Cache
    private $objPromoCode;

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

    public function DoInputControlChange($strFormId, $strControlId, $strParam) {
        return $this->Validate();
    }

    protected function BuildLabelControl() {
        $objControl = $this->objLabelControl = 
            new QLabel($this, $this->GetChildName('Label'));
    }

    protected function UpdateLabelControl() {
        return $this->objLabelControl;
    }

    protected function BindLabelControl() {
        return $this->objLabelControl;
    }

    protected function BuildSubmitControl() {
        $objControl = $this->objSubmitControl = 
            new QButton($this, $this->GetChildName('Submit'));
        $objControl->Text = _sp('Apply Promo Code');
    }

    protected function UpdateSubmitControl() {
        return $this->objSubmitControl;
    }

    protected function BindSubmitControl() {
        $objControl = $this->objSubmitControl;

        if (!$objControl)
            return;

        $objControl->AddActionArray(
            new QClickEvent(),
            new QToggleEnableAction($this->objInputControl, false),
            new QAjaxControlAction('DoSubmitControlClick', $this)
        );
    }

    public function DoSubmitControlClick($strFormId, $strControlId, $strParam) {
        $objInputControl = $this->objInputControl;

        if (!$objInputControl->Text) {
            $this->ResetPromoCode();
            return true;
        }

        if ($this->Validate())
            $this->ApplyPromoCode();

        if ($this->objInputControl)
            $this->objInputControl->Enabled = true;

        return $objInputControl;
    }

    protected function ApplyPromoCode() {
        $objPromoCode = $this->objPromoCode;
        if (!$objPromoCode);
            $objPromoCode = PromoCode::LoadByCode($objInputControl->Text);

        if (!$objPromoCode)
            return null;

        $objCart = Cart::GetCart();

        if (!$objCart->FkPromoId > 0) {
            $objInputControl->ValidationError = 
                _sp('Promo Code has already been applied to this order.');
            return false;
        }

        $objCart->FkPromoId = $objPromoCode;

        if ($objCart->UpdatePromoCode(true)) { 
            $objCart->UpdateCart();
            $this->objLabelControl->Text = sprintf(
                _sp('Promo Code applied at %s'),
                PromoCodeType::Display(
                    $objPromoCode->Type,
                    $objPromoCode->Amount
                )
            );
        }
        else {
            $this->objInputControl->ValidationError = 
                _sp('Promo Code could not be applied to your cart.');
            return false;
        }

        $this->objPromoCode = $objPromoCode;
        return $objPromoCode;
    }

    protected function ResetPromoCode() {
        
    }

    public function Validate() {
        $objInputControl = $this->objInputControl;
        $objInputControl->ValidationReset();

        if (!$objInputControl->Text)
            return true;

        $objPromoCode = PromoCode::LoadByCode($objInputControl->Text);

        if (!$objPromoCode) {
            $objInputControl->ValidationError = _sp('Invalid Promo Code.');
            return false;
        }

        $objCart = Cart::GetCart();

        if (!$objPromoCode->Started) {
            $objInputControl->ValidationError = 
                _sp('Promo Code is not active yet');
            return false;
        }

        if ($objPromoCode->Expired || !$objPromoCode->HasRemaining) {
            $objInputControl->ValidationError = 
                _sp('Promo Code has expired or has been used up.');
            return false;
        }

        if ($objPromoCode->Threshold > $objCart->Subtotal) {
            $objInputControl->ValidationError =
                _sp('Promo Code only valid when cart exceds ') . 
                _xls_currency($objPromoCode->Threshold) . '.';
            return false;
        }

        $this->objPromoCode = $objPromoCode;
        return true;
    }

    public function __get($strName) {
        switch ($strName) {
            case 'LabelControl': return $this->objLabelControl;
            case 'InputControl': return $this->objInputControl;
            case 'SubmitControl': return $this->objSubmitControl;
            default: return parent::__get($strName);
        }
    }
}

/* vim: set ft=php ts=4 sw=4 tw=0 et: */
