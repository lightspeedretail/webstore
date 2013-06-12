<h1><?= Yii::t('global','Site Map')?></h1>
<div id="sitemap">
	<div class="span4">
		<h4><?= Yii::t('global',"Informational pages") ?></h4>
			<?php foreach ($arrCustomPages as $objPage): ?>
			<div class="three columns"><?= CHtml::link(Yii::t('global',$objPage->title),$objPage->Link); ?></div>
			<?php endforeach; ?>
	</div>
    <div class="span8">
	    <h4><?= Yii::t('global',"Categories") ?></h4>
	    <?php $this->widget('CTreeView',array(
			'id'=>'sitemap-category-tree',
			'data'=> Category::GetTree(),
			'animated'=>'fast',
			'collapsed'=>false,
			'htmlOptions'=>array(
				'class'=>'filetree'
			)
		)); ?>
	</div>
</div>