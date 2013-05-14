<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'set-categories-dialog',
	'options'=>array(
		'title'=>'Set '.ucfirst($service).' Category',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'630',
		'height'=>'440',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>true,
	),
));
?>
<div class="span9">

    <h3>Match <?= ucfirst($service) ?> categories to Web Store categories</h3>
    <div class="editinstructions">
		<?php echo Yii::t('admin','It is only necessary to match first tier categories, as lower tiers will pull from their parent unless set independently. Certain categories require additional options.'); ?>
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
			'class' => 'editable.EditableColumn', //not really using it here but need the header formatting.
			'name' => $service,
			'header'=> ucfirst($service).' Category',
			'type' => 'raw',
			'editable' => array(
				'emptytext'=>' ',
			),
			'value'=>'($data->integration->'.$service.'->name0==NULL)?"<a href=\"#\" id=\"".$data->id."\" class=\"basic\">Set</a>":"<a href=\"#\" id=\"".$data->id."\" class=\"basic\">".$data->integration->'.$service.'->name0.""',
			'headerHtmlOptions' => array('class' => 'span5'),

		),
	),
));
	?>



