<div id="footer" class='container-fluid'>

	<div >

		<p class="storeinfo">
			<?php
			echo _xls_get_conf('STORE_NAME')." / ";
			echo _xls_get_conf('STORE_ADDRESS1').", ";
			echo _xls_get_conf('STORE_ADDRESS2')." / ";
			echo _xls_get_conf('STORE_HOURS')." / ";
			echo _xls_get_conf('STORE_PHONE')." / ";
			echo CHtml::link(_xls_get_conf('EMAIL_FROM'),'mailto:'._xls_get_conf('EMAIL_FROM'));
			?>
		</p>

		<p class='links'>
			<?php
					foreach (CustomPage::model()->bottomtabs()->findAll() as $arrTab) {
						echo CHtml::link(Yii::t('global',$arrTab->title),$arrTab->Link, array('id'=> $arrTab->request_url)).' / ';
					}
					echo CHtml::link(Yii::t('global','Sitemap'),$this->createUrl('site/map'));
				?>
		</p>

		<p class='social'>
			<?php if (Yii::app()->params['SOCIAL_FACEBOOK'] != "") { ?>
			<a href='<?php echo Yii::app()->params['SOCIAL_FACEBOOK']; ?>'>
				<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/facebook.png"?>' />
			</a>
			<?php }
			if (Yii::app()->params['SOCIAL_TWITTER'] != "") { ?>
			<a href='<?php echo Yii::app()->params['SOCIAL_TWITTER']; ?>'>
				<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/twitter.png"?>' />
			</a>
			<?php }
			if (Yii::app()->params['SOCIAL_PINTEREST'] != "") { ?>
				<a href='<?php echo Yii::app()->params['SOCIAL_PINTEREST']; ?>'>
					<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/pinterest.png"?>' />
				</a>
			<?php }
			if (Yii::app()->params['SOCIAL_LINKEDIN'] != "") { ?>
				<a href='<?php echo Yii::app()->params['SOCIAL_LINKEDIN']; ?>'>
					<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/linkedin.png"?>' />
				</a>
			<?php }
			if (Yii::app()->params['SOCIAL_INSTAGRAM'] != "") { ?>
				<a href='<?php echo Yii::app()->params['SOCIAL_INSTAGRAM']; ?>'>
					<img src='<?php echo Yii::app()->theme->baseUrl . "/css/assets/icons/instagram.png"?>' />
				</a>
			<?php } ?>
		</p>

		<p class="copyright">
			&copy; <?= Yii::t('global', 'Copyright') ?> <?= date("Y"); ?> <?= _xls_get_conf('STORE_NAME') ?>. <?= Yii::t('global', 'All Rights Reserved'); ?>.
		</p>

	</div>

</div>
