<?php
CHtml::$afterRequiredLabel = '';

echo CHtml::script(
	'var cityPlaceholder = ' . CJSON::encode(Yii::t('checkout', "City")) . ';'
);

$form = $this->beginWidget(
	'CActiveForm',
	array(
		'enableClientValidation' => false,
		'htmlOptions' => array('class' => "section-content",'id' => "payment", 'novalidate' => '1',
		)
	)
);
?>
<nav class="steps">
	<ol>
		<li class="completed"><span class="webstore-label"></span><?= Yii::t('checkout', 'Shipping') ?></li>
		<li class="current"><span class="webstore-label"></span><?= Yii::t('checkout', 'Payment')?></li>
		<li class=""><span class="webstore-label"></span><?= Yii::t('checkout', 'Confirmation')?></li>
	</ol>
</nav>

<h1><?= Yii::t('checkout', 'Payment')?></h1>

<?php
$isPaypalValid = $model->isPaymentMethodValid('paypal');
$this->renderPartial('_paypalpayment', array('isPaypalValid' => $isPaypalValid));

$simModules = $model->getSimPaymentMethods();
$count = count($simModules);
?>

<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<?php if (count($simModules) > 0 || $isPaypalValid == true): ?>
<div class="creditcard">
	<div class="error-holder"><?= $error ?></div>
	<div class="payment-methods">
		<?php
		$checked = 0;
		foreach ($simModules as $id => $module)
		{
			echo $form->radioButton(
				$model,
				'paymentProvider',
				array(
					'uncheckValue' => null,
					'checked' => $checked > 0 ? '' : 'checked',
					'class' => $count > 1 ? '' : 'sim-cc-radio',
					'id' => 'MultiCheckoutForm_paymentProvider_' . $id,
					'value' => $id)
			);

			echo $form->labelEx(
				$model,
				'paymentProvider',
				array(
					'class' => $count > 1 ? 'payment-method' : 'payment-method sim-cc',
					'label' => Yii::t('checkout', 'Pay with ' . $module),
					'for' => 'MultiCheckoutForm_paymentProvider_' . $id)
			);

			$checked++;
		}
		?>
	</div>
	<p class="large"><?php echo Yii::t('checkout', "Review and confirm your order. You'll be forwarded to our secure payment partner to enter your credit cart details.")?></p>

	<?php $this->renderPartial('_shippingasbillingguest', array('model' => $model, 'form' => $form)); ?>

	<?php $this->renderPartial('_billingaddress',array('model' => $model, 'form' => $form) ); ?>
</div>

<?php
endif;
$this->renderPartial(
	'_altpaymentmethods',
	array(
		'model' => $model,
		'form' => $form,
		'isPaypalValid' => $isPaypalValid,
		'simModulesCC' => $simModules,
		'paymentFormModules' => $paymentFormModules,
		'error' => $error
	)
);
?>

<?php $this->endWidget(); ?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>
