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
 * Framework template Item listed in shopping cart 
 *
 * 
 *
 */


	global $customer;
	global $_SESSION; 



if($customer): ?>
<p style="margin-left:20px;"><a href="<?php echo _xls_site_url("gift-registry/pg/"); ?>"><?php _xt("Gift Management"); ?></A></p>
<?php  endif;  ?>
<?php if(isset($_SESSION['gift_reg_code'])): ?>
	<p style="margin-left:20px;"><a href="<?php echo _xls_site_url("gift-list/pg/"); ?>"><?php _xt("Gift List"); ?></A></p>
<?php  endif;  ?>

<p style="margin-left:20px;"><a href="<?php echo _xls_site_url("gift-search/pg/"); ?>"><?php _xt("Search"); ?></A></p>
