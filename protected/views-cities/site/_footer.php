<div id="footer" class="row-fluid">

	<div class="span12">
		<div class="addresshours">
			<div class="span6 indentl">
				<?php
				echo _xls_get_conf('STORE_NAME')."<br>";
				echo _xls_get_conf('STORE_ADDRESS1')."<br>";
				echo _xls_get_conf('STORE_ADDRESS2');
				?>
			</div>
			<div class="span6 right indentr">
				<?php
				echo _xls_get_conf('STORE_HOURS')."<br>";
				echo _xls_get_conf('STORE_PHONE')."<br>";
				echo _xls_get_conf('EMAIL_FROM');
				?>
			</div>
		</div>
	</div>
	<div class="bottomtabs">
		<?php
			foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
				echo CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link).' / ';
		echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'));
		?>
	</div>
	<div class="copyright">
		&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.
	</div>

</div>

