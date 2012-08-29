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
   
 * @copyright  Copyright (c) 2011 Xsilva Systems, Inc. http://www.lightspeedretail.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 
 */

/**
 * Web Admin panel template called by xlsws_admin_ship_modules class
 * Used for shipping modules
 * 
 *
 */

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" dir="ltr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Web Store Configuration</title>

    <script type="text/javascript" src="<?=  adminTemplate('js/jquery.min.js');  ?>"></script>     
    <script type="text/javascript" src="<?=  adminTemplate('js/jquery.ui.js');  ?>"></script>     
	<script type="text/javascript" src="<?=  adminTemplate('js/admin.js'); ?>"></script>
	<script type="text/javascript" src="<?=  adminTemplate('js/corners.js'); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?=  adminTemplate('css/admin.css'); ?>" id="admincss"  />
	<link rel="stylesheet" type="text/css" href="<?=  adminTemplate('css/superfish.css'); ?>" id="superfishcss"  />

	<script type="text/javascript"> 
    $(document).ready(function(){ 
        $("ul.sf-menu").superfish(); 
    }); 
	</script>
	
	<script type="text/javascript">
  $(document).ready(function(){
    $('.rounded').corners();
    $('.rounded').corners(); /* test for double rounding */
    $('table', $('#featureTabsc_info .tab')[0]).each(function(){$('.native').hide();});
    $('#featureTabsc_info').show();
  });
  function tab(n) {
    $('#featureTabsc_info .tab').removeClass('tab_selected');
    $($('#featureTabsc_info .tab')[n]).addClass('tab_selected');
    $('#featureElementsc_info .feature').hide();
    $($('#featureElementsc_info .feature')[n]).show();
  }
  </script>
	

</head>
<body>
<?php 
if(isset($this->AlertRibbon)) 
	if (strlen($this->AlertRibbon)>0)
		echo '<div style="margin: 10px 70px 5px 70px; padding: 4px; background:  url('.adminTemplate('css/images/header.png').'); height: 28px;"><img style="padding-right: 5px;width:18px; height:17px;" align="left" src="'.adminTemplate('css/images/btn_info.png').'">'.$this->AlertRibbon.'</div>';

include_once(adminTemplate('pages.tpl.php')); ?>

<?php $this->RenderBegin(); ?>				
		<div id="mainNav">
		<?php
		
		foreach($this->arrTabs as $type=>$label)
			echo '<a class="mainNavItem'.($type == $this->currentTab ? " active" : "").'" href="'.$this->get_uri($type).'"><span class="innertab">'.$label.'</span></a>';
		?>
		</div>
		<br clear="both">
		
<div id="options"  style="width:960px;" >
	<div class="content">	
<?php

if(isset($this->HelperRibbon)) 
	if (strlen($this->HelperRibbon)>0)
		echo '<div style="padding: 5px;"><img style="padding-right: 5px; width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

?>	


	<div class="title rounded"> 
		<div class="name" style="cursor:pointer;">Editing Order <?= $this->page; ?></div> 
		<div style="float:right">
			<?php $this->btnSave->Render('CssClass=button rounded'); ?><?php $this->btnCancel->Render('CssClass=button rounded'); ?></div> 
	</div>
						
						
