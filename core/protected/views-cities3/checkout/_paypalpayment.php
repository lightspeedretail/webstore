<?php

if ($isPaypalValid === true)
{
	$paypal = Modules::LoadByName('paypal');
	echo '<div class="outofbandpayment">';
	echo '<div class="buttons">';

	echo CHtml::htmlButton(
		Yii::t('checkout', 'Pay with ')."<span></span>",
		array(
			'class' => 'paypal',
			'type' => 'submit',
			'name' => 'Paypal',
			'id' => 'Paypal',
			'value' => $paypal->id,
		)
	);

	if ($paypal->isOnlyActivePaymentMethod() === false)
	{
		echo CHtml::tag(
			'div',
			array('class' => 'or-block'),
			''
		);
	}

	echo '</div>';
	echo '</div>';
}
