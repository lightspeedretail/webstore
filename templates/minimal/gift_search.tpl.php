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
 * template Wish List (Gift Registry) Wish List viewing other's Wish Lists
 * form for search by email address
 *
 *
 */
?>

<div id="orderdisplay" class="twelve column alpha omega">
	<h1><?php _xt('Wish Lists'); ?></h1>



	<div class="row">
		<div class="four columns alpha">
			<span class="label"><?php _xt('List Name'); ?></span>
		</div>

		<div class="three columns">
			<span class="label"><?php _xt('Customer'); ?></span>
		</div>

		<div class="three columns omega">
			<span class="label"><?php _xt('Expiry'); ?></span>
		</div>

	</div>


	<?php $this->dtrGiftRegistry->Render(); ?>

		<h3><?php _xt('Search for wish list'); ?></h3>
		<div class="row">
			<span class="label">Email Address:</span><?php $this->txtEmail->Render(); ?> <?php $this->btnSearch->Render(); ?>
		</div>




</div>