<?php
		if ($cartItem->discounted === true)
		{
			$renderedPrice = CHtml::tag(
					'strike',
					array(),
					$cartItem->sellFormatted . ' ' . Yii::t('checkout', 'ea')
				)
				. ' ' .$cartItem->sellDiscountFormatted . ' ' . Yii::t('checkout', 'ea') ;
		}
		else
		{
			$renderedPrice = $cartItem->sellFormatted;
		}

		 echo '<span class="price"> ' . $renderedPrice . '</span>';
