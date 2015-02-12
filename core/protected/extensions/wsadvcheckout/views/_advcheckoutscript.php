<script>
	var advcheckoutTranslation = <?php
		echo CJSON::encode(
			array(
				'EACH_SUFFIX' => Yii::t('checkout', 'ea'),
				'INVALID_EMAIL' => Yii::t('checkout', 'Email address is invalid. Please enter a valid email.'),
				'EMAIL_REQUIRED' => Yii::t('checkout', 'Email address is required.'),
				'PASSWORD_REQUIRED' => Yii::t('checkout', 'Password is required.'),
				'LOGIN_TITLE' => Yii::t('checkout', 'Login to your account'),
				'GUEST_CHECKOUT_TITLE' => Yii::t('checkout', 'Guest Checkout')
			)
		);
	?>
</script>


