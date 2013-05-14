<div id="custom_content" class="span12">
	<?php echo $objCustomPage->page; ?>
</div>

<div class="span9 clearfix">
	<?php
	if ($dataProvider)
		$this->widget('ext.JCarousel.JCarousel', array(
		'dataProvider' => $dataProvider,
		'thumbUrl' => '$data->SliderImage',
		'imageUrl' => '$data->Link',
		'titleText' => '$data->Title',
		'captionText' => '$data->Title."<br>"._xls_currency($data->sell)',
		'target' => 'do-not-delete-this',
		//'wrap' => 'circular',
		'visible' => true,
		'skin' => 'slider',
		'clickCallback'=>'window.location.href=itemSrc;'
	)); ?>
</div>
