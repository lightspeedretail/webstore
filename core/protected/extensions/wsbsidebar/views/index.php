
<div class="span12 clickbar" onclick="$('#GenericBar').slideToggle('fast');"><?= Yii::t('global','Generic Sidebar')?></div>

<div class="containers" id="GenericBar" style="display:hidden;">
   This is a sample sidebar.

	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>get_class($this),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    <div class="row-fluid">
		<?php echo $form->labelEx($model,'strExampleText'); ?>
		<?php echo $form->textField($model,'strExampleText'); ?>
		<?php echo $form->error($model,'strExampleText'); ?>
    </div>

    <div class="row-fluid">
		<?php echo $form->labelEx($model,'strOtherText'); ?>
		<?php echo $form->textField($model,'strOtherText'); ?>
		<?php echo $form->error($model,'strOtherText'); ?>
    </div>

	<?php echo CHtml::submitButton('Submit'); ?>

	<?php $this->endWidget(); ?>

</div>


