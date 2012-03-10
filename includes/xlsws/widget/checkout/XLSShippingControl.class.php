<?php

class XLSShippingControl extends XLSCompositeControl {
    // Behavior
	protected $blnEnabled = false;

    // Configuration
    protected $strDefaultMethod = 'SHIPPING';

    protected $arrRegisteredChildren = array(
        'Wait', 'Module', 'Method', 'Label', 'Price'
    );

    // Messages
    protected $strLabelForPlaceholder = 'Please provide shipping address (Country, State, Zip/Postal Code) to receive a shipping quote.';
    protected $strLabelForError = 'Error: Unable to get shipping rates';

    // TODO :: Legacy support
    public $objMethodFields;

    protected function BuildLabelControl() {
        $objControl = new QLabel($this, $this->GetChildName('Label'));
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdateLabelControl();
        $this->BindLabelControl();

        return $objControl;
    }

    protected function UpdateLabelControl($strMessage = null) {
        $objControl = $this->GetChildByName('Label');
        $objCart = Cart::GetCart();

        if (!$objControl)
            return;

        if ($this->blnEnabled) {
            if (!is_null($strMessage))
                $objCart->ShippingData = $strMessage;

            $strMessage = $objCart->ShippingData;
        }
        else $strMessage = $this->strLabelForPlaceholder;

        if ($strMessage) {
            $strMessage = _sp($strMessage);
            
            $objControl->Visible = true;
            
            if ($objControl->Text != $strMessage)
                $objControl->Text = $strMessage;
        }
        else {
            $objControl->Visible = false;
        }

        return $objControl;
    }

    protected function BindLabelControl() {
        return $this->GetChildByName('Label');
    }

    protected function BuildPriceControl() {
        $objControl = new QLabel($this, $this->GetChildName('Price'));
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdatePriceControl();
        $this->BindPriceControl();

        return $objControl;
    }

    protected function UpdatePriceControl($fltPrice = null, $fltCost = null) {
        $objControl = $this->GetChildByName('Price');
        $objCart = Cart::GetCart();

        if (!$objControl)
            return;

        if (!$this->blnEnabled) {
            $objCart->ShippingSell = null;
            $objCart->ShippingCost = null;
            
            $objControl->Visible = false;
            
            return $objControl;
        }
        else {
            $objControl->Visible = true;
        }

        if (!is_null($fltPrice)) { 
            $objCart->ShippingSell = $fltPrice;

            if (!is_null($fltCost))
                $objCart->ShippingCost = $fltCost;
            else
                $objCart->ShippingCost = $fltPrice;
        }

        $fltPrice = $objCart->ShippingSell;

        if (!is_null($fltPrice)) {
            $fltPrice = _xls_currency($fltPrice);

            if ($objControl->Text != $fltPrice)
                $objControl->Text = $fltPrice;
        }
        else { 
            $objControl->Visible = false;
        }

        return $objControl;
    }

    protected function BindPriceControl() {
        return $this->GetChildByName('Price');
    }

    protected function BuildWaitControl() {
        $objControl = new QWaitIcon($this, $this->GetChildName('Wait'));

        $this->UpdateWaitControl();
        $this->BindWaitControl();

        return $objControl;
    }

    protected function UpdateWaitControl() {
        return $this->GetChildByName('Wait');
    }

    protected function BindWaitControl() {
        return $this->GetChildByName('Wait');
    }

    protected function BuildModuleControl() {
        $objControl = new XLSListControl($this, $this->GetChildName('Module'));
        $objControl->Name = _sp('Choose Shipping Method');
        $objControl->CssClass = 'checkout_shipping_select';
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdateModuleControl();
        $this->BindModuleControl();

        return $objControl;
    }

    protected function UpdateModuleControl($blnReset = true) {
        $objControl = $this->GetChildByName('Module');
        $objCart = Cart::GetCart();

        if (!$objControl)
            return;

        if ($blnReset) {
            $objControl->RemoveAllItems();

            foreach ($this->LoadModules() as $objModule) { 
                $strName = $objModule->name();
                $objControl->AddItem($strName, get_class($objModule));
            }

            if ($objControl->ItemCount > 0) {
                if ($objCart->ShippingModule)
                    $objControl->SelectedValue = $objCart->ShippingModule;
                else $objControl->SelectedIndex = 0;
            }
        }

        if ($this->blnEnabled) {
            $objCart->ShippingModule = $objControl->SelectedValue;
            $objControl->Visible = true;
            $objControl->Enabled = true;
        }
        else {
            $objCart->ShippingModule = null;
            $objControl->Visible = false;
            $objControl->Enabled = false;
        }

        return $objControl;
    }

