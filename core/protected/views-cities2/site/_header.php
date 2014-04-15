<div id="headerArea">

<div id="topbar" class="row col-md-6 col-xs-12">
	
		<div class="col-md-4 col-xs-4">
			<div class="shoppingnavigation">
				<?php if(_xls_get_conf('ENABLE_WISH_LIST',0)) echo CHtml::link(Yii::t('cart','Wish Lists'),array('/wishlist')) ?>
				<?php echo CHtml::link(Yii::t('cart','Checkout'),array('cart/checkout')) ?>
			</div>
		</div> 


		<div class="col-md-4 col-xs-4">

			<div id="login">
				<?php if(Yii::app()->user->isGuest): ?>
					<?php echo CHtml::link(Yii::t('global', 'Login'), array("site/login")); ?>
					
					<a href="<?= _xls_site_url('myaccount/edit'); ?>"><?php echo Yii::t('global', 'Register'); ?></a>
				<?php else: ?>
					<?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, array('/myaccount')); ?>
					<?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?>
					<?php endif; ?>
			</div>



			<?php if(_xls_get_conf('LANG_MENU',0)): ?>
				<div id="langmenu">
					<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
				</div>
			<?php endif; ?>

		</div>


		<div class="col-md-4  col-xs-4">

			<div id="shoppingcart">
				<?= $this->renderPartial('/site/_topcart',null, true); ?>
			</div>
		</div>




</div>


	<div class="col-md-6 col-sm-12 col-xs-9">
		<div id="headerimage">
			<?php echo CHtml::link(CHtml::image($this->pageHeaderImage),$this->createUrl("site/index")); ?>
		</div>
	</div>


	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>

		</button>	
	</div>


</div> <!-- end of headerArea -->

<div class="clearfix"></div>