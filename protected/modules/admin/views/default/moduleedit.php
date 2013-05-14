<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'setpromo-modal',
	'options'=>array(
		'title'=>'Set Restrictions',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'800',
		'height'=>'360',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>true,
	),
));

$this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'setdest-modal',
	'options'=>array(
		'title'=>'Set Destination Rates',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'450',
		'height'=>'400',
		'scrolling'=>'yes',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>true,
	),
));

$this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'settiers-modal',
	'options'=>array(
		'title'=>'Set Tiers',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'525',
		'height'=>'473',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>true,
	),
));
?><div class="span9">
    <div class="hero-unit">
        <h3><?php echo $this->editSectionName; ?></h3>
        <?php if (strlen($this->editSectionInstructions)>8): ?>
	        <div class="editinstructions"><?php echo $this->editSectionInstructions; ?></div>
	    <?php endif; ?>
		<?php echo $form->renderBegin(); ?>
	    <?php echo $form->renderBody();?>
        <div class="row field_label">
            <div class="span5"><?php echo CHtml::activeLabelEx($objModule,'Active:'); ?></div>
            <div class="span5"><?php
			    echo '<div class="onoff" id="'.$objModule->id.'"></div>'; //Create On/Off
			    echo CHtml::activeHiddenField($objModule,"active");
			    ?>
            </div>
        </div>
	    <?php $form->renderEnd(); ?>

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
