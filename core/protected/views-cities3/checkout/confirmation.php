<?php
	$modelId = 'Confirmation';
?>

<div class="section-content" id="confirm">
	<nav class="steps">
		<ol>
			<li class="completed"><span class="webstore-label"></span><?= Yii::t('checkout', "Shipping") ?></li>
			<li class="completed"><span class="webstore-label"></span><?= Yii::t('checkout', "Payment") ?></li>
			<li class="current"><span class="webstore-label"></span><?= Yii::t('checkout', "Confirmation") ?></li>
		</ol>
	</nav>
	<h1><?= Yii::t('checkout', 'Confirm Your Order')?></h1>
	<?php
		$form = $this->beginWidget(
			'CActiveForm',
			array('htmlOptions' => array('novalidate' => '1'))
		);
	?>
	<div class="error-holder"><?= $error ?></div>
	<div class="final-confirmation">
		<?php
			if ($cart->payment->payment_module === 'paypal')
			{
				echo CHtml::htmlButton(
					Yii::t('checkout', 'Pay with') . '<span></span>',
					array(
						'class' => 'button paypal',
						'id' => 'place-order',
						'type' => 'submit',
						'name' => 'Confirmation'
					)
				);
			}
			else
			{
				echo CHtml::htmlButton(
					Yii::t('checkout', 'Place Order'),
					array(
						'class' => 'button',
						'id' => 'place-order',
						'type' => 'submit',
						'name' => 'Confirmation'
					)
				);
			}
		?>
		<label class="checkbox">
			<?php
				echo $form->checkBox(
					$model,
					'receiveNewsletter',
					$htmlOptions = array('checked' => Yii::app()->params['DISABLE_ALLOW_NEWSLETTER'] ? '' : 'checked')
				);
				echo '<p>'.Yii::t('checkout', "I'd like to receive special offers and product information by email").'</p>';
			?>
		</label>
		<div class="comments">
			<a href="#" class="hasborder" onclick="$('.comments a').hide(); $('.comments textarea').fadeToggle().focus(); return false;">
				<?= Yii::t('checkout', 'Add a note or special request'); ?>
			</a>
			<?php
				echo $form->textArea(
					$model,
					'orderNotes',
					array('placeholder' => Yii::t('checkout', 'Enter your note or special request'))
				);
			?>
		</div>
		<p class="terms">
			<?php
				echo Yii::t('checkout', 'By clicking "Place Order" you agree to our') . ' ';
				echo
					CHtml::link(
						Yii::t('checkout', 'Terms and Conditions'),
						array("/terms-and-conditions"),
						array('target' => '_blank', 'class' => 'hasborder' )
					);
				echo
					$form->checkBox(
						$model,
						'acceptTerms',
						array('checked' => 'checked', 'style' => 'display: none')
					);
			?>
		</p>
	</div>
	<?php
		echo $form->hiddenField($model, 'billingSameAsShipping');
		echo $form->hiddenField($model, 'intBillingAddress');
		echo $form->hiddenField($model, 'billingAddress1');
		echo $form->hiddenField($model, 'billingAddress2');
		echo $form->hiddenField($model, 'billingCity');
		echo $form->hiddenField($model, 'billingState');
		echo $form->hiddenField($model, 'billingPostal');
		echo $form->hiddenField($model, 'billingCountry');
		echo $form->hiddenField($model, 'paymentProvider');
		echo $form->hiddenField($model, 'cardNameOnCard');
		echo $form->hiddenField($model, 'cardNumber');
		echo $form->hiddenField($model, 'cardCVV');
		echo $form->hiddenField($model, 'cardExpiryMonth');
		echo $form->hiddenField($model, 'cardExpiryYear');
		echo $form->hiddenField($model, 'cardType');

		$this->endWidget();
	?>

	<?php $this->renderPartial('_orderdetails', array('cart' => $cart, 'isReceipt' => false)); ?>
	<?php $this->renderPartial('_finalizecart', array('modelId' => $modelId,'cart' => $cart, 'isReceipt' => false)); ?>

</div>
<?php $this->publishJS('confirmationShippingEstimator'); ?>
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
?>
<script>
	// TODO: Move these into a strings object or pass in as an option.
	strCalculateButton = <?= CJSON::encode(Yii::t('shipping', 'Calculate')) ?>;
	calculatingLabel = <?= CJSON::encode(Yii::t('cart', 'Calculating...')) ?>;

	$(document).ready(function () {
		confirmationShippingEstimator = new ConfirmationShippingEstimator(<?= CJSON::encode($shippingEstimatorOptions) ?>);

		<?php
			// TODO: It isn't ideal to bind the promoCodeInput - ideally
			// confirmation.php wouldn't need to know about
			// promoCodeInput. But we aren't presently managing the
			// application in such a way that there's an easy alternative.
		?>

		if (typeof promoCodeInput !== 'undefined') {
			promoCodeInput.wsShippingEstimator = confirmationShippingEstimator;
		}

		<?php
		if (isset($recalculateShippingOnLoad) && $recalculateShippingOnLoad === true):
		?>
		confirmationShippingEstimator.updateShippingEstimates();
		<?php endif; ?>
	});
</script>
<?php
	Yii::app()->clientScript->registerScript(
		'instantiate wsEditCartModal',
		sprintf(
			'$(document).ready(function () {
				wsEditCartModal = new WsEditCartModal(%s);
				wsEditCartModal.checkout = checkout;
				wsEditCartModal.wsShippingEstimator = confirmationShippingEstimator;
			});',
			CJSON::encode(
				array(
					'updateCartItemEndpoint' => Yii::app()->createUrl('cart/updatecartitem'),
					'csrfToken' => Yii::app()->request->csrfToken,
					'cartId' => CHtml::activeId($modelId, 'promoCode'),
					'invalidQtyMessage' => Yii::t(
						'checkout',
						'<strong>Only {qty} are available at this time.</strong><br> If you’d like ' .
						'to order more please return at a later time or contact us.'
					)
				)
			)
		),
		CClientScript::POS_END
	);
