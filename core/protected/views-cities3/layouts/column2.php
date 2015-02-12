<?php $this->beginContent('//layouts/main'); ?>
		<?php $this->widget(
			'zii.widgets.CBreadcrumbs',
			array(
		        'links' => $this->breadcrumbs,
				'homeLink' => CHtml::link(CHtml::image('/images/breadcrumbs_home.png'), array('/site/index')),
				'separator' => ' / ',
	        )
		);?>
		<!-- breadcrumbs -->

		<?= $this->renderPartial('/site/_flashmessages',null, true); ?>
		<!-- flash messages -->

		<div id="viewport" class="row-fluid">
		    <?php echo $content; ?>
	    </div>
<?= $this->renderPartial('/site/_sidecart',null, true); ?>
<?php $this->endContent();
