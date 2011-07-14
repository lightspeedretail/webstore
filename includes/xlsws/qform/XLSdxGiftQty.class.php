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

   /*
     * XLSdxGiftQty
     * 
     * Creates the frame used for gift registry entry and quantities
     *             
     */
class XLSdxGiftQty extends QDialogBox {
		
		public $lstQty;
		public $btnAdd;
		public $btnCancel;
		
		protected $xls_parent;
		
		/*PHP object constructor*/
        public function __construct( $objParentObject, $parentObj ,  $strControlId = null) {
            parent::__construct($objParentObject, $strControlId);
			$this->xls_parent = $parentObj;
            
            $this->strTemplate = templateNamed('gift_search_detail_qty.tpl.php');
	                       
	
            $this->Visible = false;
	
			$this->lstQty = new XLSListBox($this);
			
			$this->lstQty->AddAction(new QEnterKeyEvent(), new QAjaxControlAction($this , "doAdd"));
			$this->lstQty->AddAction(new QEnterKeyEvent(), new QTerminateAction());
			
	
			$this->btnAdd = new QButton($this);
			$this->btnAdd->AddAction(new QClickEvent(), new QAjaxControlAction($this , "doAdd"));
			$this->btnAdd->Text = _sp('Add');
	            
            $this->btnCancel = new QButton($this);
			$this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this , "doCancel"));
			$this->btnCancel->Text = _sp('Cancel');
			
			
            
        }
		
        protected $strParam;
        
        public function create_qty($param , $qty){
        	$this->strParam = $param;
        	$this->lstQty->RemoveAllItems();
        	for($i =1; $i<= $qty ; $i++)
        		$this->lstQty->AddItem($i , $i);
        }
        
		public function doAdd($strFormId, $strControlId, $strParameter){
			$this->xls_parent->do_add_to_cart($strFormId, $strControlId, $this->strParam , $this->lstQty->SelectedValue);
			$this->HideDialogBox();
		}
        
        
        public function doShow(){
        	$this->ShowDialogBox();
        	$this->lstQty->Focus();
        }
        
        
        public function doCancel($strFormId, $strControlId, $strParameter){
        	$this->HideDialogBox();
        }
        
                
        
        
        
	}
?>