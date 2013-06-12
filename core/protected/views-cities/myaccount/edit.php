<h1><?php echo Yii::t('global',($model->id >0 ? 'Update your account' : 'Create a Free Account!')); ?></h1>

<?php
/* Create a new wish list form. We use the Checkout ID to reuse our CSS formatting */
?><div id="checkout">
<?php if(_xls_facebook_login() && $model->id==0): ?>
	<fieldset class="span12">
		<h3><?php echo Yii::t('checkout','Make it simple. Register with your Facebook login.'); ?></h3>
		<?php $this->widget('ext.yii-facebook-opengraph.plugins.LoginButton', array(
			'show_faces'=>true,
			'size'=>'large',
			'text'=> Yii::t('global','Register using my Facebook account'),
			'scope' => 'email,user_location,publish_actions',
			'on_login'=>'window.location.href="'.Yii::app()->createUrl('facebook/create').'";',
		)); ?>
	</fieldset>
	<h3><?php echo Yii::t('global','Or sign up manually'); ?></h3>
<?php endif; ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'myaccount',
	'enableClientValidation'=>true,
	'focus'=>array($model,'first_name'),
));
	?>

    <div id="customercontact">
      <div id="CustomerContactBillingInfo">



	    <fieldset class="span12">
	        <legend><?php echo Yii::t('checkout','Customer Contact'); ?></legend>
	        <div class="row-fluid">
		        <div class="span5">
					<?php echo $form->labelEx($model,'first_name'); ?>
					<?php echo $form->textField($model,'first_name'); ?>
					<?php echo $form->error($model,'first_name'); ?>
		        </div>

			    <div class="span5">
					<?php echo $form->label($model,'last_name'); ?>
					<?php echo $form->textField($model,'last_name'); ?>
					<?php echo $form->error($model,'last_name'); ?>
			    </div>
	        </div>
	        <div class="row-fluid">
		        <div class="span5">
					<?php echo $form->labelEx($model,'email'); ?>
					<?php echo $form->textField($model,'email'); ?>
					<?php echo $form->error($model,'email'); ?>
		        </div>

				<?php if(Yii::app()->user->isGuest): ?>
			        <div class="span5">
						<?php echo $form->labelEx($model,'email_repeat'); ?>
						<?php echo $form->textField($model,'email_repeat'); ?>
						<?php echo $form->error($model,'email_repeat'); ?>
			        </div>
				<?php endif; ?>
		    </div>
		    <div class="row-fluid">
			    <div class="span5">
				    <?php echo $form->labelEx($model,'mainphone'); ?>
				    <?php echo $form->textField($model,'mainphone'); ?>
				    <?php echo $form->error($model,'mainphone'); ?>
			    </div>
		    </div>
         </fieldset>
       </div>
     </div>


	  <div id="createaccount">
		  <legend><?php
			  echo (Yii::app()->user->isGuest ?  Yii::t('global','Choose a password') :
				  Yii::t('global','Enter a new password here to change your password'));

			  ?></legend>
        <fieldset>
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

            <div class="row-fluid">
				<?php echo $form->checkBox($model,'newsletter_subscribe'); ?>
				<?php echo $form->label($model,'newsletter_subscribe'); ?>
				<?php echo $form->error($model,'newsletter_subscribe'); ?>
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
</div><!-- form -->
