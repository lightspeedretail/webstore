<div class="<?= $cssClass . ' ' ?>shipping-options webstore-shipping-choices">
	<!-- TODO Speak to designers about why this is in a form. It doesn't need to be. -->
	<form>
		<ol>
		</ol>
		<!-- TODO Update CSS to use "options" rather than "choices" to be consistent." -->
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

