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

<div id="genericcart">
	<div class="row">
		<div class="five columns alpha"><span class="cart_header"><?php _xt('Description'); ?></span></div>
		<div class="one column rightitem"><span class="cart_header"><?php _xt('Price'); ?></span></div>
		<div class="one column">&nbsp;</div>
		<div class="one column centeritem"><span class="cart_header"><?php _xt('Qty'); ?></span></div>
		<div class="one column">&nbsp;</div>
		<div class="two columns omega rightitem"><span class="cart_header"><?php _xt('Total'); ?></span></div>

		<?php $this->dtrGenericCart->Render(); ?>

	</div>

	<div class="row">

		<?php if(isset($this->misc_components['order_subtotal'])  &&  ($this->misc_components['order_subtotal'] instanceOf QControl) ): ?>

			<div class="two columns offset-by-seven cart_price"><span class="cart_label"><?php _xt('Subtotal'); ?></span></div>
			<div class="two columns omega cart_price"><?php $this->misc_components['order_subtotal']->Render() ?></div>

		<?php endif; ?>

		<?php if(isset($this->misc_components['order_taxes'])  ): ?>

			<?php foreach($this->misc_components['order_taxes'] as $tax): ?>
				<?php if($tax->Visible): ?>
						<div class="two columns offset-by-seven cart_price"><span class="cart_label"><?php _xt($tax->Name); ?></span></div>
						<div class="two columns omega cart_price"><?php $tax->Render() ?></div>
					<?php endif; ?>
				<?php endforeach; ?>

		<?php endif; ?>

		<?php if(isset($this->misc_components['order_shipping_cost'])  &&  ($this->misc_components['order_shipping_cost'] instanceOf QControl) ): ?>

			<div class="two columns offset-by-seven cart_price"><span class="cart_label"><?php _xt("Shipping"); ?></span></div>
			<div class="two columns omega cart_price"><?php $this->misc_components['order_shipping_cost']->Render() ?></div>

		<?php endif; ?>

		<?php if(isset($this->misc_components['order_total'])  &&  ($this->misc_components['order_total'] instanceOf QControl) ): ?>

			<div class="two columns offset-by-seven cart_price"><?php _xt("Total"); ?></div>
			<div class="two columns omega cart_price"><?php $this->misc_components['order_total']->Render() ?></div>

		<?php endif; ?>
	</div>


</div>

