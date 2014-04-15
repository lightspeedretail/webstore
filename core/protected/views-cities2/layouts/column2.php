<?php $this->beginContent('//layouts/main'); ?>
<div class="row">

	<div class="col-sm-9 mainClm">

		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
	        'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.'/css/images/breadcrumbs_home.png'), array('/site/index')),
			'separator'=>' / ',
	        ));	?> <!-- breadcrumbs -->
		<?= $this->renderPartial('/site/_flashmessages',null, true); ?><!-- flash messages -->
		<div id="viewport">
		    <?php echo $content; ?>
	    </div>
	</div>

	<div class="col-sm-3 leftSidebar">
		<div class="products"><?= Yii::t('global','Products'); ?></div>
			<?php  $this->widget( 'zii.widgets.CMenu', array(
				'items' => $this->MenuTree,
				'id'=>'menutree'
			));
			?>

	</div>

</div>

<?php $this->endContent();