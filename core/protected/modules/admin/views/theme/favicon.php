<div class="span9">
	<div class="hero-unit">

		<h3><?php echo Yii::t('admin','FavIcon'); ?></h3>
		<div class="editinstructions">


			<?php echo Yii::t('admin','Upload a new FavIcon.ico file for your site. This process will replace any prior file.'); ?>
		</div>
		<div class="clearfix spaceafter"></div>
			<?php echo CHtml::beginForm('favicon', 'post', array('enctype'=>'multipart/form-data')); ?>

			<strong>Current Icon:</strong><img src="<?=Yii::app()->request->baseUrl."/images/favicon.ico?".date("His");?>">

		<hr>
			<div class="row-fluid editinstructions">
				<?php if(Yii::app()->user->fullname=="LightSpeed")
					 ?>
				<?php  if(Yii::app()->user->fullname=="LightSpeed")
					echo "<p><strong>".Yii::t('admin','To upload a new icon, drag and drop a file on top of the Choose File button, then click Upload. NOTE: You can also log into Admin Panel externally at {url} to use the Choose File button normally.',array('{url}'=>$this->createAbsoluteUrl("/admin")))."</strong></p>";
				else
					echo Yii::t('admin','To upload a new icon, click Choose File and select your file, then click Upload.');

				echo " Upload an .ico file (Recommended max size: 16x16px):";
				?>
			</div>
			<?php echo CHtml::fileField('icon_image', '', array('id'=>'icon_image','onchange'=>'js:$("#btnUpload").html("Upload");')); ?>


			<div class="clearfix spaceafter"></div>

			<p class="pull-right">
				<?php $this->widget('bootstrap.widgets.TbButton', array(
					'buttonType'=>'submit',
					'label'=>'Select',
					'type'=>'primary',
					'size'=>'large',
					'htmlOptions'=>array('id'=>'btnUpload'),
				)); ?>
			</p>

			<?php echo CHtml::endForm(); ?>
		</div>

	</div>
</div>