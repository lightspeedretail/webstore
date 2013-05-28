<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'setpromo-modal',
		'options'=>array(
			'title'=>'Set Product Restrictions',
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'800',
			'height'=>'363',
			'scrolling'=>'no',
			'resizable'=>false,
			'position'=>'center',
			'draggable'=>true,
		),
	));
?>
<?php $this->beginWidget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'addpromo-modal',
		'options'=>array(
			'title'=>'Add New Promo Code',
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'640',
			'height'=>'275',
			'scrolling'=>'no',
			'resizable'=>false,
			'position'=>'center',
			'draggable'=>true,
		),
	));
	$this->renderPartial('_newpromocode',array('model'=>new PromoCode()));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<div class="span9">

    <div class="pull-right">
	<?php
	echo CHtml::ajaxButton(Yii::t('admin','Create New Code'),
		array('payments/newpromo'),
		array(
			'type'=>"POST",
			'dataType'=>'json',
			'onClick'=>'js:jQuery($("#addpromo-modal")).dialog("open")',
		),array('id'=>'btnModalLogin','name'=>'btnModalLogin', 'class'=>'btn btn-primary')); ?>
	</div>
    <h3>Promo Codes</h3>
    <div class="editinstructions">
		<?php echo Yii::t('admin','Click any field to edit. All changes on this page will be saved immediately. To delete a code, edit the name and wipe out the text.'); ?>
    </div>
	    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'user-grid',
	    'itemsCssClass' => 'table-bordered',
	    'dataProvider' => $model->searchAllButShipping(),
		'summaryText' => '',
	    'columns'=>array(
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'code',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('payments/updatePromo'),
				    'placement' => 'right',
				    'title'=> Yii::t('admin','Promo Code (erase entry to delete code)'),
				    'options'=>array(
					    'onblur' => 'submit',
					    'success' => 'js:function(response, newValue) {
									if(response=="delete")
										$.fn.yiiGridView.update("user-grid");
								}',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'enabled',
			    'headerHtmlOptions' => array('style' => 'width: 50px'),
			    'editable' => array(
				    'type' => 'select',
				    'url' => $this->createUrl('payments/updatePromo'),
				    'source' => "{1: 'enabled',0: 'disabled'}",
				    'emptytext'=> 'disabled',
				    'placement' => 'right',
				    'options' => array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
					    'display' => 'js: function(value, sourceData) {
							 if(value=="1") label = "✓"; else label = "off";
						     var escapedValue = $("<div>").text(label).html();
						     $(this).html(escapedValue);
						     var colors = {0: "#DD0000", 1: ""};
							 $(this).css("color", colors[value]);
						}',
						'validate' => 'js: function(value) {
							if($.trim(value) == "") return "This field is required";
						}'
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'amount',
			    'headerHtmlOptions' => array('style' => 'width: 60px'),
			    'editable' => array(
				    'url' => $this->createUrl('payments/updatePromo'),
				    'placement' => 'right',
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'type',
			    'headerHtmlOptions' => array('style' => 'width: 40px'),
			    'editable' => array(
				    'type' => 'select',
				    'url' => $this->createUrl('payments/updatePromo'),
				    'source' => array(PromoCode::Percent=>'%',PromoCode::Currency=>'$'),
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
				    ),


			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'valid_from',
			    'headerHtmlOptions' => array('style' => 'width: 90px'),
			    'editable' => array(
				    'type' => 'date',
				    'viewformat' => _xls_convert_date_to_js(_xls_get_conf('DATE_FORMAT','Y-m-d')),
				    'url' => $this->createUrl('payments/updatePromo'),
				    'placement' => 'left',
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'valid_until',
			    'headerHtmlOptions' => array('style' => 'width: 90px'),
			    'editable' => array(
				    'type' => 'date',
				    'viewformat' => _xls_convert_date_to_js(_xls_get_conf('DATE_FORMAT','Y-m-d')),
				    'url' => $this->createUrl('payments/updatePromo'),
				    'placement' => 'left',
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),

		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'qty_remaining',
			    'headerHtmlOptions' => array('style' => 'width: 50px'),
			    'editable' => array(
				    'url' => $this->createUrl('payments/updatePromo'),
				    'title' => '# Uses Remaining (leave blank for unlimited)',
				    'placement' => 'left',
					'type' => 'text',
					'emptytext' => 'unlimited',
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'threshold',
			    'headerHtmlOptions' => array('style' => 'width: 70px'),
			    'editable' => array(
				    'url' => $this->createUrl('payments/updatePromo'),
				    'title' => 'Good Above $ (leave blank for no minimum)',
				    'placement' => 'left',
					'type' => 'text',
					'emptytext' => 'Any Amt',
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn', //not really using it here but need the header formatting
			    'name' => 'lscodes',
			    'type' => 'raw',
			    'editable' => array(
				    'emptytext'=>' ',
				    ),
			    'value'=>'($data->lscodes==NULL)?"<a href=\"#\" id=\"".$data->id."\" class=\"basic\">Create</a>":"<a href=\"#\" id=\"".$data->id."\" class=\"basic\">Edit</a>"',
			    'headerHtmlOptions' => array('style' => 'width: 80px'),

		    ),
//		    array(
//			    'class'=>'CButtonColumn',
//			    'deleteButtonUrl'=>'Yii::app()->controller->createUrl("payments/deletepromo",array("id"=>$data->id,"class"=>"icon-trash"))',
//			    'template'=>'{delete}',
//		    ),


		),
    ));
	?>


</div>




