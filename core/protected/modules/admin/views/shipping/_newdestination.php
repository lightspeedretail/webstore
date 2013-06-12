<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'newdestination',
	'enableClientValidation'=>true,
	'focus'=>array($model,'start_price'),
	));
	$model->setScenario('newdestination');
?>
<div class="row">
    <div class="span5">
		<?php echo $form->labelEx($model,'country'); ?>
		<?php echo $form->dropDownList($model,'country',Country::getCountriesForTaxes(false),array(
		'ajax' => array(
			'type'=>'GET',
			'url'=>CController::createUrl('shipping/Destinationstatesadd'),
			'update'=>'#'.CHtml::activeId($model,'state'),
			'data' => 'js:{"'.'country_id'.'": $("#'.CHtml::activeId($model,'country').' option:selected").val()}',
		))); ?>
		<?php echo $form->error($model,'state'); ?>
    </div>
</div>
<div class="row">
    <div class="span5">
		<?php echo $form->labelEx($model,'state'); ?>
		<?php echo $form->dropDownList($model,'state',State::getStatesForTaxes($model->country),array(
		'prompt' =>'--',
		'ajax' => array(
			'type'=>'POST',
			'dataType'=>'json',
			'data' => 'js:{"'.'state_id'.'": $("#'.CHtml::activeId($model,'state').' option:selected").val() }',
		))); ?>
		<?php echo $form->error($model,'state'); ?>
    </div>
</div>

<div class="row">

    <div class="span3"><label>Optional</label>
		<?php echo $form->labelEx($model,'zipcode1'); ?>
		<?php echo $form->textField($model,'zipcode1'); ?>
		<?php echo $form->error($model,'zipcode1'); ?>
    </div>
    <div class="span3"><label>Optional</label>
		<?php echo $form->labelEx($model,'zipcode2'); ?>
		<?php echo $form->textField($model,'zipcode2'); ?>
		<?php echo $form->error($model,'zipcode2'); ?>
    </div>
</div>

<div class="row">
    <div class="span5">
		<?php echo $form->labelEx($model,'taxcode'); ?>
		<?php echo $form->dropDownList($model,'taxcode',CHtml::listData(TaxCode::model()->findAll(array('order'=>'list_order')),'lsid','code')); ?>
		<?php echo $form->error($model,'taxcode'); ?>
    </div>
</div>

<div class="row">
    <div class="pull-right" >
	    <?php echo CHtml::ajaxSubmitButton(Yii::t('global','Save'),
		    array('shipping/newdestination'),
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
