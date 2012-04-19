<?php

class XLSBillingContactControl extends XLSCustomerContactControl { 
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
                'Email' => $objCustomer->Email,
                'Phone' => $objCustomer->Mainphone
            );

            $objInfo->UpdateFieldsFromArray($mixValueArray);
        }

        if ($objAddress) {
            $mixValueArray = array(
                'Street1' => $objCustomer->Address11,
                'Street2' => $objCustomer->Address12,
                'City' => $objCustomer->City1,
                'Country' => $objCustomer->Country1,
                'State' => $objCustomer->State1,
                'Zip' => $objCustomer->Zip1
            );

            $objAddress->UpdateFieldsFromArray($mixValueArray);
        }
    }

    public function SaveFieldsToCustomer($objCustomer = null) {
        if (is_null($objCustomer))
            $objCustomer = Customer::GetCurrent();

        $objInfo = $this->GetChildByName('Info');
        $objAddress = $this->GetChildByName('Address');

        if ($objInfo) {
            $objCustomer->Firstname = $objInfo->FirstName->Value;
            $objCustomer->Lastname = $objInfo->LastName->Value;
            $objCustomer->Company = $objInfo->Company->Value;
            $objCustomer->Email = $objInfo->Email->Value;
            $objCustomer->Mainphone = $objInfo->Phone->Value;
        }

        if ($objAddress) {
            $objCustomer->Address11 = $objAddress->Street1->Value;
            $objCustomer->Address12 = $objAddress->Street2->Value;
            $objCustomer->City1 = $objAddress->City->Value;
            $objCustomer->Country1 = $objAddress->Country->Value;
            $objCustomer->State1 = $objAddress->State->Value;
            $objCustomer->Zip1 = $objAddress->Zip->Value;
        }

        return $objCustomer;
    }

    public function SaveFieldsToCart($objCart = null) {
        if (is_null($objCart))
            $objCart = Cart::GetCart();

        $objInfo = $this->GetChildByName('Info');

        if ($objInfo) {
            $objCart->Firstname = $objInfo->FirstName->Value;
            $objCart->Lastname = $objInfo->LastName->Value;
            $objCart->Company = $objInfo->Company->Value;
            $objCart->Phone = $objInfo->Phone->Value;
            $objCart->Email = $objInfo->Email->Value;
        }

        return $objCart;
    }
    public function UpdateFieldsFromCart($objCart = null) {
        if (is_null($objCart))
            $objCart = Cart::GetCart();
        
        $objInfo = $this->GetChildByName('Info');
        $objAddress = $this->GetChildByName('Address');

        if ($objInfo) {
            $mixValueArray = array(
                'FirstName' => $objCart->Firstname,
                'LastName' => $objCart->Lastname,
                'Company' => $objCart->Company,
                'Email' => $objCart->Email,
                'Phone' => $objCart->Phone
            );

            $objInfo->UpdateFieldsFromArray($mixValueArray);
        }

        if ($objAddress) {
            $strBillingAddress = $objCart->AddressBill;

            if (count($strBillingAddress) == 6)
                $mixValueArray .= array(
                    'Street1' => $strBillingAddress[0],
                    'Street2' => $strBillingAddress[1],
                    'City' => $strBillingAddress[2],
                    'Country' => $strBillingAddress[4],
                    'State' => $strBillingAddress[3],
                    'Zip' => $strBillingAddress[5]
                );

            $objAddress->UpdateFieldsFromArray($mixValueArray);
        }
    }

    public function UpdateFieldsFromControl($objControl) {
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

