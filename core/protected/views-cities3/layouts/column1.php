<?php $this->beginContent('//layouts/main'); ?>

<?php $this->widget(
	'zii.widgets.CBreadcrumbs',
	array(
		'links'=>$this->breadcrumbs,
		'homeLink'=>CHtml::link(CHtml::image('/images/breadcrumbs_home.png'), array('/site/index')),
		'separator'=>' / ',
	)
);	?>
	<!-- breadcrumbs -->

<?php $this->widget(
	'bootstrap.widgets.TbAlert',
	array(
		'block'=>true, // display a larger alert block?
		'fade'=>true, // use transitions?
		'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
		'alerts'=>array(
		 // configurations per alert type
			'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			'danger'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
		),
	)
); ?>
	<!-- flash messages -->

<div id="viewport" class="row-fluid">
	<?php echo $content; ?>
</div>

<?php $this->endContent();