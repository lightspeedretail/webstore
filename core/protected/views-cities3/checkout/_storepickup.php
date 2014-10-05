<?php if($model->isInStorePickupActive()): ?>
	<div class="shipping-instore">
		<label class="checkbox">
			<?php
			echo CHtml::checkBox(
				'storePickupCheckBox',
				false,
				$htmlOptions = array(
					'class' => 'instore-toggle',
					'onclick' => 'updateShippingPriority(this.value)',
					'uncheckValue' => null
				)
			); ?>
			<strong><?php echo Yii::t('checkout','In-Store Pickup'); ?></strong>

			<p class="description">
				<?php echo Yii::t('checkout',"Near our store? You can pickup your order. We'll email you as soon as your items are ready."); ?>
			</p>

		</label>

		<div class="shipping-instore-details modal-conditional-block">
			<h4>
				<?php
				echo Yii::t('global','Store Details');
				?>
			</h4>
			<p class="contact-info">
				<?php
				echo _xls_html_storeaddress();
				?>
			</p>

			<p class="contact-info">
				<?php
				echo Yii::t('checkout', _xls_get_conf('STORE_HOURS'));
				?>
			</p>

			<ol class="field-containers-small" style="overflow:visible">
				<li class="field-container">
					<label class="placeheld">
						<?php
						echo Yii::t('checkout', "Name");
						?>
					</label>
					<?php
					echo $form->textField(
						$model,
						'pickupPerson',
						$htmlOptions = array('placeholder' => Yii::t('checkout', "Person picking up"),'required' => "required", 'autofocus' => "autofocus")
					);?>
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
						$htmlOptions = array('placeholder' => Yii::t('cart', "Email"))
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
					<p class="hint"><?php echo Yii::t('checkout', "Optional")?></p>
				</li>
			</ol>
			<footer class="submit submit-small">

				<?php
				echo CHtml::submitButton(
					'Submit',
					array(
						'type' => 'submit',
						'class' => 'button',
						'value' => Yii::t('checkout', "Proceed to Payment"),
					)
				); ?>
			</footer>
		</div>
	</div>
<?php endif; ?>