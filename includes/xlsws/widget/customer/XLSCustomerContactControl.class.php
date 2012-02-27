<?php

class XLSCustomerContactControl extends XLSCustomerComposite {
    protected $arrRegisteredChildren = array(
        'Info', 'Address'
    );

    protected function BuildInfoControl() {
        $objControl =  
            new CustomerInfoControl($this, $this->GetChildName('Info'));

        $this->UpdateInfoControl();
        $this->BindInfoControl();
   
        return $objControl;
    }

    protected function UpdateInfoControl() {
        $objControl = $this->GetChildByName('Info');
        if (!$objControl)
            return;

        return $objControl->Update();
    }

    protected function BindInfoControl() {
    }

    protected function BuildAddressControl() {
        $objControl = 
            new CustomerAddressControl($this, 
            $this->GetChildName('Address'));

        $this->UpdateAddressControl();
        $this->BindAddressControl();

        return $objControl;
    }

    protected function UpdateAddressControl() {
    }

    protected function BindAddressControl() {
    }
    
    public function __get($strName) {
        $objInfo = $this->GetChildByName('Info');
        if ($objInfo)
            if (in_array($strName, $objInfo->RegisteredChildren))
                return $objInfo->$strName;

        $objAddress = $this->GetChildByName('Address');
        if ($objAddress)
            if (in_array($strName, $objAddress->RegisteredChildren))
                return $objAddress->$strName;

        try {
            return parent::__get($strName);
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }
}

