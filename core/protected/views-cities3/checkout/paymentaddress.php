<?php
CHtml::$afterRequiredLabel = '';

$form = $this->beginWidget(
	'CActiveForm',
	array(
		'htmlOptions' => array('class' => "section-content",'id' => "payment", 'novalidate' => '1','autocomplete'=>'on'
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
$this->renderPartial('_paypalbuttonaim');
?>

<div class="creditcard">
	<div class="error-holder"><?= $error ?></div>

	<!------------------------------------------------------------------------------------------------------------	CREDIT CARD FORM -------------------------------------------------------------------------------------------------->

	<?php $this->widget('ext.wscreditcardform.wscreditcardform', array('model' => $model, 'form' => $form)); ?>

	<!------------------------------------------------------------------------------------------------------------	CREDIT CARD FORM  ------------------------------------------------------------------------------------------------>

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
									Yii::t('checkout', 'Hide'),
									'/myaccount/removeaddress',
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
$simModules = $model->simPaymentModulesNoCard;
$count = count($simModules);

if ($count > 0):
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
<?php endif; ?>
	<!------------------------------------------------------------------------------------------------------------	Layout Markup -------------------------------------------------------------------------------------------------->
<footer>
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
