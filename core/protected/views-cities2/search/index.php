<div class="row">
	<h1 id="advancedSearchHeader"><?php echo Yii::t('global','Advanced Search'); ?></h1>

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'advancedsearch',
		'enableClientValidation'=>true,
		'focus'=>array($model,'q'),
		));
	?>
	<div class="row">
	    <div class="row">
	        <div class="col-sm-2">
		        <?php echo $form->label($model,'q'); ?>
		    </div>
		    <div class="col-sm-3" style="margin-right: 20px;">
		        <?php echo $form->textField($model,'q'); ?>
		        <?php echo $form->error($model,'q'); ?>
	        </div>
		    <div class="col-sm-2">
			    <?php echo $form->label($model,'cat'); ?>
			</div>
		    <div class="col-sm-3">
			    <?php echo $form->dropDownList($model,'cat',Category::getTopLevelSearch(),array('prompt'=>Yii::t('global','All Categories'))); ?>
			    <?php echo $form->error($model,'cat'); ?>
		    </div>
	    </div>

	    <div class="row">
	        <div class="col-sm-2">
		        <?php echo $form->label($model,'startprice'); ?>
		    </div>
		    <div class="col-sm-3" style="margin-right: 20px;">
		        <?php echo $form->textField($model,'startprice'); ?>
		        <?php echo $form->error($model,'startprice'); ?>
	        </div>
	        <div class="col-sm-2">
		        <?php echo $form->label($model,'endprice'); ?>
		    </div>
		    <div class="col-sm-3">
		        <?php echo $form->textField($model,'endprice'); ?>
		        <?php echo $form->error($model,'endprice'); ?>
	        </div>
	    </div>

	</div>
		<?php echo CHtml::submitButton(Yii::t('global','Search'), array('id'=>'btnSubmit'));  ?>
	</fieldset>

	<?php $this->endWidget(); ?>

</div>