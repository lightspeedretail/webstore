<?php
/* This is the contents of the wishlist item edit box */
?><div class="container login popupsmall">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ShareForm',
	'enableAjaxValidation'=>true,
	'focus'=>array($model,'toName'),
));
	echo $form->hiddenField($model,'code');
	?>
    <div class="row shortrow">
 		<?= Yii::t('wishlist','Share via email'); ?>
    </div>

    <div class="row midrow">
		<?php echo $form->labelEx($model,'toName'); ?>
		<?php echo $form->textField($model,'toName'); ?>
		<?php echo $form->error($model,'toName'); ?>
    </div>

    <div class="row midrow">
		<?php echo $form->labelEx($model,'toEmail'); ?>
		<?php echo $form->textField($model,'toEmail'); ?>
		<?php echo $form->error($model,'toEmail'); ?>
    </div>

	<div class="row shortrow">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment'); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row shortrow buttons">
	    <?php
	    echo CHtml::ajaxSubmitButton(Yii::t('global','Send'),
	        CHtml::normalizeUrl(array('wishlist/email','render'=>false)),
	        array(
	            'type'=>"POST",
	            'dataType'=>'json',
	            'success'=>'js:function(data) {
	                if (data.status=="success") {
	                    alert(data.message);
	                    $("#WishitemShare").dialog("close");
	                    $.ajax({url:data.url});
	                } else { for(var key in data.errormsg) {
	                    var value = data.errormsg[key];
						$("#ShareForm_"+key+"_em_").html(value);
						$("#ShareForm_"+key+"_em_").show();
					}}
	             }',
	        ),array('id'=>'btnSendWishList')); ?>

	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
