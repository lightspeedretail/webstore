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
 * template Wish List (Gift Registry) Editing, Headers for commands
 *
 *
 *
 */

?>

<div id="wishlistdisplay">

	<div class="row rowborder">
		<div class="four columns alpha">
			<span class="label"><?php _xt('Invitees') ?></span>
		</div>
		<div class="four columns">
			<span class="label"><?php _xt('Email') ?></span>
		</div>
		<div class="one columns">
			<span class="label"><?php _xt('Send') ?></span>
		</div>
		<div class="two columns">
			<span class="label"><?php _xt('Edit') ?></span>
		</div>
		<div class="one columns omega">
			<span class="label"><?php _xt('Delete') ?></span>
		</div>
	</div>

	<?php $this->dtrEmail->Render(); ?>

	<div class="row">
		</div>
	<div class="row">

		<div class="three columns alpha lightbutton">
			<a href="#" <?php $this->pxyRecNew->RenderAsEvents(); ?>><span class="label"><?php _xt('Add Invitee') ?></span></a>
		</div>

		<div class="five columns">
		&nbsp;
		</div>
		<div class="three columns omega lightbutton">
			<a href="#" <?php $this->pxyMailAll->RenderAsEvents(); ?>><span class="label"><?php _xt('Send Mail To All') ?></span></a>
		</div>

	</div>

</div>
