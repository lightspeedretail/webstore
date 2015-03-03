<div class="section-content" id="confirm">
	<div class="thankyou">
		<h1><?= Yii::t('checkout', 'Thank you for shopping with us!') ?></h1>
		<h2><?= Yii::t('checkout', 'Your order is complete') ?></h2>

		<p class="large">
			<strong>
				<?= Yii::t('checkout', 'Order number is:') . ' ' . $cart->id_str; ?>
			</strong>
		</p>
		<p>
			<?= Yii::t('checkout', 'You will receive an email confirmation shortly at') . ' ' . $cart->customer->email ?>
		</p>
		<p>
			<?=
				CHtml::link(
					Yii::t('checkout', 'Print Receipt'),
					'#print',
					array('class' => 'print', 'onclick' => 'print(); return false;')
				);
			?>
		</p>
<!---------------- Create new account ------------------------------------------->
		<?php if ($showCreateNewAccount === true): ?>
			<?php
				$form = $this->beginWidget(
					'CActiveForm',
					array(
						'htmlOptions' => array(
							'class' => 'create-account'
						)
					)
				);
			?>
			<h3><?=  Yii::t('checkout', 'Save your information for next time') ?></h3>
			<p><?=  Yii::t('checkout', 'Faster checkout, convenient order history and more.') ?></p>
			<?php
				if ($arrError !== null)
				{
					echo $arrError;
				}
			?>
			<ol>
				<li>
					<?=
						$form->passwordField(
							$model,
							'password',
							array(
								'placeholder' => Yii::t('checkout', 'Password')
							)
						);
					?>

					<p class="hint">
						<?php
							if (_xls_get_conf('MIN_PASSWORD_LEN', 0) > 0)
							{
								echo Yii::t(
									'checkout',
									'Must be at least {minLength} characters',
									array (
										'{minLength}' => _xls_get_conf('MIN_PASSWORD_LEN')
									)
								);
							}
						?>
					</p>
				</li>
				<li>
					<?=
					$form->passwordField(
						$model,
						'password_repeat',
						array(
							'placeholder' => Yii::t('checkout', 'Verify Password')
						)
					);
					?>
				</li>
				<li><button ><?=Yii::t('checkout', 'Create Account')?></button></li>
			</ol>
			<?php $this->endWidget();?>
		<?php endif; ?>
<!-------------- Account confirmation  ------------------------------------------->
		<?php if ($showAccountCreated === true): ?>
			<div class="create-account-confirm">
				<h3><?= Yii::t('checkout', 'Your account has been created.'); ?></h3>
			</div>
		<?php endif; ?>
<!-------------- Account confirmation  ------------------------------------------->
	</div>
	<div class="receipt">
		<h2><?= Yii::t('checkout', 'Order Receipt')?><span class="order-id"><?= Yii::t('checkout', 'Order #:').' '."<strong>".$cart->id_str?></strong></span></h2>

		<?php $this->renderPartial('_orderdetails', array('cart' => $cart, 'isReceipt' => true)); ?>
		<?php $this->renderPartial('_finalizecart', array('cart' => $cart, 'isReceipt' => true)); ?>

	</div>
</div>
