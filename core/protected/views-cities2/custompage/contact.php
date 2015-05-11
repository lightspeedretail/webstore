<div id="contactFormContainer">	


<h1><?php echo Yii::t('tabs',$model->title) ?></h1>

<div class="col-sm-12">
	
	<div class="contactNote"><?php echo $model->page;  ?></div>

	<p class="note"><?= Yii::t('global','Fields with {*} are required.',array('{*}'=>'<span class="required">*</span>')) ?></p>



	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'contact-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
			'beforeValidate'=>"js:function(form) {
            $(\"#submitSpinner\").toggle();
            return true;
	        }",
			'afterValidate'=>"js:function(form, data, hasError) {
            if(hasError) {
                $(\"#submitSpinner\").toggle();
                return false;
            } else return true;
            }",
		),
	)); ?>






	<?php echo $form->errorSummary($ContactForm); ?>



	<div class="col-sm-5">

		<div>
			<?php echo $form->labelEx($ContactForm,'fromName'); ?>
			<?php echo $form->textField($ContactForm,'fromName',array('disabled'=>!Yii::app()->user->isGuest)); ?>
			<?php echo $form->error($ContactForm,'fromName'); ?>
		</div>

		<div>
			<?php echo $form->labelEx($ContactForm,'fromEmail'); ?>
			<?php echo $form->textField($ContactForm,'fromEmail',array('disabled'=>!Yii::app()->user->isGuest)); ?>
			<?php echo $form->error($ContactForm,'fromEmail'); ?>
		</div>


		<div>
			<?php echo $form->labelEx($ContactForm,'contactSubject'); ?>
			<?php echo $form->textField($ContactForm,'contactSubject',array('size'=>60,'maxlength'=>128)); ?>
			<?php echo $form->error($ContactForm,'contactSubject'); ?>
		</div>

	</div>



	<div class="col-sm-6">
		<?php echo $form->labelEx($ContactForm,'contactBody'); ?>
		<?php echo $form->textArea($ContactForm,'contactBody',array('rows'=>7, 'cols'=>220)); ?>
		<?php echo $form->error($ContactForm,'contactBody'); ?>



		<?php if(_xls_show_captcha('contactus') && CCaptcha::checkRequirements()): ?>


			<div class="">
				<?php echo $form->labelEx($ContactForm,'verifyCode'); ?>
				<div>
					<?php $this->widget('CCaptcha'); ?>
					<?php echo $form->textField($ContactForm,'verifyCode'); ?>
				</div>
				<div class="hint">Please enter the letters as they are shown in the image above.
					<br/>Letters are not case-sensitive.</div>
				<?php echo $form->error($ContactForm,'verifyCode'); ?>
			</div>


		<?php endif; ?>

		<div class="submitblock" >
			<div id="submitSpinner" style="display:none">
				<?=
					CHtml::image(
						Yii::app()->getBaseUrl(true) . '/images/wait_animated.gif'
					)
				?>
			</div>
			<?=
				CHtml::submitButton(
					Yii::t(
						'forms',
						'Submit'
					)
				);
			?>
		</div>

	</div>

</div> <!--end of contact-form -->


<?php $this->endWidget(); ?>


	<div class="carousel col-sm-9">
		<?php
		//Not sure why you would have a carousel on the contact us form, but if you do have something defined...
			$this->widget('ext.JCarousel.JCarousel', array(
			'dataProvider' => $model->taggedProducts(),
			'thumbUrl' => '$data->SliderImage',
			'imageUrl' => '$data->Link',
			'emptyText'=>'',
			'titleText' => '$data->title',
			'captionText' => '$data->title."<br>"._xls_currency($data->Price)',
			'target' => 'do-not-delete-this',
			'wrap' => 'circular',
			'visible' => true,
			'skin' => 'slider',
			'clickCallback'=>'window.location.href=itemSrc;'
		)); ?>
	</div>
</div>