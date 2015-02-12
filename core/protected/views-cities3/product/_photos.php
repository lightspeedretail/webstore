<div class="row-fluid">
	<?php $this->widget('ext.starplugins.cloudzoom',array(
		'images'=>$model->ProductPhotos,
		'instructions'=>'<legend>'.Yii::t('global','Hover over image to zoom').'</legend>',
		'css_target'=>'targetarea span11',
		'css_thumbs'=>'thumbs span11',
		'zoomClass'=>'cloudzoom',
		'zoomSizeMode'=>'lens',
		'zoomPosition'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? '3' : 'inside',
		'zoomOffsetX'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 10 : 0,
		'autoInside'=>665,
		'touchStartDelay'=>100,
		'zoomFlyOut'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 'true' : 'false',
	));
	?>
</div>