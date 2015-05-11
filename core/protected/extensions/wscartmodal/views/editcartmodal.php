<div class="menu-active overlay">
	<div id="wrapper">
		<div class="editcartmodal webstore-modal webstore-modal-overlay webstore-modal-cart webstore-overlay webstore-overlay-cart" id="cart">
			<section id="cart">
				<header class="overlay">
					<h1>
						<?php
							echo
								CHtml::link(
									CHtml::image(Yii::app()->controller->pageHeaderImage, Yii::t('cart', 'web store header image')).
									CHtml::tag('span', array(), Yii::app()->params['STORE_NAME']),
									Yii::app()->createUrl("site/index"),
									array('class' => 'logo-placement')
								);
						?>
					</h1>
					<?php
						echo CHtml::htmlButton(
							Yii::t('cart', 'Continue Shopping'),
							array('class' => 'exit continue-shopping')
						);
					?>
					<a href="#" class="edit button inset" onclick="$('table.lines').toggleClass('edit'); return false;">
						<?= Yii::t('cart', 'Edit'); ?>
					</a>
				</header>
				<article class="section-inner">
						<h1><?= Yii::t('cart', 'Shopping Cart'); ?></h1>
						<?php
							$this->widget(
								'zii.widgets.grid.CGridView',
								array(
									'htmlOptions' => array('class' => 'lines lines-container'),
									'id' => 'user-grid',
									'itemsCssClass' => 'lines',
									'dataProvider' => Yii::app()->shoppingcart->dataProvider,
									'summaryText' => '',
									'columns' => array(
										array(
											'name' => 'image',
											'header' => Yii::t('cart', 'Product'),
											'headerHtmlOptions' => array('class' => 'description', 'colspan' => 2),
											'type' => 'raw',
											'value' => 'CHtml::image($data->product->SliderImage).CHtml::tag("td",array("class" => "description"),CHtml::link("<strong>".$data -> product->title."</strong>", $data -> product->Link, array()))',
											'htmlOptions' => array(
												'class' => 'image'
											),
										),
										array(
											'name' => 'sell',
											'header' => Yii::t('cart', 'Price'),
											'headerHtmlOptions' => array('class' => 'price'),
											'type' => 'raw',
											'value' => 'wseditcartmodal::renderSellPrice($data)',
											'htmlOptions' => array(
												'class' => 'price'
											),
										),
										array(
											'name' => 'qty',
											'header' => Yii::t('cart', 'Qty.'),
											'sortable' => false,
											'headerHtmlOptions' => array('class' => 'quantity'),
											'type' => 'raw',
											'value' => 'CHtml::numberField("CartItem_qty[$data->id]",$data->qty,array(
														"data-pk" => $data->id,
														"onchange" => "wsEditCartModal.updateCart(this)",
													))',
											'htmlOptions' => array(
												'class' => 'quantity'
											),
										),
										array(
											'name' => 'sell_total',
											'header' => Yii::t('cart', 'Total'),
											'sortable' => false,
											'headerHtmlOptions' => array('class' => 'subtotal'),
											'type' => 'raw',
											'value' => '$data->sellTotalFormatted',
											'htmlOptions' => array(
												'class' => 'subtotal'
											),
										),
										array(
											'name' => 'remove',
											'header' => '',
											'headerHtmlOptions' => array('class' => 'remove'),
											'htmlOptions' => array(
												'class' => 'remove'
											),
											'type' => 'raw',
											'value' => 'CHtml::link("×","#",array(
												"data-pk"=>$data->id,"class"=>"remove", "onclick"=>"wsEditCartModal.removeItem(this, event)")
												)',
										),
									),
									'rowHtmlOptionsExpression' => 'array("id" => "cart_row_".$data->id)',
								)
							);
						?>
						<div class="cart-footer">
							<form class="promo">
								<?php
								echo CHtml::tag(
									'div',
									array(
										'id' => CHtml::activeId('EditCart', 'promoCode') . '_em_',
										'class' => 'form-error',
										'style' => 'display: none'
									),
									'<p>&nbsp;</p>'
								);
								?>
								<div style="position:relative;">
									<?php
										$this->controller->renderPartial(
											'ext.wscartmodal.views._promocodeinput',
											array('modelId' => 'EditCart', 'updateCartTotals' => false, 'reloadPageOnSuccess' => false)
										);
									?>
								</div>
								<p class="description"><?php echo Yii::t('cart', 'Specials, promotional offers and discounts') ?></p>
							</form>
							<div class="pricechange">
							</div>
							<div class="totals">
								<?php $this->widget('ext.wsshippingestimator.WsShippingEstimatorTooltip'); ?>
								<table>
									<tbody>
										<tr class="subtotal">
											<th colspan='2'><?php echo Yii::t('cart', 'Subtotal'); ?></th>
											<td id="CartSubtotal" class="cart-subtotal money"><?php echo _xls_currency(Yii::app()->shoppingcart->subtotal) ?></td>
										</tr>
										<tr id="PromoCodeLine" class="<?php echo Yii::app()->shoppingcart->displayPromoLine() ? 'webstore-promo-line' : 'webstore-promo-line hide-me';?>" >
											<th colspan='2'>
												<?= Yii::t('cart', 'Promos & Discounts'); ?>
											<td id="PromoCodeStr"  class="money promo-code-str"><?= Yii::app()->shoppingcart->totalDiscountFormatted; ?></td>
											</th>
										</tr>
										<?php $this->widget('ext.wsshippingestimator.WsShippingEstimator'); ?>
									</tbody>
									<tfoot>
									<tr class="total">
										<th colspan='2'><?php echo Yii::t('cart', 'Total'); ?></th>
										<td id="CartTotal" class="wsshippingestimator-total-estimate total-estimate money">
											<?= _xls_currency(Yii::app()->shoppingcart->total); ?>
										</td>
									</tr>
									</tfoot>
								</table>
							</div>
							<div class="submit">
								<?php
									echo CHtml::htmlButton(
										Yii::t('cart', 'Checkout'),
										array(
											'class' => 'checkout',
											'onClick' => 'wsEditCartModal.goToCheckout()'
										)
									);

									echo CHtml::htmlButton(
										Yii::t('cart', 'Continue Shopping'),
										array('class' => 'continue continue-shopping')
									);
								?>
							</div>
					</div>
				</article>
				<footer>
					<?php
						echo
							CHtml::htmlButton(
								Yii::t('cart', 'Continue Shopping'),
								array(
									'class' => 'button continue continue-shopping',
								)
							);
					?>
					<p>
						<?php
							if (Yii::app()->params['ENABLE_SSL'] == 1)
							{
								echo CHtml::image(
									Yii::app()->params['umber_assets'] . '/images/lock.png',
									'lock image ',
									array(
										'height' => 14
									)
								);

								echo CHtml::tag(
									'strong',
									array(),
									'Safe &amp; Secure '
								);

								echo Yii::t('cart', 'Bank-grade SSL encryption protects your purchase.');
							}

							$objPrivacy = CustomPage::LoadByKey('privacy');
							if ($objPrivacy instanceof CustomPage && $objPrivacy->tab_position !== 0)
							{
								echo ' ' . CHtml::link(
									Yii::t('cart', 'Privacy Policy'),
									$objPrivacy->Link,
									array('target' => '_blank')
								);
							}
						?>
					</p>
				</footer>
			</section>
		</div>
	</div>
</div>
