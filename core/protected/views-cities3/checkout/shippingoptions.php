<?php
	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'htmlOptions' => array(
				'class' => "section-content",
				'id' => "shipping",
				'novalidate' => '1'
			)
		)
	);
?>
<nav class="steps">
	<ol>
		<li class="current"><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Shipping')?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Payment')?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Confirmation')?></li>
	</ol>
</nav>
<h1><?php echo Yii::t('checkout','Shipping'); ?></h1>
<p class="introduction">
	<?php
		echo Yii::t('checkout', "Confirm your shipping address and choose your preferred shipping method.");
	?>
</p>
<div class="address-block address-block-alter">
	<p class="webstore-label">
		<?php
		echo $model->shippingFirstName . ' ' . $model->shippingLastName . '<br>' . $model->getHtmlShippingAddress();
		?>
	</p>
	<button type="button" class="small" onclick="window.location='<?= Yii::app()->user->IsGuest ? Yii::app()->createUrl('/checkout/shipping/') : Yii::app()->createUrl('/checkout/shippingaddress')?>'">

		<?php
		echo CHtml::link(
			Yii::t('cart', 'Change Address'),
			Yii::app()->user->IsGuest ? Yii::app()->createUrl('checkout/shipping/') : Yii::app()->createUrl('/checkout/shippingaddress')
		);
		?>
	</button>
</div>
<h3><?php echo Yii::t('checkout','Shipping Options'); ?></h3>
<div class="error-holder">
	<?php echo $error; ?>
</div>
<?php
	// The values of these hidden fields are set by the JavaScript when a
	// shipping option is selected.
	echo $form->hiddenField(
		$model,
		'shippingProvider',
		array('class' => 'shipping-provider-id')
	);

	echo $form->hiddenField(
		$model,
		'shippingPriority',
		array('class' => 'shipping-priority-label')
	);
?>

<table class="shipping-options">
	<thead>
		<tr>
			<th>
				<?php echo Yii::t('checkout', 'Shipping Method'); ?>
			</th>
		</tr>
	</thead>
	<?php
		$formattedCartScenarios = Checkout::formatCartScenarios($arrCartScenario);

		$orderSummaryOptions = array(
			'class' => '.summary',
			'cartScenarios' => $formattedCartScenarios,
			'setShippingOptionsEndpoint' => Yii::app()->createUrl('cart/chooseshippingoption')
		);

		Yii::app()->clientScript->registerScript(
			'instantiate OrderSummary',
			'$(document).ready(function () {
				orderSummary = new OrderSummary(' . CJSON::encode($orderSummaryOptions) . ');
				if (typeof promoCodeInput !== "undefined") {
					promoCodeInput.orderSummary = orderSummary;
				}
			});',
			CClientScript::POS_HEAD
		);

		foreach ($formattedCartScenarios as $scenarioIdx => $cartScenario): ?>
		<tr>
			<td>
				<?php
					$id = CHtml::activeId('MultiCheckoutForm', 'shippingProvider_' . $scenarioIdx);
					$isChecked = $cartScenario['providerId'] == $model->shippingProvider &&
						$cartScenario['priorityLabel'] == $model->shippingPriority;

					echo CHtml::radioButton(
						'shippingOption',
						$isChecked,
						array(
							'id' => $id,
							'data-provider-id' => $cartScenario['providerId'],
							'data-priority-label' => $cartScenario['priorityLabel'],
							'onclick' => 'orderSummary.optionSelected(this);'
						)
					);

					echo CHtml::label(
						$cartScenario['shippingOptionPriceLabel'],
						$id
					);
				?>
			</tr>
		</td>
	<?php endforeach; ?>
</table>
<footer class="submit submit-small">
	<?=
		CHtml::submitButton(
			Yii::t(
				'forms',
				'Submit'
			),
			array(
				'type' => 'submit',
				'class' => 'button',
				'value' => Yii::t(
					'checkout',
					'Proceed to Payment'
				)
			)
		);
	?>
</footer>

<?php $this->endWidget(); ?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php
		$partialPromoCodeInput = $this->renderPartial(
			'ext.wscartmodal.views._promocodeinput',
			// The shippingoptions page does not send a request to update the
			// cart totals, so we need to ensure that's done as part of
			// applying the promo code.
			//
			// Since we aren't currently redrawing the shipping options when a
			// promo code is applied, the safest thing to do here is to reload
			// the page.
			array(
				'modelId' => 'Checkout',
				'updateCartTotals' => true,
				'reloadPageOnSuccess' => true
			),
			true
		);

		$this->renderPartial(
			'_ordersummary',
			array(
				'partialPromoCodeInput' => $partialPromoCodeInput,
				'showMessage' => $model->hasTaxModeChanged
			)
		);
	?>
</aside>
