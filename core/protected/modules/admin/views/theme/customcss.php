
<div class="span9">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'custompage',
	'enableClientValidation'=>true,
	)); ?>
    <div class="hero-unit">
        <h3><?php echo $this->editSectionName; ?></h3>
        <div class="editinstructions"><?= $this->editSectionInstructions; ?></div>

	    <?php echo $form->textArea($model, 'page'); ?>


    </div>

	<div class="row">
		<div class="span11">
            <div class="row">
	            <P></P>
		        <p class="pull-right">
					<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'label'=>'Save',
					'type'=>'primary',
					'size'=>'large',
				)); ?>
				</p>
	        </div>

		</div>
	</div>
</div>
<?php $this->endWidget(); ?>