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
 * Deluxe template: Template used as HTML email to customer showing contents 
 * of placed order
 * 
 *
 */

?>

<br /><?php _xt("Dear") ?>  <?= $cart->Contact ?>,<br /><br />

<?php _xt("Thank you for your order with  ") ?>  <?= _xls_get_conf('STORE_NAME')  ?>. <br /><br />

<?php  include(templateNamed('email_cart.tpl.php')); ?>

<?php _xt("This email is a confirmation for the order. To view details or track your order, click on the visit link: ")  ?> 

<a href="<?= _xls_site_dir() . "/order-track/pg/?getuid=" . $cart->Linkid;  ?>"><?= _xls_site_dir() . "/order-track/pg/?getuid=" . $cart->Linkid;  ?></a><br /><br />

<?php _xt("Please refer to your order ID ") ?> <strong><?php echo $cart->IdStr ;   ?></strong> <?php _xt(" if you want to contact us about this order.") ?><br /><br />

			
<?=  _xls_get_conf('EMAIL_SIGNATURE'); ?>
