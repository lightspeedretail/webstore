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

class XLSImagePopup extends QDialogBox{
		
		
		public $prod;
		public $current_image = false;
		
		
		public $pnlImage;
		
		public $btnCancel;
		
        public function __construct( $prod , $objParentObject, $strControlId = null) {
            parent::__construct($objParentObject, $strControlId);

            $this->strTemplate = templateNamed('product_image_popup.tpl.php');
                       
            $this->prod = $prod;
            $this->pnlImage = new QPanel($this);
            $this->pnlImage->HtmlEntities = false;
            
            $this->btnCancel = new QButton($this);
            $this->btnCancel->Text = _sp('Close');
            $this->btnCancel->AddAction(new QClickEvent(), new QAjaxControlAction($this, 'doCancel'));
            
            
        }
		
		
        public function doShow($prod , $imageid = false){
        	$this->prod = $prod;
        	
        	
        	if($imageid)
        		$this->current_image = $imageid;
        	else
        		$this->current_image = $prod->ImageId;
        		
        	$this->pnlImage->Text = '<img src="index.php?image=' . $this->current_image . '" alt="" class="" />';        		
        	
        	$img = Images::Load($this->current_image);
        	
        	$this->Width = $img->Width + 2;
        	$this->Height = $img->Height + 2;
        	
        	$this->ShowDialogBox();
        	
        }
        
        
        public function doCancel($strFormId, $strControlId, $strParameter){
        	$this->HideDialogBox();
        }
        
	}
?>