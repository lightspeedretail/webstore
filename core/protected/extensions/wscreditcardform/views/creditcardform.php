<script>
	jQuery(function($) {
		$('[data-numeric]').payment('restrictNumeric');
		$('.creditcard-number').payment('formatCardNumber');
		$('.cc-exp').payment('formatCardExpiry');
		$('.cvv').payment('formatCardCVC');

		$(".creditcard-number").keyup(function() {
			var cardType = $.payment.cardType($('.creditcard-number').val());
			if(cardType){
				$('.card-logo img').removeClass('active');
				$('.card-logo .'+ cardType).addClass('active');
				$('.card-type').attr('value', cardType.toUpperCase());
			}
		});
	});

</script>

<div class="payment-methods">
	<?php
	$arr = $model->getPaymentModulesThatUseCard();
	$count = 0;

	foreach ($arr as $key => $label)
	{
		echo $form->radioButton(
			$model,
			'paymentProvider',
			array('uncheckValue' => null, 'checked' => $count > 0 ? '' : 'checked', 'id' => 'MultiCheckoutForm_paymentProvider_' . $key, 'value' => $key)
		);

		echo $form->labelEx(
			$model,
			'paymentProvider',
			array('class' => 'payment-method', 'label' => Yii::t('checkout', $label), 'for' => 'MultiCheckoutForm_paymentProvider_' . $key)
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
				<?php echo $form->labelEx(
				$model,
				'cardNumber',
				$htmlOptions = array('class' => 'placeheld'),
				array('label' => 'Credit Card Number')
			);
			echo $form->textField(
				$model,
				'cardNumber',
				$htmlOptions = array('placeholder' => Yii::t('checkout', "Credit Card Number"),
					'pattern' => "\d*", 'autocomplete' =>"creditcard-number",'class' => "creditcard-number",
					'required' => "required")
			);
			?>
			</li>
		</ol>

		<ol class="field-containers field-containers-small cart-details-secondary">
			<li class="field-container">
				<?php echo $form->labelEx(
				$model,
				'cardExpiry',
				$htmlOptions = array('class' => 'placeheld'),
				array('label' => 'Expiration')
			);
			echo $form->textField(
				$model,
				'cardExpiry',
				$htmlOptions = array('placeholder' => Yii::t('checkout', "MM / YY"),'size' => "6",'class' => "cc-exp", 'pattern' => "\d*",'autocomplete' =>"cc-exp", 'required' => "required")
			);
			?>
			</li>
			<li class="field-container">
				<?php echo $form->labelEx(
				$model,
				'cardCVV',
				$htmlOptions = array('class' => 'placeheld'),
				array('label' => 'CVV')
			);
			echo $form->textField(
				$model,
				'cardCVV',
				$htmlOptions = array('placeholder' => Yii::t('checkout', "CVV"), 'class' => "cvv", 'pattern' => "\d*",'autocomplete' => "off", 'size' => "4", 'required' => "required")
			);
			?>
			</li>
			<li class="card-logo">
				<img src="/images/creditcards/visa.png" class="visa">
				<img src="/images/creditcards/mastercard.png" class="mastercard">
				<img src="/images/creditcards/discover.png" class="discover">
				<img src="/images/creditcards/amex.png" class="amex">
				<img src="/images/creditcards/diners_club.png" class="dinersclub">
				<img src="/images/creditcards/maestro.png" class="maestro">
				<img src="/images/creditcards/electron.png" class="visaelectron">
				<img src="/images/creditcards/solo.png" class="solo">
				<img src="/images/creditcards/jcb.png" class="jcb">
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
