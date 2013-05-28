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
		'width'=>250,
		'height'=>250,
		'magnifierpos'=>'left',
		'magnifiersize'=>array(400,300),
		'zoomrange'=>array(2,2),
		'initzoomablefade'=>true,
		'zoomablefade'=>true,
		'speed'=>300,
		'zIndex'=>4
	));
	?>
</div>