<?php

class XLSPaymentControl extends XLSCompositeControl {
    // Behavior
	protected $blnEnabled = false;

    // Configuration
    protected $strDefaultMethod = 'Credit Card';

    protected $arrRegisteredChildren = array(
        'Label', 'Price', 'Module', 'Method'
    );

    // Messages
    protected $strLabelForPlaceholder = '';
    protected $strLabelForError = 'Error: Unable to get shipping rates';

    // Objects
    protected $objModuleControl;
    protected $objMethodControl;
    protected $objLabelControl;
    protected $objPriceControl;

    // TODO :: Legacy support
    public $objMethodFields;

    protected function BuildLabelControl() {
        $objControl = $this->objLabelControl = 
            new QLabel($this, $this->GetChildName('Label'));
        $objControl->Name = '';

        $this->UpdateLabelControl();
        $this->BindLabelControl();

        return $objControl;
    }

    protected function UpdateLabelControl($strMessage = null) {
        $objControl = $this->objLabelControl;

        if (!$objControl)
            return;

        if ($this->Enabled) $strMessage = '';
        else $strMessage = $this->strLabelForPlaceholder;

        if ($strMessage) {
            $strMessage = _sp($strMessage);
            $objControl->Visible = true;
            if ($objControl->Text != $strMessage)
                $objControl->Text = $strMessage;
        }
        else $objControl->Visible = false;

        return $objControl;
    }

    protected function BindLabelControl() {
        return $this->objLabelControl;
    }

    protected function BuildPriceControl() {
        $objControl = $this->objPriceControl = 
            new QLabel($this, $this->GetChildName('Price'));
        $objControl->Name = _sp('Total Payable');

        $this->UpdatePriceControl();
        $this->BindPriceControl();

        return $objControl;
    }

    protected function UpdatePriceControl($fltPrice = null) {
        $objControl = $this->objPriceControl;
        $objCart = Cart::GetCart();

        if (!$objControl)
            return;

        if (!is_null($fltPrice)) { 
            $objCart->Total = $fltPrice;
        }

        $fltPrice = $objCart->Total;

        if (!is_numeric($fltPrice))
            $objControl->Visible = false;
        else {
            $fltPrice = _xls_currency($fltPrice);
            $objControl->Visible = true;

            if ($objControl->Text != $fltPrice)
                $objControl->Text = $fltPrice;
        }

        return $objControl;
    }

    protected function BindPriceControl() {
        return $this->objPriceControl;
    }

    protected function BuildModuleControl() {
        $objControl = $this->objModuleControl = 
            new XLSListControl($this, $this->GetChildName('Module'));
        $objControl->Name = _sp('Choose Payment Method');
        $objControl->CssClass = 'checkout_payment_select';

        $this->UpdateModuleControl();
        $this->BindModuleControl();

        return $objControl;
    }

    protected function UpdateModuleControl() {
        $objControl = $this->objModuleControl;

        if (!$objControl)
            return;

        if (!$objControl->Visible && !$objControl->Enabled)
            return;

        $objControl->RemoveAllItems();

        // TODO :: Move module loading to autoloader
        foreach ($this->LoadModules() as $objModule) { 
            $strName = $objModule->name();
            $objControl->AddItem($strName, get_class($objModule));
        }

        $objCart = Cart::GetCart();

        if ($objControl->ItemCount > 0) {
            #if ($objCart->ShippingModule)
            #    $objControl->SelectedValue = $objCart->PaymentModule;
            #else
                $objControl->SelectedIndex = 0;
        }

        if (!is_null($objControl->SelectedValue))
            $objCart->PaymentModule = $objControl->SelectedValue;

        return $objControl;
    }

    protected function BindModuleControl() {
        $objControl = $this->objModuleControl;

        if (!$objControl)
            return;

        $objControl->AddActionArray(
            new QChangeEvent(), 
            array(
                new QToggleEnableAction($objControl),
                new QAjaxControlAction(
                    $this, 'DoModuleChange'
                )
            )
        );
    }

