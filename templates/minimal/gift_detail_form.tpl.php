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
 * template Wish List (Gift Registry) Create New Wish List form (details)
 *
 *
 *
 */

?>


<div class="orderdisplay">

		<?php _xt('Create New Wish List'); ?>


	<fieldset>
		<div class="row">
			<span class="label"><?php _xt('Name your Wish List') ?></span>
			<?php $this->txtGRName->RenderWithError(); ?>
		</div>
		
		<div class="row">

			<span class="label"><?php _xt('Choose a password') ?></span>
			<?php $this->txtGRPassword->RenderWithError(); ?>
		</div>

		<div class="row">
			<span class="label"><?php _xt('Confirm the Password') ?></span>
			<?php $this->txtGRConfPassword->RenderWithError(); ?>
		</div>

		<div class="row">
			<span class="label"><?php _xt('When should your Wish List expire? (mm/dd/yyyy)') ?></span>
			<?php $this->txtGRDate->RenderWithError(); ?>
		</div>

		<div class="row">
			<span class="label"><?php _xt('Where should the items ship to?') ?></span>
			<?php $this->txtGRShipOption->RenderWithError(); ?>
		</div>

		<div class="row">
			<label class="left"><?php _xt('Create a description for your Wish List:') ?></span>
			<?php $this->txtGRHtmlContent->RenderWithError(); ?>
		</div>
		<div class="row">
			<?php $this->btnGRSave->Render(); ?>
			<?php $this->btnGRCancel->Render(); ?>
		</div>
	</fieldset>

</div>
