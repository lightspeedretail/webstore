<tr class="<?= $cssClass . ' ' ?>webstore-estimator webstore-estimator-placeholder">
	<td colspan="4">
		<?php
			echo CHtml::link(
				Yii::t('cart', 'Est. Shipping & Taxes'),
				'#',
				array(
					'class' => 'estimate-shipping-and-taxes-link',
					'onclick' => "wsShippingEstimator.showScreen('entering-postal');$(this).toggleClass('active')"
				)
			);
		?>

			<?php
				echo
				// Show a clickable link with the current shipping country.
				CHtml::link(
					$shippingCountryName,
					'#',
					array(
						// TODO webstore-estimator-country is for CSS styles.
						'class' => 'shipping-country-link webstore-estimator-country',
						'onclick' => "wsShippingEstimator.showScreen('choosing-country')"
					)
				);
			?>

		<?php

			// Show a dropdown for selecting shipping country.
			echo CHtml::dropDownList(
				'webstore-estimator-country-form',
				$shippingCountryCode,
				$countries,
				array(
					'class' => 'shipping-country-picker',
					'onchange' => 'wsShippingEstimator.selectedCountry(this)'
				)
			);
		?>
		<div class="shipping-postal-entry">
			<?php
				// Show an input field for the postal code.
				echo CHtml::textField(
					'zip-and-postal',
					$shippingPostal,
					array(
						'class' => 'shipping-postal-input',
						'type' => 'postal',
						'placeholder' => Yii::t('cart','Zip / Postal Code')
					)
				);
				// Show a button to retrieve shipping estimates.
				echo CHtml::htmlButton (
					Yii::t('cart','Calculate'),
					array(
						'class' => 'inset',
						'onclick' => "
							wsShippingEstimator.toggleLoadingSpinner();
							wsShippingEstimator.calculateShippingEstimates()
								.done(function() {
									wsShippingEstimator.toggleLoadingSpinner();
									wsShippingEstimator.showScreen('choosing-shipping-option');
								});"
					)
				);
			?>
		</div>
	</td>
</tr>

<!-- Shipping price estimate -->
<tr class="<?= $cssClass . ' ' ?>webstore-estimator shipping-estimate-line">
	<th colspan="2">
		<?php
			echo CHtml::link(
				Yii::t('cart', 'Shipping'),
				'#',
				array(
					'onclick' => "wsShippingEstimator.toggleShowShippingOptions()"
				)
			);
		?>
		<small>
			<?php
				echo CHtml::link(
					$shippingPostal,
					'#',
					array(
						'class' => 'shipping-postal-link',
						'onclick' => "wsShippingEstimator.showScreen('entering-postal');"
					)
				);
			?>
		</small>
	</th>
	<td class="shipping-estimate money"><?= $formattedShippingPrice ?></td>
</tr>

<!-- Tax price estimate -->
<tr class="<?= $cssClass . ' ' ?>webstore-estimator tax-estimate-line">
	<th colspan="2">
		<?php echo Yii::t('cart','Tax'); ?>
		<small>
			<?php
				echo CHtml::link(
					null, // Set based on zippopotamus lookup.
					'#',
					array(
						'class' => 'shipping-city-state-link',
						'onclick' => "wsShippingEstimator.showScreen('entering-postal');"
					)
				);
			?>
		</small>
	</th>
	<td class="tax-estimate money"><?= $formattedCartTax ?></td>
</tr>

<?php
	echo CHtml::script(
		'
		var strCalculateButton = '. CJSON::encode(Yii::t('shipping', 'Calculate')) .';
		$(document).ready(function () {
			wsShippingEstimator = new WsShippingEstimator(' . $wsShippingEstimatorOptions . ');
		});'
	);
?>
