<footer id="footer" class="row-fluid">
	<div class="store">
		<p class="address">
			<?php
				echo "<strong>"._xls_get_conf('STORE_NAME')."</strong><br>";
				echo _xls_get_conf('STORE_ADDRESS1')."<br>";
				echo _xls_get_conf('STORE_ADDRESS2');
			?>
		</p>
		<p class="hours">
			<?php
				echo "<strong>"._xls_get_conf('STORE_PHONE')."</strong><br>";
				echo _xls_get_conf('STORE_HOURS')."<br>";
			?>
		</p>
	</div>
	<div>
		<ul>
			<?php
				foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab)
					echo "<li>".CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link)."</li>";
					echo "<li>".CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'))."</li>";
			?>
		</ul>
		<p class="copyright">
			&copy; <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.
		</p>
	</div>
</footer>

