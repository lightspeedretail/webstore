<h1>
	<?=
		Yii::t(
			'global',
			($model->id > 0 ? 'Update your account' : 'Create a Free Account')
		);
	?>
</h1>

<?php
/* Create a new wish list form. We use the Checkout ID to reuse our CSS formatting */
?>
<div id="checkout">
<?php if(_xls_facebook_login() && $model->id == 0): ?>
	<div id="fbook">
		<fieldset class="span12">
			<h3>
				<?=
					Yii::t(
						'checkout',
						'Make it simple. Register with your Facebook login.'
					);
				?>
			</h3>
			<?php
				$this->widget(
					'ext.yii-facebook-opengraph.plugins.LoginButton',
					array(
						'show_faces' => true,
						'size' => 'large',
						'text' => Yii::t('global', 'Register using my Facebook account'),
						'scope' => 'email,user_location,publish_actions',
						'on_login' => 'window.location.href="' . Yii::app()->createUrl('facebook/create') . '";',
					)
				);
			?>
		</fieldset>
		<h3>
			<?=
				Yii::t('global', 'Or sign up manually');
			?>
		</h3>
	</div>
<?php endif; ?>

<?php
	$form = $this->beginWidget(
		'CActiveForm',
		array(
			'id' => 'myaccount',
			'enableClientValidation' => true,
		)
	);
?>
	<div id="customercontact">
	  <div id="CustomerContactBillingInfo">
		<fieldset class="span12">
			<div class="row-fluid">
				<div class="span5">
					<?=
						$form->labelEx($model, 'first_name') .
						$form->textField($model, 'first_name') .
						$form->error($model, 'first_name');
					?>
				</div>

				<div class="span5">
					<?=
						$form->label($model,'last_name') .
						$form->textField($model,'last_name') .
						$form->error($model,'last_name');
					?>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span5">
					<?=
						$form->labelEx($model,'email') .
						$form->textField($model,'email') .
						$form->error($model,'email');
					?>
				</div>

				<?php if(Yii::app()->user->isGuest): ?>
					<div class="span5">
						<?=
							$form->labelEx($model,'email_repeat') .
							$form->textField($model,'email_repeat') .
							$form->error($model,'email_repeat');
						?>
					</div>
				<?php endif; ?>
			</div>
			<div class="row-fluid">
				<div class="span5">
					<?=
						$form->labelEx($model,'mainphone') .
						$form->textField($model,'mainphone') .
						$form->error($model,'mainphone');
					?>
				</div>
			</div>
		 </fieldset>
	   </div>
	 </div>

	  <div id="createaccount">
		  <fieldset class="span12">
				<?=
					(Yii::app()->user->isGuest ?  '' :
					  '<legend>' . Yii::t('global', 'Enter a new password here to change your password') . '</legend>'
					);
				?>

			<div class="row-fluid">
				<div class="span5">
					<?=
						$form->labelEx($model, 'password') .
						$form->passwordField(
							$model,
							'password',
							array(
								'placeholder' => '',
								'autocomplete' => 'off'
							)
						) .
						$form->error($model, 'password');
					?>
				</div>
				<div class="span5">
					<?=
						$form->labelEx($model,'password_repeat') .
						$form->passwordField(
							$model,
							'password_repeat',
							array(
								'placeholder' => '',
								'autocomplete' => 'off'
							)
						) .
						$form->error($model, 'password_repeat');
					?>
				</div>
			</div>
		  </fieldset>
	  </div>
	<div>
		<fieldset class="span12">
			<div class="row-fluid">
				<?=
					$form->checkBox($model,'newsletter_subscribe') .
					$form->label($model,'newsletter_subscribe') .
					$form->error($model,'newsletter_subscribe');
				?>
			</div>
		</fieldset>
	</div>

	<div class="clearfix"></div>

	<div class="row-fluid">
		<div class="submitblock" >
			<?=
				CHtml::submitButton(
					Yii::t(
						'forms',
						'Submit'
					),
					array(
						'id' => 'btnSubmit'
					)
				);
			?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
