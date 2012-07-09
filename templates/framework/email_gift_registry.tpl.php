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
 * Framework template Template used as HTML email for Wish List (Gift Registry) 
 * email to 3rd party
 * 
 *
 */

?>

<br /><?php _xt("Hello") ?>  <?= $receipent->ReceipentName ?>,<br/><br/>

<?= $gift->Customer->Mainname  ?> <?php _xt(" has invited you to a Wish List on ")  ?> <?= _xls_get_conf('STORE_NAME')  ?>. <br/><br/>

<?php _xt("Please visit the site using the following link.") ?><br/><br/>

<?= _xls_site_url("gift_search_detail/pg") . "?gift_token=" . $gift->GiftCode ?><br/><br/>

<?php _xt("Wish List Password: ") ?><strong><?= $gift->RegistryPassword ?></strong><br/><br/>

<?php _xt("Thank You.") ?><br/><br/>
			
<?=  _xls_get_conf('EMAIL_SIGNATURE'); ?>
