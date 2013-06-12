<div class="span9">

    <h3>Downloaded Orders</h3>
	<div class="span8">
	    <div class="editinstructions">
			<?php echo Yii::t('admin','To force an order to download again to LightSpeed, click the check under D/L (Download) and set to Not Downloaded. The order will be re-downloaded on the next LightSpeed download attempt.'); ?>
	    </div>
    </div>
        <div class="clearfix search">
            <div class="pull-right">
				<?php echo CHtml::beginForm($this->createUrl('databaseadmin/orders'),'get'); ?>
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
			    'headerHtmlOptions' => array('style' => 'span1'),
		    ),
		    array(
			    'name' => 'datetime_cre',
			    'header'=>'Order Date',
			    'headerHtmlOptions' => array('style' => 'span1'),
		    ),
		    array(
			    'name' => 'customer.fullname',
			    'header'=>'Customer',
			    'headerHtmlOptions' => array('style' => 'span1'),
		    ),
		    array(
			    'name' => 'total',
			    'sortable'=>false,
			    'headerHtmlOptions' => array('style' => 'span1'),
			    'value'=>'_xls_currency($data->total)',
		    ),
		    array(
			    'name' => 'shipping.shipping_module',
			    'headerHtmlOptions' => array('style' => 'span1'),

		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'downloaded',
			    'header'=>'D/L',
			    'sortable'=>false,
			    'headerHtmlOptions' => array('style' => 'span1'),
			    'editable' => array(
				    'type' => 'select',

				    'url' => $this->createUrl('databaseadmin/update'),
				    'placement' => 'left',
				    'source' => array(1=>'Downloaded',0=>'Not Downloaded'),
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
					    'display' => 'js: function(value, sourceData) {
							 if(value=="1") label = "✓"; else label = "off";
						     var escapedValue = $("<div>").text(label).html();
						     $(this).html(escapedValue);
						}',
					    'success' => 'js:function(response, newValue) {
										if(response=="success")
											$.fn.yiiGridView.update("user-grid");
									}',
				    ),


			    )
		    ),
		),
    ));
	?>


</div>




