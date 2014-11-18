<?php
	$cartScenario = Shipping::getSelectedCartScenarioFromSessionOrShoppingCart();

	// The calling code has the option of providing a differently configured
	// _promocodeinput. Here is a sensible default.
	if (isset($partialPromoCodeInput) === false) {
		$partialPromoCodeInput = $this->renderPartial(
			'ext.wscartmodal.views._promocodeinput',
			array(
				'modelId' => 'Checkout',
				'updateCartTotals' => true,
				'reloadPageOnSuccess' => false
			),
			true
		);
	}
?>

<div class="summary">
	<h2><?php echo Yii::t('checkout', "Order Summary") ?></h2>
	<table>
		<tbody>
		<tr>
			<th><?php echo Yii::t('checkout', "Merchandise") ?></th>
			<td id="CartSubtotal" class="cart-subtotal"><?= $cartScenario['formattedCartSubtotal'] ?></td>
		</tr>
		<tr>
			<th><?php echo Yii::t('checkout', "Estimated Shipping") ?></th>
			<td class="shipping-estimate"><?= $cartScenario['formattedShippingPrice'] ?></td>
		</tr>
		<tr id="PromoCodeLine" class="<?php echo Yii::app()->shoppingcart->promoCode ? 'webstore-promo-line' : 'webstore-promo-line hide-me';?>" >
			<th>
				<?= Yii::t('checkout', "Promo: ")?>
				<span id="PromoCodeName" class="promo-code-name">
					<?= Yii::app()->shoppingcart->promoCode ?>
				</span>
			</th>
			<td>
				<span id="PromoCodeStr" class="promo-code-str">
					<?= Yii::app()->shoppingcart->totalDiscountFormatted ?>
				</span>
			</td>
		</tr>

		<?php
			$this->renderPartial(
				'_checkout-taxes',
				array(
					'cart' => Yii::app()->shoppingcart,
					'selectedCartScenario' => $cartScenario,
					'confirmation' => false
				)
			);
		?>

		</tbody>
		<tfoot>
		<tr>
			<th><?= Yii::t('cart', "Total")?></th>
			<td id="totalCart" class="total-estimate"><?= $cartScenario['formattedCartTotal'] ?></td>
		</tr>
		</tfoot>
	</table>
	<div class="promo">
		<?php
			// Promo code errors.
			echo CHtml::tag(
				'div',
				array(
					'id' => CHtml::activeId('Checkout', 'promoCode') . '_em_',
					'class' => 'form-error',
					'style' => 'display: none'
				),
				'<p>&nbsp;</p>'
			);
		?>

		<div style="position:relative;">
			<?= $partialPromoCodeInput ?>
		</div>

		<div class="form-error" style="display: none;">
			<p><?= Yii::t('checkout', "Something bad happened.")?></p>
		</div>
	</div>
</div>
<div>
	<div class="contact">
		<h3 class="registered-name"><?php echo Yii::app()->params['STORE_NAME']; ?></h3>
		<p>
			<?php echo Yii::app()->params['STORE_PHONE']; ?><br>
			<a href="mailto:<?= Yii::app()->params['EMAIL_FROM'];?>"><?= Yii::app()->params['EMAIL_FROM']; ?></a>
		</p>
	</div>
	<?php if(Yii::app()->user->isGuest): ?>
		<div class="account">
			<h3><?= Yii::t('checkout', "Save time")?></h3>
			<p>
				<?= Yii::t('checkout', "Shopped with us before?")?><br>
				<?=
					CHtml::link(
						Yii::t(
							'checkout',
							'Login to your Account'
						),
						Yii::app()->createUrl('/checkout/index', array('showLogin' => 'true'))
					);
				?>
			</p>
			<p class="hint"><?= Yii::t('checkout', "Don't have an account? You can create one after checkout.")?></p>
		</div>
	<?php endif; ?>
</div>

<?php
Yii::app()->clientScript->registerScript(
	'instantiate checkout',
	sprintf(
		'$(document).ready(function () {
			checkout = new Checkout(%s);
		});',
		Checkout::getCheckoutJSOptions()
	),
	CClientScript::POS_HEAD
);
