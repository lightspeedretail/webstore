<div class="order-details">
	<article class="column shipping">
		<h4><?php echo Yii::t('checkout', $cart->shipping->isStorePickup ? 'Shipping Details' : 'Shipping Address'); ?></h4>
		<div class="address-block">
			<p class="webstore-label confirmation">
				<span>
					<?php
					if (!$cart->shipping->isStorePickup)
						echo $cart->shipaddress->first_name . ' ' . $cart->shipaddress->last_name . '<br>' . _xls_html_shippingaddress($cart);
					else
					{
						$module = Modules::LoadByName('storepickup');
						$config = unserialize($module->configuration);
						echo $config['label'].'<br>';
						echo _xls_html_storepickupdetails($cart) . '<br>';
					}
					?>
				</span>
				<span class="controls">
					<?php
						if ($isReceipt === false)
						{
							echo CHtml::link(
								Yii::t('checkout', 'Change'),
								Yii::app()->createUrl('/checkout/shipping'),
								array('class' => 'hasborder')
							);
						}
					?>
				</span>
			</p>
		</div>
	</article>
	<article class="column payment">
		<h4><?php echo Yii::t('checkout', 'Payment Details'); ?></h4>
		<div class="billing-block">
			<p>
				<?php
					$this->renderPartial('_paymentdetails', array('cart' => $cart, 'isReceipt' => $isReceipt))
				?>
			</p>
		</div>
	</article>
</div>
