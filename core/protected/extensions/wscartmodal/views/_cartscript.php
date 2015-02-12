<script>
	var cart = <?php
		echo CJSON::encode(
			array(
				'INVALID_PROMOCODE' => Yii::t('checkout', 'Promo code is no longer applicable to this purchase.'),
				'PROMOCODE_APPLY' => Yii::t('checkout', 'Apply'),
				'EACH_SUFFIX' => Yii::t('checkout', 'ea')
			)
		);
	?>
</script>
