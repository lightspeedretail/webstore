<label class="shippingasbilling">
	<?php if (Yii::app()->shoppingcart->shipping->isStorePickup === false): ?>
		<?php
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
		<div class="text" id="payment">
			<?= Yii::t('checkout', "Use my shipping address as my billing address") ?>
			<p class="address-abbr">
				<?= $model->strShippingAddress; ?>
			</p>
		</div>
	<?php endif; ?>
</label>
