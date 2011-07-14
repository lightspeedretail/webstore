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
 * Deluxe template: Template used as HTML email for forgotten password 
 *
 * 
 *
 */

?>


	<br /><p><?php _xt("Hello") ?> <strong><?= $cust->Firstname ?></strong>,</p>

	<p><?php printf(_sp("Your requested temporary password is '<strong>%s</strong>'") , $cust->TempPassword) ?>.</p>
	
	<p><?php printf(_sp("Your password was requested from from this Host/IP address: <strong>%s</strong>. You must change your password in your account settings once you have logged in with the temporary password above.") ,_xls_get_ip()) ?></p> 

<?php if($cust->AllowLogin == 0){ ?>
<?php _xt("Your account is currently awaiting activation. We will advise you shortly when it is ready ")  ?><br/><br/>
<?php } ?>

	<p><?=  nl2br(_xls_get_conf('EMAIL_SIGNATURE')); ?></p>