    protected function BindModuleControl() {
        $objControl = $this->GetChildByName('Module');
        $objWaitControl = $this->GetChildByName('Wait');

        if (!$objControl)
            return;

        $objControl->AddActionArray(
            new QChangeEvent(), 
            array(
                new QToggleEnableAction($objControl),
                new QAjaxControlAction(
                    $this, 'DoModuleChange',
                    $objWaitControl
                )
            )
        );
    }

    public function DoModuleChange($strFormId, $strControlId, $strParam = null){
        $objControl = $this->GetChildByName('Module');
        $objCart = Cart::GetCart();

        if ($this->strValidationError) {
            $this->strValidationError = '';
            $this->Refresh();
        }

        $this->UpdateModuleControl(false);
        $this->UpdateMethodControl();
        
        $this->UpdateLabelControl();
        $this->UpdatePriceControl();
    }

    protected function BuildMethodControl() {
        $objControl = new QPanel($this, $this->GetChildName('Method'));
        $objControl->Name = _sp('Preference:');
        $objControl->AutoRenderChildren = true;
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdateMethodControl();
        $this->BindMethodControl();

        return $objControl;
    }

    protected function UpdateMethodControl($blnReset = true) {
        // While we're dealing with V1 modules, only a full lookup
        // will return a valid shipping rate selection.
        
        $objControl = $this->GetChildByName('Method');
        $objCart = Cart::GetCart();

        if (!$objControl)
            return;

        if ($blnReset) {
            $objControl->RemoveChildControls(true);

            if ($objCart->ShippingModule) {
                $objModule = $this->LoadModules($objCart->ShippingModule);
                $this->objMethodFields = 
                    $objModule->customer_fields($objControl);
                $this->ProcessMethodControl();
            }

            if (count($this->objMethodFields) == 0)
                $objControl->Visible = false;
            else $this->BindMethodFieldControls();
        }

        if ($this->blnEnabled) {
            #$objCart->ShippingModule = $objControl->SelectedValue;
            $objControl->Visible = true;
            $objControl->Enabled = true;

            foreach ($this->objMethodFields as $objChildControl)
                $objChildControl->Enabled = true;
        }
        else {
            $objCart->ShippingMethod = null;
            $objControl->Visible = false;
            $objControl->Enabled = false;
        }

        return $objControl;
    }

    // This is a temporary method which will be replaced in the future
    protected function ProcessMethodControl($objModule = null) {
        $objCart = Cart::GetCart();

        if ($objCart->ShippingModule)
            if (!$objModule)
                $objModule = $this->LoadModules($objCart->ShippingModule);

        if ($objModule) { 
            $this->CalculateShippingRate($objModule);
        }
        else { 
            $objCart->ShippingMethod = null;
        }
    
        return $objModule;
    }

    protected function BindMethodFieldControls() {
        $objModuleControl = $this->GetChildByName('Module');
        $objWaitControl = $this->GetChildByName('Wait');

        foreach ($this->objMethodFields as $objField) {
            $objField->AddAction(
                new QChangeEvent(), 
                new QToggleEnableAction($objModuleControl)
            );
            $objField->AddAction(
                new QChangeEvent(), 
                new QToggleEnableAction($objField)
            );
            $objField->AddAction(
                new QChangeEvent(), 
                new QAjaxControlAction(
                    $this, 'DoMethodChange',
                    $objWaitControl
                )
            );
        }
    }

    protected function BindMethodControl() {
        return $this->GetChildByName('Method');
    }

    // This function will be modified in the future as we move away from 
    // legacy shipping modules
    public function DoMethodChange($strFormId, $strControlId, $strParam = null){
        $objControl = $this->GetChildByName('Method');
        $objControl->Enabled = true;

        $this->ProcessMethodControl();

        $this->UpdateModuleControl(false);
        $this->UpdateMethodControl(false);

        $this->UpdateLabelControl();
        $this->UpdatePriceControl();

        return $objControl;
    }

