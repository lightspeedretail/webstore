<div id="menubar" class="row-fluid">

	<div class="span2">
		<?php $this->widget('application.extensions.wsmenu.wsmenu'); //products dropdown menu ?>
	</div>

	<div class="span7">
		<?php $this->widget('zii.widgets.CMenu', array(
			'id'=>'menutabs',
			'itemCssClass'=>'menutab menuheight menuunderline span'.round(12/count(CustomPage::model()->toptabs()->findAll())),
			'items'=>CustomPage::model()->toptabs()->findAll()
		)); ?>
	</div>

	<div id="searchentry" class="span3">
		<?php echo $this->renderPartial("/site/_search",array(),true); ?>
	</div>

</div><!-- menubar -->

<div class="clearfix"></div>