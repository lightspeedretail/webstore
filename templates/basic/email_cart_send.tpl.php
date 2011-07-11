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
 * Basic template: Template used as HTML email for cart recipient 
 * This calls email_cart.tpl.php which provides the cart details
 * 
 *
 */

?>

<br /><?php _xt("Hello") ?>  <?= $obj->txtToName->Text ?>,<br/><br/>

<?= $obj->txtFromName->Text ?> <?php _xt('has sent you a quote') ?> :

 <br/>
 
<?php  include(templateNamed('email_cart.tpl.php')); ?>
 
 <br/>

<?php _xt("Click on the following link to view this cart.") ?><br/>
<br/>
<a href="<?= $cart->get_link(); ?>"><?= $cart->get_link(); ?></a><br/>
<br/><br/>
<?= nl2br($cart->PrintedNotes); ?>

 <br/><br/>


<?=  _xls_get_conf('EMAIL_SIGNATURE'); ?>