    // This function will be modified in the future as we move away from
    // legacy shipping modules
    protected function CalculateShippingRate($objModule) {
        $objCart = Cart::GetCart();

        $strShippingModule = $objCart->ShippingModule;
        $strShippingMethod = null;
        $strShippingData = null;
        $fltShippingPrice = null;
        $fltShippingCost = null;
        $fltShippingMarkup = 0;

        try { 
            $mixTotal = $objModule->total(
                $this->objMethodFields,
                $objCart,
                $objCart->ShipCountry,
                $objCart->ShipZip,
                $objCart->ShipState,
                $objCart->ShipCity,
                $objCart->ShipAddress2,
                $objCart->ShipAddress1,
                $objCart->ShipCompany,
                $objCart->ShipLastname,
                $objCart->ShipFirstname
            );
        }
        catch(Exception $objExc) {
            $mixTotal = false;
        }

        if ($mixTotal === false) {
            $this->ValidationError = _sp($this->strLabelForError);
        }
        else if (is_numeric($mixTotal)) {
            $fltShippingPrice = $mixTotal; 
        }
        else if (is_array($mixTotal)) {
            if (isset($mixTotal['price']) && is_numeric($mixTotal['price']))
                $fltShippingPrice = $mixTotal['price'];

            if (isset($mixTotal['msg']))
                $strShippingData = $mixTotal['msg'];

            if (isset($mixTotal['product']))
                $strShippingMethod = $mixTotal['product'];

            if (isset($mixTotal['markup']))
                $fltShippingMarkup = $mixTotal['markup'];
        }
        else { 
            $this->ValidationError = _sp($this->strLabelForError);
            QApplication::Log(E_ERROR, 'shipping', 
                sprintf(
                    _sp('Could not determine return type for module %s'),
                    $strShippingModule
                )
            );
        }

        if (!is_null($fltShippingPrice)) {
            if (is_null($fltShippingCost))
                $fltShippingCost = $fltShippingPrice - $fltShippingMarkup;

            if ($fltShippingCost < 0)
                $fltShippingCost = 0;
        }

        $this->SetShippingSelection(
            $strShippingModule, $strShippingMethod, $strShippingData, 
            $fltShippingPrice, $fltShippingCost
        );
    }

    protected function ResetShippingSelection() {
        $objCart = Cart::GetCart();

        $objCart->ShippingModule = $strModule;
        $objCart->ShippingMethod = null;
        $objCart->ShippingData = null;
        $objCart->ShippingSell = null;
        $objCart->ShippingCost = null;

        return null;
    }

    protected function SetShippingSelection(
        $strModule, $strMethod, $strData, $fltPrice, $fltCost = null
    ) {
        
        if (is_null($fltPrice)) {
            return $this->ResetShippingSelection();
        }

        if (is_numeric($fltPrice) && $fltPrice < 0)
            $fltPrice = 0;

        if (is_null($fltCost))
            $fltCost = $fltPrice;

        if (is_null($strMethod))
            $strMethod = $this->strDefaultMethod;

        if (is_null($strData)) {
            if ($this->Module)
                $strData = _sp($this->Module->SelectedName);
            else
                $strData = _sp('Shipping rate');
        }

        // TODO :: Verify if this is still required ?!
        $strData = str_replace(
            '(' . _xls_currency($fltCost) . ')', ' ', $strData
        );
        $strData = str_replace(_xls_currency($fltCost), ' ', $strData);
        $strData = str_replace('-', '', $strData);
        $strData = trim($strData);

        $objCart = Cart::GetCart();

        $objCart->ShippingMethod = $strMethod;
        $objCart->ShippingModule = $strModule;
        $objCart->ShippingData = $strData;
        $objCart->ShippingSell = $fltPrice;
        $objCart->ShippingCost = $fltCost;
    }

    protected function LoadModules($strModule = null) {
        $objCondition = QQ::Equal(QQN::Modules()->Type, 'shipping');
        $objClause = QQ::Clause(QQ::OrderBy(QQN::Modules()->SortOrder));

        if ($strModule) {
            $strModule = $strModule . '.php';
            $objCondition = QQ::AndCondition(
                $objCondition, 
                QQ::Equal(QQN::Modules()->File, $strModule)
            );
        }

        $arrShippingModules = array();
        $arrModules = Modules::QueryArray($objCondition, $objClause);

        foreach ($arrModules as $objModule) {
            $objShipModule = $this->Form->loadModule(
                $objModule->File, 'shipping'
            );

            if (!$objShipModule)
                continue;

            if (!$objShipModule->check())
                continue;

            $arrShippingModules[] = $objShipModule;
        }

        if ($strModule)
            return end($arrShippingModules);

        return $arrShippingModules;
    }
}

/* vim: set ft=php ts=4 sw=4 tw=0 et: */
