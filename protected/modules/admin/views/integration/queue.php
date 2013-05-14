<div class="span9">
<?php $name = ucfirst($model->controller); ?>
	<h3>Integration Queue for <?php echo $name; ?></h3>
		<div class="editinstructions">
			<?php echo Yii::t('admin','This is the task queue of currently pending transactions with {site}. This is for troubleshooting for technical support to see transactions in progress.',array('{site}'=>$name)); ?>
		</div>

	<?php $this->widget('bootstrap.widgets.TbGridView', array(
		'id' => 'user-grid',
		'itemsCssClass' => 'table-bordered',
		'dataProvider' => $model->search(),
		'summaryText' => '',
		'columns'=>array(
			array(
				'name' => 'id',
				'headerHtmlOptions' => array('class' => 'span1'),
			),
			array(
				'name' => 'action',
				'headerHtmlOptions' => array('class' => 'span2'),
			),
			array(
				'name' => 'data_id',
				'header'=>'Transaction ID',
				'headerHtmlOptions' => array('class' => 'span2'),
			),
			array(
				'name' => 'product.code',
				'header'=>'Product',
				'headerHtmlOptions' => array('class' => 'span1'),
			),
			array(
				'name' => 'created',
				'headerHtmlOptions' => array('class' => 'span2'),
			),
			array(
				'name' => 'modified',
				'headerHtmlOptions' => array('class' => 'span2'),
			),

		),
	));
	?>


</div>