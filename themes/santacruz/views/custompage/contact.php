<?php $this->layout='//layouts/column1'; ?>

<h1><?php echo Yii::t('tabs',$model->title) ?></h1>

<div id="addresshours" class="col-sm-6">
    <div class="row">
        <address class="col-sm-6">
            <?php
            echo _xls_get_conf('STORE_NAME')."<br>";
            echo _xls_get_conf('STORE_ADDRESS1')."<br>";
            echo _xls_get_conf('STORE_ADDRESS2');
            ?>
        </address>
        <div class="col-sm-6">
            <?= _xls_get_conf('EMAIL_FROM')."<br>"; ?>
            <?= _xls_get_conf('STORE_PHONE')."<br>"; ?>
        </div>
    </div>
    <h2>Store hours</h2>
    <div class="col-sm-6">
            <?php
            echo _xls_get_conf('STORE_HOURS')."<br>";
            ?>
    </div>
</div>

<div id="contact-page-content">
	<div class="row">
        <p class="col-sm-8 help-block">
            <?php echo $model->page;  ?>
            <?= Yii::t('global','Fields with {*} are required.',array('{*}'=>'<span class="required">*</span>')) ?>
        </p>

        <div class="clearfix"></div>
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
    </div>

	<?php echo $form->errorSummary($ContactForm); ?>

		<div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($ContactForm,'fromName'); ?>
                    <?php echo $form->textField($ContactForm,'fromName',array('disabled'=>!Yii::app()->user->isGuest,'class'=>'form-control')); ?>
                    <?php echo $form->error($ContactForm,'fromName'); ?>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($ContactForm,'fromEmail'); ?>
                    <?php echo $form->textField($ContactForm,'fromEmail',array('disabled'=>!Yii::app()->user->isGuest,'class'=>'form-control')); ?>
                    <?php echo $form->error($ContactForm,'fromEmail'); ?>
                </div>
            </div>
		</div>

	<div class="row">
        <div class="col-sm-8 form-group">
            <?php echo $form->labelEx($ContactForm,'contactSubject'); ?>
            <?php echo $form->textField($ContactForm,'contactSubject',array('size'=>60,'maxlength'=>128,'class'=>'form-control')); ?>
            <?php echo $form->error($ContactForm,'contactSubject'); ?>
        </div>
    </div>
    <div class="row">
	    <div class="col-sm-8 form-group">
            <?php echo $form->labelEx($ContactForm,'contactBody'); ?>
			<?php echo $form->textArea($ContactForm,'contactBody',array('rows'=>8, 'cols'=>180,'class'=>'form-control')); ?>
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
            <div id="submitSpinner" class="col-sm-2" style="display:none">
                <?php echo CHtml::image(Yii::app()->getBaseUrl(true).'/images/wait_animated.gif')?>
            </div>
            <div class="col-sm-2">
		        <?php echo CHtml::submitButton('Send', array('class'=>'btn btn-block btn-primary'));  ?>
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