<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?= Yii::app()->language ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?=CHtml::encode(_xls_get_conf('STORE_NAME')); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<style type="text/css">
		@media only screen and (min-device-width: 601px) {
			/*Apple Mail doesn't support max-width so we set the width in the media query*/
			.main{ width: 600px;!important}
			.shipping-box{width:254px !important;}
			.address-box{ width:254px!important;float:right !important;}
			.order-number{ float:right !important;}
			.address-box{ float:right!important;}
			.total-headers{ float:right!important; }
			.footer-right{float:right !important;}
			.footer-left{float:left !important;}
			.footer-right td{padding: 20px 5px 10px !important;}
			.footer-left td{padding:5px 20px 200px !important; }
		}

		@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
			.main{  max-width:600px; width: 100%;!important}
			.address-box{ float:left!important;}
			.order-number{ float:left !important;}
			.total-headers{ float:left !important; }
			.footer-right{float:none !important; margin:0 auto !important;}
			.footer-left{float:none !important; margin:0 auto !important;}
			.footer-right td{padding:5px 0 5px !important;}
			.footer-left td{padding:15px 0 0 !important;}
		}
	</style>
</head>
<body style="margin: 0; padding: 0;min-width: 100%!important;" bgcolor="#eeeeee">
	<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#eeeeee" style="border-collapse: collapse; margin: 0px auto;">
		<tbody>
			<tr>
				<td style="padding: 10px 0 30px 0;">
					<!--[if (gte mso 9)|(IE)]>
					<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
					<![endif]-->
					<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%" class="main" style="border-collapse:collapse;margin:0px auto;" bgcolor="#ffffff">
						<tbody>
							<tr>
								<td align="left" bgcolor="#eeeeee" style="padding-top: 20px;padding-bottom: 20px;color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 21px;">
									<?=
										CHtml::link(
											CHtml::image(
												$this->pageAbsoluteHeaderImage,
												Yii::t('email','{storename}',
													array('{storename}' => _xls_get_conf('STORE_NAME')
													)
												),
												array(
													'style' => 'display:block;'
												)
											), $this->createUrl("site/index"));
									?>
								</td>
							</tr>
							<tr>
								<td style="padding: 30px 30px 30px 30px;border: 1px solid #cccccc;">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<?= $content; ?>
									</table>
								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td valign="top">
												<table border="0" cellpadding="0" cellspacing="0" align="center">
													<tr>
														<td style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 13px;line-height:1.5em;padding-top:20px;">
															<?=
																CHtml::mailto(
																	_xls_get_conf('EMAIL_FROM'),
																	_xls_get_conf('EMAIL_FROM'),
																	array(
																		'target' => '_blank',
																		'style' => 'color: #3287cc;text-decoration: none;'
																	)
																);
															?>
														</td>
													</tr>
												</table>
												<table border="0" cellpadding="0" cellspacing="0" align="center">
													<tr>
														<td style="color:#111111;font-family:'Lucida Grande','Lucida Sans', Verdana, sans-serif;font-size: 13px;line-height:1.5em;">
															<?php if(_xls_get_conf('STORE_PHONE')): ?>
																<?=
																	Yii::t('email', 'Phone') . ': ' .
																	CHtml::link(
																		_xls_get_conf('STORE_PHONE'),
																		_xls_get_conf('STORE_PHONE'),
																		array(
																			'style' => 'color: #111111;text-decoration: none;'
																		)
																	);
																?>
															<?php endif; ?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
					<!--[if (gte mso 9)|(IE)]>
							</td>
						</tr>
					</table>
					<![endif]-->
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>