<div id="customer_registration edit_height module_config">

	<div id='editcontainer'>
	
		<div class="basic_row">
			<div class="colshortlabel">First Name:</div><div class="colfield"><?php $this->BillingContactControl->FirstName->RenderWithError('CssClass=smallfont'); ?></div>
			<div class="colshortlabel">Last Name:</div><div class="colfield"><?php $this->BillingContactControl->LastName->RenderWithError('CssClass=smallfont'); ?></div>
			<div class="colshortlabel">Phone:</div><div class="colfield"><?php $this->BillingContactControl->Phone->RenderWithError('CssClass=smallfont') ?></div>
			<div class="clear_float"></div>
		</div> 
	
		<div class="basic_row">
			<div class="colshortlabel">Company:</div><div class="colfield"><?php $this->BillingContactControl->Company->Render('CssClass=smallfont'); ?></div>
			<div class="colshortlabel">Email:</div><div class="colfieldwide"><?php $this->BillingContactControl->Email->RenderWithError('CssClass=smallfont') ?></div>
		</div>
		 
		<div class="thin_row">
		</div>
		
		<div class="basic_row">
			<div class="colshortlabel">Bill To:</div><div class="coladdress">
				<?php 
				$this->BillingContactControl->Street1->Render('CssClass=smallfont'); echo "<br clear='left'>";
				$this->BillingContactControl->Street2->Render('CssClass=smallfont'); echo "<br clear='left'>";
				$this->BillingContactControl->City->Render('CssClass=smallfont w100');
				$this->BillingContactControl->State->Render(); echo "<br clear='left'>";
				$this->BillingContactControl->Zip->Render('CssClass=smallfont w70'); echo "<br clear='left'>";
				$this->BillingContactControl->Country->Render(); 
				?></div>
			
			<div class="colshortlabel">Ship To:</div><div class="coladdress">
				<?php 
				$this->ShippingContactControl->FirstName->Render('CssClass=smallfont w70 mr10'); echo "&nbsp;&nbsp;";
				$this->ShippingContactControl->LastName->Render('CssClass=smallfont w70'); echo "<br clear='left'>";
				$this->ShippingContactControl->Street1->Render('CssClass=smallfont'); echo "<br clear='left'>";
				$this->ShippingContactControl->Street2->Render('CssClass=smallfont'); echo "<br clear='left'>";
				$this->ShippingContactControl->City->Render('CssClass=smallfont w100');
				$this->ShippingContactControl->State->Render(); echo "<br clear='left'>";
				$this->ShippingContactControl->Zip->Render('CssClass=smallfont w70'); echo "<br clear='left'>";
				$this->ShippingContactControl->Country->Render(); 
				?></div>
	
			<div class="colshortlabel">Pay Method:</div><div class="colfield">
				<?php 
				$this->PaymentControl->ModuleControl->Visible = true;
				$this->PaymentControl->ModuleControl->Enabled = true;
				$this->PaymentControl->ModuleControl->Render(); 
	
				?>
				
				<div class="colshortlabel">Amt Paid:</div><div class="colshortlabel"><?php $this->ctlPaymentAmount->Render(); ?></div>
				<div class="colshortlabel">Reference #:</div><div class="colshortlabel"><?php $this->ctlPaymentRef->Render(); ?></div>
				<div class="colshortlabel"><?php $this->ctlShipLabel->Render(); ?>:</div><div class="colshortlabel"><?php $this->ctlShippingTotal->Render(); ?></div><div class="clear_float"></div>
				<div class="colshortlabel">Order Total:</div><div class="colshortlabel"><?php $this->ctlOrderTotal->Render(); ?></div>
					
					
					
				</div>
			<div class="clear_float"></div>	
				
		
	
	<hr>

	
	<div class="clear_float"></div>
	
	
			<div class="basic_row tableheader ">
				<div class="colfield ml10">Product Code</div>
				<div class="colfield w70">Qty</div>
				<div class="colfield w300">Description</div>
				<div class="colfield">Delete This Item</div>

				<div class="clear_float"></div>
			</div> 



	<? 
		foreach ($this->arrProducts as $arrProduct) { ?>
		
			<div class="basic_row rowbg" id="row<? echo $arrProduct['Rowid']->Text; ?>">
				<div class="colfield ml10"><?php $arrProduct['Code']->Render(); ?></div>
				<div class="colfield w70"><?php $arrProduct['Qty']->Render(); ?></div>
				<div class="colfield w300"><?php $arrProduct['Description']->Render(); ?></div>
				<div class="colfield w70"><?php $arrProduct['Delete']->Render(); ?></div>


				<div class="clear_float"></div>
			</div> 
		<?	
		
		
		}
	
	?>
				
	</div>
             
              
       
</div>
									
				
		
<?php $this->RenderEnd(); ?>		
</body>
</html>
