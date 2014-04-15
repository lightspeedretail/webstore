<div id="menubar" class="col-sm-12">




	<div class="navbar-collapse collapse">
		<?php if (count(CustomPage::model()->toptabs()->findAll()))
			$this->widget('zii.widgets.CMenu', array(
			'id'=>'menutabs nav navbar-nav',
			'itemCssClass'=>' mainMenuBtn '.round(12/count(CustomPage::model()->toptabs()->findAll())),
			'items'=>CustomPage::model()->toptabs()->findAll()
		)); ?>
	</div>

	<div id="searchentry"  ==>
		<?php echo $this->renderPartial("/site/_search",array(),true); ?>
	</div>

</div><!-- menubar -->

<div class="clearfix"></div>