<div class="span9">
    <h3><?php echo Yii::t('admin','System Log'); ?></h3>
	<div class="clearfix search">
		<div class="pull-right">
			<?php echo CHtml::beginForm($this->createUrl('system/log'),'get'); ?>
			<?php echo CHtml::textField('q',Yii::app()->getRequest()->getQuery('q'),array('id'=>'xlsSearch','placeholder'=>'SEARCH...','submit'=>'')); ?>
			<?php echo CHtml::endForm(); ?>
		</div>
	</div>
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




