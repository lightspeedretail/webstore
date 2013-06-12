<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'setpromo-modal',
	'options'=>array(
		'title'=>'Edit Pending Order',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'800',
		'height'=>'353',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>true,
	),
));?>
<div class="span9">

    <h3>Pending to Download Orders</h3>
    <div class="span8">
        <div class="editinstructions">
			<?php echo Yii::t('admin','These orders are ready to be downloaded into LightSpeed. Editing provided for troubleshooting purposes only. Please use caution when using these options, and consult our online documentation and technical support resources for assistance. <strong>Note: missing tax codes will prevent an order from downloading.</strong>'); ?>
        </div>
    </div>
    <div class="clearfix search">
        <div class="pull-right">
			<?php echo CHtml::beginForm($this->createUrl('databaseadmin/pending'),'get'); ?>
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
				'value'=>'"<a href=\"#\" id=\"".$data->id."\" class=\"basic\">".$data->id_str."</a>"',
				'headerHtmlOptions' => array('class' => 'span2'),
			),
			array(
				'name' => 'customer.fullname',
				'header'=>'Customer',
				'headerHtmlOptions' => array('class' => 'span4'),
			),

			array(
				'header'=>'Items',
				'sortable'=>false,
				'name' => 'item_count',
				'headerHtmlOptions' => array('class' => 'span1'),

			),
			array(
				'name' => 'shipping.shipping_sell',
				'header'=>'Shipping',
				'sortable'=>false,
				'headerHtmlOptions' => array('class' => 'span2'),
				'value'=>'_xls_currency($data->shipping_sell)',
			),

//			array(
//				'name' => 'taxCode.code',
//				'header'=>'Tax',
//				'sortable'=>false,
//				'headerHtmlOptions' => array('style' => 'span1'),
//				'value'=>'_xls_currency($data->tax_total)." (".$data->tax_code.")"',
//			),
			array(
				'class' => 'editable.EditableColumn',
				'name' => 'tax_code_id',
				'headerHtmlOptions' => array('class' => 'span2'),
				'sortable'=>false,
				'editable' => array(
					'type' => 'select',
					'url' => $this->createUrl('databaseadmin/update'),
					'source' => CHtml::listData(TaxCode::model()->findAll(), 'lsid', 'code'),
					'options'=>array(
						'onblur' => 'submit',
						'showbuttons' => false,
						'emptytext' => 'MISSING!',
					),


				)
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




