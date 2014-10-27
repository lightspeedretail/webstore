<?php
if ($paypal !== null && $paypal->active)
{
	echo '<div class="outofbandpayment">';
	echo '<div class="buttons">';

	echo CHtml::htmlButton(
		Yii::t('checkout', 'Pay with PayPal'),
		array(
			'class' => 'paypal',
			'type' => 'submit',
			'name' => 'Paypal',
			'id' => 'Paypal',
			'value' => $paypal->id,
		)
	);

	if ($count > 0)
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