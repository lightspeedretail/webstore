<div class="error-holder">
	<?php echo $error; ?>
</div>
<ol class="field-containers-small field-container-gap">
	<li class="field-container field-container-nobottomborder">
		<?php
		echo $form->labelEx(
			$model,
			'billingAddress1',
			$htmlOptions = array('class' => 'placeheld'),
			array('label' => 'Address 1')
		);
		echo $form->textField(
			$model,
			'billingAddress1',
			$htmlOptions = array(
				'placeholder' => Yii::t('checkout', "Mailing address"),
				'class' => 'no-bottom-border',
				'required' => "required")
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
		echo
		$form->textField(
			$model,
			'billingAddress2',
			$htmlOptions = array('placeholder' => Yii::t('checkout', "Suite, Floor, etc."))
		);
		?>
	</li>
	<li class="fieldgroup city-fieldgroup">
		<ol>
			<li class="field-container">
				<?php
				echo $form->labelEx(
					$model,
					'billingPostal',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'Zip')
				);

				echo $form->textField(
					$model,
					'billingPostal',
					$htmlOptions = array('placeholder' => Yii::t('checkout', "Zip Code"), 'required' => "required" )
				);
				?>
			</li>
			<li class="field-container" id="ChooseCity">
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
					array('placeholder' => Yii::t('checkout', "City"), 'required' => 'required')
				);
				?>
			</li>
			<li class="field-container">
				<?php
				echo $form->labelEx(
					$model,
					'billingState',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'State')
				);
				echo $form->textField(
					$model,
					'billingStateCode',
					array('placeholder' => Yii::t('checkout', "State"), 'required' => 'required')
				);
				?>
			</li>
		</ol>
	</li>
	<li class="field-container field-container-select field-container-select-no-handle country-container">
		<?php
		echo $form->dropDownList(
			$model,
			'billingCountryCode',
			CHtml::listData(Country::sortShippingCountries($model->billingCountryCode), 'code', 'country'),
			$htmlOptions = array('class' => 'modal-accent-color')
		);
		?>
	</li>

</ol>

