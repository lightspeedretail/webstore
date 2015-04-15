<?php
	echo CHtml::script(
		'var cityPlaceholder = ' . CJSON::encode(Yii::t('checkout', "City")) . ';'
	);
?>
<ol class="field-containers-small field-container-gap">
	<li class="field-container field-container-split">
		<?php
		echo $form->labelEx(
			$model,
			'shippingFirstName',
			array('class' => 'placeheld')
		);
		echo $form->textField(
			$model,
			'shippingFirstName',
			$htmlOptions = array('placeholder' => Yii::t('checkout', "First Name"),'class' => 'no-right-border','required' => "required", 'autofocus' => "")
		);
		?>
	</li>
	<li class="field-container field-container-split field-container-split-latter">
		<?php
		echo $form->labelEx(
			$model,
			'shippingLastName',
			array('class' => 'placeheld')
		);
		echo $form->textField(
			$model,
			'shippingLastName',
			$htmlOptions = array('placeholder' => Yii::t('checkout', "Last Name"),'required' => "required")
		);
		?>
	</li>
	<li class="field-container-toggle">
		<a href="#" onclick="$(this).parent().remove();$('.company-container').fadeIn();$('.company-container').find('input').focus(); return false;">
			<?php echo Yii::t('checkout', 'Company')?>
		</a>
	</li>
	<li class="field-container company-container field-container-notopborder" style="display: none;">
		<label class="placeheld"><?php echo Yii::t('checkout', "Company")?></label>
		<?php
			echo $form->textField(
				$model,
				'shippingCompany',
				$htmlOptions = array(
					'placeholder' => Yii::t('checkout', 'Company'),
					'class' => 'no-top-border',
					'required' => 'required'
				)
			);
		?>
	</li>
</ol>
<ol class="field-containers-small field-container-gap">
	<li class="field-container field-container-nobottomborder">
		<?php
		echo $form->labelEx(
			$model,
			'shippingAddress1',
			$htmlOptions = array('class' => 'placeheld'),
			array('label' => 'Address 1')
		);
		echo $form->textField(
			$model,
			'shippingAddress1',
			$htmlOptions = array(
				'placeholder' => Yii::t('checkout', "Mailing address"),
				'required' => "required",
				'class' => 'no-bottom-border'
			)
		);
		?>
	</li>
	<li class="field-container">
		<?php
		echo $form->labelEx(
			$model,
			'shippingAddress2',
			$htmlOptions = array('class' => 'placeheld'),
			array('label' => 'Address 2')
		);
		echo
		$form->textField(
			$model,
			'shippingAddress2',
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
					'shippingPostal',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'Zip')
				);

				echo $form->textField(
					$model,
					'shippingPostal',
					$htmlOptions = array('placeholder' => Yii::t('checkout', "Zip"), 'required' => "required" ),
					array(
						'ajax' => array(
							'type' => 'POST',
							'dataType' => 'json',
							'url' => CController::createUrl('cart/settax'),
							'success' => 'js:function(data){ updateTax(data) }',
							'data' => 'js:{"'.'state_id'.'": $("#'.CHtml::activeId($model,'shippingState').
								' option:selected").val(),
								"'.'postal'.'": $("#'.CHtml::activeId($model,'shippingPostal').'").val()}',
						)
					)
				);
				?>
			</li>
			<li class="field-container" id="ChooseCity">
				<?php
				echo $form->labelEx(
					$model,
					'shippingCity',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'City')
				);
				echo $form->textField(
					$model,
					'shippingCity',
					array('placeholder' => Yii::t('checkout', "City"), 'required' => 'required')
				);
				?>
			</li>
			<li class="field-container">
				<?php
				echo $form->labelEx(
					$model,
					'shippingStateCode',
					$htmlOptions = array('class' => 'placeheld'),
					array('label' => 'State')
				);
				echo $form->textField(
					$model,
					'shippingStateCode',
					array('placeholder' => Yii::t('checkout', "State"), 'required' => 'required')
				);
				?>
			</li>
		</ol>
	</li>
	<li class="field-container field-container-select field-container-select-no-handle country country-container">
		<?php
		echo $form->dropDownList(
			$model,
			'shippingCountryCode',
			CHtml::listData(Country::sortShippingCountries($model->shippingCountryCode), 'code', 'country'),
			$htmlOptions = array('class' => 'modal-accent-color')
		);
		?>
	</li>

</ol>
<ol class="field-containers-small">
	<li class="field-container">
		<?php echo $form->labelEx(
			$model,
			'contactPhone',
			$htmlOptions = array('class' => 'placeheld'),
			array('label' => 'Phone')
		);
		echo $form->textField(
			$model,
			'contactPhone',
			$htmlOptions = array('placeholder' => Yii::t('checkout', "Phone"))
		);
		?>
	</li>
</ol>
<p class="tip"><?php echo Yii::t('checkout', "May be printed on label to assist delivery.") ?></p>
<ol class="field-containers-small">
	<label class="checkbox">
		<?php
		echo $form->checkBox(
			$model,
			'shippingResidential',
			$htmlOptions = array(
				'class' => 'residential-toggle',
				'value' => 1,
				'uncheckValue' => 0)
		);
		?>
		<?php echo Yii::t('checkout','This is a residential address.'); ?>
	</label>
</ol>
