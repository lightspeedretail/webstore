<div class="row-fluid">
	<legend><?= Yii::t('global','Hover over image to zoom') ?></legend>
	<?php $this->widget('ext.Yii-Image-Zoomer.YiiImageZoomer',array(
		'multiple_zoom'=>count($model->AdditionalImages)>=1 ? true:false,
		'single_image'=>$model->Images,
		'images'=>$model->Images,
		'cursorshade'=>true,
		'cursorshadecolor'=>'#fff',
		'cursorshadeopacity'=>0.5,
		'cursorshadeborder'=>'2px solid red',
		'imagevertcenter'=>false,
		'magvertcenter'=>false,
		'width'=>Yii::app()->params['DETAIL_IMAGE_WIDTH'],
		'height'=>Yii::app()->params['DETAIL_IMAGE_HEIGHT'],
		'magnifierpos'=>'left',
		'css_target'=>'targetarea span11',
		'css_thumbs'=>'image1 thumbs span11',
		'magnifiersize'=>array(400,300),
		'zoomrange'=>array(2,2),
		'initzoomablefade'=>true,
		'zoomablefade'=>true,
		'speed'=>300,
		'zIndex'=>4
	));
	?>
</div>