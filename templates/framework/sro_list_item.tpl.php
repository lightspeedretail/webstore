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
 * Framework template SRO Track individual item details 
 * only comes up directly with sro-track/pg
 * when logged in
 *
 */



?>

<div class="border">
    <b>ID #</b><?php _xt($_ITEM->LsId); ?><br/>
    Lodged: <b><?php _xt($_ITEM->DatetimeCre); ?></b><br/>
	Status: <?php _xt($_ITEM->Status); ?><br/>
	<a href="<?php echo _xls_site_url('sro-track/pg?dosearch=true&orderid='.$_ITEM->LsId.'&emailphone='.$_ITEM->CustomerEmailPhone); ?>"><?php _xt("View"); ?></a>
</div>

<?php
if ((($_CONTROL->CurrentItemIndex % 2) != 0) ||
	($_CONTROL->CurrentItemIndex == count($_CONTROL->DataSource) - 1))
	echo '<br style="clear:both;"/>';
?>

