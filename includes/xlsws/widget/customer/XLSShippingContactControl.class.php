<?php

class XLSShippingContactControl extends XLSCustomerContactControl { 
    public function UpdateFieldsFromCustomer($objCustomer = null) {
        if (is_null($objCustomer))
            $objCustomer = Customer::GetCurrent();

        $objInfo = $this->GetChildByName('Info');
        $objAddress = $this->GetChildByName('Address');

        if ($objInfo) {
            $mixValueArray = array(
                'FirstName' => $objCustomer->Firstname,
                'LastName' => $objCustomer->Lastname,
                'Company' => $objCustomer->Company,
                'Phone' => $objCustomer->Mainphone
            );

            $objInfo->UpdateFieldsFromArray($mixValueArray);
        }

        if ($objAddress) {
            $mixValueArray = array(
                'Street1' => $objCustomer->Address21,
                'Street2' => $objCustomer->Address22,
                'City' => $objCustomer->City2,
                'Country' => $objCustomer->Country2,
                'State' => $objCustomer->State2,
                'Zip' => $objCustomer->Zip2
            );

            $objAddress->UpdateFieldsFromArray($mixValueArray);
        }
    }

    public function SaveFieldsToCustomer($objCustomer = null) {
        if (is_null($objCustomer))
            $objCustomer = Customer::GetCurrent();

        $objAddress = $this->GetChildByName('Address');

        if ($objAddress) {
            $objCustomer->Address21 = $objAddress->Street1->Value;
            $objCustomer->Address22 = $objAddress->Street2->Value;
            $objCustomer->City2 = $objAddress->City->Value;
            $objCustomer->Country2 = $objAddress->Country->Value;
            $objCustomer->State2 = $objAddress->State->Value;
            $objCustomer->Zip2 = $objAddress->Zip->Value;
        }

        return $objCustomer;
    }

    public function UpdateFieldsFromCart($objCart = null) {
        if (is_null($objCart))
            $objCart = Cart::GetCart();
        
        $objInfo = $this->GetChildByName('Info');
        $objAddress = $this->GetChildByName('Address');

        if ($objInfo) {
            $mixValueArray = array(
                'FirstName' => $objCart->ShipFirstname,
                'LastName' => $objCart->ShipLastname,
                'Company' => $objCart->ShipCompany,
                'Phone' => $objCart->ShipPhone
            );
            $objInfo->UpdateFieldsFromArray($mixValueArray);
        }

        if ($objAddress) {
            $mixValueArray = array(
                'Street1' => $objCart->ShipAddress1,
                'Street2' => $objCart->ShipAddress2,
                'City' => $objCart->ShipCity,
                'Country' => $objCart->ShipCountry,
                'State' => $objCart->ShipState,
                'Zip' => $objCart->ShipZip
            );

            $objAddress->UpdateFieldsFromArray($mixValueArray);
        }
    }

    public function SaveFieldsToCart($objCart = null) {
        if (is_null($objCart))
            $objCart = Cart::GetCart();

        $objInfo = $this->GetChildByName('Info');
        $objAddress = $this->GetChildByName('Address');

        if ($objInfo) {
            $objCart->ShipFirstname = $objInfo->FirstName->Value;
            $objCart->ShipLastname = $objInfo->LastName->Value;
            $objCart->ShipCompany = $objInfo->Company->Value;
            $objCart->ShipPhone = $objInfo->Phone->Value;
        }

        if ($objAddress) {        
            $objCart->ShipAddress1 = $objAddress->Street1->Value;
            $objCart->ShipAddress2 = $objAddress->Street2->Value;
            $objCart->ShipCity = $objAddress->City->Value;
            $objCart->ShipCountry = $objAddress->Country->Value;
            $objCart->ShipState = $objAddress->State->Value;
            $objCart->ShipZip = $objAddress->Zip->Value;
        }

        return $objCart;
    }

    public function UpdateFieldsFromControl($objControl) {
        if ($objControl instanceof XLSCustomerInfoControl)
            $objInfo = $this->GetChildByName('Info');
        elseif ($objControl instanceof XLSCustomerAddressControl)
            $objAddress = $this->GetChildByName('Address');
        elseif ($objControl instanceof XLSCustomerComposite)
            $objInfo = $this->GetChildByName('Info');
            $objAddress = $this->GetChildByName('Address');

        if ($objInfo) {
            $mixValueArray = array(
                'FirstName' => $objControl->FirstName->Value,
                'LastName' => $objControl->LastName->Value,
                'Company' => $objControl->Company->Value,
                'Email' => $objControl->Email->Value,
                'Phone' => $objControl->Phone->Value
            );
            
            $objInfo->UpdateFieldsFromArray($mixValueArray);
        }

        if ($objAddress) {
            $mixValueArray = array(
                'Street1' => $objControl->Street1->Value,
                'Street2' => $objControl->Street2->Value,
                'City' => $objControl->City->Value,
                'Country' => $objControl->Country->Value,
                'State' => $objControl->State->Value,
                'Zip' => $objControl->Zip->Value
            );

            $objAddress->UpdateFieldsFromArray($mixValueArray);
        }
    }
}

