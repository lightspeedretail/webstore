<?php
$simModules = $model->simPaymentModulesNoCard;
if (count($simModules) > 0):
	?>
	<div class="alt-payment-methods payment-methods">
		<h4><?php echo Yii::t('checkout', 'Alternative Payment Methods'); ?></h4>
		<?php
		foreach ($simModules as $id => $option)
		{
			echo $form->radioButton(
				$model,
				'paymentProvider',
				array('uncheckValue' => null, 'value' => $id, 'id' => 'MultiCheckoutForm_paymentProvider_'.$id)
			);
			echo $form->labelEx(
				$model,
				'paymentProvider',
				array('class' => 'payment-method', 'label' => Yii::t('checkout', $option), 'for' => 'MultiCheckoutForm_paymentProvider_'.$id)
			);
		}
		?>
	</div>
<?php
endif;

$totalSimMethods = count($simModulesCC) + count($simModules);
$blnOnlyPaypalActive = ($totalSimMethods === 1 && $paypal->active == false);
if ($totalSimMethods > 1 || $blnOnlyPaypalActive === false):
?>
	<footer class="submit">
		<?php
		echo
		CHtml::submitButton(
			'Submit',
			array(
				'type' => 'submit',
				'class' => 'button',
				'name' => 'Payment',
				'id' => 'Payment',
				'value' => Yii::t('checkout', "Review and Confirm Order")
			)
		);
		?>
	</footer>
<?php else: ?>
	<div class="alt-payment-methods borderclose"></div>
<?php endif; ?>