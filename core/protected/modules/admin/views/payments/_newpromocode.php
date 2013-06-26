<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'newpromocode',
	'enableClientValidation'=>true,
	'focus'=>array($model,'code'),
	));
	$model->setScenario('create');
?>
<div class="row">
    <div class="span3">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code'); ?>
		<?php echo $form->error($model,'code'); ?>
    </div>
    <div class="span3">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->dropDownList($model,'type',array(PromoCode::Percent=>'%',PromoCode::Currency=>'$')); ?>
		<?php echo $form->error($model,'amount'); ?>
    </div>
</div>


<div class="row">
    <div class="span2">
		<?php echo $form->labelEx($model,'qty_remaining'); ?>
		<?php echo $form->textField($model,'qty_remaining'); ?>
		<?php echo $form->error($model,'qty_remaining'); ?>
    </div>
	<div class="span2">
		<?php echo $form->labelEx($model,'threshold'); ?>
		<?php echo $form->textField($model,'threshold'); ?>
		<?php echo $form->error($model,'threshold'); ?>
    </div>
    <div class="span2 tip">
       Second row items are optional. Other fields can be adjusted after saving.
    </div>
</div>

<div class="row">
    <div class="pull-right" >
	    <?php echo CHtml::ajaxSubmitButton(Yii::t('global','Save'),
		    array('payments/newpromo'),
		    array(
			    'type'=>"POST",
			    'success'=>'js:function(data) {
	                if (data=="success")
	                window.location.reload();
                 }',
		    ),array('id'=>'btnSubmit','class'=>'btn btn-primary btn-small')); ?>
    </div>
</div>

<?php $this->endWidget(); ?><!-- form -->
