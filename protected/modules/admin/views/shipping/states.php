<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'setdestination-modal',
		'options'=>array(
			'title'=>'Set States',
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
		'id'=>'add-modal',
		'options'=>array(
			'title'=>'Add New State',
			'autoOpen'=>false,
			'modal'=>'true',
			'width'=>'520',
			'height'=>'365',
			'scrolling'=>'no',
			'resizable'=>false,
			'position'=>'center',
			'draggable'=>true,
		),
	));
	$this->renderPartial('_newstate',array('model'=>$modeldestination));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<div class="span9">

    <div class="pull-right">
	<?php
	echo CHtml::ajaxButton(Yii::t('admin','Create New State'),
		array('shipping/newstate'),
		array(
			'type'=>"POST",
			'dataType'=>'json',
			'onClick'=>'js:jQuery($("#add-modal")).dialog("open")',
		),array('id'=>'btnModalLogin','name'=>'btnModalLogin', 'class'=>'btn btn-primary')); ?>
	</div>
    <h3>Edit States/Regions</h3>
    <div class="editinstructions">
        <div class="span8">
	        <?php echo Yii::t('admin','To delete an entry, edit the State/Region name and wipe out the text.'); ?>
        </div>
        <div class="clearfix search">
            <div class="pull-right">
				<?php echo CHtml::beginForm($this->createUrl('shipping/states'),'get'); ?>
				<?php echo CHtml::textField('q',Yii::app()->getRequest()->getQuery('q'),array('id'=>'xlsSearch','placeholder'=>'SEARCH...','submit'=>'')); ?>
				<?php echo CHtml::endForm(); ?>
            </div>
        </div>
    </div>
	    <?php $this->widget('bootstrap.widgets.TbGridView', array(
	    'id' => 'user-grid',
	    'itemsCssClass' => 'table-bordered',
	    'dataProvider' => $model->search(),
		'summaryText' => '',
	    'columns'=>array(
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'state',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateState'),
				    'placement' => 'right',
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
			    'name' => 'code',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateState'),
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'country_id',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateState'),
				    'type' => 'select',
				    'source'=>CHtml::listData(Country::model()->findAll(), 'id', 'country'),
				    'placement' => 'right',
				    'options'=>array(
					    'onblur' => 'submit',
					    'showbuttons' => false,
				    ),
			    )
			),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'sort_order',
			    'headerHtmlOptions' => array('style' => 'width: 110px'),
			    'editable' => array(
				    'url' => $this->createUrl('shipping/updateState'),
				    'options'=>array(
					    'onblur' => 'submit',
				    ),
			    )
		    ),
		    array(
			    'class' => 'editable.EditableColumn',
			    'name' => 'active',
			    'headerHtmlOptions' => array('style' => 'width: 50px'),
			    'editable' => array(
				    'type' => 'select',
				    'url' => $this->createUrl('shipping/updateState'),
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
				    ),
			    )
		    ),
		),
    ));
	?>


</div>




