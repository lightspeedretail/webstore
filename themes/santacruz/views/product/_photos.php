	<?php $this->widget('ext.starplugins.cloudzoom',array(
		'images'=>$model->ProductPhotos,
//		'instructions'=>'<legend>'.Yii::t('global','Hover over image to zoom').'</legend>',
        'instructions'=>'',
		'css_target'=>'targetarea',
		'css_thumbs'=>'thumbs',
		'zoomClass'=>'cloudzoom',
		'zoomSizeMode'=>'lens',
		'zoomPosition'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? '3' : 'inside',
		'zoomOffsetX'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 10 : 0,
		'zoomFlyOut'=>Yii::app()->params['IMAGE_ZOOM']=='flyout' ? 'true' : 'false',
	));
	?>