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
 * Basic template: Wish List (Gift Registry) Create New Wish List on crumb header
 * 
 * 
 *
 */

?>
	<div class="add_gregistry"><a href="#" <?= $this->pxyGRCreate->RenderAsEvents(); ?>><img src="<?= templateNamed('css/images/btn_add.png') ?>" alt="<?php _xt('Add'); ?>" style="margin: 0 2px -3px 0;" /> <?php _xt('Create New Wish List'); ?></a></div>
		
	<div class="registry rounded">
		<div class="registry_header">
			<p class="left"><?php _xt('Wish List'); ?></p>
			<div class="right">
				<p style="margin: 0 91px 0 0;"><?php _xt('Expiry'); ?></p>
				<p style="margin: 0 20px 0 0;"><?php _xt('Edit'); ?></p>
			</div>
		</div>
				
		<?php $this->dtrGiftRegistry->Render(); ?>

	</div>
