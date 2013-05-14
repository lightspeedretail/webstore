<?php
/* This is the contents of the modal login dialog box. It's a Render Partial since we don't need the full HTML wrappers */
?><div class="destinations">
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'destinations',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>false,
));
	?>

		<div class="tip">Define rates for your destinations. Use negative values to offer "free allowance". For example, -5 base and 5 each would charge $0 shipping one item and $5 for shipping two items, $10 for three, etc.</div>
    <table>
        <tr><th>Destination</th><th>Base<br>Charge</th><th>$ Each</th></tr>
		<?php foreach($model as $i=>$item): ?>
        <tr>
            <td class="destlabel"><?php echo Country::CodeByIdAny($item->country)."/".State::CodeByIdAny($item->state); ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]base_charge"); ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]ship_rate"); ?></td>
        </tr>
		<?php endforeach; ?>
    </table>

	<br clear="both">

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

