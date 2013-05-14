<div class="editorder" xmlns="http://www.w3.org/1999/html">

    <p><strong>For download failures:</strong> The most common reason for an order failing to download is the order contains an "Orphaned Product", a product which was deleted from LightSpeed but remains available on Web Store for ordering. LightSpeed attempts to find the item during download and fails.</p><p>The chart below shows the items on this order. Verify each product code can be found in LightSpeed. You can delete a missing item off the order here, <strong>but note the customer has already been charged for it</strong>. Once the order is downloaded into LightSpeed, you can take additional action such as adding a replacement product. To prevent this from happening again, the orphaned product should be removed from Web Store.</p>

	<?php $form=$this->beginWidget('CActiveForm', array(
	'enableAjaxValidation'=>true,
	'id'=>'editpending',
	)); ?>

	<?php
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'menu-grid',
		'dataProvider'=>$model->search(),
		'summaryText'=>'',
		'columns'=>array(
			array(
				'name'=>'code',
				'sortable'=>false,
				'htmlOptions'=>array("class"=>"span3"),
			),
			array(
				'name'=>'description',
				'sortable'=>false,
				'htmlOptions'=>array("class"=>"span4"),
			),
			array(
				'name'=>'qty',
				'sortable'=>false,
				'htmlOptions'=>array("class"=>"span1"),
			),
			array(
				'name'=>'deleteMe',
				'header'=>'Delete this item',
				'value'=>'CHtml::checkBox("cid[]",null,array("value"=>$data->id,"id"=>"cid_".$data->id))',
				'type'=>'raw',
				'htmlOptions'=>array("class"=>"span2",'onclick'=>'js:if($(this).find("input:checkbox").is(":checked")) $(this).parent().addClass(\'bgdelete\'); else $(this).parent().removeClass(\'bgdelete\');',),
			),
		),
	)); ?>
	<script>
	    function reloadGrid(data) {
	        $.fn.yiiGridView.update('menu-grid');
	    }
	</script>
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
