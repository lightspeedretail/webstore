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

    }

    protected function BuildLabelControl() {
        $objControl = $this->objLabelControl = 
            new QLabel($this, $this->GetChildName('Label'));
        
        $this->UpdateLabelControl();
        $this->BindLabelControl();

        return $objControl;
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

        $this->UpdateSubmitControl();
        $this->BindSubmitControl();

        return $objControl;
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
            array(
            	new QToggleEnableAction($this->objInputControl, false),
            	new QAjaxControlAction($this,'DoSubmitControlClick'),
            	new QToggleEnableAction($this->objInputControl, true)
            )
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
		
        return $objInputControl;
    }

    protected function ApplyPromoCode() { 
        $objPromoCode = $this->objPromoCode; 
        if (!$objPromoCode)
            $objPromoCode = PromoCode::LoadByCode($this->objInputControl->Text);

        if (!$objPromoCode)
            return null;

        $objCart = Cart::GetCart();

        if ($objCart->FkPromoId > 0) {
            $this->objInputControl->ValidationError = 
                _sp('Promo Code has already been applied to this order.');
            return false;
        }


		//See if this promo code is supposed to turn on free shipping
		//This runs AFTER the Validate() function so if we get here, it means that any criteria
		//have passed. So just apply and refresh the shipping list
        if ($objPromoCode->Shipping) {
        		$this->objInputControl->ValidationError = "";
        	   $this->objLabelControl->Text = 
                _sp('Congratulations! This order qualifies for Free Shipping!');
            	$objCart->FkPromoId = $objPromoCode->Rowid;
        		$objCart->UpdateCart();
        		$this->objPromoCode = $objPromoCode;      		
        		return;
            }	
        
		$objCart->FkPromoId = $objPromoCode->Rowid;
        $objCart->UpdateCart();
        
        
        
        $this->objLabelControl->Text = sprintf(
            _sp('Promo Code applied at %s'),
            PromoCodeType::Display(
                $objPromoCode->Type,
                $objPromoCode->Amount
            )
        );
      
        $this->objPromoCode = $objPromoCode;
	
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

		//If start date is defined, have we reached it yet
        if (!$objPromoCode->Started) {
            $objInputControl->ValidationError = 
                _sp('Promo Code is not active yet');
            return false;
        }

		//If end date is defined or remaining uses
        if ($objPromoCode->Expired || !$objPromoCode->HasRemaining) {
            $objInputControl->ValidationError = 
                _sp('Promo Code has expired or has been used up.');
            return false;
        }

		//Minimum price threshold
        if ($objPromoCode->Threshold > $objCart->Subtotal) {
            $objInputControl->ValidationError =
                _sp('Promo Code only valid when cart exceeds ') . 
                _xls_currency($objPromoCode->Threshold) . '.';
            return false;
        }

		//If this is for shipping, we need to make sure all items in the cart qualify
		//Since a shipping promo code doesn't discount the items in the cart, just return here
		$arrSorted = array();
		foreach ($objCart->GetCartItemArray() as $objItem)
			$arrSorted[] = $objItem;

		if ($objPromoCode->Shipping) {
			//Except - 0=All items must qualify  1=No Items must qualify  2=At least one item
			//Note that we don't have to test for 1 here because IsProductAffected is already doing that

			if ($objPromoCode->Except==0 || $objPromoCode==1)
			{
				$bolApplied = true;	//We start with true because we want to make sure we don't have a disqualifying item in our cart

				foreach ($arrSorted as $objItem)
					if (!$objPromoCode->IsProductAffected($objItem)) $bolApplied=false;
			}
			if ($objPromoCode->Except==2)
			{
				$bolApplied = false;
				foreach ($arrSorted as $objItem)
					if ($objPromoCode->IsProductAffected($objItem)) $bolApplied=true;
			}

			if ($bolApplied==false)
				$this->objInputControl->ValidationError = 
               		_sp('Free Shipping Promo Code cannot be used with your cart items.');
               		

			return $bolApplied;

		}
		
		//See if any items in the cart match qualify for this promo code
		$bolApplied = false;
		foreach ($arrSorted as $objItem) {
			if ($objPromoCode->IsProductAffected($objItem))
				$bolApplied = true;
		}	
		
		//If we have reached this point and $bolApplied is still false, none of our items qualify
		if (!$bolApplied) {
			$this->objInputControl->ValidationError = 
                _sp('Promo Code cannot be used with your cart items.');
                 return $bolApplied;
          }

		
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
