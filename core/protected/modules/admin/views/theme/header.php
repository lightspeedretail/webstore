<div class="span9">
	<div class="hero-unit">

		<h3><?php echo Yii::t('admin','Header and Email Image'); ?></h3>
		<div class="editinstructions">


			<?php echo Yii::t('admin','Choose the header image you wish to use with your theme. This image is used by both the site template as well as emailed receipts sent to customers.'); ?>
		</div>
		<div class="clearfix spaceafter"></div>
			<?php echo CHtml::beginForm('header', 'post', array('enctype'=>'multipart/form-data')); ?>

			<?php echo CHtml::radioButtonList('headerimage',_xls_get_conf('HEADER_IMAGE'),$arrHeaderImages); ?>

			<div class="clearfix spaceafter"></div>

		<hr>
			<div class="row-fluid editinstructions">
				<?php if(Yii::app()->user->fullname=="LightSpeed")
					echo "<p><strong>".Yii::t('admin','NOTE: You are currently logged into Admin Panel from LightSpeed. This mode does not support direct file uploading. You will need to log into Admin Panel externally at {url} using a webstore account with Admin privileges to upload a file. The Choose File button will appear non-responsive. You can still switch to an already uploaded header image.',array('{url}'=>$this->createAbsoluteUrl("/admin")))."</strong></p>"; ?>
				<?php echo Yii::t('admin','To upload a new header image to add to your collection, choose the file with the selector. Upload a .png or .jpg header file (Recommended max size: 750x125px):'); ?>
			</div>
			<?php echo CHtml::fileField('header_image', '', array('id'=>'header_image','onchange'=>'js:$("#btnUpload").html("Upload");')); ?>


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