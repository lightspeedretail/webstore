<?php
CHtml::$afterRequiredLabel = '';

$form = $this->beginWidget(
	'CActiveForm',
	array(
		'htmlOptions' => array(
			'class' => 'section-content',
			'id' => 'payment',
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
			'_shippingasbillingexisting',
			array(
				'checkbox' => $checkbox
			)
		);
	?>

	<div class="address-form invisible">
		<h4><?= Yii::t('checkout', 'Billing Address'); ?></h4>
		<ol class="address-blocks">
			<?php if(count($model->objAddresses) > 0): ?>
				<?php foreach ($model->objAddresses as $objAddress): ?>
					<li class="address-block address-block-pickable">
						<p class="webstore-label">
							<?= $objAddress->formattedblockcountry; ?>
							<span class="controls">
								<?=
									CHtml::link(
										Yii::t('checkout', 'Edit Address'),
										Yii::app()->createUrl(
											'/checkout/editaddress',
											array(
												'id' => $objAddress->id,
												'type' => 'billing'
											)
										)
									);
								?>

								<?= Yii::t('checkout', 'or'); ?>
								<?=
								CHtml::ajaxLink(
									Yii::t('checkout', 'Hide'),
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
									array('class' => 'delete')
								);
								?>
							</span>
						</p>
						<div class="buttons">
							<?=
							CHtml::htmlButton(
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
				<?=
					CHtml::link(
						Yii::t('checkout', 'Add New Address'),
						Yii::app()->createUrl(
							'/checkout/newaddress',
							array('type' => 'billing')
						),
						array('class' => 'small button')
					);
				?>
			</li>
		</ol>
	</div>
</div>

<?php
$altMethods = $model->getAlternativePaymentMethods();
$count = count($altMethods);

if ($count > 0):
?>
<div class="alt-payment-methods payment-methods">
	<h4><?= Yii::t('checkout', 'Alternative Payment Methods'); ?></h4>
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
	<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<footer>
	<?=
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
