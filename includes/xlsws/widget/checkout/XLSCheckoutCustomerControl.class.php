<?php

//We just extend the XLSCustomerControl (Billing and Shipping) for use in Checkout because we save the cart as we navigate

class XLSCheckoutCustomerControl extends XLSCustomerControl {
   
   
   	 protected function BuildBillingControl() {
   	 	parent::BuildBillingControl();
   	 	$this->UpdateBillingControl();
   	 }
   	 
	protected function UpdateBillingControl() {
 
        $objControl = $this->GetChildByName('Billing');
        if (!$objControl)
            return;

		parent::UpdateBillingControl();
		
        $objControl->UpdateFieldsFromCart();
        $objControl->UpdateFieldsFromCustomer();
        $objControl->SaveFieldsToCart();

        return $objControl;
    }
    
    
    protected function BuildShippingControl() {
        parent::BuildShippingControl();
   	 	$this->UpdateShippingControl();
    }

    protected function UpdateShippingControl() {
        $objControl = $this->GetChildByName('Shipping');
        if (!$objControl)
            return;

		parent::UpdateShippingControl();
		
        $objControl->UpdateFieldsFromCart();
        $objControl->UpdateFieldsFromCustomer();
        $objControl->SaveFieldsToCart();

        return $objControl;
    }
    

}