    public function DoModuleChange($strFormId, $strControlId, $strParam = null){
        $objControl = $this->objModuleControl;

        if (!is_null($objControl->SelectedValue)) {
            $objCart = Cart::GetCart();
            $objCart->PaymentModule = $objControl->SelectedValue;
        }

        $this->UpdateMethodControl();
        $objControl->Enabled = true;
    }

    protected function BuildMethodControl() {
        $objControl = $this->objMethodControl = 
            new QPanel($this, $this->GetChildName('Method'));
        $objControl->AutoRenderChildren = true;

        $this->UpdateMethodControl();
        $this->BindMethodControl();

        return $objControl;
    }

    protected function UpdateMethodControl() {
        $objControl = $this->objMethodControl;

        if (!$objControl)
            return;

        if (!$objControl->Visible && !$objControl->Enabled)
            return;

        $objControl->Visible = false;
        $objControl->RemoveChildControls(true);

        $this->objMethodFields = array(); // TODO :: Improve mechanism

        $objCart = Cart::GetCart();
        if (!$objCart->PaymentModule)
            return;

        $objModule = $this->LoadModules($objCart->PaymentModule);
        if (!$objModule)
            return;

        $this->objMethodFields = $objModule->customer_fields($objControl);

        foreach ($this->objMethodFields as $objChildControl) {
            $objChildControl->RenderMethod = 'RenderAsDefinition';
        }

        if (count($this->objMethodFields) > 0)
            $objControl->Visible = true;

        $this->BindMethodFieldsControl();
    }

    protected function BindMethodFieldsControl() {
        return;
    }

    protected function BindMethodControl() {
        return $this->objMethodControl;
    }

    protected function LoadModules($strModule = null) {
        $objCondition = QQ::Equal(QQN::Modules()->Type, 'payment');
        $objClause = QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder));

        if ($strModule) {
            $strModule = $strModule . '.php';
            $objCondition = QQ::AndCondition(
                $objCondition, 
                QQ::Equal(QQN::Modules()->File, $strModule)
            );
        }

        $arrPaymentModules = array();
        $arrModules = Modules::QueryArray($objCondition, $objClause);

        foreach ($arrModules as $objModule) {
            $objPaymentModule = $this->Form->loadModule(
                $objModule->File, 'payment'
            );

            if (!$objPaymentModule)
                continue;

            if (!$objPaymentModule->check())
                continue;

            $arrPaymentModules[] = $objPaymentModule;
        }

        if ($strModule)
            return end($arrPaymentModules);

        return $arrPaymentModules;
    }

    protected function BuildControl() {
        parent::BuildControl();    
    
        if ($this->blnEnabled) {
            $this->objModuleControl->Enabled = true;
            $this->objModuleControl->Visible = true;
            $this->objMethodControl->Enabled = true;
            $this->objMethodControl->Visible = true;
        }
        else {
            $this->objModuleControl->Enabled = false;
            $this->objModuleControl->Visible = false;
            $this->objMethodControl->Enabled = false;
            $this->objMethodControl->Visible = false;
        }
    }

    protected function UpdateControl() {
        if ($this->blnEnabled) {
            $this->Enabled = true;
            $this->Visible = true;
            $this->objPriceControl->Enabled = true;
            $this->objPriceControl->Visible = true;
            $this->objModuleControl->Enabled = true;
            $this->objModuleControl->Visible = true;
            $this->objMethodControl->Enabled = true;
            $this->objMethodControl->Visible = true;
        }
        else {
            $this->Enabled = false;
            $this->Visible = false;
            $this->objPriceControl->Enabled = false;
            $this->objPriceControl->Visible = false;
            $this->objModuleControl->Enabled = false;
            $this->objModuleControl->Visible = false;
            $this->objMethodControl->Enabled = false;
            $this->objMethodControl->Visible = false;
        }
        
        parent::UpdateControl();
    }

    public function __get($strName) {
        switch ($strName) {
            case 'ModuleControl': return $this->objModuleControl;
            case 'MethodControl': return $this->objMethodControl;
            case 'LabelControl': return $this->objLabelControl;
            case 'PriceControl': return $this->objPriceControl;
            default: return parent::__get($strName);
        }
    }
}

/* vim: set ft=php ts=4 sw=4 tw=0 et: */
