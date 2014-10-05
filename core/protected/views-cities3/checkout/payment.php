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
<div class="creditcard">
<div class="error-holder"><?= $error ?></div>

<!------------------------------------------------------------------------------------------------------------	CREDIT CARD FORM -------------------------------------------------------------------------------------------------->

	<?php $this->widget('ext.wscreditcardform.wscreditcardform', array('model' => $model, 'form' => $form)); ?>

<!------------------------------------------------------------------------------------------------------------	CREDIT CARD FORM  -------------------------------------------------------------------------------------------------->

	<label class="shippingasbilling">
		<?php if (Yii::app()->shoppingcart->shipping->isStorePickup === false): ?>
		<input type="checkbox" checked="checked" value="1" onclick="$('.address-form').fadeToggle();" name="MultiCheckoutForm[billingSameAsShipping]"/>
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
		<div class="address-form <?= Yii::app()->shoppingcart->shipping->isStorePickup ? '' : 'invisible'?>">
			<h4><?php echo Yii::t('checkout', "Billing Address ") ?></h4>
			<ol class="field-containers-small field-container-gap">
				<li class="field-container field-container-nobottomborder">
					<?php echo $form->labelEx(
						$model,
						'billingAddress1',
						$htmlOptions = array('class' => 'placeheld'),
						array('label' => 'Address 1')
					);
					echo $form->textField(
						$model,
						'billingAddress1',
						$htmlOptions = array('placeholder' => Yii::t('checkout', "Mailing address"), 'required' => "required")
					);
					?>
				</li>
				<li class="field-container">
					<?php
					echo $form->labelEx(
						$model,
						'billingAddress2',
						$htmlOptions = array('class' => 'placeheld'),
						array('label' => 'Address 2')
					);
					echo $form->textField(
						$model,
						'billingAddress2',
						$htmlOptions = array('placeholder' => Yii::t('checkout', "Suite, Floor, etc."))
					);
					?>
				</li>
				<li class="fieldgroup">
					<ol>
						<li class="field-container">
							<?php echo $form->labelEx(
								$model,
								'billingPostal',
								$htmlOptions = array('class' => 'placeheld'),
								array('label' => 'Address 1')
							);
							echo $form->textField(
								$model,
								'billingPostal',
								$htmlOptions = array('placeholder' => Yii::t('checkout', "Zip"),'size' => "5", 'required' => "required")
							);
							?>
						</li>
						<li class="field-container">
							<?php
							echo $form->labelEx(
								$model,
								'billingCity',
								$htmlOptions = array('class' => 'placeheld'),
								array('label' => 'City')
							);
							echo $form->textField(
								$model,
								'billingCity',
								array('size' => '12', 'placeholder' => Yii::t('checkout', "City"), 'required' => 'required')
							);
							?>
						</li>
						<li class="field-container">
							<?php
							echo $form->labelEx(
								$model,
								'billingState',
								$htmlOptions = array('class' => 'placeheld'),
								array('label' => 'State/Province')
							);
							echo $form->textField(
								$model,
								'billingState',
								array('size' => '4', 'placeholder' => Yii::t('checkout', "State"), 'required' => 'required')
							);
							?>
						</li>

						<li class="field-container field-container-select field-container-select-no-handle country">
							<?php
							echo $form->dropDownList($model,
								'billingCountry',
								$model->getCountries(),
								$htmlOptions = array('class' => 'modal-accent-color', 'options' => $this->countryCodes)
							);
							?>
						</li>
					</ol>
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
	echo CHtml::submitButton(
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

