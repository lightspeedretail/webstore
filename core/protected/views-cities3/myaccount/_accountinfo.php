<?php
	$accountOptions = CJSON::encode(
		array(
			'UPDATE_ACCOUNT_URL' => Yii::app()->createUrl('myaccount/updateprofile'),
			'UPDATE_PASSWORD_URL' => Yii::app()->createUrl('myaccount/updatepassword'),
			'PASSWORD_CANNOT_BE_BLANK' => Yii::t('form-errors', 'Password cannot be blank.')
		)
	);

	Yii::app()->clientScript->registerScript(
		'instantiate Profile',
		'$(function(){
				var profile = new Profile(' . $accountOptions  . ');
				var password = new Password(' . $accountOptions . ');
		});',
		CClientScript::POS_END
	);
?>

<?php $this->renderPartial('_editprofilemodal', array('model' => $model)); ?>
<?php $this->renderPartial('_changepasswordmodal', array('model' => $model)); ?>

<div class="account-info">
	<div class="customer">
		<p>
			<strong><span id='label-first-name'><?= $model->first_name ?></span> <span id='label-last-name'><?= $model->last_name ?></span></strong>
			<br>
			<span id='label-main-phone'><?= $model->mainphone ?></span>
			<br>
			<span id='label-email'><?= $model->email ?></span>
		</p>
		<ul class="modify">
			<li><a href="" id="edit-profile"><?= Yii::t('profile', 'Edit Profile') ?></a></li>
			<li><a href="" id="change-password-link"><?= Yii::t('profile', 'Change Password') ?></a></li>
		</ul>
	</div>
</div>

