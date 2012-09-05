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
 * template Wish List (Gift Registry) Wish List details list (description, expiry, shipping)
 *
 *
 *
 */
?>

<div class="registry rounded">
	<div class="registry_header">
		<p class="left"><?php $this->misc_components['lblGRName']->Render(); ?></p>

		<div class="right">
			<a href="#" <?php $this->pxyGREdit->RenderAsEvents(); ?> class="edit_gregistry" style="margin: 0 15px 0 0;"><img
				src="<?= templateNamed('css/images/btn_edit.png') ?>" alt="<?php _xt('Edit') ?>"
				style="margin: 3px 0 0 0;"><?php _xt('Edit') ?></a>
		</div>
	</div>

	<div style="display:block; padding: 15px 15px 0 15px;">
		<?php $this->misc_components['lblGRHTML']->Render(); ?>
	</div>
	<div class="gregistry_desc">
		<p><b><?php _xt('Expires:') ?></b> <?php $this->misc_components['lblGRExpDate']->Render(); ?></p>

		<p><b><?php _xt('Shipping option:') ?></b> <?php $this->misc_components['lblGRShipOption']->Render(); ?></p>
	</div>
</div>
