<?php $this->beginContent('//layouts/mail-layout'); ?>
							<tr>
								<td style="display: block; padding-bottom: 30px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 18px;font-weight: bold;line-height:1.5em;">
									<?= Yii::t('email', 'Contact Us question for') . ' ' . _xls_get_conf('STORE_NAME') ?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#f8f8f8" style="padding:15px 15px 15px 15px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:1.5em; border: 1px solid #dddddd;">
									<b><?= Yii::t('email', 'From:') ?></b><?= ' ' . $model->fromName ?><br/>
									<b><?=Yii::t('email', 'Email:') ?></b><?= ' ' . $model->fromEmail ?><br/>
									<b><?=Yii::t('email', 'Subject:') ?></b><?= ' ' . $model->contactSubject ?><br/>
									<br/>
									<br/>
									<?= $model->contactBody ?>
								</td>
							</tr>
<?php $this->endContent(); ?>


