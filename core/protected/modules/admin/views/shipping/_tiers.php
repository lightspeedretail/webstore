<?php
/* This is the contents of the modal login dialog box. It's a Render Partial since we don't need the full HTML wrappers

Note we use the is_null to put the blank entries on the bottom
*/
?><div class="tier-grid">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tier-grid',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
));   ?>
    <div class="editinstructions">
		<?php echo Yii::t('admin','You can define up to 10 tiers. Use two digit decimals to ensure your values don\'t leave gaps in-between rows. Start your first line defined for 0.00 and for the last amount (the high end), you can enter 999999 to catch any high cart values.'); ?>
    </div>

    <table>
        <tr><th>Start Amount</th><th>End Amount</th><th>Rate</th></tr>
		<?php foreach($model as $i=>$item): if (!is_null($item->start_price)): ?>
        <tr>
            <td><?php echo CHtml::activeTextField($item,"[$i]start_price"); ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]end_price"); ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]rate"); ?></td>
        </tr>
		<?php endif; endforeach; ?>
	    <?php foreach($model as $i=>$item): if (is_null($item->start_price)):?>
        <tr>
            <td><?php echo CHtml::activeTextField($item,"[$i]start_price"); ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]end_price"); ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]rate"); ?></td>
        </tr>
	    <?php endif; endforeach; ?>
    </table>


    <div class="row tip">
        <div class="pull-right">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
			'htmlOptions'=>array('id'=>'buttonSavePCR'),
			'label'=>'Save',
			'type'=>'primary',
			'size'=>'small',
		)); ?>
        </div>
    </div>

	<?php $this->endWidget(); ?>
</div><!-- form -->





