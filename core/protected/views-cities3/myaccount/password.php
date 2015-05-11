<h1><?= Yii::t('global', 'Change your password'); ?></h1>

<div id="checkout">

	<?php
		$form = $this->beginWidget('CActiveForm', array(
				'id' => 'myaccount',
				'enableClientValidation' => true
			));
	?>

	<div id="createaccount">
		<fieldset class="span12">
			<legend>
				<?= Yii::t('global', 'Enter a new password here to change your password'); ?>
			</legend>
			<div class="row-fluid">
				<div class="span5">
					<?= $form->labelEx($model, 'password'); ?>
					<?= $form->passwordField($model, 'password', array('placeholder' => '', 'autocomplete' => 'off')); ?>
					<?= $form->error($model, 'password'); ?>
				</div>
				<div class="span5">
					<?= $form->labelEx($model, 'password_repeat'); ?>
					<?= $form->passwordField($model, 'password_repeat', array('placeholder' => '', 'autocomplete' => 'off')); ?>
					<?= $form->error($model, 'password_repeat'); ?>
				</div>
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

</div><!-- #checkout -->
