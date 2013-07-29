<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'setpromo-modal',
	'options'=>array(
		'title'=>'Manually mark order as paid',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'600',
		'height'=>'353',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>true,
	),
));?>
<div class="span9">

    <h3>Unpaid Orders</h3>
    <div class="span8">
        <div class="editinstructions">
			<?php echo Yii::t('admin','These orders result from an attempted Checkout where payment was not completed. In 99% of cases, this is a result of a declined credit card that the customer does not resolve. You may use this information to follow up with customers if you wish. For an order where a payment was made but not recorded, this can be set here.'); ?>
        </div>
    </div>
    <div class="clearfix search">
        <div class="pull-right">
			<?php echo CHtml::beginForm($this->createUrl('databaseadmin/unpaid'),'get'); ?>
			<?php echo CHtml::textField('q',Yii::app()->getRequest()->getQuery('q'),array('id'=>'xlsSearch','placeholder'=>'SEARCH...','submit'=>'')); ?>
			<?php echo CHtml::endForm(); ?>
        </div>
    </div>

	<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'user-grid',
	'itemsCssClass' => 'table-bordered',
	'dataProvider' => $model->searchAdmin(),
	'summaryText' => '',
	'columns'=>array(
		array(
			'name' => 'id_str',
			'header'=>'Web Order #',
			'type' => 'raw',
			'value'=>'"<a href=\"#\" id=\"".$data->payment->id."\" class=\"basic\">".$data->id_str."</a>"',
			'headerHtmlOptions' => array('style' => 'span1'),
		),
		array(
			'name' => 'customer.fullname',
			'header'=>'Customer',
			'headerHtmlOptions' => array('style' => 'span1'),
		),

		array(
			'header'=>'Items',
			'sortable'=>false,
			'name' => 'item_count',
			'headerHtmlOptions' => array('style' => 'span1'),

		),
		array(
			'name' => 'shipping.shipping_sell',
			'header'=>'Shipping',
			'sortable'=>false,
			'headerHtmlOptions' => array('style' => 'span1'),
			'value'=>'_xls_currency($data->shipping_sell)',
		),

		array(
			'name' => 'taxCode.code',
			'header'=>'Tax',
			'sortable'=>false,
			'headerHtmlOptions' => array('style' => 'span1'),
			'value'=>'_xls_currency($data->tax_total)." (".$data->tax_code.")"',
		),
		array(
			'name' => 'total',
			'sortable'=>false,
			'headerHtmlOptions' => array('style' => 'span1'),
			'value'=>'_xls_currency($data->total)',
		),

	),
));
	?>


</div>




