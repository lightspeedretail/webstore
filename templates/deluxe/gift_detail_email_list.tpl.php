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
 * Deluxe template: Wish List (Gift Registry) Editing, Headers for commands
 * 
 * 
 *
 */

?>


<div class="registry rounded">
		<div class="registry_header">
			<p class="left"><?php _xt('Invitees') ?></p>
			<div class="right">
				<p style="margin: 0 289px 0 0;"><?php _xt('Email') ?></p>
				<p style="margin: 0 20px 0 0;"><?php _xt('Send Email') ?></p>
				<p style="margin: 0 20px 0 0;"><?php _xt('Edit') ?></p>
				<p style="margin: 0 15px 0 0;"><?php _xt('Delete') ?></p>
			</div>
		</div>
				
		<?php  $this->dtrEmail->Render(); ?>
		
		<div class="registry_header">
			<p class="addinvitee">
				<a href="#" <?php $this->pxyRecNew->RenderAsEvents(); ?>><img src="<?= templateNamed('css/images/btn_add.png') ?>" alt="<?php _xt('Add Invitee') ?>" /> <?php _xt('Add Invitee') ?></a>
			</p>
			<p class="mailall"><a href="#"  <?php $this->pxyMailAll->RenderAsEvents(); ?>><img src="<?= templateNamed('css/images/btn_email.png') ?>" alt="<?php _xt('Send Mail To All') ?>" style="margin: 0 10px -3px 0;" /><?php _xt('Send Mail To All') ?></a>
			</p>
		</div>
		
</div>
