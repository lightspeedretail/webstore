<div id="footer">


	<div class="row addresshours">
		<div class="col-sm-6 indentl">
			
			<div id="footerStoreName"><?php echo _xls_get_conf('STORE_NAME')."<br>"; ?></div>

			<?php
			echo _xls_get_conf('STORE_ADDRESS1')."<br>";
			echo _xls_get_conf('STORE_ADDRESS2')."<br>";
            echo _xls_get_conf('EMAIL_FROM');
			?>
		</div>
		<div class="col-sm-6 right indentr">
			<?php
			echo _xls_get_conf('STORE_HOURS')."<br>";
			echo _xls_get_conf('STORE_PHONE');
			?>
		</div>
	</div>

	<div class="row bottomtabs">
		<?php
			foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
				echo CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link,array('id'=>$arrTab->request_url)).' / ';
		echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'),array('id'=>'site-map'));
		?>
	</div>
	<div class="copyright">
		&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.
	</div>

</div>

