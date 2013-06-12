<div class="span9">

    <h3>Web Store category meta-data</h3>
    <div class="editinstructions">
		<p><?php echo Yii::t('admin','Normally Meta Descriptions in HTML headers will be the category name unless overridden here. It is only necessary to enter a Meta Description for first tier categories, as lower tiers will pull from their parent unless set independently.'); ?></p>
		 <p><?php echo Yii::t('admin','Using a Custom Page will display the text above the product grid when viewing a category. Custom pages must be set for each category line, they are not inherited.'); ?></p>
    </div>
	<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'user-grid',
	'itemsCssClass' => 'table-bordered',
	'dataProvider' => $model->searchForMatch(),
	'summaryText' => '',
	'columns'=>array(
		array(
			'name' => 'request_url',
			'header'=> 'Web Store Category',
			'headerHtmlOptions' => array('class' => 'span4'),
		),
		array(
			'name' => 'parent',
			'header'=> 'Tier',
			'value'=>'($data->parent==null ? "Primary" : "")',
			'headerHtmlOptions' => array('class' => 'span1'),
		),
		array(
			'class' => 'editable.EditableColumn',
			'name' => 'meta_description',
			'headerHtmlOptions' => array('style' => 'span3'),
			'editable' => array(
				'url' => $this->createUrl('default/updateCategory'),
				'placement' => 'left',
				'options'=>array(
					'onblur' => 'submit',
				),
			)
		),
		array(
			'class' => 'editable.EditableColumn',
			'name' => 'custom_page',
			'headerHtmlOptions' => array('style' => 'span1'),
			'editable' => array(
				'url' => $this->createUrl('default/updateCategory'),
				'type' => 'select',
				'placement' => 'left',
				'source'=>array(null=>'None') + CHtml::listData(CustomPage::model()->findAll(), 'id', 'title'),
				'options'=>array(
					'onblur' => 'submit',
					'showbuttons' => false,
				),
			)
		),

	),
));
	?>



