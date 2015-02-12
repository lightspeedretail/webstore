<?php $this->beginContent('//layouts/mail-layout'); ?>

	<tr>
		<td style="border-bottom: 1px solid #dddddd;display: block; padding-bottom: 30px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 16px;line-height:1.5em;">
			<?= Yii::t('email', 'Dear') . ' ' . $model->first_name ?>,<br/><br/>
			<?=
			Yii::t(
				'email',
				'The password that is registered at {storename} is {password}',
				array('{password}' => _xls_decrypt($model->password),
					'{storename}' => _xls_get_conf('STORE_NAME'),
				)
			);
			?>.<br/><br/>

		</td>
	</tr>

<?php $this->endContent(); ?>