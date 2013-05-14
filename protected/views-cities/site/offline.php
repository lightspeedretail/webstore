<?php
$this->pageTitle=Yii::app()->name . ' - '. Yii::t('global','Offline');
$this->layout = "/layouts/errorlayout";
?>
<div class="container">

	<div class="span12">
		<div id="headerimage">
			<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl._xls_get_conf('HEADER_IMAGE')), Yii::app()->baseUrl."/"); ?>
		</div>
	</div>



	<div class="row">

		<div class="span3">
			<img src="<?php echo Yii::app()->createAbsoluteUrl("/images/sticky_offline.png") ?>">
		</div>


		<div class="span6">
			<h1><?php echo Yii::t('offline',"Please check back later."); ?></h1>
			<h2><?php echo Yii::t('offline',"Feel free to contact us at {phone} or by email at {email}.",
				array(  '{phone}'=> _xls_get_conf('STORE_PHONE'),
						'{email}'=>"<a href=\"mailto:"._xls_get_conf('EMAIL_FROM')."\">"._xls_get_conf('EMAIL_FROM')."</a>"));
				?></h2>
		</div>
	</div>

</div>

	

