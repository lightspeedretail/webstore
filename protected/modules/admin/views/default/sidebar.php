<div class="span9">
    <div class="hero-unit">
        <h3><?php echo $this->editSectionName; ?></h3>
        <div class="editinstructions"><?php echo $this->editSectionInstructions; ?></div>
		<?php echo CHtml::beginForm(); ?>
        <div class="row">
            <div class="span4"><?php echo CHtml::activeLabelEx($model,'Active:'); ?></div>
            <div class="span5"><?php
						echo '<div class="onoff" id="'.$model->id.'"></div>'; //Create On/Off
						echo CHtml::activeHiddenField($model,"active");

				?></div>
            <div class="span3"><?php echo CHtml::activeLabelEx($model,'Make this sidebar visible',array('class'=>'helpertext')); ?></div>
        </div>

        <p class="pull-right">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'label'=>'Save',
			'type'=>'primary',
			'size'=>'large',
		)); ?>
        </p>
		<?php echo CHtml::endForm(); ?>
    </div>

</div>
