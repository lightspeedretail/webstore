<h1 xmlns="http://www.w3.org/1999/html"><?php echo $this->pageHeader; ?></h1>

<p>Site Page</p>

<?php
$data = array();
foreach($arrmodel as $m){  // loop to get the data (this is different from the complex way)
	echo CHtml::link(Yii::t('category',$m->label),array('/search','c'=>$m->id))."<br>";
}

// the pagination widget with some options to mess
$this->widget('CLinkPager', array(
		'currentPage'=>$pages->getCurrentPage(),
		'itemCount'=>$item_count,
		'pageSize'=>$page_size,
		'maxButtonCount'=>5,
//'nextPageLabel'=>'My text >',
		'header'=>'',
		'htmlOptions'=>array('class'=>'pages'),
	));
	
	