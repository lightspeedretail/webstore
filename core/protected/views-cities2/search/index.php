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
	        <div class="col-sm-4 term">
		        <?php echo $form->label($model,'q'); ?>
		        <?php echo $form->textField($model,'q'); ?>
		        <?php echo $form->error($model,'q'); ?>
	        </div>
		    <div class="col-sm-4 cat">
			    <?php echo $form->label($model,'cat'); ?>
			    <?php echo $form->dropDownList($model,'cat',Category::getTopLevelSearch(),array('prompt'=>Yii::t('global','All Categories'))); ?>
			    <?php echo $form->error($model,'cat'); ?>
		    </div>
	    </div>

	    <div class="row">
		    <div class="col-sm-4 startprice">
			    <?php echo $form->label($model,'startprice'); ?>
		        <?php echo $form->textField($model,'startprice'); ?>
		        <?php echo $form->error($model,'startprice'); ?>
	        </div>
		    <div class="col-sm-4 endprice">
			    <?php echo $form->label($model,'endprice'); ?>
		        <?php echo $form->textField($model,'endprice'); ?>
		        <?php echo $form->error($model,'endprice'); ?>
	        </div>
	    </div>

	</div>
		<?php echo CHtml::submitButton(Yii::t('global','SEARCH'), array('id'=>'btnSubmit'));  ?>
	</fieldset>

	<?php $this->endWidget(); ?>

</div>