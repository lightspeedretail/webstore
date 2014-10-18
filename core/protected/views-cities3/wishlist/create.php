<h1><?php
	if ($model->id >0)
		echo Yii::t('global','Editing Wish List').": ".$model->registry_name;
	else
		echo Yii::t('global','Create a new Wish List');
		?></h1>

<?php
/* Create a new wish list form. We use the Checkout ID to reuse our CSS formatting */
?><div id="checkout"">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wishlistdisplay',
	'enableClientValidation'=>true,
	'focus'=>array($model,'registry_name'),
));
	if ($model->id > 0) {
		echo $form->hiddenField($model,'gift_code');
		echo $form->hiddenField($model,'id');
	}
	?>
        <div class="row-fluid">
				<?php echo $form->labelEx($model,'registry_name'); ?>
				<?php echo $form->textField($model,'registry_name'); ?>
				<?php echo $form->error($model,'registry_name'); ?>
        </div>
	    <div class="row-fluid">
			<?php echo $form->label($model,'registry_description'); ?>
			<?php echo $form->textArea($model,'registry_description'); ?>
			<?php echo $form->error($model,'registry_description'); ?>
	    </div>
		<div class="row-fluid">
			<?php echo $form->label($model,'event_date'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'model' => $model,
				'attribute' => 'event_date',
				'options' => array(
					'showAnim' => 'fold',
					'dateFormat' => 'yy-mm-dd', // save to db format
					'altField' => '#self_pointing_id',
					'altFormat' => _xls_convert_date_to_js(_xls_get_conf('DATE_FORMAT','Y-m-d')), // show to user format
				),
				'htmlOptions' => array(
					'style' => 'height:20px;'
				),
			));	?>
			<?php echo $form->error($model,'event_date'); ?>
	    </div>
        <div class="row-fluid spaceafter">
	            <?php echo $form->labelEx($model,'visibility'); ?>
		        <?php echo $form->radioButtonList($model,'visibility',$model->getVisibilities(), array('separator'=>'')); ?>
				<?php echo $form->error($model,'visibility'); ?>
        </div>
        <div class="row-fluid">
                <label>Allow buyers to ship my wish list items directly to me at:</label>
	            <?php echo $form->dropDownList($model,'ship_option',$model->getShippingOptions()); ?>
				<?php echo $form->error($model,'ship_option'); ?>
        </div>
		<div class="row-fluid spaceafter">
			<label>As wish list items are purchased:</label>
	        <?php echo $form->radioButtonList($model,'after_purchase',$model->getAfterPurchase(), array('separator'=>'')); ?>
			<?php echo $form->error($model,'after_purchase'); ?>
        </div>

	<?php if ($model->id > 0): ?>
	    <div class="row-fluid rememberMe">
		    <?php echo $form->checkBox($model,'deleteMe',array(
		    'onclick'=>'$("#btnSubmit").val("'. Yii::t('global','DELETE THIS WISHLIST').'"),
		    $("#btnSubmit").addClass("btnDelete")
		    ')); ?>
			<?php echo $form->labelEx($model,'deleteMe'); ?>
			<?php echo $form->error($model,'deleteMe'); ?>
	    </div>
	<?php endif; ?>

        <div class="submitblock" >
			<?php echo CHtml::submitButton('Submit', array('id'=>'btnSubmit'));  ?>
        </div>



	<?php $this->endWidget(); ?>
</div><!-- form -->
