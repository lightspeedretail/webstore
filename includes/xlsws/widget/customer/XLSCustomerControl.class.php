<?php

class XLSCustomerControl extends XLSCompositeControl {
    protected $arrRegisteredChildren = array(
        'Billing', 'Shipping', 'CheckSame'
    );

    public function GetCustomerControlNames($objControl) {
        $objControlArray = array();
        if ($objControl) $objControlArray[] = $objControl;
        else $objControlArray = array($this->Billing, $this->Shipping);

        foreach ($objControlArray as $objControl) {
            $strControlArray = array();

            if ($objControl->Info)
                $strControlArray = array_merge($strControlArray, 
                $objControl->Info->RegisteredChildren
            );

            if ($objControl->Address)
                $strControlArray = array_merge($strControlArray, 
                $objControl->Address->RegisteredChildren
            );
        }

        return $strControlArray;
    }

    protected function BuildBillingControl() {
        $objControl =
            new BillingContactControl($this, $this->GetChildName('Billing'));

        $this->UpdateBillingControl();
        $this->BindBillingControl();

        $objControl->Info->Template = 
            templateNamed('checkout_reg_account_info.tpl.php');
        $objControl->Address->Template = 
            templateNamed('reg_billing_address.tpl.php');
        
        // TODO :: This is bad
        $objControl->Info->CssClass = 'c1';
        $objControl->Address->CssClass = 'c1';

        return $objControl;
    }

    protected function UpdateBillingControl() {
        $objControl = $this->GetChildByName('Billing');
        if (!$objControl)
            return;

        $objControl->UpdateFieldsFromCart();
        $objControl->UpdateFieldsFromCustomer();
        $objControl->SaveFieldsToCart();

        return $objControl;
    }

    protected function BindBillingControl() {
        $objControl = $this->GetChildByName('Billing');
        if (!$objControl)
            return;

        foreach ($this->GetCustomerControlNames($objControl) as $strName) {
            $objChildControl = $objControl->$strName;
            if (!$objChildControl)
                continue;

            $objChildControl->AddAction(
                new QChangeEvent(),
                new QAjaxControlAction($this, 'DoBillingFieldUpdate')
            );
        }
    }

    protected function BuildShippingControl() {
        $objControl =
            new ShippingContactControl($this, $this->GetChildName('Shipping'));

        $this->UpdateShippingControl();
        $this->BindShippingControl();

        // TODO :: This is bad
        $objControl->CssClass = 'c2';
        $objControl->Template = templateNamed('reg_shipping_address.tpl.php');
        
        return $objControl;
    }

    protected function UpdateShippingControl() {
        $objControl = $this->GetChildByName('Shipping');
        if (!$objControl)
            return;

        $objControl->UpdateFieldsFromCart();
        $objControl->UpdateFieldsFromCustomer();
        $objControl->SaveFieldsToCart();

        return $objControl;
    }

    protected function BindShippingControl() {
        $objControl = $this->GetChildByName('Shipping');
        if (!$objControl)
            return;

        foreach ($this->GetCustomerControlNames($objControl) as $strName) {
            $objChildControl = $objControl->$strName;
            if (!$objChildControl)
                continue;

            $objChildControl->AddAction(
                new QChangeEvent(),
                new QAjaxControlAction($this, 'DoShippingFieldUpdate')
            );
        }
    }

    protected function BuildCheckSameControl() {
        $objControl =
            new QCheckBox($this, $this->GetChildName('CheckSame'));
        $objControl->Text = _sp('Shipping Address is the same' . 
            ' as BillingAddress');

        $this->UpdateCheckSameControl();
        $this->BindCheckSameControl();

        return $objControl;
    }

    protected function UpdateCheckSameControl() {
        return;
    }

    protected function BindCheckSameControl() {
        $objControl = $this->GetChildByName('CheckSame');
        if (!$objControl)
            return;

        $objControl->AddAction(
            new QChangeEvent(), 
            new QAjaxControlAction($this, 'DoCheckSameChange')
        );
    }

    public function DoCheckSameChange($strFormId, $strControlId, $strParam) {
        $objControl = $this->GetChildByName('CheckSame');
        if (!$objControl)
            return;

        $objBilling = $this->GetChildByName('Billing');
        $objShipping = $this->GetChildByName('Shipping');

        if (!$objBilling || !$objShipping)
            return;

        $blnVisible = $objControl->Checked;

        foreach ($this->GetCustomerControlNames($objShipping) as $strName) {
            $objChildControl = $objShipping->$strName;
            if (!$objChildControl)
                continue;
            
            $objChildControl->Enabled = !$blnVisible;
        }

        if ($objControl->Checked) {
            $objShipping->UpdateFieldsFromControl($objBilling);
            $objShipping->SaveFieldsToCustomer();
            $objShipping->SaveFieldsToCart();
        }
    }

    public function DoBillingFieldUpdate($strFormId, $strControlId, $strParam) {
        $objControl = $this->GetChildByName('CheckSame');
        
        if (!$objControl)
            return;

        $objBilling = $this->GetChildByName('Billing');
        $objShipping = $this->GetChildByName('Shipping');

        if (!$objBilling || !$objShipping)
            return;

        $objField = $this->Form->GetControl($strControlId);
        $objField->Validate();

        $objBilling->SaveFieldsToCustomer();
        $objBilling->SaveFieldsToCart();

        if (!$objControl || !$objControl->Checked)
            return;

        if (!$objBilling || !$objShipping)
            return;

        $objShipping->UpdateFieldsFromControl($objBilling);
    
        if ($objControl->Checked)
            $this->DoShippingFieldUpdate($strFormId, $strControlId, $strParam);
    }

    public function DoShippingFieldUpdate($strFormId, $strControlId, $strParam){
        $objControl = $this->GetChildByName('CheckSame');
        $objBilling = $this->GetChildByName('Billing');
        $objShipping = $this->GetChildByName('Shipping');

        $objShipping->SaveFieldsToCustomer();
        $objShipping->SaveFieldsToCart();
    }
}

