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
 * Framework template My Account template (Logged in, My Account top link) 
 *
 * 
 *
 */


	global $customer;
	
?>



<br style="clear: both;"/>


	<div class="border rounded">
		<div class="border_header">
			<p class="left"><?php _xt('Welcome'); ?> <?= $this->customer->Firstname ?>
			<p class="right" style="margin: -2px 15px 0 0;"><a href="index.php?xlspg=customer_register"><img src="<?= templateNamed('css/images/btn_edit.png') ?>" onclick="document.location.href='index.php?xlspg=customer_register'" alt="<?php _xt('Edit') ?>"/></a></p>
		</div>
		<div class="padding">
			<p>Name : <?= $this->customer->Firstname . " " . $this->customer->Lastname ?></p>
			<p>Phone : <?= $this->customer->Mainphone ?></p>
			<p>Address : <?= $this->customer->Address11 ?>, <?= $this->customer->Address12 ?> <?= $this->customer->City1 ?>, <?= $this->customer->State1 ?> <?= $this->customer->Zip1 ?></p>
			
		</div>
	</div>


<br style="clear: both;"/>


	<div class="border rounded">
		<div class="border_header">
			<p class="left"><?php _xt("My Orders"); ?></p>
		</div>
		<div class="padding">
		<?php if(count($this->orders) > 0): ?>
			<table width='100%'>
			<?php foreach($this->orders as $order): ?>
				<tr>
				<td><a href="order-track/pg/?getuid=<?php _xt($order->Linkid); ?>"><?php _xt($order->IdStr);?></a></td>
				<!--
					<td><?php _xt($order->DatetimePosted); ?></td>
					<td><?php _xt($order->Status); ?></td>
				-->
				</tr>			
			<?php endforeach; ?>
			</table>
		<?php else: ?>
				<?php _xt("You have not placed any orders with us yet"); ?>
		<?php endif; ?>
		</div>
	</div>



<br style="clear: both;"/>


<?php if(_xls_get_conf('ENABLE_GIFT_REGISTRY')):   ?>
	<div class="border rounded">
		<div class="border_header">
			<p class="left"><?php _xt("My Wish lists"); ?></p>
		</div>
		
		<div class="padding">
		<?php if(count($this->giftregistries) > 0): ?>
			<?php foreach($this->giftregistries as $registry): ?>
					<a href="index.php?xlspg=gift_registry&registry_id=<?=  $registry->Rowid  ?>"><?=  $registry->RegistryName  ?></a><br/>
			<?php endforeach; ?>
		<?php else: ?>
				<?php _xt("You have not created any wish list yet."); ?><br/>
				<a href="index.php?xlspg=gift_registry"><?php _xt('Click here') ?></a> to create a wish list.
		<?php endif; ?>
		</div>
		

	</div>
<?php endif;  ?>



<?php if(_xls_get_conf('ENABLE_SRO')):   ?>
	<div class="border rounded">
		<div class="border_header">
			<p class="left"><?php _xt("My Repairs"); ?></p>
		</div>
		
		<div class="padding">
		<?php if(count($this->repairs) > 0): ?>
			<?php foreach($this->repairs as $repair): ?>
					<a href="index.php?xlspg=sro_track&dosearch=&zipcode=<?= $repair->Zipcode ?>&orderid=<?= $repair->LsId ?>"><?=  $repair->LsId  ?></a><br/>
			<?php endforeach; ?>
		<?php else: ?>
				<?php _xt("You have not placed any repair orders with us yet"); ?>
		<?php endif; ?>
		</div>
	</div>
<?php endif;  ?>
