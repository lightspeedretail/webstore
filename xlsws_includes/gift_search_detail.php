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
	 global $XLSWS_VARS;
	 
	require(__QCODO__.'/qform/QJsCalendar.class.php');

	if(!isset($XLSWS_VARS['gift_code']) && !isset($XLSWS_VARS['gift_token'])){
		header("location:index.php?xlspg=gift_search");
		exit;
	}	
	
	
	
	
    /**
     * xlsws_glist class
     * This is the controller class for the find wish list pages
     * This class is responsible for querying the database for various aspects needed on this page
     * and assigning template variables to the views related to the find wish list and view wish list pages
     * that an external shopper sees, not the creator of the wish list
     */		
   class xlsws_glist extends xlsws_index {
   	 
   		protected $dtrGiftList; //the registry listing data repeater
		protected $objGiftDetail; //the gift registry details object
		protected $objGiftList; //the registry listing data object
		protected $txtGListPassword; //the gift registry password textbox
		protected $btnGetIn; //the view gift registry button
		protected $btnGetOut; //the leave gift registry button
		protected $logTrack; //unused, ignore
		
		protected $pxyPurchaseNow; //the callback widget for when someone tries to buy an item from a registry

		protected $blnClearCart = false; //clear the cart before? 
		
		protected $dlgGiftQty; //the quantity dropdown in the modal box for adding an item to gift registry

        /**
         * build_main - constructor for this controller
         * @param none
         * @return none
         */  
        protected function build_main() {
		
			global $XLSWS_VARS;
			
			
			
			$this->mainPnl = new QPanel($this);

			$this->mainPnl->Template = templateNamed('gift_search_detail.tpl.php');
			

			$this->crumbs[] = array('key'=>'xlspg=gift_search' , 'case'=> '' , 'name'=> _sp('Wish List'));
			
			$this->txtGListPassword = new XLSTextBox($this);
			$this->txtGListPassword->TextMode =QTextMode::Password;
			$this->txtGListPassword->AddAction(new QEnterKeyEvent(), new QServerAction('btnGetIn_Click'));
			
			
			$this->btnGetIn = new QButton($this);
			$this->btnGetIn->Text = _sp('Submit');
			
			if(isset($XLSWS_VARS['gift_token']))
				$XLSWS_VARS['gift_code'] = $XLSWS_VARS['gift_token'];
			
			if(isset($XLSWS_VARS['gift_code'])){
				$this->btnGetIn->ActionParameter = $XLSWS_VARS['gift_code'];
				$this->txtGListPassword->ActionParameter = $XLSWS_VARS['gift_code'];
			}
			$this->btnGetIn->AddAction(new QClickEvent(), new QServerAction('btnGetIn_Click'));
						
			$this->objGiftDetail=GiftRegistry::LoadByGiftCode($XLSWS_VARS['gift_code']);
			
			// if no password then just go in
			if($this->objGiftDetail->RegistryPassword == '')
				$this->logTrack =1;
						
			// if already authed, then just go in
			if(_xls_stack_get('GIFT_REGISTRY_AUTHED') == $this->objGiftDetail->Rowid)
				$this->logTrack =1;
			
			$this->crumbs[] = array('key'=>'xlspg=gift_search_detail&gift_code='.$XLSWS_VARS['gift_code'] , 'case'=> '' , 'name'=> $this->objGiftDetail->RegistryName);
			
			
			$this->dtrGiftList = new QDataRepeater($this);
			$this->dtrGiftList->Template = templateNamed('gift_search_detail_item.tpl.php');

			$this->dtrGiftList->SetDataBinder('dtrGiftList_Bind');
			//$this->dtgRegList->UseAjax = true;
			
            $this->pxyPurchaseNow = new QControlProxy($this);
            
            $cart = Cart::GetCart();
   			
           	$this->pxyPurchaseNow->AddAction(new QClickEvent() , new QAjaxAction('show_qty_box'));
            $this->pxyPurchaseNow->AddAction(new QClickEvent() , new QJavaScriptAction("return false;"));
            
            
            $this->dlgGiftQty = new XLSdxGiftQty($this , $this);

	    }
		
        /**
         * Purchase_now - adds an item from gift registry to cart and changes its status if applicable
         * @param GiftRegistryItem object - the gift registry line item object
         * @return Qbutton - a rendered button with its text
         */  	    
	    public function Purchase_now($gift_product){
	    	
	    	$status = $gift_product->getPurchaseStatus();
	    	
	    	
	    	$strControlId = 'btnGRBuy' . $gift_product->Rowid;
	    	$btnGRBuy = $this->GetControl($strControlId);
	    	if (!$btnGRBuy) {
	    		$btnGRBuy = new QButton($this->dtrGiftList, $strControlId);
	    		$btnGRBuy->ToolTip='Add this item to cart';
	    		$btnGRBuy->ActionParameter = $gift_product->Rowid;
	    		$btnGRBuy->AddAction(new QClickEvent(), new QAjaxAction('show_qty_box'));
	    		$btnGRBuy->CausesValidation = false;
	    		$btnGRBuy->Text = 'Add to cart';
	    		$btnGRBuy->CssClass = 'btn_wishlist_add_to_cart';
	    	}

	    	
	    	switch($status){
	    		case GiftRegistryItems::PURCHASED_BY_CURRENT_GUEST:
	    			$btnGRBuy->Text = _sp("Thank you!");
	    			$btnGRBuy->Enabled = false;
	    			$btnGRBuy->Width = "100";	    			
	    			break;
	    		case GiftRegistryItems::PURCHASED_BY_ANOTHER_GUEST:	
	    			$btnGRBuy->Text = _sp("Purchased");
	    			$btnGRBuy->Enabled = false;
	    			$btnGRBuy->Width = "100";	    			
	    			break;
	    		case GiftRegistryItems::INCART_BY_CURRENT_GUEST:	
	    			$btnGRBuy->Text = _sp("Thank you!");
	    			$btnGRBuy->Enabled = false;
	    			$btnGRBuy->Width = "100";	    			
	    			break;
	    		case GiftRegistryItems::INCART_BY_ANOTHER_GUEST:	
	    			$btnGRBuy->Text = _sp("Purchased");
	    			$btnGRBuy->Enabled = false;
	    			$btnGRBuy->Width = "100";	    			
	    			break;
	    		case GiftRegistryItems::MULTIPLE_ITEMS_REMAIN:
	    		default:
	    			$btnGRBuy->Text = _sp("Buy now");
	    			$btnGRBuy->Enabled = true;
	    			$btnGRBuy->Width = "100";	    			
	    	}
	    	
	    	
	    	return $btnGRBuy->Render(false);
	    	
	    	
	    }
	    

        /**
         * dtgGiftList_Bind - binds a list of line item to the applied gift registry
         * @param none
         * @return none
         */  		    
		public function dtrGiftList_Bind() {


			$this->objGiftList=GiftRegistryItems::LoadArrayByRegistryId($this->objGiftDetail->Rowid);
			
			$this->dtrGiftList->DataSource = $this->objGiftList;
		
		}
		
        /**
         * btnGetIn_Click - Callback function for when you try to enter into a gift registry
         * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
         * @return none
         */  			
		public function btnGetIn_Click($strFormId, $strControlId, $strParameter){

			if($this->txtGListPassword->Text ==''){
				$this->txtGListPassword->Warning='Required';
				return;
			}

			$regInfo=GiftRegistry::LoadByGiftCode($strParameter);
			
			if($this->txtGListPassword->Text != $regInfo->RegistryPassword){
				$this->txtGListPassword->Warning='Wrong Password';
				return;
			}
			else{
				$this->logTrack=1;
			
				_xls_stack_add('GIFT_REGISTRY_AUTHED' , $regInfo->Rowid);	
			}
		}
		
		
        /**
         * show_qty_box - Callback function to decide whether to show modal box to choose quantity to add
         * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
         * @return none
         */  			
		protected function show_qty_box($strFormId, $strControlId, $strParameter){
			
   			$item = GiftRegistryItems::Load($strParameter);
   			
   			if(!$item){
   				QApplication::ExecuteJavaScript("alert('" . _sp('Sorry, selected item was not found.')  .  "')");
   				return;
   			}

   			if($item->RegistryId != $this->objGiftDetail->Rowid){
   				QApplication::ExecuteJavaScript("alert('" . _sp('Item not found in Wish List.')  .  "')");
   				return;
   			}
   			
   			$status = $item->getPurchaseStatus();
   			if(($status != GiftRegistryItems::NOT_PURCHASED) && ($status != GiftRegistryItems::MULTIPLE_ITEMS_REMAIN)){
   				_qalert(_sp("This item has already been purchased or being purchased by another customer."));
   				$this->dtrGiftList->Refresh();
   				return;
   			}

   			if($item->Qty == 1)
   				$this->do_add_to_cart($strFormId, $strControlId, $strParameter , 1);
   			else{
   				$this->dlgGiftQty->create_qty( $strParameter , $item->Qty - $item->AddedQty - $item->PurchasedQty);
   				$this->dlgGiftQty->doShow();
   			}
   				
   			
		}

        /**
         * do_add_to_cart - Adds an item from the registry to cart and removes it from the remaining pile
         * @param integer, integer, string $strFormId, $strControlId, $strParameter :: Passed by Qcodo by default
         * @param integer - quantity you want to add
         * @return none
         */  			
   		public function do_add_to_cart($strFormId, $strControlId, $strParameter , $qty = 1){

   			
   			
   			$item = GiftRegistryItems::Load($strParameter);
   			
   			if(!$item){
   				QApplication::ExecuteJavaScript("alert('" . _sp('Sorry, selected item was not found.')  .  "')");
   				return;
   			}

   			if($item->RegistryId != $this->objGiftDetail->Rowid){
   				QApplication::ExecuteJavaScript("alert('" . _sp('Item not found in Wish List.')  .  "')");
   				return;
   			}
   			
   			
   			$status = $item->getPurchaseStatus();
   			if(($status != GiftRegistryItems::NOT_PURCHASED) && ($status != GiftRegistryItems::MULTIPLE_ITEMS_REMAIN)){
   				_qalert(_sp("This item has already been purchased or being purchased by another customer."));
   				$this->dtrGiftList->Refresh();
   				return;
   			}
   			
			$prod = Product::Load($item->ProductId);
			
			
   		   	if(!$prod){
   				QApplication::ExecuteJavaScript("alert('" . _sp('The product in Wish List does not exist.')  .  "')");
   				return;
   			}


   			$cart = Cart::GetCart();
   			
   			$this->cartPnl->RemoveChildControls(true);
   			
   			

   			$item->PurchaseStatus = Cart::add_to_cart($prod , $qty , FALSE , FALSE , 0 , CartType::giftregistry , $strParameter);
   			$item->Save();

   			$cart = Cart::GetCart();
   			$cart->GiftRegistry = $this->objGiftDetail->Rowid;
   			$cart->ssave();
   			
   			
   			$this->build_cart();
   			$this->cartPnl->Refresh();
   			$this->dtrGiftList->Refresh();

		}
		
		
		
		
		
	
		
   }
   
   
   if(!defined('CUSTOM_STOP_XLSWS'))
   	xlsws_glist::Run('xlsws_glist', templateNamed('index.tpl.php'));

?>
