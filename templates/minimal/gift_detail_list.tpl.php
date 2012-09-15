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
 * template Wish List (Gift Registry) Create New Wish List on crumb header
 *
 *
 *
 */

?>
<div id="orderdisplay" class="twelve column alpha omega">
	<div class="row">
		<div class="eight columns alpha">
			<h1><?php _xt('Wish Lists'); ?></h1>
		</div>
		<div class="four columns omega">
			<a href="#" <?= $this->pxyGRCreate->RenderAsEvents(); ?>><?php _xt('Create New Wish List'); ?></a>
		</div>
	</div>

	<div class="row">
		<div class="four columns alpha">
			<span class="label"><?php _xt('Name'); ?></span>
		</div>

		<div class="three columns">
			<span class="label"><?php _xt('Expiry'); ?></span>
		</div>

		<div class="three columns omega">

		</div>

	</div>

	<?php $this->dtrGiftRegistry->Render(); ?>
</div>