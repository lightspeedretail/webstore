<div id="menubar" class="row-fluid">

		<?php if (count(CustomPage::model()->toptabs()->findAll()))
			$this->widget('zii.widgets.CMenu', array(
			'items'=>CustomPage::model()->toptabs()->findAll(),
			'activeCssClass'=>'active',
			'htmlOptions'=>array('class'=>'hidden-md')
		)); ?>

<!-- Search -->
		<?php echo $this->renderPartial("/site/_search",array(),true); ?>
</div>

<?php if(Yii::app()->theme->info->showSeparateMobileMenu): ?>
	<div id="menubar-md" class="visible-md hidden-lg">
		<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<?= Yii::t('global','Menu'); ?>
		</a>
		<div class="nav-collapse collapse">
			<?php
				$this->widget('zii.widgets.CMenu', array(
						'items'=> count(CustomPage::model()->toptabs()->findAll()) ? array_merge(CustomPage::model()->toptabs()->findAll(), $this->MenuTreeTop) : $this->MenuTreeTop,
						'activeCssClass'=>'active',
						'htmlOptions'=>array('class'=>'nav')
					)
				);
			?>
		</div>
	</div>
<?php endif; ?>

<div class="clearfix"></div>

