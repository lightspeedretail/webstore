<div class="section-content" id="confirm">
	<div class="thankyou">
		<h1><?php echo Yii::t('checkout', "Thank you for shopping with us!") ?></h1>
		<h2><?php echo Yii::t('checkout', "Your order is complete") ?></h2>

		<p class="large">
			<strong>
				<?php
				echo Yii::t('checkout', "Order number is: ") . $cart->id_str;
				?>
			</strong>
		</p>
		<p>
			<?php echo Yii::t('checkout', "Your will receive an email confirmation shortly at ") . $cart->customer->email ?>
		</p>

		<p>
			<?php echo CHtml::link(Yii::t('checkout','Print Receipt'), '#print', array('class' => 'print','onclick' => 'print(); return false;')); ?>
		</p>

<!------------------------------------------------------------------------------------------------------------	Create new account -------------------------------------------------------------------------------------------------->

		<?php
		$form = $this->beginWidget(
			'CActiveForm',
			array('htmlOptions' => array('class' => "create-account")
			)
		);
		?>
		<h3><?php echo Yii::t('checkout', "Save your information for next time") ?></h3>
		<p><?php echo Yii::t('checkout', "Faster checkout, convenient order history and more.") ?></p>
		<ol>
			<li>
				<?php
				echo $form->passwordField(
					$model,
					'createPassword',
					$htmlOptions = array('placeholder' => Yii::t('checkout', "Password"))
				);
				?>
				<p class="hint">
					<?php echo Yii::t('checkout', "Must be at least 6 characters"); ?>
				</p>
			</li>
			<li>
				<?php
				echo $form->passwordField(
					$model,
					'createPassword',
					$htmlOptions = array('placeholder' => Yii::t('checkout', "Verify Password"))
				);
				?>
			</li>
			<li><button >Create Account</button></li>
		</ol>
		<?php $this->endWidget();?>

<!------------------------------------------------------------------------------------------------------------- Account confirmation  -------------------------------------------------------------------------------------------------->
		<div class="create-account-confirm">
			<h3><?php echo Yii::t('checkout', "Your account has been created.") ?></h3>
			<p><?php echo Yii::t('checkout', "You'll receive email confirmation shortly.") ?></p>
		</div>
<!------------------------------------------------------------------------------------------------------------- Account confirmation  -------------------------------------------------------------------------------------------------->

	</div>
	<div class="receipt">
		<h2><?php echo Yii::t('checkout', "Order Receipt")?><span class="order-id"><?php echo Yii::t('checkout', "Order #:")?> <strong><?php echo $cart->id_str?></strong></span></h2>

		<?php $this->renderPartial('_orderdetails',array('cart' => $cart, 'isReceipt' => true)); ?>

		<article class="cart">
			<?php
			$this->widget(
				'zii.widgets.grid.CGridView',
				array(
					'htmlOptions' => array('class' => 'lines lines-container'),
					'id' => 'user-grid',
					'itemsCssClass' => 'lines',
					'dataProvider' => $cart->dataProvider,
					'summaryText' => '',
					'columns' => array(
						array(
							'type' => 'raw',
							'value' => '"<strong>$data->qty</strong>".CHtml::numberField("CartItem_qty[$data->id]",$data->qty,array(
	                                        "data-pk"=>$data->id,
	                                        "size" => "2",
//	                                        "onblur" =>"updateCart(this, $data->qty)",
	                                        "onchange" =>"updateCart(this)",
	                                    ))',
							'htmlOptions' => array(
								'class' => 'quantity',
							),
						),
						array(
							'type' => 'raw',
							'value' => 'CHtml::image(Images::GetLink($data -> product -> image_id,ImagesType::slider)).CHtml::tag("td",array("class" => "description"),CHtml::link("<strong>".$data -> product->title."</strong>" ."<span class=\'price\'> ".$data->sellFormatted."ea</span>", $data -> product->Link, array()))',
							'htmlOptions' => array(
								'class' => 'image'
							),
						),
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


				<table class="totals">
					<tbody>
					<tr>
						<th colspan='3'>
							<?php
							echo Yii::t('cart','Shipping');
							?>
							<small>
								<?php
								echo $cart->shipping->shipping_data;
								?>
							</small>
						</th>
						<td>
							<?php
							echo _xls_currency($cart->shipping_sell);
							?>
						</td>
					</tr>
					<tr>
						<th colspan='3'>
							<?php
							echo Yii::t('cart','Tax');
							?>
							<!--							TODO: get tax rate-->
							<!--							<small>--><?php //echo Yii::app()->shoppingcart->TaxRate; ?><!--</small>-->
						</th>
						<td>
							<?php
							echo _xls_currency($cart->TaxTotal);
							?>
						</td>
					</tr>
					<tr class="total">
						<th colspan='3'>
							<?php
							echo Yii::t('cart','Total');
							?>
						</th>
						<td>
							<?php
							echo _xls_currency($cart->total);
							?>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</article>

	</div>
</div>


