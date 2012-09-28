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
 * template My Account template (Logged in, My Account top link)
 *
 * 
 *
 */

?>
<div id="orderdisplay" class="twelve column alpha omega">

		<div class="row">
			<div class="ten columns alpha">
				<h1><?php _xt('Welcome'); ?> <?= $this->customer->Firstname ?></h1>
			</div>
			<div class="two columns omega">
				<a href="customer-register/pg">Edit Profile</a>
			</div>
		</div>

		<div class="row">
			<div class="two columns alpha"><span class="label"><?php _xt('Name') ?>:</span></div>
				<div class="four columns"><?php echo $this->customer->Firstname . " " . $this->customer->Lastname ?></div>
			<div class="two columns alpha"><span class="label"><?php _xt('Phone') ?>:</span></div>
				<div class="four columns"><?php echo $this->customer->Mainphone ?></div><br clear="left">
			<div class="two columns alpha"><span class="label"><?php _xt('Address') ?>:</span></div>
				<div class="four columns"><?= $this->customer->Address11 ?>,
					<?= $this->customer->Address12 ?><br clear="left"><?= $this->customer->City1 ?>,
					<?= $this->customer->State1 ?> <?= $this->customer->Zip1 ?></div>
		</div>

		<div class="row">
			<div class="two columns alpha"><span class="label"><?php _xt('My Orders') ?>:</span></div>
		</div>
		<div class="row">
			<?php if(count($this->orders) > 0): ?>
				<?php foreach($this->orders as $order): ?>
				<div class="row">
					<div class="two columns alpha omega">
						<a href="<?php echo _xls_site_url("order-track/pg"); ?>?getuid=<?= $order->Linkid; ?>"><?= $order->IdStr; ?></a>
					</div>
					<div class="two columns">
						<?= $order->DatetimePosted; ?>
					</div>
					<div class="four columns omega">
						<?php _xt($order->Status); ?>
					</div>
				</div>
				<?php endforeach; ?>
				<?php else: ?>
					<?php _xt("You have not placed any orders with us yet"); ?>
			<?php endif; ?>
		</div>

	<?php if(_xls_get_conf('ENABLE_GIFT_REGISTRY')):   ?>
		<div class="row">
			<div class="two columns alpha"><span class="label"><a href="<?php echo _xls_site_url('gift-registry/pg'); ?>"><?php _xt('My Wish lists') ?></a>:</span></div>
		</div>
		<div class="row">
			<?php if(count($this->giftregistries) > 0): ?>
			<?php foreach($this->giftregistries as $registry): ?>

				<div class="two columns alpha omega">
					<a href="<?php echo _xls_site_url("gift-registry/pg"); ?>?registry_id=<?= $registry->Rowid; ?>"><?=  $registry->RegistryName  ?></a>
				</div>
				<?php endforeach; ?>
			<?php else: ?>
			<?php _xt("You have not created any wish list yet."); ?><br>
				<a href="<?php echo _xls_site_url("gift-registry/pg"); ?>"><?php _xt('Click here to create a wish list.') ?></a>
			<?php endif; ?>
		</div>
	<?php endif;  ?>



	<?php if(_xls_get_conf('ENABLE_SRO')):   ?>
	<div class="row">
		<div class="two columns alpha"><span class="label"><?php _xt('My Repairs') ?>:</span></div>
	</div>
	<div class="row">
		<?php if(count($this->repairs) > 0): ?>
		<?php foreach($this->repairs as $repair): ?>
			<div class="two columns alpha omega">
				<a href="<?= _xls_site_url("sro-track/pg") ?>?dosearch=true&emailphone=<?= $repair->CustomerEmailPhone ?>&orderid=<?= $repair->LsId ?>"><?=  $repair->LsId  ?></a>
			</div>
			<?php endforeach; ?>
		<?php else: ?>
			<?php _xt("You have not placed any repair orders with us."); ?><br>
		<?php endif; ?>
	</div>
	<?php endif;  ?>

</div>