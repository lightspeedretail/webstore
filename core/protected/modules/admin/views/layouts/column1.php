
<?php $this->beginContent('application.modules.admin.views.layouts.main'); ?>
<?php $this->Widget('zii.widgets.jui.CJuiDialog',array(
	'id'=>'alert-box',
	'options'=>array(
		'title'=>'Alert',
		'autoOpen'=>false,
		'modal'=>'true',
		'width'=>'400',
		'height'=>'150',
		'scrolling'=>'no',
		'resizable'=>false,
		'position'=>'center',
		'draggable'=>false,
	),
));
?>
		<?php echo $content; ?>

<?php $this->endContent(); ?>