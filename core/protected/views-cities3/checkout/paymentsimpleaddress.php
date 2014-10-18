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

<div class="outofbandpayment">
	<div class="buttons">
		<?php
		$paypal = Modules::LoadByName('paypal');
		if ($paypal->active)
		{
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

			echo CHtml::tag(
				'div',
				array('class' => 'or-block'),
				''
			);
		}
		?>
	</div>
</div>
<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->

<div class="creditcard">
	<div class="error-holder"><?= $error ?></div>
	<div class="payment-methods">
		<?php
		$arr = $model->getSimPaymentModulesThatUseCard();
		$count = count($arr);
		$checked = 0;
		foreach ($arr as $module)
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
			<input type="checkbox"
			       checked="checked"
			       value="<?= $checkbox['id'] ?>"
			       onclick="$('.address-form').fadeToggle();$('footer input').fadeToggle();"
			       name="<?= $checkbox['name'] ?>"
				<?= _xls_get_conf('SHIP_SAME_BILLSHIP') == 1 ? 'disabled' : '' ?>
			/>
			<span class="text">
				<?php
				echo Yii::t('checkout', $checkbox['label'])
				?>
				<br>
				<span class="address-abbr">
					<?php
					echo $checkbox['address'];
					?>
				</span>
			</span>
	</label>

	<div class="address-form invisible">
		<h4><?php echo Yii::t('checkout','Billing Address'); ?></h4>
		<ol class="address-blocks">
			<?php if(count($model->objAddresses) > 0): ?>
				<?php foreach ($model->objAddresses as $objAddress): ?>
					<li class="address-block address-block-pickable">
						<p class="webstore-label">
							<?php
							echo $objAddress->formattedblockcountry;
							?>
							<span class="controls">
							<a href="#">Edit Address</a> or <a class="delete">Delete</a>
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
				<button class="small">Add New Address</button>
			</li>
		</ol>
	</div>
	</div>
<div class="alt-payment-methods payment-methods">
	<h4><?php echo Yii::t('checkout', 'Alternative Payment Methods'); ?></h4>
	<?php
	foreach ($model->simPaymentModulesNoCard as $id => $option)
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

<?php $this->endWidget(); ?>

<aside class="section-sidebar webstore-sidebar-summary">
	<?php $this->renderPartial('_ordersummary'); ?>
</aside>
