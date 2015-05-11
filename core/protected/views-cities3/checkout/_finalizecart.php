<article class="cart">
	<?php
		//Allows the edit button portion of the table to be empty on thankyou.php page
		$editButton = array(
			'type' => '',
			'value' => ''
		);
		//if we are on the confimation.php page display the editcart button and call the shopping cart
		if ($isReceipt === false)
		{
			$editButton = array(
				'type' => 'raw',
				'value' => 'CHtml::link("Edit","#",array(
					"data-pk"=>$data->id,"class"=>"edit", "onclick"=>"$(this).closest(\'tr\').addClass(\'active\'); $(this).closest(\'input\').focus(); return false;")
					).CHtml::link("&times;","#",array("data-pk"=>$data->id,"class"=>"remove", "onclick"=>"wsEditCartModal.removeItem(this, event); return false;"))',
				'htmlOptions' => array(
					'class' => 'controls'
				),
			);

			$cartDisplay = Yii::app()->shoppingcart;
		}else{
		//if we are on the thankyou.php page use the $cart object since the shopping cart has been emptied upon
		// completion of the final transaction.
			$cartDisplay = $cart;
		}

	$this->widget(
		'zii.widgets.grid.CGridView',
		array(
			'htmlOptions' => array(
				'class' => 'lines lines-container'
			),
			'id' => 'user-grid',
			'itemsCssClass' => 'lines',
			'dataProvider' => $cartDisplay->dataProvider,
			'summaryText' => '',
			'columns' => array(
				array(
					'type' => 'raw',
					'value' => '"<strong>".$data->qty."</strong>".CHtml::numberField("CartItem_qty[$data->id]",$data->qty,array(
													"data-pk"=>$data->id,
													"size" => "3",
													"onchange" =>"wsEditCartModal.updateCart(this)",
												))',
					'htmlOptions' => array(
						'class' => 'quantity',
						'size' => '2'
					),
				),
				array(
					'type' => 'raw',
					'value' => '
								CHtml::image($data->product->SliderImage) .
								CHtml::tag("td",array("class" => "description"),
								CHtml::link("<strong>".$data->product->title." "."</strong>" .
								Yii::app()->getController()->renderPartial(\'_formattedCartItemSellPriceWithDiscount\', array(\'cartItem\' => $data), true), $data->product->Link, array())
								)',
					'htmlOptions' => array(
						'class' => 'image'
					),
				),
				$editButton,
				array(
					'type' => 'raw',
					'value' => '$data->sellTotalFormatted',
					'htmlOptions' => array(
						'class' => 'subtotal'
					),
				),
			),
			'rowHtmlOptionsExpression' => 'array("id" => "cart_row_".$data->id)',
		)
	);
	?>

	<div class="lines-footer">

		<?php if ($isReceipt === false):?>
			<form class="promo">
				<?=
					CHtml::tag(
						'div',
						array(
							'id' => CHtml::activeId($modelId, 'promoCode') . '_em_',
							'class' => 'form-error',
							'style' => 'display: none'
						),
						'<p>&nbsp;</p>'
					);
				?>
				<div style="position:relative;">
					<?php
						$this->renderPartial(
							'ext.wscartmodal.views._promocodeinput',
							array('modelId' => $modelId, 'updateCartTotals' => true, 'reloadPageOnSuccess' => false)
						);
					?>
				</div>
				<div class="form-error" style="display: none">
					<p><?= Yii::t('cart', 'something bad happened...') ?></p>
				</div>
				<p class="description">
					<?= Yii::t('cart', 'Specials, promotional offers and discounts') ?>
				</p>
			</form>
		<?php endif; ?>

		<div class="totals">
			<table class="totals">
				<tbody>
					<tr id="PromoCodeLine" class="<?= $cartDisplay->promoCode ? 'webstore-promo-line' : 'webstore-promo-line hide-me';?>" >
						<th colspan='3'>
							<?= Yii::t('cart', 'Promo & Discounts'); ?>
							<td id="PromoCodeStr" class="promo-code-str">
							<?= $cartDisplay->totalDiscountFormatted; ?>
						</td>
						</th>
					</tr>
					<tr>
						<th colspan='3'>
							<?= Yii::t('cart', 'Shipping'); ?>
							<small>
								<?= $cartDisplay->shipping->shipping_data; ?>
							</small>
						</th>
						<td class="shipping-estimate">
							<?= _xls_currency($cartDisplay->shippingCharge); ?>
						</td>
					</tr>
					<?php
						$this->renderPartial('_checkout-taxes',
							array(
								'cart' => $cartDisplay,
								'selectedCartScenario' => null,
								'confirmation' => true
							)
						);
					?>
					<tr class="total">
						<th colspan='3'>
							<?= Yii::t('cart', 'Total'); ?>
						</th>
						<td id="totalCart" class="wsshippingestimator-total-estimate total-estimate">
							<?= _xls_currency($cartDisplay->total); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</article>
