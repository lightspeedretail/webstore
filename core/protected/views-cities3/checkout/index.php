<script>
	var login = <?php echo CJSON::encode(
		array(
			'contactEmail' => $model->email,
			'blnShowLogin' => $blnShowLogin,
			'showLoginPasswordField' => $showLoginPasswordField,
		)
	);
?>
</script>
<div id="wrapper">
	<div class="webstore-overlay webstore-modal-overlay webstore-overlay-narrow webstore-checkout">
		<section id="start">
			<header class="overlay">
				<h1>
					<?php
						echo CHtml::link(
							CHtml::image(Yii::app()->params['HEADER_IMAGE'], Yii::t('cart','web store header image')).
							CHtml::tag('span', array(), Yii::app()->params['STORE_NAME']),
							Yii::app()->createUrl("site/index"),
							array('class' => 'logo-placement')
						);
					?>
				</h1>
				<?php echo CHtml::link(Yii::t('cart','Continue Shopping'), $this->createUrl("site/index"), array('class' => 'exit')); ?>

			</header>
			<div class="section-inner">
				<h1>
					<?php
					echo Yii::t('checkout', "What's your email address?");
					?>
				</h1>
				<?php
				$form = $this->beginWidget(
					'CActiveForm',
					array('htmlOptions' => array('novalidate' => '1'),
						'enableClientValidation'=>true
					)
				);
				?>
				<div>
					<?php
					echo $form->checkBox(
							$model,
							'guest',
							array(
								'id' => 'IsGuest',
								'style' => 'display: none',
								'uncheckValue' => null,
								'checked' => 'checked'
							)
					);
					?>
				</div>

				<div class="error-holder">
					<?php echo $error; ?>
				</div>
				<ol>
					<li class="field-container">
						<label class="placeheld">Email Address</label>
						<?php
						echo $form->emailField(
							$model,
							'email',
							$htmlOptions = array('placeholder' => Yii::t('cart', "Email Address"),'required' => 'required', 'autofocus' => '')
						); ?>
					</li>
					<li class="flippable password-block field-container field-container-password">
						<!-- Default state -->
						<div class="side front step1">
							<p><?= Yii::t('checkout', "We use your email to send shipping confirmation, updates and for easy order lookup."); ?></p>
						</div>
						<!-- User wants to login with password -->
						<div class="side back step2">
							<label class="placeheld">Password</label>
							<?php
							echo $form->passwordField(
								$model,
								'password',
								$htmlOptions = array('placeholder' => Yii::t('global', "Password"))
							);
							?>
							<?php
							echo CHtml::linkButton(
								Yii::t('checkout', 'Forgot password?'),
								array('class' => 'button inset reset_toggle')
							);
							?>
						</div>
						<!-- User wants to lookup password -->
						<div class="side reset hint step3">
							<p><?= Yii::t('checkout',"Enter your email address and we'll send a password reset link.");?></p>
							<?php
							echo CHtml::linkButton(
								Yii::t('global', 'Cancel'),
								array('class' => 'button inset reset_toggle cancel')
							); ?>
						</div>
					</li>
				</ol>

				<footer class="submit">
					<div class = "login invisible">
						<?php
							echo CHtml::submitButton(
								Yii::t('checkout', 'Login'),
								array(
									'id' => 'login-button',
									'type'  => 'submit',
									'class' => 'button is-login',
									'data-alt-name' => 'Send Password Reset Email',
									'name' => 'login-button',
									'value' => Yii::t('checkout', 'Login')
								)
							);
						?>
						<p>
						<?php
							echo "-".Yii::t('checkout',"or")."- "."<a href='' class='alternate guest_checkout'>".Yii::t('checkout',"Checkout as Guest")."</a>"
						?>
						</p>
					</div>
					<div class="choices">
						<?php
							echo CHtml::submitButton(
								'Submit',
								array(
									'type' => 'button',
									'class' => 'button new_customer',
									'value' => Yii::t('checkout', "I'm a new customer"),
								)
							);

							echo CHtml::htmlButton(
								Yii::t('checkout', "I've ordered before"),
								array(
									'type' => 'button',
									'class' => 'ordered_before',
								)
							);
						?>
					</div>
					<div class="new_customer invisible">
						<?php
						echo CHtml::submitButton(
							'Submit',
							array(
								'type' => 'button',
								'class' => 'button',
								'value' => Yii::t('cart', "Continue to Shipping"),
							)
						);
						?>
						<p>
							<?php
							echo '-' .
								Yii::t('checkout', 'or') .
								'- ' .
								"<a href=''  onclick='return false;' class='alternate ordered_before'>" .
								Yii::t('checkout', 'Login to Account') .
								'</a>'
							?>
						</p>
					</div>
					<div class="guest invisible">
						<?php
						echo CHtml::submitButton(
							'Submit',
							array(
								'id' => 'guest-button',
								'type' => 'button',
								'class' => 'button',
								'value' => Yii::t('checkout', "Guest Checkout"),
							)
						);
						?>
						<p>
							<?php
								echo "-".Yii::t('checkout', "or")."- "."<a href='' class='alternate ordered_before'>".Yii::t('checkout', "Login to Account")."</a>"
							?>
						</p>
					</div>
				</footer>
				<?php $this->endWidget(); ?>
			</div>
			<footer>
				<div class="">
					<?php
					echo
					CHtml::htmlButton(
						Yii::t('cart', 'Continue Shopping'),
						array(
							'class' => 'button continue',
							'value' => Yii::t('checkout', "See Shipping Options"),
							'onClick'=>"window.location.href="."'".Yii::app()->createUrl('site/index')."'"
						)
					);
					?>
				</div>
				<p>
					<?php
					if (Yii::app()->params['ENABLE_SSL'] == 1)
					{
						echo
							CHtml::image(
								Yii::app()->params['umber_assets'] . '/images/lock.png',
								'lock image ',
								array(
									'height'=> 14
								)
							).
							CHtml::tag('strong',array(),'Safe &amp; Secure ').Yii::t('cart','Bank-grade SSL encryption protects your purchase.');
					}

					$objPrivacy = CustomPage::LoadByKey('privacy');
					if ($objPrivacy instanceof CustomPage && $objPrivacy->tab_position !== 0)
					{
						echo ' ' .
							CHtml::link(
								Yii::t('cart', 'Privacy Policy'),
								$objPrivacy->Link,
								array('target' => '_blank')
							);
					}
					?>
				</p>
			</footer>
		</section>
	</div>
</div>
