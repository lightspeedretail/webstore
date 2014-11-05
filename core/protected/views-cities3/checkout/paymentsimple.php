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
		<li class="completed"><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Shipping')?></li>
		<li class="current"><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Payment')?></li>
		<li class=""><span class="webstore-label"></span><?php echo Yii::t('checkout', 'Confirmation')?></li>
	</ol>
</nav>

<h1><?php echo Yii::t('checkout', 'Payment')?></h1>

<?php
$paypal = Modules::LoadByName('paypal');
$simModulesCC = $model->getSimPaymentModulesThatUseCard();
$count = count($simModulesCC);
$this->renderPartial('_paypalbuttonsim', array('paypal' => $paypal, 'count' => $count));
?>

<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<?php if (count($simModulesCC) > 0 || $paypal->active == true): ?>
<div class="creditcard">
	<div class="error-holder"><?= $error ?></div>
	<div class="payment-methods">
		<?php
		$checked = 0;
		foreach ($simModulesCC as $module)
		{
			echo $form->radioButton(
				$model,
				'paymentProvider',
				array(
					'uncheckValue' => null,
					'checked' => $checked > 0 ? '' : 'checked',
					'class' => $count > 1 ? '' : 'sim-cc-radio',
					'id' => 'MultiCheckoutForm_paymentProvider_' . $module['id'],
					'value' => $module['id'])
			);

			echo $form->labelEx(
				$model,
				'paymentProvider',
				array(
					'class' => $count > 1 ? 'payment-method' : 'payment-method sim-cc',
					'label' => Yii::t('checkout', 'Pay with ' . $module['label']),
					'for' => 'MultiCheckoutForm_paymentProvider_' . $module['id'])
			);

			$checked++;
		}
		?>
	</div>
	<p class="large"><?php echo Yii::t('checkout', "Review and confirm your order. You'll be forwarded to our secure payment partner to enter your credit cart details.")?></p>

<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
	<label class="shippingasbilling">
		<?php
		if (Yii::app()->shoppingcart->shipping->isStorePickup === false):
		echo $form->checkBox(
			$model,
			'billingSameAsShipping',
			$htmlOptions = array(
				'boolean',
				'trueValue' => 'on',
				'onclick' => '$(".address-form").fadeToggle();',
				'checked' => "checked",
				'disabled' => _xls_get_conf('SHIP_SAME_BILLSHIP') == 1 ? true : false,
			)
		);
		?>
		<span class="text" id="payment">
			<?php
			echo Yii::t('checkout', "Use my shipping address as my billing address")
			?>
			<br>
			<span class="address-abbr">
				<?php
				echo $model->strShippingAddress;
				?>
			</span>
		</span>
		<?php endif; ?>
	</label>



<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
	<?php $this->renderPartial('_billingaddress',array('model' => $model, 'form' => $form) ); ?>
</div>

<?php
endif;
$this->renderPartial(
	'_altpaymentmethods',
	array(
		'model' => $model,
		'form' => $form,
		'paypal' => $paypal,
		'simModulesCC' => $simModulesCC
	)
);
?>

<?php $this->endWidget(); ?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>
