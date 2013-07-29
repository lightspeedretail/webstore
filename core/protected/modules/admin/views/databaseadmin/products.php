<div class="span9">

    <h3>Products</h3>
	<div class="span8">
	    <div class="editinstructions">
			<?php echo Yii::t('admin','Please use extreme caution with this option, and contact technical support for assistance. Use this screen to make changes for products which are orphaned. You can also view pending orders including a product to determine issues with inventory levels. Note deleting a product will remove it from all wish lists.'); ?>
	    </div>
    </div>
        <div class="clearfix search">
            <div class="pull-right">
				<?php echo CHtml::beginForm($this->createUrl('databaseadmin/products'),'get'); ?>
				<?php echo CHtml::textField('q',Yii::app()->getRequest()->getQuery('q'),array('id'=>'xlsSearch','placeholder'=>'SEARCH...','submit'=>'')); ?>
				<?php echo CHtml::endForm(); ?>
            </div>
        </div>

	    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'product-grid',
	    'itemsCssClass' => 'table-bordered',
	    'dataProvider' => $model->searchAdmin(),
		'summaryText' => '',
	    'columns'=>array(
		    array(
			    'name' => 'id',
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'code',
			    'editable' => array(
				    'title'=>'To Delete, erase entry and click check',
				    'url' => $this->createUrl('databaseadmin/products'),
				    'placement' => 'right',
				    'options'=>array(
					    'onblur' => 'cancel',
					    'showbuttons' => true,
					    'success' => 'js:function(response, newValue) {
									if(response!="delete")
									{
										$("#alert-box").html(response);
										$("#alert-box").dialog("open");
									}
									$.fn.yiiGridView.update("product-grid");

								}',
				    ),
			    )
		    ),
		    array(
			    'name' => 'current',
			    'header'=>'Cur',
			    'value'=>'($data->current==0?"N":"Y")',
		    ),
		    array(
			    'name' => 'web',
			    'value'=>'($data->web==0?"N":"Y")',
		    ),
		    array(
			    'name' => 'master_model',
			    'header'=>'Mstr',
			    'value'=>'($data->master_model==0?"N":"Y")',
		    ),
		    array(
			    'name' => 'parent',
		    ),
		    array(
			    'name' => 'inventory',
		    ),
		    array(
			    'name' => 'inventory_total',
			    'header'=>'iTotal',
		    ),
		    array(
			    'name' => 'inventory_reserved',
			    'header'=>'iRsrv',
		    ),
		    array(
			    'name' => 'inventory_avail',
			    'header'=>'iAvail',
		    ),
		    array(
			    'name' => 'modified',
		    ),



		),
    ));
	?>


</div>




