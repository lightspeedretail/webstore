<?php
$this->pageTitle=Yii::app()->name . ' - '. Yii::t('global','Login');
$this->breadcrumbs=array(
	Yii::t('global','Login'),
);
?>
<h1><?= Yii::t('global','Login') ?></h1>
<?php
if(_xls_facebook_login()): ?>
	<?php $this->widget('ext.yii-facebook-opengraph.plugins.LoginButton', array(
		'show_faces'=>false,
		'size'=>'large',
		'text'=> Yii::t('global','Log in with Facebook'),
		'scope' => 'email,user_location,publish_actions',
		'on_login'=>'window.location.href="'.Yii::app()->createUrl('facebook/create').'";',
	)); ?>
<?php endif; ?>
<div class="login">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>false,
		'focus'=>array($model,'email'),
	));   ?>
	<div class="row midrow">
		<div class="col-sm-4">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="col-sm-4">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="row">
		<div class="col-sm-4">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe',array('class'=>'rememberMe')); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div>
		<div class="col-sm-4">
			<?php echo CHtml::ajaxLink(Yii::t('global','Forgot Password?'),
				$this->createUrl('site/forgotpassword'),
				array(
					'type'=>"POST",
					'data'=>'js:jQuery($("#login-form")).serialize()',
					'dataType' => 'json',
					'success'=>'js:function(data) {
			                if (data.status=="success") {
			                    alert(data.message);
			                    $.ajax({url:data.url});
			                } else  alert(data.message);
						}'

				),
			array('class'=>'forgotpassword')); ?>

		</div>
	</div>

	<div class="row shortrow buttons">
		<div class="col-sm-12">
			<?php
			echo CHtml::SubmitButton(Yii::t('global','Login'),
				array('site/login'),
				array('id'=>'btnModalLogin','name'=>'btnModalLogin')); ?>
		</div>
	</div>




	<?php $this->endWidget(); ?>
</div><!-- form -->
