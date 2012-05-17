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
	
	<style type="text/css" xml:space="preserve">
		/*<![CDATA[*/
		      @import url(<?= adminTemplate('css/admin.css') ?>) all;
			  @import url(<?= adminTemplate('css/superfish.css') ?>) all;
		/*]]>*/
	</style>
	
</head>
<body>
<?php include_once(adminTemplate('pages.tpl.php')); ?>

<?php $this->RenderBegin(); ?>				
		<br /><br />
			
		<div id="options" class="accord rounded"> 
		<div id="tabs">
			<ul>
				<?php foreach($this->arrTabs as $type=>$label): ?>
				<a href="<?= $this->get_uri($type); ?>" >
					<li class="rounded 
						<?php if($type == $this->currentTab): ?>
							active
						<?php endif; ?> {5px top transparent}" style="display:block; float: left">
						<?= $label; ?>
					</li>
				</a>
				<?php endforeach; ?>
			</ul>
		</div>

<?php

if(isset($this->HelperRibbon)) 
	if (strlen($this->HelperRibbon)>0)
		echo '<div style="padding: 5px;"><img style="padding-right: 5px; width:44px; height:35px;" align="left" src="'.adminTemplate('css/images/questionmark.png').'"> '.$this->HelperRibbon.'</div>';

?>	

<div id="customer_registration edit_height module_config">

<fieldset>
		<legend><?php _xt('Account Information') ?></legend>

		<div class="left margin">
			<dl>
				<dt><?php _xt("First Name"); ?></dt><dd><?php $this->BillingContactControl->FirstName->RenderWithError(); ?></dd>
				<dt><?php _xt("Last Name"); ?>:</dt><dd><?php $this->BillingContactControl->LastName->RenderWithError(); ?></dd>
			</dl>
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Company"><?php _xt("Company"); ?></label></dt>
				<dd><?php $this->BillingContactControl->Company->Render(); ?></dd>
			</dl><br />
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Phone"><span class="red">*</span> <?php _xt("Phone"); ?></label></dt>
				<dd><?php $this->BillingContactControl->Phone->RenderWithError() ?></dd>
			</dl>	
		</div>

		<div class="left margin clear">
			<dl>
				<dt><label for="Email"><span class="red">*</span> <?php _xt("Email"); ?></label></dt>
				<dd><?php $this->BillingContactControl->Email->RenderWithError() ?></dd>
			</dl>	
		</div>

		
		
		</fieldset>	
			
<? /*
            case 'txtCRFName':
                return $this->BillingContactControl->FirstName;

            case 'txtCRLName': 
                return $this->BillingContactControl->LastName;

            case 'txtCRCompany': 
                return $this->BillingContactControl->Company;

            case 'txtCRMPhone': 
                return $this->BillingContactControl->Phone;

            case 'txtCREmail': 
                return $this->BillingContactControl->Email;
                
            case 'txtCRConfEmail':
                return $this->BillingContactControl->EmailConfirm;  

            case 'txtCRBillAddr1':
                return $this->BillingContactControl->Street1;
            
            case 'txtCRBillAddr2':
                return $this->BillingContactControl->Street2;

            case 'txtCRBillCity':
                return $this->BillingContactControl->City;

            case 'txtCRBillCountry':
                return $this->BillingContactControl->Country;

            case 'txtCRBillState':
                return $this->BillingContactControl->State;

            case 'txtCRBillZip':
                return $this->BillingContactControl->Zip;
              */   ?>
              
              
       
</div>
									
				
		
<?php $this->RenderEnd(); ?>		
</body>
</html>
