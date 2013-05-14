<div class="span12 clickbar" onclick="$('#OrderLookup').slideToggle('fast');"><?= Yii::t('global','Order Lookup')?></div>
<div class="containers" id="OrderLookup" style="display:hidden;">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>get_class($this),
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
)); ?>

    <div class="row-fluid">
		<?php echo $form->labelEx($model,'emailPhone'); ?>
		<?php echo $form->textField($model,'emailPhone'); ?>
		<?php echo $form->error($model,'emailPhone'); ?>
    </div>

    <div class="row-fluid">
		<?php echo $form->labelEx($model,'orderId'); ?>
		<?php echo $form->textField($model,'orderId'); ?>
		<?php echo $form->error($model,'orderId'); ?>
	</div>

	<?php echo CHtml::submitButton('Search'); ?>

	<?php $this->endWidget(); ?>

</div><!-- form -->


