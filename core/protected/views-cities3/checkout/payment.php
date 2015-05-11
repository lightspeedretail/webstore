<?php
CHtml::$afterRequiredLabel = '';

$form = $this->beginWidget(
	'CActiveForm',
	array(
		'htmlOptions' => array(
			'class' => "section-content",
			'id' => "payment",
			'novalidate' => '1',
			'autocomplete' => 'on'
		)
	)
);
?>

<nav class="steps">
	<ol>
		<li class="completed"><span class="webstore-label"></span><?= Yii::t('checkout', 'Shipping')?></li>
		<li class="current"><span class="webstore-label"></span><?= Yii::t('checkout', 'Payment')?></li>
		<li class=""><span class="webstore-label"></span><?= Yii::t('checkout', 'Confirmation')?></li>
	</ol>
</nav>

<h1><?= Yii::t('checkout', 'Payment')?></h1>

<?php
$this->renderPartial('_paypalpayment', array('isPaypalValid' => $model->isPaymentMethodValid('paypal')));
?>

<div class="creditcard">
<div class="error-holder"><?= $error ?></div>
<?php
	$this->widget(
		'ext.wscreditcardform.wscreditcardform',
		array(
			'model' => $model,
			'form' => $form
		)
	);
	$this->renderPartial(
		'_shippingasbillingguest',
		array(
			'model' => $model,
			'form' => $form
		)
	);
	$this->renderPartial(
		'_billingaddress',
		array(
			'model' => $model,
			'form' => $form
		)
	);
?>

</div>

<?php
$altMethods = $model->getAlternativePaymentMethods();
if (count($altMethods) > 0):
?>
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
				$this->renderPartial(
					'_paymentform',
					array(
						'form' => $paymentFormModules[$id],
						'moduleId' => $id,
					)
				);
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
<?php endif; ?>

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
				'value' => Yii::t(
					'checkout',
					'Review and Confirm Order'
				)
			)
		);
	?>
</footer>

<?php $this->endWidget(); ?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>

