<div id="footer" class="row-fluid">

	<div class="span12">
		<div class="addresshours">
			<div class="span3 indentl responsive-break">
				<?php
				echo _xls_get_conf('STORE_NAME')."<br>";
				echo _xls_get_conf('STORE_ADDRESS1')."<br>";
				echo _xls_get_conf('STORE_ADDRESS2');
				?>
			</div>
			<div class="span6 footertabs responsive-break">
				<?php
					foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab) {
						echo CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link, array('id'=> $arrTab->request_url)).' / ';
					}
					echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'),array('id'=>'site-map'));
				?>

			</div>
			<div class="span3 right indentr responsive-break">
				<?php
				echo _xls_get_conf('STORE_HOURS')."<br>";
				echo _xls_get_conf('STORE_PHONE')."<br>";
				echo _xls_get_conf('EMAIL_FROM');
				?>
			</div>
		</div>
		<div class="copyright">
			&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.
		</div>
	</div>
</div>

