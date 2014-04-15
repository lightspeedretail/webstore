<div id="topbar" class="row-fluid">
	<div class="span9">
		<div id="headerimage">
			<?php echo CHtml::link(CHtml::image($this->pageHeaderImage),$this->createUrl("site/index")); ?>
		</div>
	</div>
	<div class="span3">
		<div id="login">
			<?php if(Yii::app()->user->isGuest): ?>
				<?php echo CHtml::link(Yii::t('global', 'Login'), array("site/login")); ?>
				&nbsp;/&nbsp;
				<?php echo CHtml::link(Yii::t('global', 'Register'), $this->createAbsoluteUrl('/myaccount')); ?>
			<?php else: ?>
				<?php echo CHtml::link(CHtml::image(Yii::app()->user->profilephoto).Yii::app()->user->firstname, $this->createAbsoluteUrl('/myaccount')); ?>
				&nbsp;&nbsp;/&nbsp;&nbsp;<?php echo CHtml::link(Yii::t('global', 'Logout'), array("site/logout")); ?>
				<?php endif; ?>
		</div>
		<?php if(_xls_get_conf('LANG_MENU',0)): ?>
			<div id="langmenu">
				<?php $this->widget('application.extensions.'._xls_get_conf('PROCESSOR_LANGMENU').'.'._xls_get_conf('PROCESSOR_LANGMENU')); ?>
				</div>
		<?php endif; ?>
	</div>
</div>