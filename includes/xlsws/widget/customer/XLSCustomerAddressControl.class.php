<?php

class XLSCustomerAddressControl extends XLSCustomerComposite {
    protected $arrRegisteredChildren = array(
        'Street1', 'Street2', 'City', 'Zip', 'Country', 'State'
    );

    protected $blnFilterDestinations = false;

    protected function BuildStreet1Control() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('Street1'));
        $objControl->Name = _sp('Address');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 255);

        $this->UpdateStreet1Control();
        $this->BindStreet1Control();

        return $objControl;
    }

    protected function UpdateStreet1Control() {
    }

    protected function BindStreet1Control() {
    }

    protected function BuildStreet2Control() {
        $objControl =
            new XLSTextControl($this, $this->GetChildName('Street2'));
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 255);

        $this->UpdateStreet2Control();
        $this->BindStreet2Control();

        return $objControl;
    }

    protected function UpdateStreet2Control() {
    }

    protected function BindStreet2Control() {
    }

    protected function BuildCityControl() {
        $objControl = 
            new XLSTextControl($this, $this->GetChildName('City'));
        $objControl->Name = _sp('City');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 64);

        $this->UpdateCityControl();
        $this->BindCityControl();

        return $objControl;
    }

    protected function UpdateCityControl() {
    }

    protected function BindCityControl() {
    }

    protected function BuildCountryControl() {
        $objControl =
            new XLSCountryControl(
            $this, $this->GetChildName('Country')
        );
        $objControl->Name = _sp('Country');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdateCountryControl();
        $this->BindCountryControl();

        return $objControl;
    }

    protected function UpdateCountryControl() {
        $objControl = $this->GetChildByName('Country');

        if (!$objControl)
            return;

        $objControl->Update();

        return $objControl;
    }

    protected function BindCountryControl() {
        $objControl = $this->GetChildByName('Country');

        if (!$objControl)
            return;

        $objControl->AddAction(
            new QChangeEvent(), 
            new QAjaxControlAction($this,'DoCountryControlChange')
        );

        return $objControl;
    }

    public function DoCountryControlChange($strFormId, $strControlId, 
        $strParameter) 
    {
        $this->UpdateZipControl();
        $objControl = $this->UpdateStateControl();
        $objControl->SetFocus();
    }

    protected function BuildStateControl() {
        $objControl =
            new XLSStateControl($this, 
                $this->GetChildName('State'),
                $this->GetChildName('Country')
        );
        $objControl->Name = _sp('State');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';

        $this->UpdateStateControl();
        $this->BindStateControl();

        return $objControl;
    }

    protected function UpdateStateControl() {
        $objControl = $this->GetChildByName('State');

        if (!$objControl)
            return;

        $objControl->Update();

        return $objControl;
    }

    protected function BindStateControl() {
    }

    protected function BuildZipControl() {
        $objControl =
            new XLSZipFieldControl($this, $this->GetChildName('Zip'));
        $objControl->Name = _sp('Zip/Postal Code');
        $objControl->Required = true;
        $objControl->RenderMethod = 'RenderAsDefinition';
        $objControl->SetCustomAttribute('maxlength', 16);

        $this->UpdateZipControl();
        $this->BindZipControl();

        return $objControl;
    }

    protected function UpdateZipControl() {
        $objControl = $this->GetChildByName('Zip');
        $objCountryControl = $this->GetChildByName('Country');

        if (!$objCountryControl)
            return;

        $strCountry = $objCountryControl->SelectedValue;
        if (!$strCountry)
            return;

        $objCountry = Country::LoadByCode($strCountry);
        if (!$objCountry)
            return;

        $objControl->Regex = $objCountry->ZipValidatePreg;
    }

    protected function BindZipControl() {
    }

    public function UpdateField($strField, $strValue) {
        $strProperty = false;

        $objField = $this->GetChildByName($strField);
        if (!$objField)
            return;

        if ($objField->Value != $strValue) {
            $objField->Value = $strValue;

            if ($strField == 'Country') {
                $this->UpdateStateControl();
                $this->UpdateZipControl();
            }

            return true;
        }   

        return false;
    }   
}

