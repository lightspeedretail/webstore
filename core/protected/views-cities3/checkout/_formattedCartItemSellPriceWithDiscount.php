<?php
		if ($cartItem->discounted === true)
		{
			$renderedPrice = CHtml::tag(
					'strike',
					array(),
					$cartItem->sellFormatted
				).
				$cartItem->sellDiscountFormatted;
		}
		else
		{
			$renderedPrice = $cartItem->sellFormatted;
		}

		 echo "<span class='price'> ".$renderedPrice."</span>";
