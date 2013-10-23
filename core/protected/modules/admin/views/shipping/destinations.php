<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'setdestination-modal',
		'options'=>array(
			'title'=>'Set Destinations',
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'800',
			'height'=>'353',
			'scrolling'=>'no',
			'resizable'=>false,
			'position'=>'center',
			'draggable'=>true,
		),
	));
?>
<?php $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'adddestination-modal',
		'options'=>array(
			'title'=>'Add New Destination',
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'520',
			'height'=>'385',
			'scrolling'=>'no',
			'resizable'=>false,
			'position'=>'center',
			'draggable'=>true,
		),
	));
	$this->renderPartial('_newdestination',array('model'=>$modeldestination));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<div class="span9">

    <div class="pull-right">
	<?php
	echo CHtml::ajaxButton(Yii::t('admin','Create New Destination'),
		'#',
		array(
			'type'=>"POST",
			'dataType'=>'json',
			'onClick'=>'js:jQuery($("#adddestination-modal")).dialog("open")',
		),array('id'=>'btnModalLogin','name'=>'btnModalLogin', 'class'=>'btn btn-primary')); ?>
	</div>
    <h3>Destinations</h3>
    <div class="editinstructions">
		<p><?php echo Yii::t('admin','Define a destination including the appropriate tax code that applies to that location. (Tax Codes are downloaded from your LightSpeed tax setup.) Destinations are applied from specific to general, so for example US/California will take priority over US/Any. Postal codes are optional if you need to define a very specific area. List order below does not matter.');?></p>
		<p><?php echo Yii::t('admin','The Any/Any entry will apply if the customer shipping address does not match any other line.  (Note: Any/Any is ignored if {link} is active.) To delete an entry, choose Delete from the top of the Select Country dropdown.',
			array('{link}'=>CHtml::link('Only Ship to Defined Destinations',$this->createUrl('shipping/edit',array('id'=>ShippingController::GLOBAL_SHIPPING))))); ?></p>
    </div>
	    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'user-grid',
	    'itemsCssClass' => 'table-bordered',
	    'dataProvider' => $model->search(),
		'summaryText' => '',
	    'columns'=>array(
		    array(
			    'class' => 'editable.EditableColumn',
			    'sortable'=>false,
			    'id' => 'country',
			    'name' => 'country',
			    'headerHtmlOptions' => array('id'=>'$data->id','style' => 'width: 110px'),
			    'editable' => array(
				    'type' => 'select',
				    'url' => $this->createUrl('shipping/updateDestination'),
				    'placement' => 'right',
				    'source' => Country::getCountriesForTaxes(),
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,

					    'success' => 'js:function(response, newValue) {
									if(response=="delete")
										window.location.reload();
									else {
										var pk = $(this).data("pk");
										var country = newValue;
										var state = $("a[rel=user-grid_Destination_state][data-pk="+pk+"]");
										var newurl = "'.$this->createUrl('shipping/destinationstates').'?a=1&country_id=" + country;
										$.get(newurl,function(jsdata) {
											response = $.parseJSON(jsdata);
											state.editable("option", "source", jsdata);
											state.editable("setValue", null);
											 });
									}
								}',
					    'emptytext'=>'Any',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'state',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'sortable'=>false,
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateDestination'),
				    'type' => 'select',
				    'source'=> $this->createUrl('shipping/destinationstates').'?a=1&country_id=0',
				    'placement' => 'right',
				    'onInit' => 'js: function(e, params) {
							if ($(this).data("value")>=0) {
								var pk = $(this).data("pk");
								var country = $("a[rel=user-grid_Destination_country][data-pk="+pk+"]").editable("getValue").country;
								var newurl = "'.$this->createUrl('shipping/destinationstates').'?a=1&country_id=" + country;
								$(this).editable("option", "source", newurl);
							}}',
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
					    'emptytext'=>'Please Choose',
				    ),

			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'sortable'=>false,
			    'name' => 'zipcode1',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateDestination'),
				    //'title'=> Yii::t('admin','Promo Code (erase entry to delete code)'),
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'sortable'=>false,
			    'name' => 'zipcode2',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateDestination'),
				    'options'=>array(
					    'onblur' => 'submit',

				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'sortable'=>false,
			    'name' => 'taxcode',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateDestination'),
				    'type' => 'select',
				    'source'=>CHtml::listData(TaxCode::model()->findAll(), 'lsid', 'code'),
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
					    'emptytext' => 'MISSING!',
				    ),
			    )
		    ),
		),
    ));
	?>


</div>




