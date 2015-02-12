<script>
	jQuery(function($) {
		new CreditCard({
			enabledCardTypes: <?= CJSON::encode($arrEnabledCreditCardLabel); ?>,
			cardTypeNotSupported: <?= CJSON::encode($strCardTypeNotSupported); ?>
		});
	});
</script>

<div class="payment-methods">
	<?php
	$arr = $model->getAimPaymentMethods();
	$count = 0;

	foreach ($arr as $id => $label)
	{
		echo $form->radioButton(
			$model,
			'paymentProvider',
			array('uncheckValue' => null, 'checked' => $count > 0 ? '' : 'checked', 'id' => 'MultiCheckoutForm_paymentProvider_' . $id, 'value' => $id)
		);

		echo $form->labelEx(
			$model,
			'paymentProvider',
			array('class' => 'payment-method', 'label' => Yii::t('checkout', $label), 'for' => 'MultiCheckoutForm_paymentProvider_' . $id)
		);

		$count++;
	}
	?>

</div>

<div style="display: none;">
	<?php
	echo $form->textField(
		$model,
		'cardType',
		array(
			'class' => 'card-type'
		)
	);
	?>
</div>
	<div class="card-details">
		<ol class="field-containers field-container-gap">
			<li class="field-container">
			<?php
				echo $form->labelEx(
					$model,
					'cardNumber',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'Credit Card Number')
				);

				echo $form->textField(
					$model,
					'cardNumber',
					$htmlOptions = array(
						'placeholder' => Yii::t('checkout', 'Credit Card Number'),
						'pattern' => '\d*',
						'autocomplete' => 'creditcard-number',
						'class' => 'creditcard-number',
						'required' => 'required'
					)
				);
			?>
			</li>
		</ol>

		<ol class="field-containers field-containers-small cart-details-secondary">
			<li class="field-container">
			<?php
				echo $form->labelEx(
					$model,
					'cardExpiry',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'Expiration')
				);

				echo $form->textField(
					$model,
					'cardExpiry',
					$htmlOptions = array(
						'placeholder' => Yii::t('checkout', "MM / YY"),
						'size' => "6",
						'class' => "cc-exp",
						'pattern' => "\d*",
						'autocomplete' => "cc-exp",
						'required' => "required"
					)
				);
			?>
			</li>
			<li class="field-container">
			<?php
				echo $form->labelEx(
					$model,
					'cardCVV',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'CVV')
				);

				echo $form->textField(
					$model,
					'cardCVV',
					$htmlOptions = array(
						'placeholder' => Yii::t('checkout', "CVV"),
						'class' => "cvv",
						'pattern' => "\d*",
						'autocomplete' => "off",
						'size' => "4",
						'required' => "required"
					)
				);
			?>
			</li>
			<li class="card-logo">
				<div class="sprite visa"></div>
				<div class="sprite mastercard"></div>
				<div class="sprite discover"></div>
				<div class="sprite amex"></div>
				<div class="sprite dinersclub"></div>
				<div class="sprite maestro"></div>
				<div class="sprite visaelectron"></div>
				<div class="sprite jcb"></div>
			</li>
		</ol>
		<p class="cardholder">
			<?php echo $model->cardNameOnCard; ?>
			<a onclick="showCardNameInputField()" href="#"><?php echo Yii::t('checkout', 'Change'); ?></a>
		</p>
		<ol class="field-containers cardholder-field" style="display: none;">
			<li class="field-container">
			<?php
				echo $form->labelEx(
					$model,
					'cardNameOnCard',
					array('class' => 'placeheld', 'placeholder' => 'CardHolder Name')
				);
				echo $form->textField(
					$model,
					'cardNameOnCard',
					array()
				);
			?>
			</li>
		</ol>
	</div>

<!--TODO: include solo-->
<!--else if (/^6767/.test(accountNumber)) {-->
<!--return "solo";-->
<!--}-->
