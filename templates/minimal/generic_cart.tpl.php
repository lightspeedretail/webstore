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
 * template Cart display on CheckOut screen
 *
 *
 *
 */

?>

<div class="cart rounded">
	<div class="cart_header">
		<?php _xt('Description'); ?>
	</div>

	<div class="receipt_titles rounded-top">
		<p><?php _xt('Price'); ?></p>

		<p><?php _xt('Quantity'); ?></p>

		<p><?php _xt('Total'); ?></p>
	</div>

	<?php

	$this->dtrGenericCart->Render();


	?>


	<div class="cart_notes">
		<p><!--  --></p>

		<div class="receipt_subtotals rounded">

			<?php if (isset($this->misc_components['order_subtotal'])
			&& ($this->misc_components['order_subtotal'] instanceOf QControl)
		): ?>
			<?php _xt('Subtotal'); ?> <?php $this->misc_components['order_subtotal']->Render() ?> <br/>
			<?php endif; ?>


			<?php if (isset($this->misc_components['order_shipping_cost'])
			&& ($this->misc_components['order_shipping_cost'] instanceOf QControl)
		): ?>
			<?php if (_xls_get_conf('TAX_INCLUSIVE_PRICING', '') != '1'): ?>
				<?php _xt('Shipping'); ?> <?php $this->misc_components['order_shipping_cost']->Render() ?> <br/>
				<?php elseif (_xls_get_conf('SHIPPING_TAXABLE', '') == '1'): ?>
				<?php _xt('Shipping (tax included)'); ?> <?php $this->misc_components['order_shipping_cost']->Render(
				) ?> <br/>
				<?php else: ?>
				<?php _xt('Shipping'); ?> <?php $this->misc_components['order_shipping_cost']->Render() ?> <br/>
				<?php endif; ?>
			<?php endif; ?>




			<?php if (isset($this->misc_components['order_taxes'])): ?>
			<?php foreach ($this->misc_components['order_taxes'] as $tax): ?>
				<?php if ($tax->Visible): ?>
					<?php _xt($tax->Name); ?> <?php $tax->Render() ?> <br/>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	</div>



	<?php if (
	isset($this->misc_components['order_total']) && ($this->misc_components['order_total'] instanceOf QControl)
): ?>
	<div class="receipt_total rounded">
		<?php _xt('Total'); ?> <?php $this->misc_components['order_total']->Render() ?>
	</div>
	<?php endif; ?>

</div>
	
