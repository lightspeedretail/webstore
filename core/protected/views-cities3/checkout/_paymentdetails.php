<?php
// Receipt / Thank You page
if ($isReceipt === true)
{
	// Advanced Integration Method
	if (isset($cart->payment->payment_card) === true)
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/creditcards/' . $cart->payment->payment_card . '.png',
			'card type',
			array('class' => 'card-tiny')
		);
		echo Yii::t('checkout', 'Card ending in ') . $cart->payment->card_digits . '<br>';
		echo _xls_html_billingaddress($cart);
	}

	// Simple Integration Credit Card Method
	elseif (Yii::app()->getComponent($cart->payment->payment_module)->uses_credit_card && $cart->payment->payment_module !== 'paypal')
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-only')
		);
		echo '<strong>' . $cart->payment->payment_name . '</strong><br>';
		echo _xls_html_billingaddress($cart);
	}

	// Paypal
	elseif ($cart->payment->payment_module === 'paypal')
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-tiny')
		);
		echo '<strong>' . $cart->payment->payment_name . '</strong>';
	}

	// Methods with subforms, ex. Purchase Order
	elseif ($cart->payment->hasSubForm() === true)
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-tiny')
		);
		echo '<strong>' . $cart->payment->payment_name . '</strong><br>';
		echo '<span class="payment-note">' . Yii::t('checkout', $cart->payment->payment_data) . '</span>';
	}

	// All other SIM methods
	else
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-only')
		);
		echo '<strong>' . $cart->payment->payment_name . '</strong>';
		echo '<span class="payment-note">' . Yii::t('checkout', $cart->payment->instructions) . '</span>';
	}
}

// Confirmation page
else
{
	// Advanced Integration Method
	if (isset($cart->payment->payment_card) === true)
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/creditcards/' . $cart->payment->payment_card . '.png',
			'card type',
			array('class' => 'card-tiny')
		);
		echo Yii::t('checkout', 'Card ending in ') . $cart->payment->card_digits . '<br>';
		echo _xls_html_billingaddress($cart);
	}

	// Simple Integration Credit Card Method
	elseif (Yii::app()->getComponent($cart->payment->payment_module)->uses_credit_card && $cart->payment->payment_module !== 'paypal')
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-only')
		);
		echo '<span class="payment-note">'. Yii::t('checkout', $cart->payment->instructions) . '</span>';
		echo _xls_html_billingaddress($cart);
	}

	// Paypal
	elseif ($cart->payment->payment_module === 'paypal')
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-tiny')
		);
		echo '<span class="payment-note">' . Yii::t('checkout', $cart->payment->instructions) . '</span><br>';
		echo _xls_html_billingaddress($cart);
	}

	// Methods with subforms, ex. Purchase Order
	elseif ($cart->payment->hasSubForm() === true)
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-tiny')
		);
		echo '<strong>' . $cart->payment->payment_name . '</strong><br>';
		echo '<span class="payment-note">' . Yii::t('checkout', $cart->payment->payment_data) . '</span>';
	}

	// All other SIM methods
	else
	{
		echo CHtml::image(
			Yii::app()->params['umber_assets'] . '/images/altpayments/' . $cart->payment->payment_module . '.png',
			'card type',
			array('class' => 'card-only')
		);
		echo '<strong>' . $cart->payment->payment_name . '</strong>';
		echo '<span class="payment-note">' . Yii::t('checkout', $cart->payment->instructions) . '</span>';
	}
}

if ($isReceipt === false)
{
	echo '<span class="controls">' .
		CHtml::link(
			Yii::t(
				'checkout',
				'Change'
			),
			Yii::app()->createUrl('/checkout/final'),
			array('class' => 'hasborder')
		) .
		'</span>';
}

