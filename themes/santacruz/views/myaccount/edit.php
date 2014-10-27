<?php $this->layout='//layouts/column1'; ?>
    <h1><?= Yii::t('global',($model->id >0 ? 'Update your account' : 'Create a free account')); ?></h1>
<!--Create a new wish list form. We use the Checkout ID to reuse our CSS formatting-->
    <?php if(_xls_facebook_login() && $model->id==0): ?>
        <fieldset class="">
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
            'htmlOptions'=>array('role'=>'form'),
        ));
    ?>

        <div id="customercontact">
	        <div class="row">
                <h3 class="hidden-xs col-sm-5"><?php echo Yii::t('checkout','Customer Contact'); ?></h3>
                <h3 class="hidden-xs col-sm-5"><?php
                    echo (Yii::app()->user->isGuest ?  Yii::t('global','Login Information') :
                    Yii::t('global','Enter a new password here to change your password'));
                ?></h3>
            </div>
        </div>
        <div class="row">
	        <div class="col-sm-5">
		        <div class="form-group">
					<?php echo $form->labelEx($model,'first_name',array('class'=>'sr-only')); ?>
					<?php echo $form->textField($model,'first_name',array('placeholder'=>'First Name', 'class'=>'form-control')); ?>
					<?php echo $form->error($model,'first_name'); ?>
		        </div>

			    <div class="form-group">
					<?php echo $form->label($model,'last_name',array('class'=>'sr-only')); ?>
					<?php echo $form->textField($model,'last_name',array('placeholder'=>'Last Name','class'=>'form-control')); ?>
					<?php echo $form->error($model,'last_name'); ?>
			    </div>
                <div class="form-group">
                    <?php echo $form->label($model,'mainphone',array('class'=>'sr-only')); ?>
                    <?php echo $form->textField($model,'mainphone',array('placeholder'=>'Primary Phone','class'=>'form-control')); ?>
                    <?php echo $form->error($model,'mainphone'); ?>
                </div>
	        </div>
	        <div class="col-sm-5">
		        <div class="form-group">
					<?php echo $form->label($model,'email',array('class'=>'sr-only')); ?>
					<?php echo $form->textField($model,'email',array('placeholder'=>'Email','class'=>'form-control')); ?>
					<?php echo $form->error($model,'email'); ?>
		        </div>

				<?php if(Yii::app()->user->isGuest): ?>
			        <div class="form-group">
						<?php echo $form->label($model,'email_repeat',array('class'=>'sr-only')); ?>
						<?php echo $form->textField($model,'email_repeat',array('placeholder'=>'Confirm Email','class'=>'form-control')); ?>
						<?php echo $form->error($model,'email_repeat'); ?>
			        </div>
				<?php endif; ?>
                <div class="form-group">
                    <?php echo $form->label($model,'password',array('class'=>'sr-only')); ?>
                    <?php echo $form->passwordField($model,'password', array('placeholder'=>"Password", 'class'=>'form-control','autocomplete'=>"off")); ?>
                    <?php echo $form->error($model,'password'); ?>
                </div>
                <div class="form-group">
                    <?php echo $form->label($model,'password_repeat',array('class'=>'sr-only')); ?>
                    <?php echo $form->passwordField($model,'password_repeat',array('placeholder'=>'Confirm Password', 'class'=>'form-control', 'autocomplete'=>"off")); ?>
                    <?php echo $form->error($model,'password_repeat'); ?>
                </div>

		    </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?php echo $form->label($model,'newsletter_subscribe',array('id'=>'subscribe-label')); ?>
                    <?php echo $form->checkBox($model,'newsletter_subscribe',array('class'=>'checkbox')); ?>
                    <?php echo $form->error($model,'newsletter_subscribe'); ?>
                </div>
            </div>
            <div class="col-sm-offset-3 col-sm-2">
                <div class="submitblock form-group " >
                    <?php echo CHtml::submitButton('Submit', array('id'=>'btnSubmit','class'=>'btn btn-block btn-primary'));  ?>
                </div>
            </div>
        </div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
