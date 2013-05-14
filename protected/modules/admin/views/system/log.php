<div class="span9">
    <h3><?php echo Yii::t('admin','System Log'); ?></h3>
	<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id' => 'user-grid',
	'itemsCssClass' => 'table-bordered',
	'dataProvider' => $model->searchAll(),
	'summaryText' => '',
	'columns'=>array(
		array(
			'name' => 'created',
			'type'=>'raw',
			'headerHtmlOptions' => array('class' => 'span1'),
			'value'=>'$data->created',
		),
//		array(
//			'name' => 'level',
//			'headerHtmlOptions' => array('class' => 'span1'),
//			),
		array(
			'name' => 'message',
			'type'=>'raw',
			'headerHtmlOptions' => array('class' => 'span5 logview'),
			'value'=>'"<pre>".$data->message."</pre>"',
			),
	),
));
	?>


</div>




