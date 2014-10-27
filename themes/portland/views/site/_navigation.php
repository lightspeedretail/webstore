<div id="headerimagebg" class="span12 subpage-header">
	<div id="menubar" class="row-fluid">
		<div id="menubarinner">
				<div class="span2 menutab products">
					<?php $this->widget('application.extensions.wsmenu.wsmenu', array(
						'categories'=> Category::GetTree(),
						'menuheader'=> Yii::t('global','Products'),
						'showarrow'=>true,
					)); //products dropdown menu ?>
				</div>
			<div class="span10">
				<?php if (count(CustomPage::model()->toptabs()->findAll()))
					$this->widget('zii.widgets.CMenu', array(
					'id'=>'menutabs',
					'itemCssClass'=>'menutab menuheight menuunderline span'.round(12/count(CustomPage::model()->toptabs()->findAll())),
					'items'=>CustomPage::model()->toptabs()->findAll()
				)); ?>
			</div>
		</div>
	</div><!-- menubar -->

	<div class="clearfix"></div>
</div>