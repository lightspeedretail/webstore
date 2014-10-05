<div id="contact-page" class="row-fluid">

		<h1><?php echo Yii::t('tabs',$model->title) ?></h1>

		<div id="custom-page-content">
			<?php echo $model->page;  ?>
		</div>

		<div class="clearfix spaceafter"></div>

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

		<div class="row-fluid">
			<div class="span6">
				<?php echo $form->labelEx($ContactForm,'fromName'); ?>
				<?php echo $form->textField($ContactForm,'fromName',array('disabled'=>!Yii::app()->user->isGuest)); ?>
				<?php echo $form->error($ContactForm,'fromName'); ?>
			</div>
			<div class="span6">
				<?php echo $form->labelEx($ContactForm,'fromEmail'); ?>
				<?php echo $form->textField($ContactForm,'fromEmail',array('disabled'=>!Yii::app()->user->isGuest)); ?>
				<?php echo $form->error($ContactForm,'fromEmail'); ?>
			</div>

		</div>

		<div class="row-fluid">
			<?php echo $form->labelEx($ContactForm,'contactSubject'); ?>
			<?php echo $form->textField($ContactForm,'contactSubject',array('size'=>60,'maxlength'=>128)); ?>
			<?php echo $form->error($ContactForm,'contactSubject'); ?>
		</div>

		<div class="row-fluid">
			<?php echo $form->labelEx($ContactForm,'contactBody'); ?>
			<?php echo $form->textArea($ContactForm,'contactBody',array('rows'=>4, 'cols'=>180)); ?>
			<?php echo $form->error($ContactForm,'contactBody'); ?>
		</div>

		<?php if(_xls_show_captcha('contactus') && CCaptcha::checkRequirements()): ?>
			<div class="row-fluid">
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
			<div id="submitSpinner" style="display:none"><?php
				echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?></div>
			<?php echo CHtml::submitButton('Submit');  ?>

		</div>



		<?php $this->endWidget(); ?>


		<div class="carousel span9">
			<?php
			//Not sure why you would have a carousel on the contact us form, but if you do have something defined...
			$this->widget('ext.JCarousel.JCarousel', array(
				'dataProvider' => $model->taggedProducts(),
				'thumbUrl' => '$data->SliderImage',
				'imageUrl' => '$data->Link',
				'emptyText'=>'',
				'altText' => '$data->title',
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