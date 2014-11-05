<div class="<?= $cssClass . ' ' ?>shipping-options webstore-shipping-choices">
	<form>
		<ol>
		</ol>
		<?php
			echo CHtml::button(
				Yii::t('cart', 'Done'),
				array(
					'class' => 'close-shipping-options close-shipping-choices',
					'onclick' => "
						var selectedOptions = $(this).parent().find('input[name=shipping_option]:checked');

						// It is possible that no option has is selected. For
						// example, when an option that is not shown here (due to
						// WsShippingEstimator::MAX_SHIPPING_OPTIONS) was selected during checkout.
						if (selectedOptions.length === 1) {
							// We can only have one selected option in this context.
							wsShippingEstimator.selectedShippingOption(selectedOptions[0]);
						}

						wsShippingEstimator.showScreen('shipping-option-chosen', 'fadeOut');
					"
				)
			);
		?>
	</form>
</div>

