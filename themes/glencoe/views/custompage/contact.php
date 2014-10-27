<h1><?php echo Yii::t('tabs',$model->title) ?></h1>

<div class="row">
	<div id="custom_content">
		<?php echo $model->page;  ?>
	</div>

	<p class="col-sm-12 note"><?= Yii::t('global','Fields with {*} are required.',array('{*}'=>'<span class="required">*</span>')) ?></p>



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

		<div class="row">
            <div class="col-sm-1">
				<?php echo $form->labelEx($ContactForm,'fromName'); ?>
	        </div>
	        <div class="col-sm-5">
				<?php echo $form->textField($ContactForm,'fromName',array('disabled'=>!Yii::app()->user->isGuest)); ?>
				<?php echo $form->error($ContactForm,'fromName'); ?>
	        </div>
        </div>
        <div class="row">
            <div class="col-sm-1">
	            <?php echo $form->labelEx($ContactForm,'fromEmail'); ?>
	        </div>
	        <div class="col-sm-5">
	            <?php echo $form->textField($ContactForm,'fromEmail',array('disabled'=>!Yii::app()->user->isGuest)); ?>
	            <?php echo $form->error($ContactForm,'fromEmail'); ?>
	        </div>

		</div>


		<div class="row">
			<div class="col-sm-1">
				<?php echo $form->labelEx($ContactForm,'contactSubject'); ?>
			</div>
			<div class="col-sm-5">
				<?php echo $form->textField($ContactForm,'contactSubject',array('size'=>60,'maxlength'=>128)); ?>
				<?php echo $form->error($ContactForm,'contactSubject'); ?>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-1">
				<?php echo $form->labelEx($ContactForm,'contactBody'); ?>
			</div>
			<div class="col-sm-9">
				<?php echo $form->textArea($ContactForm,'contactBody',array('rows'=>4, 'cols'=>180)); ?>
				<?php echo $form->error($ContactForm,'contactBody'); ?>
			</div>
		</div>

		<?php if(_xls_show_captcha('contactus') && CCaptcha::checkRequirements()): ?>
		<div class="row">
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

		<div class="row">
	        <div class="col-sm-8 submitblock" >
	            <div id="submitSpinner" style="display:none"><?php
				    echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif',
				                Yii::t('global','wait gif'))?>
	            </div>
			    <?php echo CHtml::submitButton('Submit');  ?>

	        </div>
	     </div>



<?php $this->endWidget(); ?>


	<div class="carousel col-sm-9">
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