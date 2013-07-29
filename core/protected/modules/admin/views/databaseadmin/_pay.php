<div class="editorder" xmlns="http://www.w3.org/1999/html">

    <p>To mark an order as paid manually, enter the payment details and click Save. The order will be downloaded to LightSpeed on the next Download Orders attempt.</p>

	<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>true,
	'action'=>$this->createUrl('databaseadmin/pay',array('id'=>$model->id)),
	'id'=>'editpending',
	)); ?>
	<?php echo $form->hiddenField($objCart,'id'); ?>
    <div class="row">
		<div class="span3">
			<label>Paid Status</label>
		</div>
        <div class="span3">
			<?php echo $form->dropDownList($objCart,'cart_type',array(CartType::awaitpayment=>'Unpaid',CartType::order=>'Paid')); ?>
		</div>
    </div>

	<div class="row">
		<div class="span3">
			<?php echo $form->labelEx($model,'payment_module'); ?>
		</div>
        <div class="span3">
			<?php echo $form->dropDownList($model,'payment_module',
			CHtml::listData(Modules::model()->findAllByAttributes(array('category'=>'payment','active'=>1),array('order'=>'name')), 'module', 'name'));
			?>
		</div>
    </div>

    <div class="row">
        <div class="span3">
			<?php echo $form->labelEx($model,'payment_data'); ?>
        </div>
        <div class="span3">
			<?php echo $form->textField($model,'payment_data'); ?>
        </div>
    </div>

    <div class="row">
        <div class="span3">
			<?php echo $form->labelEx($model,'payment_amount'); ?>
        </div>
        <div class="span3">
			<?php echo $form->textField($model,'payment_amount'); ?>
        </div>
    </div>


</div>
   <div class="row pagination-centered">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
	    'htmlOptions'=>array('id'=>'buttonSavePCR'),
	    'label'=>'Save',
	    'type'=>'primary',
	    'size'=>'small',
	    )); ?>
    </div>
	<?php $this->endWidget(); ?>
