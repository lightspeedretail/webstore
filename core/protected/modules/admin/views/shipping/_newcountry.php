<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'newcountry',
	'enableClientValidation'=>true,
	'focus'=>array($model,'start_price'),
	));
	$model->setScenario('create');
?>
<div class="row">
    <div class="span5">
		<label>Full Country Name</label>
		<?php echo $form->textField($model,'country'); ?>
		<?php echo $form->error($model,'country'); ?>
    </div>
</div>
<div class="row">
    <div class="span5">
        <label>Country Abbreviation</label>
		<?php echo $form->textField($model,'code'); ?>
		<?php echo $form->error($model,'code'); ?>
    </div>
</div>
<div class="row">
    <div class="span5">
		<?php echo $form->labelEx($model,'region'); ?>
		<?php echo $form->dropDownList($model,'region',array(
	    'AF'=>'Africa',
	    'AN'=>'Antartica',
	    'AS'=>'Asia',
	    'AU'=>'Australia',
	    'EU'=>'Europe',
	    'LA'=>'Latin/South America',
	    'NA'=>'North America')); ?>
		<?php echo $form->error($model,'region'); ?>
    </div>
</div>

<div class="row">
    <div class="pull-right" >
	    <?php echo CHtml::ajaxSubmitButton(Yii::t('global','Save'),
		    array('shipping/newcountry'),
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
