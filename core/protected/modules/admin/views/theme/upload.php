<div class="span9">
	<div class="hero-unit">

		<h3>Upload a Theme</h3>
		<div class="editinstructions">
			<?php if(Yii::app()->user->fullname=="LightSpeed")
				echo "<p><strong>".Yii::t('admin','NOTE: You are currently logged into Admin Panel from LightSpeed. This mode does not support direct file uploading. You will need to log into Admin Panel externally at {url} using a webstore account with Admin privileges to upload a file. The Choose File button will appear non-responsive.',array('{url}'=>$this->createAbsoluteUrl("/admin")))."</strong></p>"; ?>

			<?php echo Yii::t('admin','This form allows you to upload a .zip file containing a Web Store theme. This process will automatically extract and place the files into your /themes folder. After uploading, you can go to {link} to use this theme.',array('{link}'=>CHtml::link('Options',$this->createUrl('default/edit',array('id'=>19))))); ?>
		</div>

			<?php echo CHtml::beginForm('upload', 'post', array('enctype'=>'multipart/form-data')); ?>
			<div class="row">
				<div class="span5"><?php echo CHtml::label(Yii::t('admin','Choose your theme .zip file (Max size: {max}):',array('{max}'=>ini_get('upload_max_filesize'))), 'theme_file'); ?></div>
				<div class="span5"><?php echo CHtml::fileField('theme_file', '', array('id'=>'theme_file')); ?></div>
			</div>

			<p class="pull-right">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'label'=>'Upload',
					'type'=>'primary',
					'size'=>'large',
				)); ?>
			</p>

			<?php echo CHtml::endForm(); ?>
		</div>

	</div>
</div>