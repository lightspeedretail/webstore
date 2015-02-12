<?php
if (Yii::app()->shoppingcart->promoCode === null)
{
	$promoCodeInputPlaceholder = '';
	$promoCodeButtonLabel = Yii::t('checkout', 'Apply');
	$promoCodeButtonClass = '';
} else {
	$promoCodeInputPlaceholder = Yii::app()->shoppingcart->promoCode;
	$promoCodeButtonLabel = Yii::t('checkout', 'Remove');
	$promoCodeButtonClass = 'promocode-applied';
}

// The Promo Code text input.
echo CHtml::textField(
	CHtml::activeId($modelId, 'promoCode'),
	$promoCodeInputPlaceholder,
	array(
		'placeholder' => Yii::t('cart', 'Promo Code'),
		'class' => "promo-code-value",
		'onkeypress' => sprintf(
			'promoCodeInput.togglePromoCodeEnterKey(event, %s)',
			json_encode(CHtml::activeId($modelId, 'promoCode'))
		),
		'readonly' => Yii::app()->shoppingcart->promoCode !== null
	)
);

// The Apply/Remove Promo Code button.
echo CHtml::htmlButton(
	$promoCodeButtonLabel,
	array(
		'type' => 'button',
		'class' => 'inset promocode-apply ' . $promoCodeButtonClass,
		'onclick' => sprintf(
			'promoCodeInput.togglePromoCode(%s);',
			json_encode(CHtml::activeId($modelId, 'promoCode'))
		)
	)
);
?>

<script>
	$(document).ready(function() {
		if (typeof promoCodeInput === 'undefined') {
			promoCodeInput = new PromoCodeInput({
				checkout: checkout,
				updateCartTotals: <?= CJSON::encode($updateCartTotals); ?>,
				reloadPageOnSuccess: <?= CJSON::encode($reloadPageOnSuccess); ?>
			});
		}
	});
</script>

