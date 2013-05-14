<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'newtier',
	'enableClientValidation'=>true,
	'focus'=>array($model,'start_price'),
	));
	$model->setScenario('create');

?>
<div class="row">
    <div class="span2">
		<?php echo $form->labelEx($model,'start_price'); ?>
		<?php echo $form->textField($model,'start_price'); ?>
		<?php echo $form->error($model,'start_price'); ?>
    </div>
    <div class="span2">
		<?php echo $form->labelEx($model,'end_price'); ?>
		<?php echo $form->textField($model,'end_price'); ?>
		<?php echo $form->error($model,'end_price'); ?>
    </div>
    <div class="span2">
		<?php echo $form->labelEx($model,'rate'); ?>
		<?php echo $form->textField($model,'rate'); ?>
		<?php echo $form->error($model,'rate'); ?>
    </div>

</div>


<div class="row">
    <div class="pull-right" >
	    <?php echo CHtml::ajaxSubmitButton(Yii::t('global','Save'),
		    array('shipping/newtier'),
		    array(
			    'type'=>"POST",
			    'success'=>'js:function(data) {
	                if (data=="success")
	                window.location.reload();
	                else alert(data);
                 }',
		    ),array('id'=>'btnSubmit','class'=>'btn btn-primary btn-small')); ?>
    </div>
</div>

<?php $this->endWidget(); ?><!-- form -->
