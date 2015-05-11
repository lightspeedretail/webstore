<?php if($model->isInStorePickupActive()): ?>
	<div class="shipping-instore">
		<label class="checkbox">
			<?php
			if ($shouldDisplayShippingAddresses)
			{
				echo CHtml::checkBox(
					'storePickupCheckBox',
					$isStorePickupSelected,
					$htmlOptions = array(
						'class' => 'instore-toggle',
						'uncheckValue' => null
					)
				);
			}   else    {
				echo CHtml::hiddenField(
					'storePickupCheckBox',
					1
				);
			}
			?>
			<strong><?php echo Yii::t('checkout','In-Store Pickup'); ?></strong>

			<p class="description">
				<?php echo Yii::t('checkout',"Near our store? You can pickup your order. We'll email you as soon as your items are ready."); ?>
			</p>

		</label>

		<div class="shipping-instore-details modal-conditional-block <?= $onLoadDisplayInStorePickup ? 'active' : '' ?>">
			<h4>
				<?php
				echo Yii::t('global','Store Details');
				?>
			</h4>
			<div class="clearfix">
				<p class="contact-info">
					<?php echo _xls_html_storeaddress();?>
				</p>

				<p class="contact-info">
					<?php echo Yii::t('checkout', _xls_get_conf('STORE_HOURS'));?>
				</p>
			</div>

			<ol class="field-containers-small" style="overflow:visible">
				<li class="field-container field-container-split">
					<?php
					echo $form->labelEx(
						$model,
						'pickupFirstName',
						array('class' => 'placeheld')
					);
					echo $form->textField(
						$model,
						'pickupFirstName',
						$htmlOptions = array('placeholder' => Yii::t('checkout', "First Name"),'class' => 'no-right-border','required' => "required", 'autofocus' => "")
					);
					?>
				</li>
				<li class="field-container field-container-split field-container-split-latter">
					<?php
					echo $form->labelEx(
						$model,
						'pickupLastName',
						array('class' => 'placeheld')
					);
					echo $form->textField(
						$model,
						'pickupLastName',
						$htmlOptions = array('placeholder' => Yii::t('checkout', "Last Name"),'required' => "required")
					);
					?>
				</li>
			</ol>
			<p class="tip"><?php echo Yii::t('checkout',"We'll contact this person when the order is ready."); ?></p>

			<ol class="field-containers-small field-container-gap">
				<li class="field-container field-container-nobottomborder">
					<label class="placeheld"><?php echo Yii::t('global', "Email")?></label>
					<?php
					echo $form->emailField(
						$model,
						'pickupPersonEmail',
						$htmlOptions = array(
							'placeholder' => Yii::t('cart', "Email"),
							'class' => 'no-bottom-border'
						)
					);
					?>
					<p class="hint"><?php echo Yii::t('checkout', "Optional")?></p>
				</li>
				<li class="field-container">
					<label class="placeheld"><?php echo Yii::t('checkout', "Mobile Phone")?></label>
					<?php
					echo $form->telField(
						$model,
						'pickupPersonPhone',
						$htmlOptions = array('placeholder' => Yii::t('checkout', "Mobile Phone"))
					);
					?>
				</li>
			</ol>
			<footer class="submit submit-small">
				<?=
					CHtml::submitButton(
						Yii::t(
							'forms',
							'Submit'
						),
						array(
							'type' => 'submit',
							'class' => 'button',
							'value' => Yii::t(
								'checkout',
								"Proceed to Payment"
							),
						)
					);
				?>
			</footer>
		</div>
	</div>
<?php endif; ?>