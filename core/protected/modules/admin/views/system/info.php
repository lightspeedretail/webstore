<div class="span9">
	<div class="hero-unit">
		<h3><?php echo Yii::t('admin','System Information'); ?></h3>
		<div class="clear">&nbsp;</div>
		<div class="row">
			<div class="span3" style="text-align: right">
				<span class="label"><?php echo Yii::t('admin','Web Store Version');?></span>
			</div>
			<div class="span3">
				<span class="editinstructions"><?php echo XLSWS_VERSION; ?></span>
			</div>
			<div class="span3">&nbsp;</div>
		</div>
		<div class="row">
			<div class="span3" style="text-align: right">
				<span class="label"><?php echo Yii::t('admin','Build');?></span>
			</div>
			<div class="span3">
				<span class="editinstructions"><?php echo XLSWS_BUILDDATE; ?></span>
			</div>
			<div class="span3">&nbsp;</div>
		</div>
		<div class="row">
			<div class="span3" style="text-align: right">
				<span class="label"><?php echo Yii::t('admin','PHP Version');?></span>
			</div>
			<div class="span3">
				<span class="editinstructions"><?php echo phpversion(); ?></span>
			</div>
			<div class="span3">&nbsp;</div>
		</div>
		<?php
			if(Yii::app()->params['LIGHTSPEED_HOSTING']):
			$m = ($this->isMT ? "M" : "S");?>
			<div class="row">
			<div class="span3" style="text-align: right">
				<span class="label">Lightspeed Hosting Mode</span>
			</div>
			<div class="span3">
				<span class="editinstructions"><?= $m ?></span>
			</div>
			<div class="span3">&nbsp;</div>
		</div>
		<?php endif; ?>
		<?php
			if($this->isCloud): ?>
			<div class="row">
			<div class="span3" style="text-align: right">
				<span class="label">Lightspeed Retail</span>
			</div>
			<div class="span3">
				<span class="editinstructions"><?= Yii::app()->params['LIGHTSPEED_CLOUD']?></span>
			</div>
			<div class="span3">&nbsp;</div>
		</div>
		<?php endif; ?>
	</div>
</div>