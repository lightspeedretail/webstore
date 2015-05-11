<?php
CHtml::$afterRequiredLabel = '';

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
$isPaypalValid = $model->isPaymentMethodValid('paypal');
$this->renderPartial('_paypalpayment', array('isPaypalValid' => $isPaypalValid));

$simModules = $model->getSimPaymentMethods();
$count = count($simModules);
?>
<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<?php if (count($simModules) > 0 || $isPaypalValid === true): ?>
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

	<?php $this->renderPartial('_shippingasbillingexisting', array('checkbox' => $checkbox)); ?>

	<div class="address-form invisible">
		<h4><?php echo Yii::t('checkout', 'Billing Address'); ?></h4>
		<ol class="address-blocks">
			<?php if(count($model->objAddresses) > 0): ?>
				<?php foreach ($model->objAddresses as $objAddress): ?>
					<li class="address-block address-block-pickable">
						<p class="webstore-label">
							<?php
							echo $objAddress->formattedblockcountry;
							?>
							<span class="controls">
							<a href="/checkout/editaddress?id=<?= $objAddress->id ?>&type=billing"><?php echo Yii::t('checkout','Edit Address'); ?></a>
								<?php echo Yii::t('checkout', 'or'); ?>
								<?php
								echo CHtml::ajaxLink(
									Yii::t('checkout', 'Remove'),
									Yii::app()->createUrl('myaccount/removeaddress'),
									array(
										'type' => 'POST',
										'data' => array(
											'CustomerAddressId' => $objAddress->id,
											'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken
										),
										'success' => 'function(data) {
										var addressBlock = $(this).parents(".address-block")[0];
										$(addressBlock).remove();
									}.bind(this)'
									),
									array(
										'class' => 'delete'
									)
								);
								?>
							</span>
						</p>
						<div class="buttons">
							<?php
							echo CHtml::htmlButton(
								Yii::t('checkout', $objAddress->id == $model->intShippingAddress ? 'Use shipping address' : 'Use this address'),
								array(
									'type' => 'submit',
									'class' => $objAddress->id == $model->intBillingAddress ? 'small default' : 'small',
									'name' => 'BillingAddress',
									'id' => 'BillingAddress',
									'onclick' => '$("form").removeClass("error").end().find(".required").remove().end().find(".form-error").remove().end()',
									'value' => $objAddress->id
								)
							);
							?>
						</div>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
			<li class="add">
				<?php echo CHtml::link(Yii::t('checkout', 'Add New Address'), '/checkout/newaddress?type=billing', array('class' => 'small button')); ?>
			</li>
		</ol>
	</div>
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
