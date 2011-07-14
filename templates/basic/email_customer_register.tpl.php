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
 * Basic template: Template used as HTML email for new registration 
 *
 * 
 *
 */

?>

<br /><?php _xt("Hello") ?>  <?= $cust->Firstname ?>,<br/><br/>

<?php _xt("Thank you for registering at") ?>  <?= _xls_get_conf('STORE_NAME')  ?>. <br/><br/>

<?php _xt("You can access your account by clicking the login button available on each page.  ") ?> <br/><br/>

<?php if($cust->AllowLogin == 0){ ?>
<?php _xt("Your account is currently awaiting activation. We will advise you once it has been activated ")  ?><br/><br/>
<?php } ?>
			
<?=  _xls_get_conf('EMAIL_SIGNATURE'); ?>
