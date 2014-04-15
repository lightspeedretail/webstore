<div class="span9">
	<div class="hero-unit">

		<h3>Upload a Theme</h3>
		<div class="editinstructions"><p>
				<?php echo Yii::t('admin','This form allows you to upload a .zip file containing a Web Store theme. This process will automatically extract and place the files into your /themes folder. After uploading, you can go to {link} to use this theme.',array('{link}'=>CHtml::link('Options',$this->createUrl('default/edit',array('id'=>19))))); ?></p>

			<?php if(Yii::app()->user->fullname=="LightSpeed")
			echo "<p>".Yii::t('admin','To upload a new theme, drag and drop the .zip on top of the Choose File button, then click Upload. NOTE: You can also log into Admin Panel externally at {url} to use the Choose File button normally.',array('{url}'=>$this->createAbsoluteUrl("/admin")))."</p>"; ?>

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