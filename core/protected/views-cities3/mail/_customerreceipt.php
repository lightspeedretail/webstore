<?php $this->beginContent('//layouts/mail-layout'); ?>

			<tr>
				<td style="border-bottom: 1px solid #dddddd;display: block; padding-bottom: 30px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 16px;line-height:1.5em;">
					<?= Yii::t('email', 'Dear') . ' ' . $cart->customer->first_name ?>,<br/><br/>
					<?= Yii::t('email', 'Thank you for your order with') . ' ' . _xls_get_conf('STORE_NAME') ?>.
				</td>
			</tr>
			<tr>
				<td>
					<table width="180" align="left" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td style="padding-top:1em;padding-bottom:1em;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 21px;line-height:1.5em;">
								<b><?= Yii::t('email', 'Order Receipt') ?></b>
							</td>
						</tr>
					</table>
					<!--[if (gte mso 9)|(IE)]>
					<table width="140" align="right" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
					<![endif]-->
					<table align="right" border="0" cellpadding="0" cellspacing="0" class="order-number" style="max-width: 100%; max-width: 140px;">
						<tr>
							<td style="padding-top:1em;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 13px;line-height:1.5em;">
								<table align="right" border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td style="border: 1px solid #dddddd;padding: 10px 10px 10px 10px;font-size:12px;">
											<?=
												CHtml::link(
													Yii::t('email', 'Order #:') . ' ' . '<span>' . $cart->id_str . '</span>',
													Yii::app()->controller->createAbsoluteUrl(
														'cart/receipt',
														array(
															'getuid' => $cart->linkid)
													),
													array(
														'target' => '_blank',
														'style' => 'color: #3287cc;text-decoration: none;'
													)
												);
											?>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<!--[if (gte mso 9)|(IE)]>
							</td>
						</tr>
					</table>
					<![endif]-->
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td valign="top">
								<!--[if (mso)|(IE)]>
								<table width="279" align="left" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td valign="top" style="padding-right:25px;">
								<![endif]-->
								<table border="0" cellpadding="0" cellspacing="0" width="279" align="left">
									<tr>
										<td style="padding-right:25px;">
											<table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 254px;padding-bottom:20px;" align="left" class="shipping-box">
												<tr>
													<td style="padding-top:1.33em;padding-bottom:1.33em;color:#666666;font-family: 'Lucida Grande', 'Lucida Sans', Verdana, sans-serif; font-size:15px;line-height:1.5em;">
														<b><?= Yii::t('email', 'SHIPPING ADDRESS') ?></b>
													</td>
												</tr>
												<tr>
													<td width="100%" valign="top" style="padding:15px 15px 15px 15px;border: 1px solid #dddddd;">
														<table border="0" cellpadding="0" cellspacing="0" width="100%">
															<tr>
																<td style="color:#777777;font-family: 'Lucida Grande', 'Lucida Sans', Verdana, sans-serif; font-size:12px;line-height:1.5em;">

																	<?php if (!$cart->shipping->isStorePickup): ?>
																		<?= $cart->shipaddress->first_name . ' ' . $cart->shipaddress->last_name ?><br/>
																		<?php if ($cart->shipaddress->customer_id == $cart->customer_id): ?>
																			<?=  $cart->shipaddress->address1 ?><br/>
																			<?= $cart->shipaddress->address2 ?><br/>
																			<?=  $cart->shipaddress->city . ', ' . $cart->shipaddress->state . ', ' . $cart->shipaddress->postal . ', ' . $cart->shipaddress->country ?><br/>
																		<?php else: ?>
																			<?=  Yii::t('email', 'Gift Recipient Address') ?>
																		<?php endif; ?>
																	<?php else: ?>
																		<?php
																			$module = Modules::LoadByName('storepickup');
																			$config = unserialize($module->configuration);
																			echo $config['label'] . '<br/>';
																			$str = '';
																			$str .= $cart->shipaddress->first_name . ' ' . $cart->shipaddress->last_name . '<br>';
																			$str .= $cart->shipaddress->store_pickup_email ? CHtml::mailto(
																				$cart->shipaddress->store_pickup_email,
																				$cart->shipaddress->store_pickup_email,
																				array(
																					'target' => '_blank',
																					'style' => 'color: #3287cc;text-decoration: none;'
																				)
																			) : CHtml::mailto(
																				$cart->customer->email,
																				$cart->customer->email,
																				array(
																					'target' => '_blank',
																					'style' => 'color: #3287cc;text-decoration: none;'
																				)
																			);
																			$str .= $cart->shipaddress->phone ? '<br>' . $cart->shipaddress->phone : '';
																			echo $str;
																		?>
																	<?php endif; ?>

																</td>
															</tr>
															<tr>
																<td style="color:#777777;font-family: 'Lucida Grande', 'Lucida Sans', Verdana, sans-serif; font-size:12px;line-height:1.5em;padding-top:7px;">
																	<strong><?= Yii::t('email', 'Shipping:') . ' ' . '</strong>' . $cart->shipping->shipping_data; ?>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!--[if (mso)|(IE)]>
										</td>
									</tr>
								</table>
								<![endif]-->
								<!--[if mso]>
								</td><td>
								<![endif]-->
								<!--[if (mso)|(IE)]>
								<table width="254" align="left" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td valign="top">
								<![endif]-->
								<table align="left" border="0" cellpadding="0" cellspacing="0" style="width: 254px;padding-bottom:20px;" class="address-box">
									<tr>
										<td valign="top" style="padding-top:1.33em;padding-bottom:1.33em;color:#666666;font-family: 'Lucida Grande', 'Lucida Sans', Verdana, sans-serif; font-size:15px;line-height:1.5em;">
											<b><?= Yii::t('email', 'BILLING') ?></b>
										</td>
									</tr>
									<tr>
										<td valign="top" style="padding:15px 15px 15px 15px;border: 1px solid #dddddd;width:254px;">
											<table border="0" cellpadding="0" cellspacing="0" >
												<tr>
													<td style="color:#777777;font-family: 'Lucida Grande', 'Lucida Sans', Verdana, sans-serif; font-size:12px;line-height:1.5em;">
														<?php if ($cart->billaddress): ?>
															<?= $cart->billaddress->first_name . ' ' . $cart->billaddress->last_name ?><br/>
															<?=
																CHtml::mailto(
																	$cart->customer->email,
																	$cart->customer->email,
																	array(
																		'target' => '_blank',
																		'style' => 'color: #3287cc;text-decoration: none;'
																	)
																);
															?><br/>
															<?= $cart->billaddress->address1 ?><br/>
															<?= $cart->billaddress->address2 ?><br>
															<?= $cart->billaddress->city . ', ' . $cart->billaddress->state . ', ' . $cart->billaddress->postal . ', ' . $cart->billaddress->country ?><br/>
															<?= $cart->customer->mainphone ?><br/>
														<?php endif; ?>
													</td>
												</tr>
												<tr>
													<td style="color:#777777;font-family: 'Lucida Grande', 'Lucida Sans', Verdana, sans-serif; font-size:12px;line-height:1.5em;padding-top:7px;">
														<?php if (strlen($cart->payment->payment_data)>0): ?>
															<strong><?= Yii::t('email', 'Payment') . ' ' . '</strong>' . $cart->payment->payment_data ?>
														<?php endif; ?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<!--[if (mso)|(IE)]>
										</td>
									</tr>
								</table>
								<![endif]-->
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="padding-top:20px;padding-bottom:20px;">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th align="left" width="90%"style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;">
									<?= Yii::t('email', 'ITEM') ?>
								</th>
								<th align="right" width="10%"style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;">
									<?= Yii::t('email', 'PRICE') ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$items = $cart->cartItems;
								$index = 0;
								$extraStyle = '';

								foreach ($items as $item):
							?>
								<?php $extraStyle = ($index === count($items) - 1 ? 'border-bottom:1pt solid #dddddd;' : '') ?>
								<tr>
									<td>
										<table border="0" cellpadding="8" cellspacing="0" width="100%">
											<tr>
												<td style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;border-top:1pt solid #dddddd;width:40px;<?= $extraStyle ?>">
													<b><?= $item->qty . 'X' ?></b>
												</td>
												<td style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 13px;padding-left:5px;padding-right:5px;border-top:1pt solid #dddddd;<?= $extraStyle ?>">
													<b><?= $item->description . ' (' . $item->code . ')' ?></b>
												</td>
											</tr>
										</table>
									</td>
									<td align="right" style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 13px;border-top:1pt solid #dddddd;<?= $extraStyle ?>">
										<b><?= _xls_currency($item->sell_total) ?></b>
									</td>
								</tr>
							<?php
								$index++;
								endforeach;
							?>
						<tr>
							<td align="right" class="total-headers" style="color:#666666;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;padding-right:61px;">
								<b><?= Yii::t('email', 'SubTotal') ?></b>
							</td>
							<td align="right" style="color:#666666;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;">
								<?= _xls_currency($cart->subtotal) ?>
							</td>
						</tr>
						<tr>
							<td align="right" class="total-headers" style="color:#666666;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;padding-right:61px;">
								<b><?= Yii::t('email', 'Tax') ?></b>
							</td>
							<td align="right" style="color:#666666;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;"><?= _xls_currency($cart->tax_total)?></td>
						</tr>
						<tr>
							<td align="right" class="total-headers" style="color:#666666;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;padding-right:61px;">
								<b><?= Yii::t('email', 'Shipping') ?></b>
							</td>
							<td align="right" style="color:#666666;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;">
								<?= _xls_currency($cart->shippingCharge) ?>
							</td>
						</tr>
						<tr>
							<td align="right" class="total-headers" style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:30px;padding-right:61px;">
								<b><?= Yii::t('email', 'Total') ?></b>
							</td>
							<td align="right" style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 13px;line-height:30px;">
								<b><?= _xls_currency($cart->total) ?></b>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f8f8f8" style="padding:15px 15px 15px 15px;color:#111111;font-family:'Lucida Grande',
													 'Lucida Sans', Verdana, sans-serif;font-size: 12px;line-height:1.5em; border: 1px solid #dddddd;">
					<?= Yii::t('email', 'This email is a confirmation for the order. To view details or track your order, click on the visit link:') ?><br/>
					<?=
						CHtml::link(
							Yii::app()->controller->createAbsoluteUrl(
								'cart/receipt',
								array(
									'getuid' => $cart->linkid
								)
							),
							Yii::app()->controller->createAbsoluteUrl(
								'cart/receipt',
								array(
									'getuid' => $cart->linkid
								)
							),
							array(
								'target' => '_blank','style' => 'color: #3287cc;text-decoration: none;')
						);
					?>
				</td>
			</tr>

<?php $this->endContent(); ?>
