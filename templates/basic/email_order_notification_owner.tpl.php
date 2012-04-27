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
 * Basic template: Template used as HTML email to store that an order 
 * has been placed. Orders are downloaded to LightSpeed, so this provides
 * redundancy if desired.
 *
 */

?>

<?php _xt("Hi Store owner") ?>,<br/><br/>

<?php _xt("You have a new order  ") ?>  <?php echo $cart->IdStr ;   ?>. <br/><br/>

<?php  include(templateNamed('email_cart.tpl.php')); ?>

<?php _xt("Click here to view the order: ")  ?> <br/><br/>

<a href="<?= _xls_site_dir() . "/order-track/pg/?getuid=" . $cart->Linkid;  ?>"><?= _xls_site_dir() . "/order-track/pg/?getuid=" . $cart->Linkid;  ?></a><br/><br/>

<?=  _xls_get_conf('EMAIL_SIGNATURE'); ?>
