<?php $this->beginContent('//layouts/mail-layout'); ?>

<tr>
	<td style="border-bottom: 1px solid #dddddd;display: block; padding-bottom: 30px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 16px;line-height:1.5em;">
		<?= Yii::t('email', 'Dear') . ' ' . $model->first_name ?>,<br/><br/>
		<?=	Yii::t('email', 'Please visit the following link to reset your password; if the link cannot be clicked, copy and paste it into your browser navigation bar:'); ?>
	</td>
</tr>
<br/>
<tr>
	<td bgcolor="#f8f8f8" style="padding:15px 15px 15px 15px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 15px;line-height:1.5em; border: 1px solid #dddddd;">
		<?php
			$url = $this->createAbsoluteUrl('myaccount/resetpassword', array('id' => $model->id, 'token' => $model->token));
			echo CHtml::link($url, $url, array('target' => '_blank','style' => 'color: #3287cc;text-decoration: none;'));
		?>
	</td>
</tr>

<?php $this->endContent(); ?>