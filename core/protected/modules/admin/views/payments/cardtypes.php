<div class="span9">

    <div class="pull-right">
    <h3>Credit Card Types</h3>
    <div class="editinstructions">
		<?php echo Yii::t('admin','Enable or disable card types your customers use. Note this is universal, and will apply to all payment modules that use the credit card entry blank.'); ?>
    </div>
	    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'user-grid',
	    'itemsCssClass' => 'table-bordered',
	    'dataProvider' => $model->search(),
		'summaryText' => '',
	    'columns'=>array(
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'label',
			    'headerHtmlOptions' => array('style' => 'span2'),
			    'editable' => array(
				    'url' => $this->createUrl('payments/cardtypes'),
				    'placement' => 'right',
				    'title'=> Yii::t('admin','Card Name'),
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
				    'url' => $this->createUrl('payments/cardtypes'),
				    'source' => "{1: 'enabled',0: 'disabled'}",
				    'emptytext'=> 'disabled',
				    'placement' => 'left',
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

		),
    ));
	?>


</div>




