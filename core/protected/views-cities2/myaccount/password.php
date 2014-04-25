<h1><?php echo Yii::t('global', 'Change your password'); ?></h1>

<div id="checkout">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'myaccount',
	'enableClientValidation'=>true,
	'focus'=>array($model,'password')
));
?>

<div id="createaccount">
	<fieldset class="span12">
		<legend>
			<?php
				echo Yii::t('global','Enter a new password here to change your password');
			?>
		</legend>
		<div class="row-fluid">
			<div class="span5">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password', array('placeholder'=>"", 'autocomplete'=>"off")); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class="span5">
				<?php echo $form->labelEx($model,'password_repeat'); ?>
				<?php echo $form->passwordField($model,'password_repeat',array('placeholder'=>"", 'autocomplete'=>"off")); ?>
				<?php echo $form->error($model,'password_repeat'); ?>
			</div>
		</div>
	</fieldset>
</div>

<div class="clearfix"></div>

<div class="row-fluid">
	<div class="submitblock" >
		<?php echo CHtml::submitButton('Submit', array('id'=>'btnSubmit'));  ?>
	</div>
</div>

<?php $this->endWidget(); ?>

</div><!-- #checkout -->
