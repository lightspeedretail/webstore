<?php
/* This is the contents of the modal login dialog box. It's a Render Partial since we don't need the full HTML wrappers

Note we use the is_null to put the blank entries on the bottom
*/
?><div class="tier-grid">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'tier-grid',
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>false,
	));   ?>
	<div class="editinstructions">
		<?php echo Yii::t('admin','Select a new password to be emailed to the user. (Don\'t fret too much about choosing, they\'ll change it to something else anyway.)'); ?>
	</div>

	<div>
		<table class="span5">
			<tr><th>Choose random password</th></tr>
		</table>
	</div>

	<?php echo CHtml::activeRadioButtonList(
		$model,
		'password_repeat',
		$model->randomPasswords

	); ?>



	<div class="row tip">
		<div class="pull-right">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
				'htmlOptions'=>array('id'=>'buttonSavePCR'),
				'label'=>'Save and Send',
				'type'=>'primary',
				'size'=>'small',
			)); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->





