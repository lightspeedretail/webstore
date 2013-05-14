<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';

?>

<h3>Admin Panel Login</h3>

<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>

	<div class="row">
		<div class="span4"><?php echo $form->labelEx($model,'email'); ?></div>
		<div class="span4"><?php echo $form->textField($model,'email'); ?></div>
		<div class="span4"><?php echo $form->error($model,'email'); ?></div>
	</div>

	<div class="row">
		<div class="span4"><?php echo $form->labelEx($model,'password'); ?></div>
		<div class="span4"><?php echo $form->passwordField($model,'password'); ?></div>
		<div class="span4"><?php echo $form->error($model,'password'); ?></div>
	</div>

	<div class="row buttons pull-right">
		<div class="span6">
			<?php echo CHtml::submitButton('Login'); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
