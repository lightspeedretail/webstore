<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
		'id'=>'modal',
		'options'=>array(
			'title'=>'Edit Countries',
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
			'title'=>'Create New Country',
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
	$this->renderPartial('_newcountry',array('model'=>new Country()));
	$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<div class="span9">

    <div class="pull-right">
	<?php
	echo CHtml::ajaxButton(Yii::t('admin','Create New Country'),
		'#',
		array(
			'type'=>"POST",
			'dataType'=>'json',
			'onClick'=>'js:jQuery($("#add-modal")).dialog("open")',
		),array('id'=>'btnModalLogin','name'=>'btnModalLogin', 'class'=>'btn btn-primary')); ?>
	</div>
    <h3>Edit Countries</h3>
    <div class="editinstructions">
	    <div class="span8">
		<?php echo Yii::t('admin','Note, the Zip Validation field uses RegEx expressions, or can be left blank to allow any postal code format. Please check our online guide for help with this field. To delete an entry, edit the Country name and wipe out the text.'); ?>
        </div>
	    <div class="clearfix search">
		    <div class="pull-right">
		    <?php echo CHtml::beginForm($this->createUrl('shipping/countries'),'get'); ?>
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
				'name' => 'country',
				'headerHtmlOptions' => array('style' => 'width: 140px'),
				'editable' => array(
					'url' => $this->createUrl('shipping/updatecountry'),
					'placement' => 'right',
					'options'=>array(
						'onblur' => 'submit',
						'emptytext'=>'Any',
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
				'headerHtmlOptions' => array('style' => 'width: 60px'),
				'editable' => array(
					'url' => $this->createUrl('shipping/updatecountry'),
					'placement' => 'right',
					'emptytext'=>'Any',
					'options'=>array(
						'onblur' => 'submit',
						'showbuttons' => false,
					),
				)
			),
			array(
				'class' => 'editable.EditableColumn',
				'name' => 'region',
				'headerHtmlOptions' => array('style' => 'width: 120px'),
				'editable' => array(
					'url' => $this->createUrl('shipping/updatecountry'),
					'type' => 'select',
					'source'=>array(
						'AF'=>'Africa',
						'AN'=>'Antartica',
						'AS'=>'Asia',
						'AU'=>'Australia',
						'EU'=>'Europe',
						'LA'=>'Latin/South America',
						'NA'=>'North America'),
					'options'=>array(
						'onblur' => 'submit',
						'showbuttons' => false,
					),
				)
			),
			array(
				'class' => 'editable.EditableColumn',
				'name' => 'sort_order',
				'headerHtmlOptions' => array('style' => 'width: 60px'),
				'editable' => array(
					'url' => $this->createUrl('shipping/updatecountry'),
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
					'url' => $this->createUrl('shipping/updatecountry'),
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
			array(
				'class' => 'editable.EditableColumn',
				'name' => 'zip_validate_preg',
				'headerHtmlOptions' => array('style' => 'width: 110px'),
				'editable' => array(
					'url' => $this->createUrl('shipping/updatecountry'),
					'options'=>array(
						'onblur' => 'submit',
						'placement'=>'left'
					),
				)
			),

		),
    ));
	?>

</div>





