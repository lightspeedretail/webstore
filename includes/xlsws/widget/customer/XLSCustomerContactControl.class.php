<?php

class XLSCustomerContactControl extends XLSCustomerComposite {
    protected $arrRegisteredChildren = array(
        'Info', 'Address'
    );

    protected $objInfoControl;
    protected $objAddressControl;

    protected function BuildInfoControl() {
        $objControl = $this->objInfoControl = 
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
        $objControl = $this->objAddressControl = 
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
        if (in_array($strName, $this->arrRegisteredChildren))
            return $this->GetChildByName($strName);

        $objInfo = $this->objInfoControl;
        if ($objInfo)
            if (in_array($strName, $objInfo->RegisteredChildren))
                return $objInfo->GetChildByName($strName);

        $objAddress = $this->objAddressControl;
        if ($objAddress)
            if (in_array($strName, $objAddress->RegisteredChildren))
                return $objAddress->GetChildByName($strName);

        try {
            return parent::__get($strName);
        }
        catch (QCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }
}

