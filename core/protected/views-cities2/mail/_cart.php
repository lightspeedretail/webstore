<?php $this->beginContent('//layouts/mail-layout'); ?>

	<tr>
		<td style="border-bottom: 1px solid #dddddd;display: block; padding-bottom: 30px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 16px;line-height:1.5em;">
			<?= Yii::t('email', 'Dear') . ' ' . $model->toName ?>,<br/><br/>
			<?=
				Yii::t(
					'email',
					'{name} has sent you the following message from {storename}',
					array('{name}' => $model->fromName,
						'{storename}' => _xls_get_conf('STORE_NAME'),
						)
				);
			?>.<br/>

		</td>
	</tr>
<br/>
	<tr>
		<td bgcolor="#f8f8f8" style="padding:15px 15px 15px 15px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 15px;line-height:1.5em; border: 1px solid #dddddd;">
			<?= $model->comment ?>
		</td>
	</tr>

<?php $this->endContent(); ?>