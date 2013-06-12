<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'newstate',
	'enableClientValidation'=>true,
	'focus'=>array($model,'start_price'),
	));
	$model->setScenario('create');
?>
<div class="row">
    <div class="span5">
		<label>Full State/Region Name</label>
		<?php echo $form->textField($model,'state'); ?>
		<?php echo $form->error($model,'state'); ?>
    </div>
</div>
<div class="row">
    <div class="span5">
        <label>State/Region Abbreviation</label>
		<?php echo $form->textField($model,'code'); ?>
		<?php echo $form->error($model,'code'); ?>
    </div>
</div>
<div class="row">
    <div class="span5">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->dropDownList($model,'country_id',Country::getCountriesForTaxes(false)); ?>
		<?php echo $form->error($model,'state'); ?>
    </div>
</div>

<div class="row">
    <div class="pull-right" >
	    <?php echo CHtml::ajaxSubmitButton(Yii::t('global','Save'),
		    array('shipping/newstate'),
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
