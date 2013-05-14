<h1><?php echo Yii::t('global',$objCustomPage->title) ?></h1>

<div class="span12">
	<?php echo $objCustomPage->page;  ?>

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






	<?php echo $form->errorSummary($model); ?>

		<div class="row-fluid">
            <div class="span6">
				<?php echo $form->labelEx($model,'fromName'); ?>
				<?php echo $form->textField($model,'fromName',array('disabled'=>!Yii::app()->user->isGuest)); ?>
				<?php echo $form->error($model,'fromName'); ?>
	        </div>
            <div class="span6">
	            <?php echo $form->labelEx($model,'fromEmail'); ?>
	            <?php echo $form->textField($model,'fromEmail',array('disabled'=>!Yii::app()->user->isGuest)); ?>
	            <?php echo $form->error($model,'fromEmail'); ?>
	        </div>

		</div>

		<div class="row-fluid">
			<?php echo $form->labelEx($model,'contactSubject'); ?>
			<?php echo $form->textField($model,'contactSubject',array('size'=>60,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'contactSubject'); ?>
		</div>

		<div class="row-fluid">
			<?php echo $form->labelEx($model,'contactBody'); ?>
			<?php echo $form->textArea($model,'contactBody',array('rows'=>4, 'cols'=>180)); ?>
			<?php echo $form->error($model,'contactBody'); ?>
		</div>

		<?php if(_xls_show_captcha('contactus') && CCaptcha::checkRequirements()): ?>
		<div class="row-fluid">
			<?php echo $form->labelEx($model,'verifyCode'); ?>
			<div>
			<?php $this->widget('CCaptcha'); ?>
			<?php echo $form->textField($model,'verifyCode'); ?>
			</div>
			<div class="hint">Please enter the letters as they are shown in the image above.
			<br/>Letters are not case-sensitive.</div>
			<?php echo $form->error($model,'verifyCode'); ?>
		</div>
		<?php endif; ?>

        <div class="span8 submitblock" >
            <div id="submitSpinner" style="display:none"><?php
			    echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?></div>
		    <?php echo CHtml::submitButton('Submit');  ?>

        </div>



<?php $this->endWidget(); ?>


	<div class="carousel span9">
		<?php
		//Not sure why you would have a carousel on the contact us form, but if you do have something defined...
		if ($dataProvider)
			$this->widget('ext.JCarousel.JCarousel', array(
			'dataProvider' => $dataProvider,
			'thumbUrl' => '$data->SliderImage',
			'imageUrl' => '$data->Link',
			'titleText' => '$data->title',
			'captionText' => '$data->title."<br>"._xls_currency($data->sell)',
			'target' => 'do-not-delete-this',
			'wrap' => 'circular',
			'visible' => true,
			'skin' => 'slider',
			'clickCallback'=>'window.location.href=itemSrc;'
		)); ?>
	</div>
</div>