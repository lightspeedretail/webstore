<div class="row-fluid">
	<h1><?php echo Yii::t('global','Advanced Search'); ?></h1>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'advancedsearch',
		'enableClientValidation'=>true,
		'focus'=>array($model,'q'),
		));
	?>
	<div class="row-fluid">
	    <div class="row-fluid">
	        <div class="span5">
		        <?php echo $form->label($model,'q'); ?>
		        <?php echo $form->textField($model,'q'); ?>
		        <?php echo $form->error($model,'q'); ?>
	        </div>
	    </div>

	    <div class="row-fluid">
	        <div class="span4">
		        <?php echo $form->label($model,'startprice'); ?>
		        <?php echo $form->textField($model,'startprice'); ?>
		        <?php echo $form->error($model,'startprice'); ?>
	        </div>
	        <div class="span5">
		        <?php echo $form->label($model,'endprice'); ?>
		        <?php echo $form->textField($model,'endprice'); ?>
		        <?php echo $form->error($model,'endprice'); ?>
	        </div>
	    </div>

	    <div class="row-fluid">
	        <div class="span9">
		        <?php echo $form->label($model,'cat'); ?>
		        <?php echo $form->dropDownList($model,'cat',Category::getTopLevelSearch(),array('prompt'=>Yii::t('global','All Categories'))); ?>
		        <?php echo $form->error($model,'cat'); ?>
	        </div>
	    </div>
	</div>
		<?php echo CHtml::submitButton(Yii::t('global','Search'), array('id'=>'btnSubmit'));  ?>
	</fieldset>

	<?php $this->endWidget(); ?>

</div>