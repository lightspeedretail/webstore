<?php
/*
  LightSpeed Web Store
 
  NOTICE OF LICENSE
 
  This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to support@lightspeedretail.com <mailto:support@lightspeedretail.com>
 * so we can send you a copy immediately.
 
  DISCLAIMER
 
 * Do not edit or add to this file if you wish to upgrade Web Store to newer
 * versions in the future. If you wish to customize Web Store for your
 * needs please refer to http://www.lightspeedretail.com for more information.
 
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

	require(__DATAGEN_CLASSES__ . '/GiftRegistryItemsGen.class.php');

	/**
	 * The GiftRegistryItems class defined here contains any
	 * customized code for the GiftRegistryItems class in the
	 * Object Relational Model.  It represents the "xlsws_gift_registry_items" table 
	 * in the database, and extends from the code generated abstract GiftRegistryItemsGen
	 * class, which contains all the basic CRUD-type functionality as well as
	 * basic methods to handle relationships and index-based loading.
	 * 
	 * @package My Application
	 * @subpackage DataObjects
	 * 
	 */
	class GiftRegistryItems extends GiftRegistryItemsGen {
		/**
		 * Default "to string" handler
		 * Allows pages to _p()/echo()/print() this object, and to define the default
		 * way this object would be outputted.
		 *
		 * Can also be called directly via $objGiftRegistryItems->__toString().
		 *
		 * @return string a nicely formatted string representation of this object
		 */
		public function __toString() {
			return sprintf('GiftRegistryItems Object %s',  $this->intRowid);
		}

		const NOT_PURCHASED = 0;
		const PURCHASED_BY_CURRENT_GUEST = 2;
		const PURCHASED_BY_ANOTHER_GUEST = 4;
		const INCART_BY_CURRENT_GUEST = 8;
		const INCART_BY_ANOTHER_GUEST = 16;
		const MULTIPLE_ITEMS_REMAIN = 24;
		const ALL_QTY_PURCHASED = 32;
		
		protected $intPurchaseCount = 0;
		protected $intAddedCount = 0;
		
		
		

		public function getPurchaseStatus(){
			
            $customer = Customer::GetCurrent();
	    	
	    	if($this->PurchaseStatus == 0){
	    		return self::NOT_PURCHASED;
	    		
	    	}
	    	
	    	// Get the cart item
	    	$cartItems = CartItem::QueryArray(QQ::AndCondition(QQ::Equal(QQN::CartItem()->GiftRegistryItem , $this->Rowid)));

	    	if(!$cartItems || (count($cartItems) == 0)){
	    		// reset it back to not being purchased
	    		$this->PurchaseStatus = 0;
	    		$this->Save();
	    		return $this->getPurchaseStatus();
	    	}
	    	
		    // Is it in current user's cart
		    $ccart = Cart::GetCart();
	    	
			$purchased = 0;
			$added = 0;
			$current_purchase = false;
			$current_added = false;
			
			
			foreach($cartItems as $cartItem){
		    	$cart = $cartItem->Cart;
	
		    	
		    	if(!$cart)
		    		continue;
		    	
		    	
		    	// Has the purchase been already taken place
		    	if($cart->Type == CartType::order){
		    		// how many has been purchased?
		    		$purchased += $cartItem->Qty;
		    		
		    		if($customer && ($cart->CustomerId == $customer->Rowid)) // Thank the current customer for purchase.
		    			$current_purchase = true;
		    	}
		    	
		    	
		    	// check the time difference - for when it was added to cart
		    	$time = $cartItem->DatetimeAdded;
		    	$time = $time->AddHours(floatval(_xls_get_conf('RESET_GIFT_REGISTRY_PURCHASE_STATUS' , 6)));
		    	
		    	// if it has not been purchased in the specified number of hours then reset!
		    	if(!$time->IsEarlierThan(QDateTime::Now())){
		    		$added += $cartItem->Qty;
		    	}
		    	
		    	if($ccart->Rowid == $cart->Rowid)
		    		$current_added = true;
		    	
		    	
				
			
	    	}
	    	
	    	
		    $this->intPurchaseCount = $purchased;
		    $this->intAddedCount = $added;
	    	
	    	if(($purchased == 0 ) && ($added == 0)){
		    	$this->PurchaseStatus = 0;
		    	$this->Save();
		    	return self::NOT_PURCHASED;
		    }
	    	
		    if($purchased >= $this->Qty){
				if($current_purchase)	    	
		    			return self::PURCHASED_BY_CURRENT_GUEST;
				else
		    			return self::PURCHASED_BY_ANOTHER_GUEST;
		    }
		    

		    if(($purchased + $added) >= $this->Qty ){
				if($current_added)	    	
		    			return self::INCART_BY_CURRENT_GUEST;
				else
		    			return self::INCART_BY_ANOTHER_GUEST;
		    }

		    
		    return self::MULTIPLE_ITEMS_REMAIN;
	    	
		}
		



		// Override or Create New Properties and Variables
		// For performance reasons, these variables and __set and __get override methods
		// are commented out.  But if you wish to implement or override any
		// of the data generated properties, please feel free to uncomment them.
		protected $strSomeNewProperty;

		public function __get($strName) {
			switch ($strName) {
				
				case 'PurchasedQty':
					return $this->intPurchaseCount;
				case 'AddedQty':
					return $this->intAddedCount;
				case 'Prod':
					$prod = Product::Load($this->intProductId);
					if($prod)
						return $prod;
					else
						return new Product();
					
					break;
				
				case 'PurchasedBy': 
					if($this->getPurchaseStatus() != self::NOT_PURCHASED){
						
						$ret = '';
						
						$cartItems = CartItem::QueryArray(QQ::AndCondition(QQ::Equal(QQN::CartItem()->GiftRegistryItem , $this->Rowid)));
						//$cartItem = CartItem::Load($this->PurchaseStatus);
						
						foreach($cartItems as $cartItem){
							$ret .=  ($cartItem->Cart->Customer?($cartItem->Cart->Customer->Firstname . ' ' . $cartItem->Cart->Customer->Lastname) : ( $cartItem->Cart->Name)) . "\n";
						}
						
						
						return $ret;
							
					}else
						return parent::__get($strName);

				default:
					try {
						return parent::__get($strName);
					} catch (QCallerException $objExc) {
						$objExc->IncrementOffset();
						throw $objExc;
					}
			}
		}

	}
?>
