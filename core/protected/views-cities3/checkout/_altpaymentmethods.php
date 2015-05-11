<?php
$altMethods = $model->getAlternativePaymentMethods();
if (count($altMethods) > 0):
?>
	<div class="error-holder"><?= $error ?></div>
	<div class="alt-payment-methods payment-methods">
		<h4><?php echo Yii::t('checkout', 'Alternative Payment Methods'); ?></h4>
		<?php
		foreach ($altMethods as $id => $option)
		{
			echo $form->radioButton(
				$model,
				'paymentProvider',
				array('uncheckValue' => null, 'value' => $id, 'id' => 'MultiCheckoutForm_paymentProvider_'.$id)
			);

			if (array_key_exists($id, $paymentFormModules))
			{
				$this->renderPartial('_paymentform', array('form' => $paymentFormModules[$id], 'moduleId' => $id));
			}
			else
			{
				echo $form->labelEx(
					$model,
					'paymentProvider',
					array('class' => 'payment-method', 'label' => Yii::t('checkout', $option), 'for' => 'MultiCheckoutForm_paymentProvider_'.$id)
				);
			}
		}
		?>
	</div>
<?php
endif;

$totalSimMethods = count($simModulesCC) + count($altMethods);
$blnOnlyPaypalActive = ($totalSimMethods === 0 && $isPaypalValid == true);
if ($totalSimMethods > 0 || $blnOnlyPaypalActive === false):
?>
	<footer class="submit">
		<?=
			CHtml::submitButton(
				Yii::t(
					'forms',
					'Submit'
				),
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